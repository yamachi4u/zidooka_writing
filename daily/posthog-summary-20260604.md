# PostHog Data Summary And Recommendations — 2026-06-04

## Scope

- Site: `zidooka.com`
- PostHog project memo: `drat/posthog-experiments.md`
- Local implementation checked:
  - `downloads/zidooka-tw/functions.php`
  - `downloads/zidooka-tw/assets/posthog-experiments.js`
- Active since: 2026-06-03

:::note
PostHog personal API access was added to `.env` on 2026-06-04. Raw API outputs are saved under `daily/posthog/`.
:::

## Current PostHog Setup

PostHog is injected from the theme when `POSTHOG_KEY` is available. The JavaScript initializes PostHog with:

- `api_host: https://us.i.posthog.com`
- `person_profiles: identified_only`

Five feature-flag experiments are active:

| Experiment | Flag key | Variants | Intended KPI |
|---|---|---:|---|
| Article font size | `zdk_font_size` | control / large | engagement, read time |
| Line height | `zdk_line_height` | control / loose | engagement, read time |
| TOC sticky | `zdk_toc_sticky` | control / sticky | scroll depth |
| Related posts layout | `zdk_related_posts` | control / grid4 | related-post CTR |
| Ad position | `zdk_ad_position` | control / early | ad visibility, RPM |

The implementation currently captures:

- `zdk_experiment_impression`
  - properties: `experiment`, `variant`
- `ad_click`
  - properties: `slot`, `path`

## API Data Pulled On 2026-06-04

Source files:

- `daily/posthog/top-events-20260604.json`
- `daily/posthog/experiment-impressions-20260604.json`
- `daily/posthog/ad-clicks-20260604.json`

Query window: last 7 days. The experiments were started on 2026-06-03, so this is effectively early rollout data.

Top PostHog events:

| Event | Events | Persons |
|---|---:|---:|
| `zdk_experiment_impression` | 1,798 | 325 |
| `$pageview` | 384 | 347 |
| `$pageleave` | 239 | 200 |
| `$feature_flag_called` | 180 | 36 |
| `$autocapture` | 44 | 33 |

Experiment impressions by variant:

| Experiment | Variant | Events | Persons |
|---|---:|---:|---:|
| `zdk_line_height` | null | 321 | 321 |
| `zdk_related_posts` | null | 321 | 321 |
| `zdk_font_size` | null | 321 | 321 |
| `zdk_ad_position` | null | 320 | 320 |
| `zdk_toc_sticky` | null | 320 | 320 |
| `zdk_toc_sticky` | sticky | 23 | 21 |
| `zdk_ad_position` | control | 22 | 19 |
| `zdk_line_height` | loose | 22 | 19 |
| `zdk_font_size` | large | 21 | 18 |
| `zdk_related_posts` | grid4 | 20 | 18 |
| `zdk_related_posts` | control | 19 | 17 |
| `zdk_font_size` | control | 18 | 17 |
| `zdk_ad_position` | early | 17 | 16 |
| `zdk_line_height` | control | 17 | 16 |
| `zdk_toc_sticky` | control | 16 | 14 |

`ad_click` returned no rows in this query window.

:::warning
Most experiment impressions are being recorded with `variant = null`. This means the current A/B data cannot be used to pick winners yet.
:::

## What The Data Can Tell Us Today

The experiments only started on 2026-06-03. As of 2026-06-04, the useful interpretation is instrumentation-focused, not winner-focused:

- PostHog is wired into the WordPress theme.
- Feature flags are being read client-side through `posthog.getFeatureFlag()`.
- Experiment impressions are captured for each active flag.
- Feature Flag definitions are active and configured as 50/50 multivariate flags.
- Ad click tracking is attempted on filled AdSense elements, but no `ad_click` rows were returned yet.

:::conclusion
Do not pick winning variants yet. The setup is too new, and most current impressions have `variant = null`, so the experiment data needs instrumentation repair before interpretation.
:::

## Main Issues Found

1. Too many experiments are running at once.

Five independent 50/50 flags create up to 32 combinations. That makes it hard to attribute a lift or drop to one design change unless PostHog experiments are analyzed with enough traffic and proper breakdowns.

2. Outcome events are incomplete.

`zdk_experiment_impression` tells us exposure, but not whether the user read further, clicked related posts, used TOC, or reached ad slots. `ad_click` exists, but it only covers one monetization signal and may be unreliable because ad iframe click tracking is limited.

3. Feature flag reads are likely happening before flags finish loading.

