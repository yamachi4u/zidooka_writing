---
title: "Understanding OpenCode Go's Rolling Usage Limits"
slug: opencode-go-rolling-usage-limits-en
date: 2026-05-12 09:00:00
categories:
  - journal
tags:
  - OpenCode
  - AI
  - CLI
  - Pricing
status: publish
featured_image: ../images/2026/05/opencode-go-contract.png
---

After subscribing to OpenCode Go, the first thing that caught my attention was the "rolling usage limits" system.

I initially assumed a flat monthly subscription meant unlimited usage. But in reality, usage is capped across three time windows: 5 hours, weekly, and monthly. It felt a bit restrictive at first, but once I understood how it works, it actually makes a lot of sense.

:::conclusion
Rolling usage limits work in three tiers — 5 hours, weekly, and monthly — where dollar-based caps determine how many requests you can make based on each model's pricing.
:::

## The Basics of Usage Limits

OpenCode Go has three tiers of usage caps:

| Period | Usage Cap (in dollars) |
|--------|----------------------|
| 5 hours | $12 |
| Weekly | $30 |
| Monthly | $60 |

The key insight is that **caps are defined in dollar amounts, not request counts**.

This means cheaper models (like DeepSeek V4 Flash) allow many more requests, while more expensive models (like GLM-5.1) give you fewer requests per dollar.

## Estimated Requests by Model

Here's how many requests you can make per 5-hour window, depending on the model:

| Model | 5h | Weekly | Monthly |
|-------|------|--------|---------|
| DeepSeek V4 Flash | 31,650 | 79,050 | 158,150 |
| Qwen3.5 Plus | 10,200 | 25,200 | 50,500 |
| MiniMax M2.5 | 6,300 | 15,900 | 31,800 |
| Qwen3.6 Plus | 3,300 | 8,200 | 16,300 |
| DeepSeek V4 Pro | 3,450 | 8,550 | 17,150 |
| MiniMax M2.7 | 3,400 | 8,500 | 17,000 |
| Kimi K2.5 | 1,850 | 4,630 | 9,250 |
| GLM-5 | 1,150 | 2,880 | 5,750 |

:::note
These numbers are estimates based on typical usage patterns. Actual request counts vary depending on token usage per request.
:::

## What Happens When You Hit the Limit?

Even after hitting your usage cap, **free models (like Big Pickle) remain available**.

If you've also topped up your Zen balance (credits), you can enable the "Use balance" option in the console. When enabled, Go falls back to your Zen credits after the usage limit is reached — so you can keep using the same premium models.

In short:

- Within cap → no extra charges
- Beyond cap → fall back to free models, or continue with Zen credits

## Why Rolling Limits?

The reason it's not "unlimited for $10/month" is fairness across users.

If one person sends massive amounts of heavy requests, response times degrade for everyone else. The 5-hour window prevents short bursts from monopolizing capacity, keeping the service stable for all subscribers.

In my daily workflow, using DeepSeek V4 Pro or Qwen3.6 Plus, I typically send around 50 requests per hour. With a 5-hour cap of 3,300-3,450 requests, I'd need to be pushing extremely hard to hit that limit. Normal coding sessions won't come close.

## Summary

- Usage caps are in three tiers: $12 per 5 hours, $30 per week, $60 per month
- Caps are dollar-based, so request counts vary by model pricing
- Free models remain available after hitting caps
- Zen credits allow continued premium model usage beyond caps
- Rolling windows ensure stable quality for all users

At $10/month ($5 for the first month), this pricing model strikes a well-designed balance between cost and quality.