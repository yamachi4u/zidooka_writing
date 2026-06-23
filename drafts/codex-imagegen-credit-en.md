---
title: "How Much Does Codex Image Generation Cost in Credits? Less Than You Think"
slug: "codex-imagegen-credit-en"
categories:
  - AI
tags:
  - Codex
  - imagegen
  - OpenAI
  - credits
status: publish
---

How many credits does it actually cost to generate an image with Codex? I checked the official OpenAI documentation.

## What Are "Credits" Anyway?

In the Codex world, **credits** are the unit of usage for your ChatGPT plan.

Think of it like your **mobile data plan**. You pay a monthly fee and get a certain amount of data. Different activities use different amounts — streaming video uses more than texting. Same with Codex.

- **Plus ($20/month)**: A fixed number of messages per 5-hour window
- **Pro ($100/month)**: 5x the usage of Plus
- **When you run out**: Buy more credits, wait for the reset, or switch to a lighter model

Credits reset every 5 hours. Different models and features consume them at different rates. **GPT-5.4-mini** is light (~1-2 credits per message). **Fast mode** is heavy (2x consumption). **Image generation** is what this article is about.

Note: Business and Enterprise plans are moving to a per-token billing model, but Plus/Pro still use the simpler "~X credits per message" system. The numbers in this article use Plus/Pro credit notation.

## TL;DR

**One 1024×1024 image generation = ~5-6 credits.**

A regular text message (GPT-5.4) costs ~7 credits on Plus/Pro plans, so image generation is actually slightly cheaper per operation.

## Credit Comparison Table

| Item | Credits (Plus/Pro) |
|------|-------------------|
| Text message (GPT-5.4) | ~7 credits |
| Image generation 1024×1024 | ~5-6 credits |
| Image generation 1024×1536 | ~7-8 credits |
| Cloud task (GPT-5.3-Codex) | ~25 credits |
| Fast mode | 2x normal rate |

Image generation is often assumed to be heavier than text, but at the base size it's actually lighter. Higher quality and larger sizes can increase consumption up to 3-5x.

## Does the Skill Itself Eat Tokens?

A common concern: "If I keep the imagegen skill installed, does it bloat my context?"

Codex uses **progressive disclosure** for skills:

| State | Context Impact |
|-------|---------------|
| Skill not in use | Name + description only (all skills combined under 8,000 chars) |
| Skill activated | Full SKILL.md (24KB) + reference files (~30KB) loaded |

So unless you actually ask Codex to generate an image, the skill has almost no impact on your context window.

## Is It "Intercepting" the API?

No. Codex's built-in image generation simply uses the Responses API's native `image_generation` tool. No user API key required — it runs entirely within your ChatGPT plan.

```
Your prompt
  → GPT-5.5 rewrites it (prompt optimization)
  → Internal gpt-image-2 generates the image
  → Result returned as base64
```

## CLI Fallback Is a Different Beast

| Method | Billing | Notes |
|--------|---------|-------|
| Built-in `image_gen` tool | ChatGPT plan credits | 3-5x rate, no API key needed |
| CLI fallback (scripts/image_gen.py) | OpenAI API key | Separate per-image API pricing |

The CLI fallback charges your own API key at standard image API rates, completely independent of Codex's rate limits.

:::conclusion
Codex image generation consumes roughly the same or fewer credits than a regular text message. The skill's context impact is minimal thanks to progressive disclosure. You can ask Codex to "draw something" without worrying about burning through your limits too fast.
:::

## References

- <https://developers.openai.com/codex/pricing>
- <https://developers.openai.com/codex/skills>
- <https://developers.openai.com/api/docs/guides/tools-image-generation>
