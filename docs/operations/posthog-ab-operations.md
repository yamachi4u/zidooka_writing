# PostHog A/B Operations — ZIDOOKA

Last updated: 2026-06-05

## Purpose

This file tells future agents what PostHog A/B operation is currently being run on `zidooka.com`, why it is configured that way, and what must be checked before changing it.

This is a child operation under `docs/operations/README.md`. If the way operations are managed changes, update the registry README first, then update this file.

## Current State

Only one A/B test should be active:

| Flag | State | Reason |
|---|---|---|
| `zdk_font_size` | active | Readability test. Low-risk and can be judged with read-depth / engagement outcomes. |
| `zdk_line_height` | inactive | Stopped to avoid confounding with font-size readability effects. |
| `zdk_toc_sticky` | inactive | Stopped until navigation outcomes have enough baseline data. |
| `zdk_related_posts` | inactive | Stopped until related-click measurement has enough baseline data. |
| `zdk_ad_position` | inactive | Stopped because current CSS selector targets `.zidooka-xserver-ad` (affiliate banner), not AdSense. Revenue experiments must be isolated. CSS also hides rather than repositions the ad. Needs implementation review before re-activation. |

## Why This Operation Exists

On 2026-06-04, five 50/50 flags were active at the same time. That can create up to 32 combined variants, making it difficult to know which change caused a change in behavior.

The initial implementation also recorded many `zdk_experiment_impression` events with `variant = null`. That was fixed by waiting for PostHog feature flags and capturing impressions only when the variant is a string.

After that, outcome events were added so future decisions can be based on behavior, not just exposure.

## Weekly Check Cadence

Run `npm run posthog:check` at least once per week (recommended: every Monday + Thursday).

```
npm run posthog:check
```

This generates a structured report at `daily/posthog/YYYY-MM-DD.md` containing:
- Current flag states
- Active experiment health check (null rate, sample sizes)
- Variant impression breakdown
- Outcome event comparison with lift calculation
- Winner recommendation or "check again" date
- Pipeline — next experiment candidate

The script uses `POSTHOG_PERSONAL_API_KEY` and `POSTHOG_PROJECT_ID` from `.env`. These must be present.

When an agent opens a session and detects PostHog A/B work is relevant, the first action should be to run `npm run posthog:check` and read the latest report.

## Decision Thresholds

These are hard thresholds defined in `scripts/posthog-check.mjs` (`DECISION` block). Update the script and this doc together when changing thresholds.

| Threshold | Value | Applies To |
|---|---|---|
| `minDays` | 5 | Minimum days of clean data before deciding |
| `minImpressionsPerVariant` | 200 | Minimum `zdk_experiment_impression` per variant |
| `minOutcomesPerVariant` | 100 | Minimum total outcome events per variant (sum of all 4 outcome events) |
| `meaningfulLift` | 15% | Minimum lift on an outcome metric to count as a win |
| `maxNullRate` | 30% | Maximum allowable null-variant rate before data is declared unhealthy |

### Decision Logic

For any active experiment:

1. **Health guard**: If null rate > `maxNullRate`, do NOT decide. Fix instrumentation first.
2. **Sample guard**: If impressions or outcomes are below minimums, extend the experiment and re-check.
3. **Winner determination**: Compare outcome *rates* per impression, not raw event counts.
   - Primary signals: `zdk_read_depth` (75%+90%), `zdk_engaged_60s`
   - Secondary signals: `zdk_toc_click`, `zdk_related_click`
   - If one variant wins on more outcomes than the other with >15% lift each, it's the winner.
   - If no outcome reaches 15% lift in either direction, declare inconclusive and close as no-difference.
4. **After decision**: Update `drat/posthog-experiments.md` with the result, then move to the next pipeline candidate.

## Experiment Lifecycle

```
[Propose] → [Queue] → [Implement] → [Monitor] → [Decide] → [Apply Winner] → [Archive]
                                                       ↓ (or)
                                                  [Close (no diff)]
```

### Phase Timeline

