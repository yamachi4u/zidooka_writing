---
created: 2026-07-11
status: prepared
verify_date: 2026-07-28
title: zdk_header_density A/B preparation
---

## Decision

Prepare a PostHog test for the site header vertical density, but keep it inactive while the sidebar ad test is running.

## Variants

- `control`: current spacious header.
- `compact`: reduce `.zdk-header-inner` vertical padding to 10px desktop and 8px mobile.

## Hypothesis

The compact header brings campaign and article content into view sooner without harming engagement. The spacious header may retain stronger brand presence, so this is not an objective defect.

## Outcomes

Primary: `zdk_read_depth`, `zdk_engaged_60s`. Secondary: `zdk_toc_click`.

## Activation gate

Activate only after 2026-07-23 when the sidebar ad A/B is closed, and only if `npm run posthog:check` reports no active experiment. Set `startedAt` and deadline in `scripts/posthog-check.mjs` at activation time.