The flags are active in PostHog and have 50/50 variants, but the dominant impression rows have `variant = null`. The likely cause is this flow:

```js
if (typeof posthog !== 'undefined' && posthog.__loaded) {
  init();
}
```

`posthog.__loaded` does not guarantee feature flag values have been resolved. The experiment code should use `posthog.onFeatureFlags()` or otherwise avoid capturing impressions until a real string variant is available.

4. `zdk_related_posts` says `grid4`, but CSS uses 2 columns.

Current CSS:

```css
.exp-related-grid4 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
```

If the experiment is meant to test 4 items/columns, the implementation and experiment name are inconsistent.

5. `zdk_ad_position` does not clearly move an ad earlier.

Current CSS:

```css
.exp-ad-early .zidooka-xserver-ad:first-of-type { display: none; }
```

This hides the first matching ad inside `.entry-content`. That may reduce ad exposure instead of testing an earlier ad position.

6. TOC sticky selector is broad.

The selector includes `[class*="toc"]`, which may catch unintended elements. Sticky behavior should be checked on mobile and long posts before being trusted as a site-wide experiment.

## Recommendations

### Priority 1 — Fix Null Variant Capture

Update `assets/posthog-experiments.js` so it:

- waits for `posthog.onFeatureFlags()` before reading variants
- captures impressions only when `typeof variant === 'string'`
- optionally captures a separate diagnostic event such as `zdk_experiment_flag_missing` for missing flags

Expected impact: the next API pull should show `control` / treatment variants as the dominant rows instead of `null`.

### Priority 2 — Add Outcome Events Before Judging Winners

Add lightweight events that map to each experiment:

| Experiment | Add event | Useful properties |
|---|---|---|
| Font size / line height | `zdk_read_depth` | `percent`, `path`, `variant` |
| Font size / line height | `zdk_engaged_60s` | `path`, `variant` |
| TOC sticky | `zdk_toc_click` | `path`, `anchor`, `variant` |
| Related posts | `zdk_related_click` | `path`, `target`, `variant` |
| Ad position | `zdk_ad_visible` | `path`, `slot`, `variant` |

### Priority 3 — Reduce Active Experiments

Run experiments in smaller batches:

1. Keep `zdk_font_size` and `zdk_line_height` together only if the goal is readability.
2. Pause `zdk_related_posts`, `zdk_toc_sticky`, and `zdk_ad_position` until outcome events are implemented.
3. Re-enable one revenue or navigation experiment at a time.

### Priority 4 — Fix Naming / Implementation Mismatches

- Rename `grid4` to match the actual 2-column layout, or change CSS to the intended layout.
- Replace the ad-position CSS with a real insertion/movement strategy, or reframe the experiment as "hide first ad" if that is intentional.
- Narrow the TOC selector to known TOC markup only.

### Priority 5 — Create A Repeatable PostHog Export Script

Add a small script once a personal API key is available:

- env keys:
  - `POSTHOG_PERSONAL_API_KEY`
  - `POSTHOG_PROJECT_ID`
  - `POSTHOG_HOST`
- output:
  - `daily/posthog/posthog-events-YYYYMMDD.json`
  - `daily/posthog/posthog-summary-YYYYMMDD.md`

The first report should query:

- `zdk_experiment_impression` by `experiment` and `variant`
- outcome events by `experiment` / `variant`
- `ad_click` by `path` and `slot`
- top paths receiving experiment traffic

## Decision For This Week

:::step
Keep PostHog running, but treat 2026-06-03 to 2026-06-09 as an instrumentation validation period. Do not declare winners until outcome events and at least several days of traffic are available.
:::

## Action Taken Locally

`downloads/zidooka-tw/assets/posthog-experiments.js` was updated locally on 2026-06-04 to:

- wait for `posthog.onFeatureFlags()` when available
- skip impression capture until at least one string variant is resolved
- record `zdk_experiment_impression` only when `typeof variant === 'string'`

**Additional improvements applied 2026-06-04 (second pass):**

1. **Removed duplicate `init()` call** — When `onFeatureFlags` is available, `init()` is no longer called immediately after registration. This eliminates the race window where flags could be partially resolved.
2. **Added fallback polling** — When `onFeatureFlags` is not available (older PostHog versions), polls `getFeatureFlag` every 200ms (up to 20 attempts / 4s) until at least one flag resolves as a string.
3. **Timeout calls `init()` as last resort** — The 10s timeout now calls `init()` directly if initialization hasn't completed yet, instead of just clearing the interval silently.
4. **`init()` returns boolean** — Returns `true` when initialization is complete, `false` when flags are not yet resolved. This supports the polling mechanism without side effects.

