import {
  fetchJson,
  getAccessToken,
  parseArgs,
  printTable,
  splitCsv,
} from './google-api-common.mjs';

const GOOGLE_TOKEN_URL = 'https://oauth2.googleapis.com/token';

function usage() {
  console.log(`Usage:
  node scripts/adsense-report.mjs [options]

Options:
  --account    AdSense account ID (default: GOOGLE_ADSENSE_ACCOUNT_ID)
  --start-date  Start date (default: 28daysAgo)
  --end-date    End date (default: yesterday)
  --dimensions  CSV dimensions (default: DATE)
  --metrics     CSV metrics (default: ESTIMATED_EARNINGS,IMPRESSIONS,CLICKS,PAGE_VIEWS_RPM,COST_PER_CLICK)
  --limit       Row limit (default: 30)
  --currency    Currency code (default: JPY)
  --json        Print raw JSON
  --key-file    Service account JSON path
  --oauth       Use OAuth refresh token (default: auto if GOOGLE_ADSENSE_REFRESH_TOKEN is set)
  --list-accounts  List accessible AdSense accounts

Examples:
  node scripts/adsense-report.mjs --list-accounts
  node scripts/adsense-report.mjs --dimensions DATE,WEEK --metrics ESTIMATED_EARNINGS,IMPRESSIONS,CLICKS
  node scripts/adsense-report.mjs --start-date 2026-01-01 --end-date 2026-05-27`);
}

function formatDateParam(year, month, day) {
  return { year, month, day };
}

function parseDateToken(token) {
  if (/^\d{4}-\d{2}-\d{2}$/.test(token)) {
    const [y, m, d] = token.split('-').map(Number);
    return formatDateParam(y, m, d);
  }
  const now = new Date();
  now.setHours(0, 0, 0, 0);
  if (token === 'today') {
    return formatDateParam(now.getFullYear(), now.getMonth() + 1, now.getDate());
  }
  if (token === 'yesterday') {
    const d = new Date(now);
    d.setDate(d.getDate() - 1);
    return formatDateParam(d.getFullYear(), d.getMonth() + 1, d.getDate());
  }
  const ago = token.match(/^(\d+)daysAgo$/);
  if (ago) {
    const d = new Date(now);
    d.setDate(d.getDate() - Number(ago[1]));
    return formatDateParam(d.getFullYear(), d.getMonth() + 1, d.getDate());
  }
  throw new Error(`Unsupported date token: ${token}`);
}

function normalizeRows(data) {
  return (data.rows || []).map((row) => {
    const mapped = {};
    (data.headers || []).forEach((header, index) => {
      const name = header.name || header;
      const cell = row.cells?.[index];
      mapped[name] = cell?.value ?? '';
    });
    return mapped;
  });
}

async function getOAuthAccessToken() {
  const clientId = process.env.GOOGLE_ADSENSE_CLIENT_ID;
  const clientSecret = process.env.GOOGLE_ADSENSE_CLIENT_SECRET;
  const refreshToken = process.env.GOOGLE_ADSENSE_REFRESH_TOKEN;

  if (!clientId || !clientSecret || !refreshToken) {
    throw new Error('Missing OAuth credentials. Set GOOGLE_ADSENSE_CLIENT_ID, GOOGLE_ADSENSE_CLIENT_SECRET, and GOOGLE_ADSENSE_REFRESH_TOKEN in .env');
  }

  const response = await fetch(GOOGLE_TOKEN_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      client_id: clientId,
      client_secret: clientSecret,
      refresh_token: refreshToken,
      grant_type: 'refresh_token',
    }),
  });

  if (!response.ok) {
    const detail = await response.text();
    throw new Error(`Token refresh failed (${response.status}): ${detail}`);
  }

  const data = await response.json();
  return data.access_token;
}

async function listAccounts(token) {
  const data = await fetchJson('https://adsense.googleapis.com/v2/accounts', {
    token,
  });
  return data.accounts || [];
}

async function generateReport(account, token, body) {
  const params = new URLSearchParams();
  if (body.dateRange) {
    params.set('dateRange', body.dateRange);
  }
  if (body.startDate) {
    params.set('startDate.year', body.startDate.year);
    params.set('startDate.month', body.startDate.month);
    params.set('startDate.day', body.startDate.day);
  }
  if (body.endDate) {
    params.set('endDate.year', body.endDate.year);
    params.set('endDate.month', body.endDate.month);
    params.set('endDate.day', body.endDate.day);
  }
  if (body.dimensions) {
    body.dimensions.forEach((dim) => params.append('dimensions', dim));
  }
  if (body.metrics) {
    body.metrics.forEach((met) => params.append('metrics', met));
  }
  if (body.limit) {
    params.set('limit', body.limit);
  }
  if (body.currencyCode) {
    params.set('currencyCode', body.currencyCode);
  }

  return fetchJson(
    `https://adsense.googleapis.com/v2/accounts/${encodeURIComponent(account)}/reports:generate?${params}`,
    { token },
  );
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  if (args.help) {
    usage();
    return;
  }

  const useOAuth = args.oauth || process.env.GOOGLE_ADSENSE_REFRESH_TOKEN;
  const accessToken = useOAuth
    ? await getOAuthAccessToken()
    : (await getAccessToken({
        keyFile: args['key-file'],
        scopes: ['https://www.googleapis.com/auth/adsense.readonly'],
      })).accessToken;

  if (args['list-accounts']) {
    const accounts = await listAccounts(accessToken);
    if (!accounts.length) {
      console.log('No accessible AdSense accounts found.');
      return;
    }
    printTable(accounts.map((a) => ({
      name: a.displayName,
      id: a.name,
      reportingId: a.reportingDimensionId,
      currency: a.currencyCode,
      timezone: a.timeZone,
    })));
    return;
  }

  const account = args.account || process.env.GOOGLE_ADSENSE_ACCOUNT_ID;
  if (!account) {
    throw new Error('Missing account. Set GOOGLE_ADSENSE_ACCOUNT_ID or pass --account');
  }

  const dimensions = splitCsv(args.dimensions, ['DATE']);
  const metrics = splitCsv(args.metrics, ['ESTIMATED_EARNINGS', 'IMPRESSIONS', 'CLICKS', 'PAGE_VIEWS_RPM', 'COST_PER_CLICK']);
  const startDate = parseDateToken(args['start-date'] || '28daysAgo');
  const endDate = parseDateToken(args['end-date'] || 'yesterday');
  const limit = Number(args.limit || 30);
  const currencyCode = args.currency || 'JPY';

  const data = await generateReport(account, accessToken, {
    dateRange: 'CUSTOM',
    startDate,
    endDate,
    dimensions,
    metrics,
    limit,
    currencyCode,
  });

  if (args.json) {
    console.log(JSON.stringify(data, null, 2));
    return;
  }

  console.log(`AdSense account: ${account}`);
  console.log(`Date range: ${args['start-date'] || '28daysAgo'} -> ${args['end-date'] || 'yesterday'}`);
  printTable(normalizeRows(data));
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
