import 'dotenv/config';
import { parseArgs, printTable, splitCsv } from './google-api-common.mjs';

const API_BASE = 'https://ssl.bing.com/webmaster/api.svc/json';

function usage() {
  console.log(`Usage:
  node scripts/bing-webmaster-report.mjs [options]

Options:
  --site        Site URL (default: GOOGLE_GSC_SITE or BING_SITE_URL)
  --preset      rank-traffic | top-queries | top-pages | crawl-stats | url-info | query-detail
  --query       Query string (for query-detail preset)
  --page        Page path (for url-info preset)
  --start-date  Start date YYYY-MM-DD (for query-detail)
  --end-date    End date YYYY-MM-DD (for query-detail)
  --limit       Row limit (default: 25)
  --json        Print raw JSON
  --key         Bing API key (default: BING_API_KEY)

Presets:
  rank-traffic  GetRankAndTrafficStats — daily impressions/clicks
  top-queries   GetQueryStats — top search queries
  top-pages     GetPageStats — top pages by traffic
  crawl-stats   GetCrawlStats — crawl volume data
  url-info      GetUrlInfo — index status for a single page (requires --page)
  query-detail  GetQueryPageStats — detail for a specific query (requires --query)

Examples:
  node scripts/bing-webmaster-report.mjs --preset rank-traffic
  node scripts/bing-webmaster-report.mjs --preset top-queries --limit 20
  node scripts/bing-webmaster-report.mjs --preset url-info --page /some-page
  node scripts/bing-webmaster-report.mjs --preset crawl-stats --json`);
}

async function bingApiCall(method, params, apiKey) {
  const url = new URL(`${API_BASE}/${method}`);
  url.searchParams.set('apikey', apiKey);
  for (const [key, value] of Object.entries(params)) {
    if (value !== undefined && value !== null) {
      url.searchParams.set(key, String(value));
    }
  }

  const response = await fetch(url.toString());
  if (!response.ok) {
    const text = await response.text();
    throw new Error(`Bing API error (${response.status}): ${text}`);
  }
  return response.json();
}

function extractArray(data) {
  if (Array.isArray(data)) return data;
  if (data && Array.isArray(data.d)) return data.d;
  return [];
}