:::note
The null-variant data in this report was captured by the **production theme** running an unguarded version. The local fix was applied after data collection. After deployment, the next API pull should show `control`/treatment variants as the dominant rows.
:::

:::note
This fix has been deployed to production via remote-agent WebDAV and verified. See the Deploy & Verification Log below.
:::

## Suggested Next Action

1. ~~**Deploy the updated `posthog-experiments.js` to production**~~ — **Done.** Pushed via remote-agent WebDAV, verified with `node --check`, pull-back exact match, and public URL confirmation.
2. ~~**Reduce active experiments**~~ — **Done.** 4 flags disabled via PostHog API (zdk_line_height, zdk_toc_sticky, zdk_related_posts, zdk_ad_position). Only zdk_font_size remains active.
3. ~~**Add outcome events**~~ — **Done.** Added zdk_read_depth, zdk_engaged_60s, zdk_toc_click, zdk_related_click.
4. Re-run the PostHog API pull and verify null-variant events drop to near-zero.
5. Consider adding a diagnostic `zdk_experiment_flag_missing` event triggered by the timeout fallback for future debugging.

---

## Deploy & Verification Log (2026-06-04)

### Push (remote-agent WEBDAV)
- **Source**: `downloads/zidooka-tw/assets/posthog-experiments.js`
- **Remote**: `zidooka/wp-content/themes/zidooka-tw/assets/posthog-experiments.js`
- **Backup**: `posthog-experiments.js.bak.1780534038323`

### Verification
| Check | Result |
|---|---|
| `node --check` syntax | ✅ Passed |
| Pull-back match (fc) | ✅ Exact match |
| Public URL fetch | ✅ All three fixes present |

### Confirmed Fixes In Production
1. **`posthog.onFeatureFlags(init)`** at line 17 — waits for flag resolution
2. **String-only capture guard** (`typeof flags[key] === 'string'`) at line 128 — filters out `null` variants
3. **Duplicate init removal** (`if (initialized) return true`) at line 69 — prevents re-entry

:::note
The next PostHog API pull should show `control`/treatment variants as the dominant rows instead of `null`.
:::

---

## Outcome Events & Flag Reduction (2026-06-04 2nd Pass)

### 1. Feature Flag Reduction (PostHog API)

All 5 flags were active simultaneously, creating up to 32 variant combinations → high confound risk.

| Flag | ID | Action | API Response |
|------|----|--------|-------------|
| `zdk_line_height` | 699961 | `PATCH {active:false}` | ✅ active=False |
| `zdk_toc_sticky` | 699964 | `PATCH {active:false}` | ✅ active=False |
| `zdk_related_posts` | 699962 | `PATCH {active:false}` | ✅ active=False |
| `zdk_ad_position` | 699963 | `PATCH {active:false}` | ✅ active=False |
| `zdk_font_size` | 699960 | **kept active** | ✅ active=True |

**zdk_ad_position はCSS上、最初の広告を隠す実装であり「広告視認性試験」として機能していないため、停止優先とした。**

### 2. Outcome Events Added

4 new `posthog.capture` events added to `posthog-experiments.js`. Each fires via event delegation (no mutation observers, no framework dependency).

| Event | Trigger | Props |
|-------|---------|-------|
| `zdk_read_depth` | scroll past 25/50/75/90% (throttled via rAF) | `depth`, `path`, `url`, `variants` |
| `zdk_engaged_60s` | 60s cumulative visible time (pause on tab hidden) | `path`, `url`, `variants` |
| `zdk_toc_click` | click on `.zidooka-toc a` / `.toc a` / `[class*="toc"] a` | `text`, `href`, `path`, `url`, `variants` |
| `zdk_related_click` | click on `.zidooka-related-posts a` / `.related-posts a` / `[class*="related"] a` | `text`, `href`, `path`, `url`, `variants` |

All existing features preserved:
- null variant prevention (`typeof flags[key] === 'string'` guard)
- `onFeatureFlags` + fallback polling + timeout safety
- CTA/consultation banners untouched

### 3. Variants Object Convention

`variants` prop is a flat object containing only resolved string variants at init time:
```json
{"font_size": "large"}
```

This is available on every outcome event for direct breakdown in PostHog Insights.

### 4. Operational Rule Updated

`drat/posthog-experiments.md` に以下を追記:
- 同時稼働は原則1本、多くても同一目的2本まで
- 広告/CTA系は常に単独実験
- 勝敗判定は impression ではなく outcome event で行う
