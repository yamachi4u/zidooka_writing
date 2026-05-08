import { mkdir, writeFile } from 'fs/promises';
import path from 'path';
import {
  fetchJson,
  getAccessToken,
  parseArgs,
  printTable,
  splitCsv,
} from './google-api-common.mjs';

function usage() {
  console.log(`Usage:
  node scripts/ga4-report.mjs [options]

Options:
  --property    GA4 property id (default: GOOGLE_GA4_PROPERTY_ID)
  --start-date  Start date (default: 28daysAgo)
  --end-date    End date (default: yesterday)
  --dimensions  CSV dimensions (default depends on preset)
  --metrics     CSV metrics (default depends on preset)
  --limit       Row limit (default: 10)
  --preset      overview | landing-pages | landing-page-channels | acquisition | campaigns | countries | devices | events | conversions
  --compare     previous | previous-period
  --csv-out     Save rows to CSV
  --json        Print raw JSON
  --key-file    Service account JSON path
  --filter-dimension  Dimension name to filter (e.g., landingPagePlusQueryString)
  --filter-match      Filter match type: contains | exact (default: contains)
  --filter-expressions  Comma-separated values to filter by

Examples:
  npm run ga4 -- --preset overview
  npm run ga4 -- --preset landing-pages --limit 20
  npm run ga4 -- --preset acquisition --compare previous
  npm run ga4 -- --preset events --start-date 2026-02-25 --end-date 2026-03-24 --csv-out daily/ga4-events.csv
  npm run ga4 -- --preset landing-pages --filter-dimension landingPagePlusQueryString --filter-expressions /jp/calendar`);
}

