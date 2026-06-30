# PostHog A/B Status
> Last check: 2026-06-30  |  Run `npm run posthog:check` to refresh
> **Before acting**: Check `daily-agent/YYYYMMDD.md` for active claims

## Active Experiment

| Field | Value |
|-------|-------|
| Flag | `zdk_header_image` |
| Days running | 7 |
| Decision deadline | 2026-07-07 |

## Health

| Metric | Status | Value |
|--------|--------|-------|
| Null rate | OK | 0.0% (max 30.0%) → |
| Impressions (ctrl/treat) | OK | 991 / 1018 |
| Outcome events (ctrl/treat) | LOW | 0 / 1 |

## Outcomes

| Event | Ctrl | Treat | Lift |
|-------|------|-------|------|
| Read Depth (25/50/75/90%) | 0 | 1 | +∞ |
| Engaged 60s | 0 | 0 | - |
| TOC Click | 0 | 0 | - |
| Related Click | 0 | 0 | - |

## Next Action

**[WAIT]** wait_outcomes: Need 100 outcome events per variant. Currently have 0/1 (c/t). Check again in a few days.

### Closeout Steps

1. Wait 3-4 days for more data to accumulate
2. Re-run `npm run posthog:check`
3. If still insufficient after 14 total days, consider closing as inconclusive

## Pipeline

| Status | Experiment | Flag |
|--------|------------|------|
| **running** | Header image | `zdk_header_image` |
| **running** | Code block fold | `zdk_code_fold` |
| pending | zdk_toc_sticky | `zdk_toc_sticky` |
| pending | zdk_ad_position | `zdk_ad_position` |
| pending | zdk_related_posts | `zdk_related_posts` |
| pending | zdk_line_height | `zdk_line_height` |
| pending | zdk_font_size | `zdk_font_size` |

## Meta Alerts

- **[MED]** flag_resolution_errors: 15 zdk_flag_resolution_error events in past 7 days.
- **[MED]** dead_outcome: Outcome event(s) with zero data: Engaged 60s, TOC Click, Related Click.

---
*Full: daily/posthog/2026-06-30.md | Pipeline: drat/posthog-experiments.md | Policy: docs/operations/posthog-ab-operations.md*