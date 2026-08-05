---
title: "DeepSeek V4 Flash 0731 vs GPT-5.6 Luna: How Big Is the Price Gap for the Same Work?"
categories:
  - AI
tags:
  - DeepSeek
  - DeepSeek V4
  - GPT-5.6
  - Luna
  - OpenAI
  - Generative AI
  - Comparison
  - Pricing
  - Subscription
  - API
  - OpenCode
status: publish
slug: deepseek-v4-flash-0731-vs-gpt-5-6-luna-cost-en
featured_image: ../images/2026/deepseek-v4-flash-vs-luna-cost.png
---

In July 2026, both majors shipped new models. DeepSeek released **DeepSeek V4 Flash 0731** on July 31, and OpenAI released the **GPT-5.6** family on July 9. This post compares the two cheapest tiers against each other: GPT-5.6 Luna versus DeepSeek V4 Flash 0731. How much does the price change when you hand them the same job?

Short answer: DeepSeek does the same amount of work for **one-third to one-quarter** of Luna's cost. On a subscription it feels close to unlimited. Here are the price tables and the real cost feel, in order.

## What is GPT-5.6 Luna?

GPT-5.6 is not a single model. It ships in three tiers: Sol, Terra, and Luna. Luna is the fastest and cheapest, roughly the nano tier of earlier GPT-5 families. It has a 1,050,000-token context window and accepts text and image input.

Official API pricing is below. All units are per million tokens.

| Model | Input | Cached input | Output |
|---|---|---|---|
| GPT-5.6 Luna | $0.2 | $0.02 | $1.2 |
| GPT-5.6 Terra | $2 | $0.2 | $12 |

For context, Terra costs 10x more than Luna. Sol, the flagship, costs even more.

## DeepSeek V4 Flash 0731 pricing

DeepSeek V4 Flash 0731 offers a 1M-token context, thinking and non-thinking modes, and tool calls. It has 284B total parameters and 13B active parameters during inference.

The price depends on the route you use.

| Route | Input | Output |
|---|---|---|
| DeepSeek official API | $0.14 ($0.0028 on cache hit) | $0.28 |
| OpenRouter | $0.09 | $0.18 |

:::note
The official cache-hit price of $0.0028 is nearly free. If you reuse the same long system prompt, this is where DeepSeek pulls far ahead.
:::

## What one job actually costs

Raw numbers are too big to feel. Let's assume one task is 10,000 input tokens plus 2,000 output tokens.

| Model | One task | 100 tasks per month |
|---|---|---|
| DeepSeek official API | about $0.002 | about $0.2 |
| GPT-5.6 Luna | about $0.004 | about $0.4 |

Output-heavy work widens the gap. Luna charges $1.2 per million output tokens, while DeepSeek charges $0.28. On output-centric jobs, DeepSeek runs at **less than one-quarter** of Luna's cost.

:::example
A real session I ran on DeepSeek V4 Flash showed a usage cost of $0.01. That is the practical feel of one conversation. It gives you an intuitive anchor for the numbers above.
:::

## On a subscription, it feels unlimited

API is usage-based, so the bill grows with use. If you use a model daily as an agent, a subscription feels better.

DeepSeek V4 Flash 0731 is available in OpenCode Go ($5 for the first month, $10 per month after). OpenCode's documentation estimates up to **158,150 requests per month** for a typical usage pattern. That is an estimate based on token volume, not a guaranteed count, but for personal workloads it is hard to exhaust.

Compared with a ChatGPT subscription (Plus is $20 per month), the monthly cost is halved. The bigger change is that you stop worrying about message caps.

:::conclusion
The decision rule is simple: **do you burn more than $10 (about 1,500 yen) of API usage per month?** If yes, take the OpenCode Go subscription. If no, the official API is cheaper, and OpenRouter is fine for a quick test. Also weigh the type of data you will send and the feature differences, not just the price.
:::

## Caveats

Price alone is not a reason to switch. Three things to keep in mind.

- **Privacy**: OpenCode Go's privacy table marks DeepSeek V4 Flash as "used for model training" with "no agreement" for data retention. Do not use it for customer data, API keys, or unpublished contracts
- **Features**: Luna supports image input, web search, and a code interpreter. DeepSeek supports the Responses API and tool calls, but the feature set differs. For image workflows, Luna is easier
- **Quality**: DeepSeek leads on coding-agent benchmarks, scoring 82.7 on Terminal Bench 2.1. Benchmarks are measured in specific environments, so judge by how it feels on your own tasks

## Wrap-up

DeepSeek V4 Flash 0731 handles the same work at one-third to one-quarter of GPT-5.6 Luna's cost, and a subscription makes it feel close to unlimited. That saving trades off against privacy and feature differences, so check those two before you switch.

My earlier post, [Four Practical Ways to Use DeepSeek V4 Flash 0731](https://www.zidooka.com/archives/4596), covers the official API, OpenCode Go, OpenRouter, and self-hosting in more detail.

## References

- [DeepSeek API: V4 Flash update on July 31, 2026](https://api-docs.deepseek.com/updates/)
- [DeepSeek API: models and pricing](https://api-docs.deepseek.com/quick_start/pricing/)
- [OpenCode Go: pricing, models, and privacy](https://dev.opencode.ai/docs/de/go/)
- [OpenAI: GPT-5.6](https://openai.com/index/gpt-5-6/)
- [OpenAI API: GPT-5.6 Luna model](https://developers.openai.com/api/docs/models/gpt-5.6-luna)
- [OpenRouter: DeepSeek V4 Flash 0731](https://openrouter.ai/deepseek/deepseek-v4-flash-0731)

*Information and prices were checked on August 4, 2026. Model availability, quotas, and pricing may change.*
