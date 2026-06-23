# PostHog A/B Status
> Last check: 2026-06-23  |  Run `npm run posthog:check` to refresh
> **Before acting**: Check `daily-agent/YYYYMMDD.md` for active claims

## Active Experiment

| Field | Value |
|-------|-------|
| Flag | `zdk_code_fold` |
| Days running | 1 |
| Decision deadline | 2026-06-29 |

## Health

| Metric | Status | Value |
|--------|--------|-------|
| Null rate | OK | 0.0% (max 30.0%) ↓ |
| Impressions (ctrl/treat) | LOW | 36 / 28 |
| Outcome events (ctrl/treat) | OK | 889 / 772 |

## Outcomes

| Event | Ctrl | Treat | Lift |
|-------|------|-------|------|
| Read Depth (25/50/75/90%) | 761 | 640 | +8.1% |
| Engaged 60s | 128 | 132 | +32.6% |
| TOC Click | 0 | 0 | - |
| Related Click | 0 | 0 | - |

## Next Action

**[WAIT]** wait_impressions: Need 200 impressions per variant. Currently control=36 treatment=28. Check again in a few days.

### Closeout Steps

1. Wait 3-4 days for more data to accumulate
2. Re-run `npm run posthog:check`
3. If still insufficient after 14 total days, consider closing as inconclusive

## Pipeline

| Priority | Experiment | Flag |
|----------|------------|------|
| **next** | Code block fold | `zdk_code_fold` |
| 2 | Header image | `zdk_header_image` |
| 3 | Author position | `zdk_author_pos` |

## Meta Alerts

- **[MED]** flag_resolution_errors: 214 zdk_flag_resolution_error events in past 7 days.
- **[MED]** dead_outcome: Outcome event(s) with zero data: TOC Click, Related Click.

---
*Full: daily/posthog/2026-06-23.md | Pipeline: drat/posthog-experiments.md | Policy: docs/operations/posthog-ab-operations.md*