function buildPreset(preset) {
  switch (preset) {
    case 'landing-pages':
      return {
        dimensions: ['landingPagePlusQueryString'],
        metrics: ['sessions', 'totalUsers', 'screenPageViews', 'engagedSessions', 'averageSessionDuration'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'landing-page-channels':
      return {
        dimensions: ['landingPagePlusQueryString', 'sessionDefaultChannelGroup'],
        metrics: ['sessions', 'totalUsers', 'engagedSessions', 'screenPageViews'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'acquisition':
      return {
        dimensions: ['sessionSourceMedium'],
        metrics: ['sessions', 'totalUsers', 'engagedSessions', 'screenPageViews'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'campaigns':
      return {
        dimensions: ['sessionCampaignName', 'sessionSourceMedium'],
        metrics: ['sessions', 'totalUsers', 'engagedSessions', 'screenPageViews'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'countries':
      return {
        dimensions: ['country'],
        metrics: ['sessions', 'totalUsers', 'screenPageViews', 'engagedSessions'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'devices':
      return {
        dimensions: ['deviceCategory'],
        metrics: ['sessions', 'totalUsers', 'screenPageViews', 'engagedSessions'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
    case 'events':
      return {
        dimensions: ['eventName'],
        metrics: ['eventCount', 'totalUsers', 'keyEvents'],
        orderBys: [{ metric: { metricName: 'eventCount' }, desc: true }],
      };
    case 'conversions':
      return {
        dimensions: ['eventName'],
        metrics: ['keyEvents', 'totalUsers', 'eventCount'],
        orderBys: [{ metric: { metricName: 'keyEvents' }, desc: true }],
      };
    case 'overview':
    default:
      return {
        dimensions: ['sessionDefaultChannelGroup'],
        metrics: ['sessions', 'totalUsers', 'screenPageViews', 'engagedSessions'],
        orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
      };
  }
}

function buildBody({ dimensions, metrics, startDate, endDate, limit, orderBys, dimensionFilter }) {
  const body = {
    dimensions: dimensions.map((name) => ({ name })),
    metrics: metrics.map((name) => ({ name })),
    dateRanges: [
      {
        startDate,
        endDate,
      },
    ],
    limit,
    keepEmptyRows: false,
    orderBys,
  };
  if (dimensionFilter) {
    body.dimensionFilter = dimensionFilter;
  }
  return body;
}

function buildDimensionFilter(filterDimension, filterMatch, filterExpressions) {
  if (!filterDimension || !filterExpressions) return undefined;
  const expressions = filterExpressions.split(',').map((e) => e.trim()).filter(Boolean);
  if (!expressions.length) return undefined;

  const filterType = filterMatch === 'exact' ? 'exact' : 'contains';
  const matchType = filterType === 'exact' ? 'EXACT' : 'CONTAINS';

  if (expressions.length === 1) {
    return {
      filter: {
        fieldName: filterDimension,
        stringFilter: {
          matchType,
          value: expressions[0],
        },
      },
    };
  }

  // Multiple expressions -> OR group
  return {
    orGroup: {
      expressions: expressions.map((value) => ({
        filter: {
          fieldName: filterDimension,
          stringFilter: {
            matchType,
            value,
          },
        },
      })),
    },
  };
}

function normalizeRows(data, dimensions, metrics) {
  return (data.rows || []).map((row) => {
    const mapped = {};
    dimensions.forEach((dimension, index) => {
      mapped[dimension] = row.dimensionValues?.[index]?.value ?? '';
    });
    metrics.forEach((metric, index) => {
      mapped[metric] = row.metricValues?.[index]?.value ?? '';
    });
    return mapped;
  });
}

function toNumber(value) {
  const parsed = Number(value);
  return Number.isFinite(parsed) ? parsed : 0;
}

function round(value, digits = 2) {
  const factor = 10 ** digits;
  return Math.round(value * factor) / factor;
}

function formatMetric(metric, value) {
  const numeric = toNumber(value);
  if (metric.toLowerCase().includes('duration')) {
    return round(numeric, 2).toString();
  }
  if (Number.isInteger(numeric)) {
    return numeric.toString();
  }
  return round(numeric, 2).toString();
}

function prettifyRows(rows, dimensions, metrics) {
  return rows.map((row) => {
    const mapped = {};
    dimensions.forEach((dimension) => {
      mapped[dimension] = row[dimension] ?? '';
    });
    metrics.forEach((metric) => {
      mapped[metric] = formatMetric(metric, row[metric]);
    });
    return mapped;
  });
}

function csvEscape(value) {
  const text = String(value ?? '');
  if (/[",\r\n]/.test(text)) {
    return `"${text.replace(/"/g, '""')}"`;
  }
  return text;
}

async function writeCsv(filePath, rows) {
  const headers = rows.length ? Object.keys(rows[0]) : [];
  const lines = [];
  const directory = path.dirname(filePath);
  if (directory && directory !== '.') {
    await mkdir(directory, { recursive: true });
  }
  if (headers.length) {
    lines.push(headers.map(csvEscape).join(','));
    for (const row of rows) {
      lines.push(headers.map((header) => csvEscape(row[header])).join(','));
    }
  }
  await writeFile(filePath, `${lines.join('\n')}\n`, 'utf8');
}

function isIsoDateToken(value) {
  return /^\d{4}-\d{2}-\d{2}$/.test(String(value));
}

function normalizeDate(date) {
  const value = new Date(date);
  value.setHours(0, 0, 0, 0);
  return value;
}

function addDays(date, days) {
  const next = new Date(date);
  next.setDate(next.getDate() + days);
  return normalizeDate(next);
}

function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function resolveDateToken(token, today = normalizeDate(new Date())) {
  if (isIsoDateToken(token)) {
    return normalizeDate(new Date(`${token}T00:00:00`));
  }
  if (token === 'today') {
    return today;
  }
  if (token === 'yesterday') {
    return addDays(today, -1);
  }
  const ago = String(token).match(/^(\d+)daysAgo$/);
  if (ago) {
    return addDays(today, -Number(ago[1]));
  }
  throw new Error(`Unsupported date token for comparison: ${token}`);
}

function getPreviousRange(startToken, endToken) {
  const today = normalizeDate(new Date());
  const currentStart = resolveDateToken(startToken, today);
  const currentEnd = resolveDateToken(endToken, today);
  const spanDays = Math.floor((currentEnd - currentStart) / 86400000) + 1;
  const previousEnd = addDays(currentStart, -1);
  const previousStart = addDays(previousEnd, -(spanDays - 1));
  return {
    startDate: formatDate(previousStart),
    endDate: formatDate(previousEnd),
  };
}

function rowKey(row, dimensions) {
  return dimensions.map((dimension) => row[dimension] ?? '').join('\u241f');
}

function mergeComparisonRows(currentRows, previousRows, dimensions, metrics) {
  const allKeys = new Set([
    ...currentRows.map((row) => rowKey(row, dimensions)),
    ...previousRows.map((row) => rowKey(row, dimensions)),
  ]);
  const currentMap = new Map(currentRows.map((row) => [rowKey(row, dimensions), row]));
  const previousMap = new Map(previousRows.map((row) => [rowKey(row, dimensions), row]));
  const merged = [];

  for (const key of allKeys) {
    const current = currentMap.get(key) || {};
    const previous = previousMap.get(key) || {};
    const row = {};

    dimensions.forEach((dimension) => {
      row[dimension] = current[dimension] ?? previous[dimension] ?? '';
    });

    metrics.forEach((metric) => {
      const currentValue = toNumber(current[metric]);
      const previousValue = toNumber(previous[metric]);
      const delta = currentValue - previousValue;
      const deltaPct = previousValue === 0 ? '' : round((delta / previousValue) * 100, 2);
      row[`${metric}_current`] = currentValue;
      row[`${metric}_previous`] = previousValue;
      row[`${metric}_delta`] = round(delta, 2);
      row[`${metric}_delta_pct`] = deltaPct === '' ? '' : `${deltaPct}%`;
    });

    merged.push(row);
  }

  return merged.sort((left, right) => {
    const firstMetric = metrics[0];
    return toNumber(right[`${firstMetric}_current`]) - toNumber(left[`${firstMetric}_current`]);
  });
}

async function runReport({ property, token, body }) {
  return fetchJson(
    `https://analyticsdata.googleapis.com/v1beta/properties/${property}:runReport`,
    {
      method: 'POST',
      token,
      body,
    },
  );
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  if (args.help) {
    usage();
    return;
  }

  const property = args.property || process.env.GOOGLE_GA4_PROPERTY_ID;
  if (!property) {
    throw new Error('Missing GA4 property id. Set GOOGLE_GA4_PROPERTY_ID or pass --property');
  }

  const preset = buildPreset(args.preset || 'overview');
  const dimensions = splitCsv(args.dimensions, preset.dimensions);
  const metrics = splitCsv(args.metrics, preset.metrics);
  const limit = Number(args.limit || 10);
  const startDate = args['start-date'] || '28daysAgo';
  const endDate = args['end-date'] || 'yesterday';
  const compare = args.compare || '';
  const currentBody = buildBody({
    dimensions,
    metrics,
    startDate,
    endDate,
    limit,
    orderBys: preset.orderBys,
  });

  const { accessToken } = await getAccessToken({
    keyFile: args['key-file'],
    scopes: ['https://www.googleapis.com/auth/analytics.readonly'],
  });

  const currentData = await runReport({
    property,
    token: accessToken,
    body: currentBody,
  });

  if (args.json && !compare) {
    console.log(JSON.stringify(currentData, null, 2));
    return;
  }

  let currentRows = normalizeRows(currentData, dimensions, metrics);
  let outputRows = prettifyRows(currentRows, dimensions, metrics);

  console.log(`GA4 property ${property}`);
  console.log(`Date range: ${startDate} -> ${endDate}`);

  if (compare) {
    if (!['previous', 'previous-period'].includes(compare)) {
      throw new Error(`Unsupported compare mode: ${compare}`);
    }

    const previousRange = getPreviousRange(startDate, endDate);
    const previousBody = buildBody({
      dimensions,
      metrics,
      startDate: previousRange.startDate,
      endDate: previousRange.endDate,
      limit,
      orderBys: preset.orderBys,
    });
    const previousData = await runReport({
      property,
      token: accessToken,
      body: previousBody,
    });

    const previousRows = normalizeRows(previousData, dimensions, metrics);
    outputRows = mergeComparisonRows(currentRows, previousRows, dimensions, metrics);

    console.log(`Compare range: ${previousRange.startDate} -> ${previousRange.endDate}`);

    if (args.json) {
      console.log(JSON.stringify({ current: currentData, previous: previousData }, null, 2));
      return;
    }
  }

  printTable(outputRows);

  if (args['csv-out']) {
    await writeCsv(args['csv-out'], outputRows);
    console.log(`CSV written: ${args['csv-out']}`);
  }
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