function parseNetDate(value) {
  if (!value) return '';
  const match = String(value).match(/\/Date\((\d+)/);
  if (match) {
    const d = new Date(Number(match[1]));
    return d.toISOString().slice(0, 10);
  }
  return String(value).slice(0, 10);
}

function normalizeRankTraffic(data) {
  const items = extractArray(data);
  if (!items.length) {
    return [];
  }
  return items.map((item) => ({
    Date: parseNetDate(item.Date),
    Impressions: item.Impressions ?? 0,
    Clicks: item.Clicks ?? 0,
  }));
}

function normalizeTopQueries(data) {
  const items = extractArray(data);
  if (!items.length) {
    return [];
  }
  return items.map((item) => ({
    Query: item.Query ?? '',
    Impressions: item.Impressions ?? 0,
    Clicks: item.Clicks ?? 0,
    ClicksPerDay: typeof item.ClicksPerDay === 'number' ? item.ClicksPerDay.toFixed(2) : (item.ClicksPerDay ?? ''),
    ImpressionsPerDay: typeof item.ImpressionsPerDay === 'number' ? item.ImpressionsPerDay.toFixed(2) : (item.ImpressionsPerDay ?? ''),
  }));
}

function normalizeTopPages(data) {
  const items = extractArray(data);
  if (!items.length) {
    return [];
  }
  return items.map((item) => ({
    Page: item.Page ?? '',
    Impressions: item.Impressions ?? 0,
    Clicks: item.Clicks ?? 0,
  }));
}

function normalizeCrawlStats(data) {
  const items = extractArray(data);
  if (!items.length) {
    return [];
  }
  return items.map((item) => ({
    Date: parseNetDate(item.Date),
    CrawledPages: item.CrawledPages ?? 0,
    CrawlErrors: item.CrawlErrors ?? 0,
  }));
}

function normalizeUrlInfo(data) {
  if (!data) {
    return [];
  }
  return [{
    Page: data.Page ?? '',
    IsIndexed: data.IsIndexed ?? false,
    CrawlStatus: data.CrawlStatus ?? '',
    LastCrawled: data.LastCrawled ?? '',
  }];
}

function normalizeQueryDetail(data) {
  const items = extractArray(data);
  if (!items.length) {
    return [];
  }
  return items.map((item) => ({
    Page: item.Page ?? '',
    Impressions: item.Impressions ?? 0,
    Clicks: item.Clicks ?? 0,
    Position: typeof item.AveragePosition === 'number' ? item.AveragePosition.toFixed(1) : (item.AveragePosition ?? ''),
  }));
}

async function main() {
  const args = parseArgs(process.argv.slice(2));
  if (args.help) {
    usage();
    return;
  }

  const apiKey = args.key || process.env.BING_API_KEY;
  if (!apiKey) {
    throw new Error('Missing Bing API key. Set BING_API_KEY or pass --key');
  }

  const siteUrl = args.site || process.env.GOOGLE_GSC_SITE || process.env.BING_SITE_URL;
  if (!siteUrl) {
    throw new Error('Missing site URL. Set GOOGLE_GSC_SITE, BING_SITE_URL, or pass --site');
  }

  const preset = args.preset || 'rank-traffic';

  let data;

  switch (preset) {
    case 'rank-traffic': {
      data = await bingApiCall('GetRankAndTrafficStats', { siteUrl }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeRankTraffic(data);
      console.log(`Bing Webmaster — Rank & Traffic Stats`);
      console.log(`Site: ${siteUrl}`);
      printTable(rows);
      break;
    }

    case 'top-queries': {
      data = await bingApiCall('GetQueryStats', { siteUrl }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeTopQueries(data).slice(0, Number(args.limit || 25));
      console.log(`Bing Webmaster — Top Queries`);
      console.log(`Site: ${siteUrl}`);
      printTable(rows);
      break;
    }

    case 'top-pages': {
      data = await bingApiCall('GetPageStats', { siteUrl }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeTopPages(data).slice(0, Number(args.limit || 25));
      console.log(`Bing Webmaster — Top Pages`);
      console.log(`Site: ${siteUrl}`);
      printTable(rows);
      break;
    }

    case 'crawl-stats': {
      data = await bingApiCall('GetCrawlStats', { siteUrl }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeCrawlStats(data);
      console.log(`Bing Webmaster — Crawl Stats`);
      console.log(`Site: ${siteUrl}`);
      printTable(rows);
      break;
    }

    case 'url-info': {
      const page = args.page;
      if (!page) {
        throw new Error('--page is required for url-info preset');
      }
      const fullUrl = page.startsWith('http') ? page : `${siteUrl.replace(/\/$/, '')}${page}`;
      data = await bingApiCall('GetUrlInfo', { siteUrl, url: fullUrl }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeUrlInfo(data);
      console.log(`Bing Webmaster — URL Info`);
      console.log(`Site: ${siteUrl}`);
      printTable(rows);
      break;
    }

    case 'query-detail': {
      const query = args.query;
      if (!query) {
        throw new Error('--query is required for query-detail preset');
      }
      data = await bingApiCall('GetQueryPageStats', { siteUrl, query }, apiKey);
      if (args.json) {
        console.log(JSON.stringify(data, null, 2));
        return;
      }
      const rows = normalizeQueryDetail(data).slice(0, Number(args.limit || 25));
      console.log(`Bing Webmaster — Query Detail`);
      console.log(`Site: ${siteUrl}, Query: "${query}"`);
      printTable(rows);
      break;
    }

    default:
      throw new Error(`Unknown preset: ${preset}`);
  }

  if (args['csv-out'] && data) {
    const rows = normalizeRankTraffic(data)
      .concat(normalizeTopQueries(data))
      .concat(normalizeTopPages(data));
    if (rows.length) {
      const { writeFile, mkdir } = await import('fs/promises');
      const path = await import('path');
      const dir = path.default.dirname(args['csv-out']);
      if (dir && dir !== '.') {
        await mkdir(dir, { recursive: true });
      }
      const headers = Object.keys(rows[0]);
      const csv = [
        headers.join(','),
        ...rows.map((r) => headers.map((h) => `"${String(r[h] ?? '').replace(/"/g, '""')}"`).join(',')),
      ].join('\n');
      await writeFile(args['csv-out'], csv + '\n', 'utf8');
      console.log(`CSV written: ${args['csv-out']}`);
    }
  }
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
