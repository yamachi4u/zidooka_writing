import {
  fetchJson,
  getAccessToken,
  parseArgs,
  printTable,
  splitCsv,
} from './google-api-common.mjs';

function usage() {
  console.log(`Usage:
  node scripts/gsc-query.mjs [options]

Options:
  --site        Search Console property string (default: GOOGLE_GSC_SITE)
  --start-date  Start date YYYY-MM-DD (required unless preset defaults used)
  --end-date    End date YYYY-MM-DD (required unless preset defaults used)
  --dimensions  CSV dimensions (default: query,page)
  --type        web | image | video | news | discover | googleNews (default: web)
  --limit       Row limit (default: 25)
  --preset      top-queries | top-pages | page-query
  --json        Print raw JSON
  --key-file    Service account JSON path

Examples:
  npm run gsc -- --site=https://www.zidooka.com/ --preset top-queries --start-date=2026-02-25 --end-date=2026-03-23
  npm run gsc -- --site=https://tools.zidooka.com/ --dimensions=page --limit 20`);
}

function buildPreset(preset) {
  switch (preset) {
    case 'top-pages':
      return { dimensions: ['page'] };
    case 'page-query':
      return { dimensions: ['page', 'query'] };
    case 'top-queries':
    default:
      return { dimensions: ['query', 'page'] };
  }
}

function normalizeRows(data, dimensions) {
  return (data.rows || []).map((row) => {
    const mapped = {};
    dimensions.forEach((dimension, index) => {
      mapped[dimension] = row.keys?.[index] ?? '';
    });
    mapped.clicks = row.clicks ?? 0;
    mapped.impressions = row.impressions ?? 0;
    mapped.ctr = row.ctr ?? 0;
    mapped.position = row.position ?? 0;
    return mapped;
  });
}

function formatRows(rows) {
  return rows.map((row) => ({
    ...row,
    ctr: `${(Number(row.ctr) * 100).toFixed(2)}%`,
    position: Number(row.position).toFixed(2),
  }));
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  if (args.help) {
    usage();
    return;
  }

  const site = args.site || process.env.GOOGLE_GSC_SITE;
  if (!site) {
    throw new Error('Missing Search Console site. Set GOOGLE_GSC_SITE or pass --site');
  }

  const preset = buildPreset(args.preset || 'top-queries');
  const dimensions = splitCsv(args.dimensions, preset.dimensions);
  const startDate = args['start-date'];
  const endDate = args['end-date'];

  if (!startDate || !endDate) {
    throw new Error('Missing --start-date or --end-date');
  }

  const body = {
    startDate,
    endDate,
    dimensions,
    type: args.type || 'web',
    rowLimit: Number(args.limit || 25),
  };

  const { accessToken } = await getAccessToken({
    keyFile: args['key-file'],
    scopes: ['https://www.googleapis.com/auth/webmasters.readonly'],
  });

  const encodedSite = encodeURIComponent(site);
  const data = await fetchJson(
    `https://searchconsole.googleapis.com/webmasters/v3/sites/${encodedSite}/searchAnalytics/query`,
    {
      method: 'POST',
      token: accessToken,
      body,
    },
  );

  if (args.json) {
    console.log(JSON.stringify(data, null, 2));
    return;
  }

  console.log(`GSC site ${site}`);
  console.log(`Date range: ${startDate} -> ${endDate}`);
  printTable(formatRows(normalizeRows(data, dimensions)));
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
