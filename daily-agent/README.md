# daily-agent

Shared daily coordination log for parallel agents working in `zidooka_writing`.

## Purpose

- Keep one append-only coordination file per day:
  - `daily-agent/YYYYMMDD.md`
- Avoid duplicated work across Codex, Claude Code, and Opencode.
- Particularly important for PostHog A/B experiment operations (flag state changes, deployments, winner decisions).
- Leave a usable handoff trail inside the workspace, not only in tool chat history.

## Operating Rule

1. Before substantial work, open today's file.
2. Read the latest entries.
3. Add a `start` or `claim` entry before taking a task.
4. Append `doing`, `blocked`, `handoff`, and `done` entries as the task moves.
5. Do not delete or rewrite another agent's entries.
6. When working on PostHog experiments, always check the daily-agent log first to avoid parallel flag state changes.

## Quick Start

```powershell
.\daily-agent.cmd --agent Codex --task "initial check"
```

This command:
- creates `daily-agent/YYYYMMDD.md` if missing
- writes the daily header once
- appends a `start` entry

## Entry Format

Use one line per event:

```text
- [2026-06-05 14:20] [Codex] [claim] Run posthog:check and review active experiments
```

Add short follow-up bullets only when needed:

```text
  - next: fix null rate if >30%
  - note: do not activate next flag until font_size is decided
```

## Status Words

- `start` — entering a work session
- `claim` — claiming a task (other agents should not touch)
- `doing` — actively working
- `blocked` — blocked by something (state why)
- `handoff` — handing off to another agent or next session
- `done` — completed
- `memo` — informational note, no claim

## Sections For New Daily File

- `## Shared Context`
- `## Open Handoffs`
- `## Claims / Progress Log`

## Structured Entry Format (2026-06-23+)

Use YAML frontmatter + sections for machine-parseable logs:

```markdown
---
date: YYYY-MM-DD
project: zidooka-writing
agent: <agent-name>
experiments:
  - id: zdk_code_fold
    status: running|stopped|waiting
    action: wait_impressions|check_data|decide
decisions:
  - id: YYYY-MM-DD-slug
    status: pending|running|completed
    verify_date: YYYY-MM-DD
alerts:
  - severity: high|medium|low
    message: "..."
---

## Summary
一言でこのセッションの結果

## Actions Taken
- 箇条書きでやったこと

## Pending Items
- [ ] 次のエージェントへのタスク
```

Template location: `daily-agent/TEMPLATE.md`

## PostHog A/B Coordination

When multiple agents may interact with PostHog experiments, the daily-agent log is the single source of truth for:

- Which experiments are active / paused / decided
- Who is currently deploying changes to `posthog-experiments.js` or `functions.php`
- When `npm run posthog:check` was last run and what it recommended
- Whether `zdk_font_size` winner has been decided yet

Before changing a feature flag state via PostHog API, append a `claim` entry and wait for other agents to acknowledge if needed.