| Phase | Typical Duration | Action |
|---|---|---|
| Propose | any time | Add to pipeline section in `drat/posthog-experiments.md` |
| Queue | until next slot | Priority-ordered in pipeline |
| Implement | 1 session | Implement JS/CSS changes, create/activate flag, deploy, update registry |
| Monitor | 5–14 days | `npm run posthog:check` twice/week; wait for thresholds |
| Decide | 1 session | Read latest report; apply or reject |
| Apply Winner | 1 session | Merge winning variant into production code; close flag; archive |

## Active Outcome Events

Implemented in `downloads/zidooka-tw/assets/posthog-experiments.js` and deployed to:

`https://www.zidooka.com/wp-content/themes/zidooka-tw/assets/posthog-experiments.js`

| Event | Trigger | Main properties |
|---|---|---|
| `zdk_read_depth` | 25/50/75/90% scroll depth | `depth`, `path`, `url`, `variants` |
| `zdk_engaged_60s` | 60 seconds of visible time | `path`, `url`, `variants` |
| `zdk_toc_click` | TOC link click | `text`, `href`, `path`, `url`, `variants` |
| `zdk_related_click` | related-post link click | `text`, `href`, `path`, `url`, `variants` |
| `zdk_experiment_impression` | resolved feature flag exposure | `experiment`, `variant` |

`variants` should contain only resolved string variants, for example:

```json
{"font_size":"large"}
```

## Operating Rules

- Run one experiment at a time by default.
- At most, run two experiments only when they share the same purpose and can be interpreted together.
- Never run ad or CTA experiments together with readability or navigation experiments.
- CTA / consultation banner experiments are prohibited because increased inquiry volume can exceed business capacity.
- Do not use `zdk_experiment_impression` alone to pick winners.
- Use outcome rates by variant:
  - `zdk_read_depth` 75% / 90%
  - `zdk_engaged_60s`
  - `zdk_toc_click`
  - `zdk_related_click`
- Keep `drat/posthog-experiments.md` updated whenever a flag is created, stopped, restarted, or judged.
- Run `npm run posthog:check` and read `daily/posthog/YYYY-MM-DD.md` before making experiment decisions.

## Current Experiment Schedule

| Experiment | Start | Earliest Decision | Status |
|---|---|---|---|
| `zdk_font_size` | 2026-06-03 | 2026-06-10 | monitoring (check Mon/Thu) |

## Verification Checklist

Before and after any PostHog experiment change:

```powershell
node --check downloads/zidooka-tw/assets/posthog-experiments.js
node scripts/remote-agent/index.js push --file=zidooka/wp-content/themes/zidooka-tw/assets/posthog-experiments.js --src=downloads/zidooka-tw/assets/posthog-experiments.js
node scripts/remote-agent/index.js pull --file=zidooka/wp-content/themes/zidooka-tw/assets/posthog-experiments.js --out=tmp_remote_agent/posthog-experiments.js
```

Then verify the public file includes the expected event names and null-variant guard:

```powershell
$url = 'https://www.zidooka.com/wp-content/themes/zidooka-tw/assets/posthog-experiments.js'
$body = (Invoke-WebRequest -Uri $url -UseBasicParsing).Content
@('posthog.onFeatureFlags(init)', "typeof flags[key] === 'string'", 'zdk_read_depth', 'zdk_engaged_60s', 'zdk_toc_click', 'zdk_related_click') | ForEach-Object {
  if ($body.Contains($_)) { "OK $_" } else { "MISSING $_" }
}
```

## PostHog API Check

Use `.env` values without printing secrets.

```powershell
$envPath = '.env'
Get-Content $envPath | ForEach-Object {
  if ($_ -match '^([^#=]+)=(.*)$') {
    [Environment]::SetEnvironmentVariable($matches[1].Trim(), $matches[2].Trim(), 'Process')
  }
}
$headers = @{ Authorization = "Bearer $env:POSTHOG_PERSONAL_API_KEY" }
$resp = Invoke-RestMethod -Uri "$env:POSTHOG_HOST/api/projects/$env:POSTHOG_PROJECT_ID/feature_flags/?limit=50" -Headers $headers
$resp.results | Where-Object { $_.key -like 'zdk_*' } | Sort-Object key | ForEach-Object { "$($_.key) active=$($_.active)" }
```

Expected state:

