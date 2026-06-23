---
title: "Codex GPT-5.5 Rate-Limit Cost Per Token Jumped ~10–20x Since June 16 — Issue #28879"
slug: codex-rate-limit-spike-28879-en
status: publish
categories:
  - AI
  - ChatGPT
tags:
  - Codex
  - OpenAI
  - GPT-5.5
  - rate limit
  - Plus
parent: codex-rate-limit-spike-28879-jp
---

Around June 16, 2026, ChatGPT Plus users started reporting a severe regression in Codex rate-limit consumption. The same model, same configuration, same account — yet the daily prompt count dropped from 20+ to just 2–3 before hitting the 5-hour cap.

The most detailed report is **Issue #28879** on the openai/codex repository.

:::conclusion
On GPT-5.5 (Plus plan), the per-token rate-limit cost appears to have increased roughly 10–20x. A 57K-token prompt that consumed ~1% of the 5-hour budget on June 12 now translates to 10–27% for a 20K-token prompt. The cause is unconfirmed, but the timing matches an active incident on the OpenAI status page.
:::

## What Happened

Reporter @mihneaptu has been using the Codex desktop app on Windows 11 with `gpt-5.5`. Configuration remained unchanged across the regression window.

### June 12 (normal — 20+ prompts/day)

| Time | Input tokens | Reasoning tokens | Primary limit % |
|------|-------------|-----------------|----------------|
| 06:08 | 22,334 | 488 | 2% |
| 06:09 | 52,161 | 1,130 | 3% |
| 06:51 | 57,112 | 3,645 | 9% → 11% |
| 06:54 | 79,961 | 5,125 | 15% → 16% |

A 57K-token prompt with 3.6K reasoning consumed only **~1%** of the 5-hour budget.

### June 18 (broken — 2–3 prompts/day)

| Time | Input tokens | Reasoning tokens | Primary limit % |
|------|-------------|-----------------|----------------|
| 07:22:27 | 18,910 | 152 | 18% |
| 07:22:30 | 20,480 | 0 | 45% |
| 07:22:34 | 23,469 | 48 | 57% |
| 07:22:43 | 23,631 | 0 | 68% |
| 07:22:50 | 23,839 | 0 | 77% |

A 20K-token prompt with near-zero reasoning now consumes **10–27%** of the 5-hour budget. That's roughly a 10–20x increase in per-token rate-limit cost.

## What Was Ruled Out

The reporter verified from session logs:

- **Not reasoning effort**: Reasoning tokens dropped from 3,645–5,125 to 0–152. The issue is not increased thinking
- **Not prompt bloat**: Input tokens decreased from 57K–80K to 18K–23K. Prompts are actually smaller
- **Not config change**: `model_reasoning_effort` was `high`/`xhigh` on both good and bad days
- **Not the weekly cap**: Secondary (7-day) window was at only 12%. The problem is solely in the 5-hour primary window

## Timing Correlation

The OpenAI status page shows an active incident starting around June 16 — matching exactly when the usable prompt count dropped from ~9 sessions/day to ~4.

:::note
Issue #28879 suggests either the Plus-tier GPT-5.5 rate-limit budget was reduced or the per-token weighting was changed. As of now, there's no official response from OpenAI.
:::

## Impact Assessment

| Area | Impact |
|------|--------|
| Plus users | 20+ prompts/day → 2–3 prompts. Major blow to the $20/month value proposition |
| Pro users | Same change may affect Pro tier as well |
| Agentic workflows | Autonomous agents consuming many prompts in short windows are hit hardest |
| codex-reset relevance | Banked reset credits suddenly become a lifeline. See our article on <https://github.com/aaamosh/codex-reset> |

:::warning
This is an unresolved incident with no official statement from OpenAI yet. If you depend on Codex rate limits for critical workflows, consider diversifying across multiple models and providers.
:::

## References

- Issue #28879: <https://github.com/openai/codex/issues/28879>
- codex-reset (CLI tool for banked resets): <https://github.com/aaamosh/codex-reset>
- OpenAI Status: <https://status.openai.com>

## Summary

Codex Issue #28879 strikes at the core of the Plus plan's value proposition. A 10–20x increase in per-token rate-limit cost — whether a bug or an intentional change — makes GPT-5.5 nearly unusable for anything beyond a handful of interactions per day.

Affected users should manage their banked resets with codex-reset and consider spreading their workload across alternative models like Kimi K2.7 Code or Claude Opus while OpenAI investigates.
