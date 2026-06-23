# Bing Webmaster Tools API Operations

## Prerequisites

1. Sign in to [Bing Webmaster Tools](https://www.bing.com/webmasters)
2. Add and verify your site (e.g., `https://www.zidooka.com/`)
3. Settings → API Access → Generate API Key
4. Add `BING_API_KEY` to `.env`

## Commands

```powershell
# Daily rank & traffic stats (default)
npm run bing

# Top search queries
npm run bing -- --preset top-queries --limit 30

# Top pages by traffic
npm run bing -- --preset top-pages --limit 20

# Crawl statistics
npm run bing -- --preset crawl-stats

# URL index status
npm run bing -- --preset url-info --page /some-page

# Query detail (which pages rank for a query)
npm run bing -- --preset query-detail --query "something went wrong"

# Raw JSON
npm run bing -- --preset rank-traffic --json

# CSV output
npm run bing -- --preset top-queries --csv-out daily/bing-queries.csv
```

## Integration Notes

- API key is user-scoped (not per-site), works across all verified sites.
- The JSON endpoint (`/api.svc/json/`) is used for all calls.
- Site URL defaults to `GOOGLE_GSC_SITE` from `.env` if `BING_SITE_URL` is not set.
- Combine with `npm run adsense`, `npm run ga4`, and `npm run gsc` for cross-channel analysis.

## Agent Integration

```powershell
node scripts/bing-webmaster-report.mjs --preset rank-traffic
node scripts/bing-webmaster-report.mjs --preset top-queries --limit 10
```