```text
zdk_ad_position active=False
zdk_font_size active=True
zdk_line_height active=False
zdk_related_posts active=False
zdk_toc_sticky active=False
```

## Health Monitoring

These checks are automated in `npm run posthog:check`. Key health signals to watch:

| Signal | Green | Yellow | Red |
|---|---|---|---|
| Null variant rate | <20% | 20–30% | >30% |
| Impressions/variant/week | >300 | 100–300 | <100 |
| Weekly check compliance | 2x/week | 1x/week | 0x/week |

If null rate is in yellow or red, investigate before deciding. Possible causes:
- `posthog-experiments.js` not deployed to production (check via public URL fetch)
- `posthog.onFeatureFlags` timing edge case (investigate with diagnostic event)
- Cache/CDN serving old version of the JS file

### Null Rate Troubleshooting Procedure

When null rate exceeds 30% (red), follow these steps in order:

1. **Verify JS deployment**:
   ```powershell
   $url = 'https://www.zidooka.com/wp-content/themes/zidooka-tw/assets/posthog-experiments.js'
   $body = (Invoke-WebRequest -Uri $url -UseBasicParsing).Content
   $body.Contains('zdk_flag_resolution_error')
   ```
   If `False`, the latest JS with the diagnostic event is not deployed. Push it immediately.

2. **Check diagnostic events**:
   ```powershell
   # Query PostHog for zdk_flag_resolution_error events (7d)
   ```
   Run `npm run posthog:check` and read the Meta Recommendations section. Look for `flag_resolution_errors` entry.
   - If `timeout` dominates (10s safety net): PostHog SDK may be slow to evaluate flags. Consider extending the timeout from 10s to 15s.
   - If `fallback_exhausted` dominates: `onFeatureFlags` callback may not be available. Investigate the PostHog SDK version.
   - If both are low but null rate is high: The `init()` path that captures impressions may be blocking too early. Check if `hasResolvedFlag` check is correctly gating.

3. **Clear CDN/cache**:
   - If using Lolipop or other shared host with server-side cache, clear it.
   - Add a cache-busting query parameter to the JS URL temporarily: rename file or update `filemtime` reference.
   - Verify by requesting the URL with `?t=<timestamp>` and comparing with the local file.

4. **Verify PostHog env vars**:
   ```powershell
   node -e "require('dotenv/config'); console.log('HOST:', process.env.POSTHOG_HOST); console.log('PROJ:', process.env.POSTHOG_PROJECT_ID); console.log('KEY:', process.env.POSTHOG_PERSONAL_API_KEY ? 'SET' : 'MISSING');"
   ```
   Ensure `POSTHOG_HOST` is `https://us.posthog.com` (not `https://us.i.posthog.com` which is the SDK endpoint).

5. **Re-check after fix**:
   ```powershell
   npm run posthog:check
   ```
   Null rate should drop below 30% within 24-48 hours as old cached sessions expire and new resolved sessions accumulate.

6. **If null rate persists above 30%** after all steps:
   - The experiment data is too noisy to decide. Options:
     - Close the experiment as inconclusive and move to the next pipeline item.
     - Switch from PostHog feature-flag A/B to a simpler server-side A/B (random assignment in PHP, no client-side flag dependency).
     - Add a prominent "this session is not in the experiment" fallback that still captures outcome events for baseline data.

## Delegation To Opencode

The preferred implementer for PostHog changes is:

`opencode-go/deepseek-v4-flash`

When delegating, require:

- implementation in `downloads/zidooka-tw/assets/posthog-experiments.js`
- `node --check`
- remote-agent push
- pull-back exact match
- public URL confirmation
- PostHog API flag-state confirmation when flags are changed
- updates to `drat/posthog-experiments.md`

## References

- `drat/posthog-experiments.md` — experiment registry, pipeline, action log
- `daily/posthog/YYYY-MM-DD.md` — automated weekly check reports
- `daily/posthog-summary-20260604.md` — initial implementation summary
- `scripts/posthog-check.mjs` — automated check script
- `downloads/zidooka-tw/assets/posthog-experiments.js` — JS implementation
- `docs/REMOTE_UPLOAD.md`
