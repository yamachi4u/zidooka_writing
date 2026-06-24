# PostHog A/B Status
> Last check: 2026-06-23  |  Run `npm run posthog:check` to refresh
> **Before acting**: Check `daily-agent/YYYYMMDD.md` for active claims

## Active Experiments

| Field | Value |
|-------|-------|
| Flag (primary) | `zdk_code_fold` |
| Days running | 2 |
| Decision deadline | 2026-06-29 |
| Flag (co-running) | `zdk_header_image` |
| Started | 2026-06-24 |
| Decision deadline | 2026-07-08 |

## Health — `zdk_code_fold`

| Metric | Status | Value |
|--------|--------|-------|
| Null rate | OK | 0.0% (max 30.0%) → |
| Impressions (ctrl/treat) | LOW | 60 / 42 |
| Outcome events (ctrl/treat) | OK | 845 / 747 |

## Outcomes — `zdk_code_fold`

| Event | Ctrl | Treat | Lift |
|-------|------|-------|------|
| Read Depth (25/50/75/90%) | 726 | 619 | +21.8% |
| Engaged 60s | 119 | 128 | +53.7% |
| TOC Click | 0 | 0 | - |
| Related Click | 0 | 0 | - |

## Next Action

**[WAIT]** wait_impressions: Need 200 impressions per variant for `zdk_code_fold`. Currently control=60 treatment=42.

`zdk_header_image` just started (2026-06-24). Wait for data to accumulate.

### Closeout Steps

1. Wait 3-4 days for more data to accumulate
2. Re-run `npm run posthog:check`
3. If still insufficient after 14 total days, consider closing as inconclusive

## Pipeline

| Priority | Experiment | Flag | Status |
|----------|------------|------|--------|
| **active** | Code block fold | `zdk_code_fold` | monitoring |
| **active** | Header image | `zdk_header_image` | deployed 2026-06-24 |
| **next** | Author position | `zdk_author_pos` | queued |

All 3 experiments use server-side body class assignment.

## Meta Alerts

- **[MED]** flag_resolution_errors: 221 zdk_flag_resolution_error events in past 7 days.
- **[MED]** dead_outcome: Outcome event(s) with zero data: TOC Click, Related Click.

---
*Full: daily/posthog/2026-06-23.md | Pipeline: drat/posthog-experiments.md | Policy: docs/operations/posthog-ab-operations.md*