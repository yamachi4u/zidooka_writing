# ZIDOOKA Operations Registry

Last updated: 2026-06-04

## What This Folder Is

This folder is the place for "operation of operations": ongoing rules, ownership, current state, decision logic, and handoff notes that future agents must understand before changing the site.

Use this folder when a task is not just a one-time edit, but an operating policy that affects future decisions.

Examples:

- A/B test governance
- analytics measurement policy
- recurring SEO / revenue monitoring rules
- deployment verification requirements
- guardrails that explain why a tempting change should not be made

## How Agents Should Use This Folder

Before starting work that touches an ongoing system:

1. Read this `README.md`.
2. Find the relevant operation file in the registry below.
3. Read the operation file before implementing or asking opencode to implement.
4. Update the operation file if the current state, active policy, or rationale changes.
5. Update the relevant live log (`drat/` or `daily/`) when there is an action, deployment, or measurement result.

## Registry

| Operation | Status | Required read | Live log | Why it exists |
|---|---|---|---|---|
| PostHog A/B testing | active | `docs/operations/posthog-ab-operations.md` | `drat/posthog-experiments.md`, `daily/posthog-summary-20260604.md`, `daily/posthog/` | Prevent confounded tests and keep winner decisions based on outcome events. Weekly auto-check via `npm run posthog:check`. |

## File Roles

| Location | Role |
|---|---|
| `docs/operations/` | Stable operating policy, current state, decision rules, and handoff instructions. |
| `drat/` | Experiment registries, plans, working strategy, and action logs. |
| `daily/` | Dated measurements, implementation verification, and one-day reports. |
| `downloads/zidooka-tw/` | Local copy of WordPress theme files that may be pushed with remote-agent. |

## Update Rules

When an operation changes, update files in this order:

1. `docs/operations/<operation>.md`
   - current state
   - why this state exists
   - what future agents should or should not do
2. relevant `drat/*.md`
   - action log
   - experiment registry or plan
3. relevant `daily/*.md`
   - dated measurement and verification details
4. `AGENTS.md`
   - only when the rule is important enough that every future agent should see it quickly

Do not put secrets in these files. Mention only key names such as `POSTHOG_PERSONAL_API_KEY`.

## Decision Standard

An operation file should answer these questions without requiring the next agent to reconstruct history:

- What is currently active?
- What is intentionally inactive?
- Why is it configured this way?
- What evidence led to this decision?
- What should be measured next?
- What would be a mistake to change casually?
- Which files and commands are used to verify the operation?

## Delegating Implementation

If the user asks to implement an operations change through opencode, the orchestrating agent should:

- keep the policy decision in `docs/operations/`
- delegate implementation to the requested opencode model
- require validation commands
- verify the final state independently
- update the operation file after the implementation changes the operating state

Current preferred implementer for PostHog work:

`opencode-go/deepseek-v4-flash`

## Current Notes

- PostHog A/B testing is deliberately in a reduced mode: only `zdk_font_size` is active.
- This is not because the other ideas are bad. They are paused because five simultaneous tests created confounding.
- Future A/B tests should be queued and run sequentially unless the operation file says otherwise.
