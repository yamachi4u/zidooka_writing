# AdSense API Operations

## Overview

Google AdSense reports are accessed via OAuth 2.0 Desktop flow (not Service Account) because the AdSense account (`pub-5002038850592836`) is under a different Google account than the GA4/GSC Cloud project.

## Auth Architecture

- **Cloud Project**: `erudite-wind-441111-n3` (アカウントA)
- **OAuth Client**: Desktop app (`749255848915-...`)
- **AdSense Account**: アカウントB (yamaguchi.kazunori.98@gmail.com)
- **Auth Flow**: PKCE OAuth → refresh token in `.env` → automatic token refresh on each run

## Commands

```powershell
# Daily revenue report (default: 28 days, JPY)
npm run adsense

# Custom period
npm run adsense -- --start-date 2026-01-01 --end-date 2026-05-27

# List accessible accounts
npm run adsense -- --list-accounts

# Raw JSON output
npm run adsense -- --json

# Other dimensions/metrics
npm run adsense -- --dimensions DATE,WEEK --metrics ESTIMATED_EARNINGS,IMPRESSIONS,CLICKS
```

## Re-auth (if refresh token expires)

```powershell
node scripts/adsense-oauth-setup.mjs
```

This opens a browser; log in with **アカウントB** and approve. Copy the new `GOOGLE_ADSENSE_REFRESH_TOKEN` to `.env`.

## Credentials (all in `.env`, gitignored)

| Var | Source |
|-----|--------|
| `GOOGLE_ADSENSE_ACCOUNT_ID` | AdSense dashboard URL |
| `GOOGLE_ADSENSE_CLIENT_ID` | Cloud Console OAuth Client |
| `GOOGLE_ADSENSE_CLIENT_SECRET` | Cloud Console OAuth Client |
| `GOOGLE_ADSENSE_REFRESH_TOKEN` | `node scripts/adsense-oauth-setup.mjs` output |

## Scripts

- `scripts/adsense-report.mjs` — Main report CLI
- `scripts/adsense-oauth-setup.mjs` — One-time OAuth token setup

## Agent Integration

Codex / Claude Code / Opencode can invoke:

```powershell
node scripts/adsense-report.mjs --start-date 2026-05-01 --end-date 2026-05-27
```

The script reads `.env` automatically via `dotenv/config`.
