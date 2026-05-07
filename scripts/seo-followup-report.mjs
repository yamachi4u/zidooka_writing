import { mkdir, writeFile } from 'fs/promises';
import path from 'path';
import {
  fetchJson,
  getAccessToken,
  parseArgs,
  splitCsv,
} from './google-api-common.mjs';

const DEFAULT_TRACKED_PAGES = [
  {
    path: '/archives/185',
    url: 'https://www.zidooka.com/archives/185',
    label: 'ChatGPT files.oaiusercontent.com',
  },
  {
    path: '/archives/240',
    url: 'https://www.zidooka.com/archives/240',
    label: 'Cursor high demand',
  },
  {
    path: '/archives/633',
    url: 'https://www.zidooka.com/archives/633',
    label: 'X post fetch failed',
  },
  {
    path: '/archives/2672',
    url: 'https://www.zidooka.com/archives/2672',
    label: 'Copilot errors hub',
  },
  {
    path: '/archives/2755',
    url: 'https://www.zidooka.com/archives/2755',
    label: 'Copilot rate_limited',
  },
  {
    path: '/archives/4154',
    url: 'https://www.zidooka.com/archives/4154',
    label: 'X errors summary',
  },
];

const DEFAULT_GA4_PROPERTY = '344037190';
const DEFAULT_GSC_SITE = 'https://www.zidooka.com/';

