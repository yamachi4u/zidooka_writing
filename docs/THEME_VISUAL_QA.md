# Zidooka Theme Visual QA Loop

## Goal

Major theme changes are driven by repeatable screenshots, layout metrics, and PostHog outcomes. The loop must remain resumable by any agent.

## Start Here

1. Read `daily-agent/YYYYMMDD.md` and `drat/posthog-status.md`.
2. Run `npm run theme:audit -- before-<task>`.
3. Review `images-agent-browser/theme-audit-20260711/<phase>/metrics.json` and screenshots.
4. Classify each finding as objective defect or subjective design choice.
5. Fix objective defects directly. Put subjective choices into the PostHog queue.
6. Deploy only the scoped theme files, then run `npm run theme:audit -- after-<task>`.
7. Run `npm run posthog:check`, update the experiment registry and both daily logs.

## Fixed Audit Matrix

Pages:
- `/` (front page)
- `/archives/4575` (English long-form article)
- `/archives/4582` (Japanese article)

Viewports:
- 1440x900 light and dark
- 834x1112 light
- 390x844 light and dark

Each case saves a viewport screenshot, full-page screenshot, and layout metrics. Required health checks are zero horizontal overflow, zero page errors, visible content within its container, and no incoherent fixed-element overlap.

## Decision Boundary

Direct fix:
- overflow, clipping, overlap, malformed CSS, broken responsive behavior
- inaccessible contrast or controls
- stale experiment code still changing production after a flag closes
- inconsistent behavior that violates an existing design rule

PostHog A/B:
- spacing density, content order, image prominence, navigation emphasis
- changes where both versions are valid and success depends on reader behavior

Do not A/B test obvious defects.

## Experiment Policy

- One experiment at a time.
- Ad or CTA experiments run alone; do not overlap them with readability/navigation tests.
- Capture `zdk_experiment_impression` and attach the resolved variant to outcome events.
- Primary outcomes: `zdk_read_depth`, `zdk_engaged_60s`.
- Secondary outcomes: `zdk_toc_click`, `zdk_related_click`.
- Minimum decision thresholds live in `docs/operations/posthog-ab-operations.md`.

## Current Queue

- Active through 2026-07-23: sidebar ad offer test (server-side A8 assignment).
- Prepared, inactive: `zdk_header_density`, control=spacious current header, compact=reduced vertical padding.
- Activate `zdk_header_density` only after the ad test closes and `npm run posthog:check` confirms no active experiment.
- Earliest decision: 5 days after activation and only after thresholds are met.

## Initial Baseline, 2026-07-11

- Before: campaign header measured 833px high at 1440px viewport because `.zdk-ad__link` remained inline after malformed CSS.
- Direct fix: removed the literal newline token, forced block layout, capped campaign creative at 420px.
- After: campaign header 272px desktop, 242px mobile; all 15 cases had zero horizontal overflow and zero page errors.
- Closed `zdk_header_image` assignment was still running in PHP despite the PostHog flag being inactive; remove that assignment permanently.