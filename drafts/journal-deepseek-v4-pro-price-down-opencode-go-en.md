---
title: "DeepSeek V4 Pro dropped to 1/4 price — what does it mean for OpenCode Go users?"
slug: journal-deepseek-v4-pro-price-down-opencode-go-en
categories:
  - journal
  - AI
tags:
  - DeepSeek
  - OpenCode Go
  - Pricing
  - API Cost
  - AI
status: publish
---

DeepSeek V4 Pro's API pricing has been permanently reduced to 25% of its original launch price. This article examines whether OpenCode Go has reflected this change in its usage limits for the model.

The short answer: **as of now, Go's limits are still based on pre-price-drop pricing.**

:::note
This article reflects data as of May 26, 2026. DeepSeek's 75%-off promotion runs until 2026/05/31 15:59 UTC, after which the 1/4 pricing becomes official and permanent.
:::

## DeepSeek V4 Pro pricing change

DeepSeek V4 Pro launched on April 24, 2026. Since then, pricing has been reduced to **one-quarter of the original**.

| Item | Original Price | Current Price (75% off) |
|------|---------------|------------------------|
| Input (cache miss) | $1.74 / 1M tokens | $0.435 / 1M tokens |
| Input (cache hit) | $0.0145 / 1M tokens | $0.003625 / 1M tokens |
| Output | $3.48 / 1M tokens | $0.87 / 1M tokens |

The cache-hit price was reduced even earlier (from April 26) to 1/10 of launch price.

## OpenCode Go limits for DeepSeek V4 Pro

OpenCode Go ($10/month) currently offers these limits for DeepSeek V4 Pro:

| Period | Requests | Dollar-equivalent limit |
|--------|----------|------------------------|
| 5 hours | 3,450 | $12 |
| Week | 8,550 | $30 |
| Month | 17,150 | $60 |

## Calculating the impact

Using OpenCode's published estimated token pattern (750 input + 82,000 cached + 290 output per request):

**Before price drop:**
750×$1.74/1M + 82,000×$0.0145/1M + 290×$3.48/1M = **$0.003503/request**

3,450 requests × $0.003503 = **$12.09** — nearly exactly the $12 5-hour limit.

**After price drop:**
750×$0.435/1M + 82,000×$0.003625/1M + 290×$0.87/1M = **$0.000875/request**

3,450 requests × $0.000875 = **$3.02**

:::conclusion
Before the price drop, 3,450 requests cost OpenCode about $12 in DeepSeek API fees. After the drop, the same 3,450 requests cost roughly **$3** — a 75% cost reduction that has not yet been passed on to Go subscribers.
:::

## What if limits were recalculated?

If Go recalculated limits using the new pricing:

$12 ÷ $0.000875 = **~13,700 requests / 5 hours**

That's roughly **4x** the current limit.

| Period | Current | Theoretical max |
|--------|---------|-----------------|
| 5 hours | 3,450 | ~13,700 |
| Week | 8,550 | ~34,200 |
| Month | 17,150 | ~68,500 |

## Why hasn't it been updated?

Several possibilities:

1. **Documentation timing**: The Go docs were last updated May 25 — the limits may simply not have been revised yet.
2. **Waiting for formal pricing**: The 75% off is still labeled a "promotion" until May 31. After June 1, it becomes official — OpenCode may adjust after the formal change.
3. **In-progress adjustment**: Internal changes may be underway.

## What this means for Go users

DeepSeek V4 Pro is one of the most popular models on Go. If limits are eventually raised, the value proposition improves significantly.

That said, at a flat $10/month, most users are unlikely to feel constrained by the current limits. In practice, exceeding 3,450 requests in 5 hours requires unusually heavy usage.

:::note
Whether the price drop benefit flows back to users as expanded limits is entirely up to OpenCode. Worth watching.
:::