function usage() {
  console.log(`Usage:
  node scripts/seo-followup-report.mjs [options]

Options:
  --property        GA4 property id (default: GOOGLE_GA4_PROPERTY_ID)
  --site            GSC site (default: GOOGLE_GSC_SITE)
  --key-file        Service account JSON path
  --start-date      Start date (default: 7daysAgo)
  --end-date        End date (default: yesterday)
  --pages           CSV landing paths or full URLs to track
  --query-limit     Queries per page in report (default: 5)
  --out-dir         Output directory (default: daily/seo-followup)
  --label           Report label prefix (default: seo-followup)

Examples:
  node scripts/seo-followup-report.mjs
  node scripts/seo-followup-report.mjs --start-date 2026-04-07 --end-date 2026-04-13
  node scripts/seo-followup-report.mjs --pages /archives/633,/archives/4154`);
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

function formatCompactDate(date) {
  return formatDate(date).replace(/-/g, '');
}

function isIsoDateToken(value) {
  return /^\d{4}-\d{2}-\d{2}$/.test(String(value));
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
  throw new Error(`Unsupported date token: ${token}`);
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

function toNumber(value) {
  const parsed = Number(value);
  return Number.isFinite(parsed) ? parsed : 0;
}

function round(value, digits = 2) {
  const factor = 10 ** digits;
  return Math.round(value * factor) / factor;
}

function formatPct(value) {
  if (value === '' || value === null || value === undefined || !Number.isFinite(value)) {
    return '';
  }
  return `${round(value, 2)}%`;
}

function formatSigned(value) {
  if (!Number.isFinite(value)) {
    return '';
  }
  if (value > 0) {
    return `+${round(value, 2)}`;
  }
  return `${round(value, 2)}`;
}

function formatSignedPct(value) {
  if (value === '' || value === null || value === undefined || !Number.isFinite(value)) {
    return '';
  }
  const rounded = round(value, 2);
  if (rounded > 0) {
    return `+${rounded}%`;
  }
  return `${rounded}%`;
}

function normalizeTrackedPages(value, site) {
  const pages = splitCsv(value, []).map((item) => item.trim()).filter(Boolean);
  if (!pages.length) {
    return DEFAULT_TRACKED_PAGES;
  }

  const siteBase = String(site || '').replace(/\/+$/, '');
  return pages.map((item) => {
    const url = item.startsWith('http') ? item : `${siteBase}${item.startsWith('/') ? item : `/${item}`}`;
    const normalizedUrl = url.replace(/\/+$/, '');
    const parsed = new URL(normalizedUrl);
    return {
      path: parsed.pathname,
      url: normalizedUrl,
      label: parsed.pathname,
    };
  });
}

async function runGa4Report({ property, token, startDate, endDate, trackedPages }) {
  const body = {
    dimensions: [{ name: 'landingPagePlusQueryString' }],
    metrics: [
      { name: 'sessions' },
      { name: 'totalUsers' },
      { name: 'engagedSessions' },
      { name: 'screenPageViews' },
    ],
    dateRanges: [{ startDate, endDate }],
    keepEmptyRows: true,
    limit: trackedPages.length,
    orderBys: [{ metric: { metricName: 'sessions' }, desc: true }],
    dimensionFilter: {
      filter: {
        fieldName: 'landingPagePlusQueryString',
        inListFilter: {
          values: trackedPages.map((page) => page.path),
          caseSensitive: false,
        },
      },
    },
  };

  const data = await fetchJson(
    `https://analyticsdata.googleapis.com/v1beta/properties/${property}:runReport`,
    {
      method: 'POST',
      token,
      body,
    },
  );

  const rows = new Map();
  for (const row of data.rows || []) {
    const pagePath = row.dimensionValues?.[0]?.value ?? '';
    rows.set(pagePath, {
      sessions: toNumber(row.metricValues?.[0]?.value),
      totalUsers: toNumber(row.metricValues?.[1]?.value),
      engagedSessions: toNumber(row.metricValues?.[2]?.value),
      screenPageViews: toNumber(row.metricValues?.[3]?.value),
    });
  }
  return rows;
}

async function runGscQuery({ token, site, body }) {
  const encodedSite = encodeURIComponent(site);
  return fetchJson(
    `https://searchconsole.googleapis.com/webmasters/v3/sites/${encodedSite}/searchAnalytics/query`,
    {
      method: 'POST',
      token,
      body,
    },
  );
}

async function fetchGscPageMetrics({ token, site, pageUrl, startDate, endDate }) {
  const body = {
    startDate,
    endDate,
    dimensions: ['page'],
    type: 'web',
    rowLimit: 1,
    dimensionFilterGroups: [
      {
        filters: [
          {
            dimension: 'page',
            operator: 'equals',
            expression: pageUrl,
          },
        ],
      },
    ],
  };
  const data = await runGscQuery({ token, site, body });
  const row = data.rows?.[0];
  if (!row) {
    return {
      clicks: 0,
      impressions: 0,
      ctr: 0,
      position: 0,
    };
  }
  return {
    clicks: toNumber(row.clicks),
    impressions: toNumber(row.impressions),
    ctr: Number(row.ctr || 0),
    position: Number(row.position || 0),
  };
}

async function fetchGscTopQueries({ token, site, pageUrl, startDate, endDate, limit }) {
  const body = {
    startDate,
    endDate,
    dimensions: ['query'],
    type: 'web',
    rowLimit: limit,
    dimensionFilterGroups: [
      {
        filters: [
          {
            dimension: 'page',
            operator: 'equals',
            expression: pageUrl,
          },
        ],
      },
    ],
  };
  const data = await runGscQuery({ token, site, body });
  return (data.rows || []).map((row) => ({
    query: row.keys?.[0] ?? '',
    clicks: toNumber(row.clicks),
    impressions: toNumber(row.impressions),
    ctr: Number(row.ctr || 0),
    position: Number(row.position || 0),
  }));
}

function mergeGa4Rows(trackedPages, currentMap, previousMap) {
  return trackedPages.map((page) => {
    const current = currentMap.get(page.path) || {};
    const previous = previousMap.get(page.path) || {};
    const currentSessions = toNumber(current.sessions);
    const previousSessions = toNumber(previous.sessions);
    const currentUsers = toNumber(current.totalUsers);
    const previousUsers = toNumber(previous.totalUsers);
    const currentEngaged = toNumber(current.engagedSessions);
    const previousEngaged = toNumber(previous.engagedSessions);
    const currentViews = toNumber(current.screenPageViews);
    const previousViews = toNumber(previous.screenPageViews);

    return {
      ...page,
      ga4: {
        sessionsCurrent: currentSessions,
        sessionsPrevious: previousSessions,
        sessionsDelta: currentSessions - previousSessions,
        sessionsDeltaPct: previousSessions === 0 ? '' : ((currentSessions - previousSessions) / previousSessions) * 100,
        usersCurrent: currentUsers,
        usersPrevious: previousUsers,
        usersDelta: currentUsers - previousUsers,
        engagedCurrent: currentEngaged,
        engagedPrevious: previousEngaged,
        engagedDelta: currentEngaged - previousEngaged,
        viewsCurrent: currentViews,
        viewsPrevious: previousViews,
        viewsDelta: currentViews - previousViews,
      },
    };
  });
}

function mergeGscRows(mergedRows, currentRows, previousRows, queryRows) {
  return mergedRows.map((row) => {
    const current = currentRows.get(row.url) || {
      clicks: 0,
      impressions: 0,
      ctr: 0,
      position: 0,
    };
    const previous = previousRows.get(row.url) || {
      clicks: 0,
      impressions: 0,
      ctr: 0,
      position: 0,
    };
    return {
      ...row,
      gsc: {
        clicksCurrent: current.clicks,
        clicksPrevious: previous.clicks,
        clicksDelta: current.clicks - previous.clicks,
        clicksDeltaPct: previous.clicks === 0 ? '' : ((current.clicks - previous.clicks) / previous.clicks) * 100,
        impressionsCurrent: current.impressions,
        impressionsPrevious: previous.impressions,
        impressionsDelta: current.impressions - previous.impressions,
        impressionsDeltaPct: previous.impressions === 0 ? '' : ((current.impressions - previous.impressions) / previous.impressions) * 100,
        ctrCurrent: current.ctr * 100,
        ctrPrevious: previous.ctr * 100,
        ctrDelta: (current.ctr - previous.ctr) * 100,
        positionCurrent: current.position,
        positionPrevious: previous.position,
        positionDelta: current.position - previous.position,
      },
      topQueries: queryRows.get(row.url) || [],
    };
  });
}

function buildMarkdown({
  currentRange,
  previousRange,
  rows,
  trackedPages,
}) {
  const lines = [];
  lines.push('# SEO Follow-up Report');
  lines.push('');
  lines.push(`- Current: ${currentRange.startDate} -> ${currentRange.endDate}`);
  lines.push(`- Previous: ${previousRange.startDate} -> ${previousRange.endDate}`);
  lines.push(`- Pages: ${trackedPages.length}`);
  lines.push('');

  lines.push('## GA4 Summary');
  lines.push('');
  lines.push('| Page | Sessions | Delta | Users | Engaged | Views |');
  lines.push('| --- | ---: | ---: | ---: | ---: | ---: |');
  for (const row of rows) {
    lines.push(
      `| [${row.label}](${row.url}) | ${row.ga4.sessionsCurrent} | ${formatSigned(row.ga4.sessionsDelta)} (${formatSignedPct(row.ga4.sessionsDeltaPct) || '-'}) | ${row.ga4.usersCurrent} | ${row.ga4.engagedCurrent} | ${row.ga4.viewsCurrent} |`,
    );
  }
  lines.push('');

  lines.push('## GSC Summary');
  lines.push('');
  lines.push('| Page | Clicks | Delta | Impressions | CTR | Position |');
  lines.push('| --- | ---: | ---: | ---: | ---: | ---: |');
  for (const row of rows) {
    lines.push(
      `| [${row.label}](${row.url}) | ${row.gsc.clicksCurrent} | ${formatSigned(row.gsc.clicksDelta)} (${formatSignedPct(row.gsc.clicksDeltaPct) || '-'}) | ${row.gsc.impressionsCurrent} | ${formatPct(row.gsc.ctrCurrent)} | ${round(row.gsc.positionCurrent, 2)} |`,
    );
  }
  lines.push('');

  lines.push('## Page Notes');
  lines.push('');
  for (const row of rows) {
    lines.push(`### ${row.label}`);
    lines.push('');
    lines.push(`- URL: ${row.url}`);
    lines.push(`- GA4 sessions: ${row.ga4.sessionsCurrent} (prev ${row.ga4.sessionsPrevious}, ${formatSigned(row.ga4.sessionsDelta)} / ${formatSignedPct(row.ga4.sessionsDeltaPct) || '-'})`);
    lines.push(`- GSC clicks: ${row.gsc.clicksCurrent} (prev ${row.gsc.clicksPrevious}, ${formatSigned(row.gsc.clicksDelta)} / ${formatSignedPct(row.gsc.clicksDeltaPct) || '-'})`);
    lines.push(`- GSC impressions: ${row.gsc.impressionsCurrent} (prev ${row.gsc.impressionsPrevious}, ${formatSigned(row.gsc.impressionsDelta)} / ${formatSignedPct(row.gsc.impressionsDeltaPct) || '-'})`);
    lines.push(`- GSC CTR: ${formatPct(row.gsc.ctrCurrent)} (prev ${formatPct(row.gsc.ctrPrevious)}, delta ${formatSigned(row.gsc.ctrDelta)}pt)`);
    lines.push(`- GSC position: ${round(row.gsc.positionCurrent, 2)} (prev ${round(row.gsc.positionPrevious, 2)}, delta ${formatSigned(row.gsc.positionDelta)})`);
    if (row.topQueries.length) {
      lines.push('- Top queries:');
      for (const query of row.topQueries) {
        lines.push(`  - \`${query.query}\`: ${query.clicks} clicks / ${query.impressions} impressions / ${formatPct(query.ctr * 100)} / pos ${round(query.position, 2)}`);
      }
    } else {
      lines.push('- Top queries: none');
    }
    lines.push('');
  }

  return `${lines.join('\n')}\n`;
}

async function writeJson(filePath, value) {
  const directory = path.dirname(filePath);
  await mkdir(directory, { recursive: true });
  await writeFile(filePath, `${JSON.stringify(value, null, 2)}\n`, 'utf8');
}

async function writeText(filePath, value) {
  const directory = path.dirname(filePath);
  await mkdir(directory, { recursive: true });
  await writeFile(filePath, value, 'utf8');
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  if (args.help) {
    usage();
    return;
  }

  const property = args.property || process.env.GOOGLE_GA4_PROPERTY_ID || DEFAULT_GA4_PROPERTY;
  const site = args.site || process.env.GOOGLE_GSC_SITE || DEFAULT_GSC_SITE;
  if (!property) {
    throw new Error('Missing GA4 property id. Set GOOGLE_GA4_PROPERTY_ID or pass --property');
  }
  if (!site) {
    throw new Error('Missing Search Console site. Set GOOGLE_GSC_SITE or pass --site');
  }

  const startToken = args['start-date'] || '7daysAgo';
  const endToken = args['end-date'] || 'yesterday';
  const currentRange = {
    startDate: formatDate(resolveDateToken(startToken)),
    endDate: formatDate(resolveDateToken(endToken)),
  };
  const previousRange = getPreviousRange(startToken, endToken);
  const queryLimit = Number(args['query-limit'] || 5);
  const trackedPages = normalizeTrackedPages(args.pages, site);
  const outDir = path.resolve(process.cwd(), args['out-dir'] || 'daily/seo-followup');
  const label = args.label || 'seo-followup';
  const filenameBase = `${label}-${formatCompactDate(resolveDateToken(endToken))}`;

  const [ga4TokenData, gscTokenData] = await Promise.all([
    getAccessToken({
      keyFile: args['key-file'],
      scopes: ['https://www.googleapis.com/auth/analytics.readonly'],
    }),
    getAccessToken({
      keyFile: args['key-file'],
      scopes: ['https://www.googleapis.com/auth/webmasters.readonly'],
    }),
  ]);

  const [ga4Current, ga4Previous] = await Promise.all([
    runGa4Report({
      property,
      token: ga4TokenData.accessToken,
      startDate: currentRange.startDate,
      endDate: currentRange.endDate,
      trackedPages,
    }),
    runGa4Report({
      property,
      token: ga4TokenData.accessToken,
      startDate: previousRange.startDate,
      endDate: previousRange.endDate,
      trackedPages,
    }),
  ]);

  const gscCurrentMap = new Map();
  const gscPreviousMap = new Map();
  const queryMap = new Map();

  for (const page of trackedPages) {
    const [currentMetrics, previousMetrics, topQueries] = await Promise.all([
      fetchGscPageMetrics({
        token: gscTokenData.accessToken,
        site,
        pageUrl: page.url,
        startDate: currentRange.startDate,
        endDate: currentRange.endDate,
      }),
      fetchGscPageMetrics({
        token: gscTokenData.accessToken,
        site,
        pageUrl: page.url,
        startDate: previousRange.startDate,
        endDate: previousRange.endDate,
      }),
      fetchGscTopQueries({
        token: gscTokenData.accessToken,
        site,
        pageUrl: page.url,
        startDate: currentRange.startDate,
        endDate: currentRange.endDate,
        limit: queryLimit,
      }),
    ]);
    gscCurrentMap.set(page.url, currentMetrics);
    gscPreviousMap.set(page.url, previousMetrics);
    queryMap.set(page.url, topQueries);
  }

  let mergedRows = mergeGa4Rows(trackedPages, ga4Current, ga4Previous);
  mergedRows = mergeGscRows(mergedRows, gscCurrentMap, gscPreviousMap, queryMap);

  const markdown = buildMarkdown({
    currentRange,
    previousRange,
    rows: mergedRows,
    trackedPages,
  });

  const jsonPayload = {
    currentRange,
    previousRange,
    trackedPages,
    rows: mergedRows,
  };

  const markdownPath = path.join(outDir, `${filenameBase}.md`);
  const jsonPath = path.join(outDir, `${filenameBase}.json`);
  await Promise.all([
    writeText(markdownPath, markdown),
    writeJson(jsonPath, jsonPayload),
  ]);

  console.log(`SEO follow-up report written: ${markdownPath}`);
  console.log(`SEO follow-up data written: ${jsonPath}`);
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
