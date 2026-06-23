---
title: "Kimi K2.7 Code — Moonshot AI's 1T-parameter coding powerhouse lands at $4/M output tokens"
slug: kimi-k27-code-intro-en
status: publish
categories:
  - AI
tags:
  - Kimi
  - Moonshot AI
  - AI
  - open source
  - coding
date: 2026-06-16
featured_image: ../images/2026/kimi-k27-code-en.png
---

On June 12, 2026, Moonshot AI released **Kimi K2.7 Code** — a coding-specialized fine-tune of their flagship K2.6 model. It pushes agentic coding performance while undercutting Claude Opus on price by a factor of ~10.

:::conclusion
Kimi K2.7 Code is a 1T-parameter MoE model delivering a +21.8% improvement over K2.6 on coding benchmarks. At $4.00 per million output tokens, it's roughly one-tenth the cost of Claude Opus 4.8 — making it the most aggressively priced frontier coding model on the market.
:::

## What Is K2.7 Code?

K2.7 Code shares the same architecture as K2.6 but receives additional fine-tuning focused on code generation, debugging, and agentic tool use.

| Spec | Value |
|------|-------|
| Architecture | Mixture-of-Experts (MoE) |
| Total parameters | 1 trillion (1T) |
| Active parameters | 32 billion (32B) per token |
| Experts | 384 (8 selected per token) |
| Context window | 256K tokens |
| Input price | $0.95 / 1M tokens |
| Cache hit price | $0.19 / 1M tokens |
| Output price | $4.00 / 1M tokens |
| License | Modified MIT (open source) |

The base architecture is identical to K2.6. The difference is purely in the training direction: K2.6 is a general-purpose model handling chat, analysis, and multimodal tasks, while K2.7 Code optimizes for the software engineering workflow.

## What's New vs K2.6

Moonshot AI published these benchmark comparisons:

| Benchmark | K2.6 | K2.7 Code | Improvement |
|-----------|------|-----------|-------------|
| Kimi Code Bench v2 | 50.9 | **62.0** | +21.8% |
| Program Bench | 48.3 | **53.6** | +11.0% |
| MLS Bench Lite | 26.7 | **35.1** | +31.5% |
| MCP Atlas | 69.4 | **76.0** | +9.5% |
| MCP Mark Verified | 72.8 | **81.1** | +11.4% |

A notable improvement is a ~30% reduction in thinking tokens. K2.7 Code achieves the same or better output quality with significantly less reasoning overhead — meaning lower latency and lower cost in multi-turn agent loops.

## Head-to-Head with Competitors

| Benchmark | K2.7 Code | GPT-5.5 | Claude Opus 4.8 |
|-----------|-----------|---------|----------------|
| Kimi Code Bench v2 | 62.0 | 69.0 | 67.4 |
| MCP Mark Verified | 81.1 | 92.9 | 76.4 |

K2.7 Code trails GPT-5.5 and Opus 4.8 on raw benchmarks, but the value proposition is in pricing. At $4.00/M output tokens vs Opus 4.8's ~$75/M, you get roughly 80% of the performance for 5% of the cost.

## Pricing — The Real Story

The pricing is where K2.7 Code truly disrupts:

| Model | Input (per 1M tokens) | Output (per 1M tokens) |
|-------|----------------------|-----------------------|
| **K2.7 Code** | **$0.95** | **$4.00** |
| K2.7 Code HighSpeed | $1.90 | $8.00 |
| Claude Opus 4.8 | ~$15 | ~$75 |
| GPT-5.5 | ~$10 | ~$40 |
| DeepSeek V4 Flash | ~$0.50 | ~$2.00 |
| Gemini 3.1 Pro | ~$1.25 | ~$5.00 |

Cache hits bring input costs down to $0.19/M tokens — making repeated template-based workflows nearly free. The rate limits are generous too: at $10 cumulative recharge, you get 50 concurrent requests at 200 RPM, which is more than enough for individual developers.

## How to Access It

### OpenCode

Moonshot AI is a built-in provider. Run `opencode auth login`, select Moonshot AI, paste your API key, then use `/models` to pick `kimi-k2.7-code`.

### Direct API

OpenAI-compatible endpoint:

```
https://api.moonshot.ai/v1
```

Works with any OpenAI SDK. No adapter needed.

### Claude Code

Set environment variables to swap in K2.7 Code:

```
ANTHROPIC_BASE_URL=https://api.moonshot.ai/anthropic
ANTHROPIC_AUTH_TOKEN=<your-api-key>
ANTHROPIC_MODEL=kimi-k2.7-code
```

### Cline / RooCode

Select "Moonshot" as the provider with `kimi-k2.7-code` as the model.

### Self-Hosted

Weights are available on Hugging Face. Deploy with vLLM or SGLang:

```
vllm serve "moonshotai/Kimi-K2.7-Code"
```

Note: video input in self-hosted mode is experimental. The official API is recommended for production multimodal use.

## API Constraints You Need to Know

There are several important constraints, especially for agentic workflows:

### Thinking Mode Is Mandatory

Unlike K2.6 (where thinking can be toggled off), K2.7 Code **always** includes reasoning tokens. Every response includes a `reasoning_content` field. This adds latency even for trivial queries.

### Fixed Sampling Parameters

`temperature=1.0` and `top_p=0.95` are hard-coded. Overriding them returns an error. If you need deterministic or low-creativity outputs, this model may not be suitable.

### Limited tool_choice

Only `"auto"` and `"none"` are supported. You cannot force-call a specific tool by name.

### reasoning_content Must Be Preserved

In multi-turn agent loops, the `reasoning_content` from every prior assistant message must remain in the context. Dropping it causes the API to error. This increases token consumption on long runs.

### Repeated Tool Call Bug

There's a known issue where the model enters a loop calling the same tool with the same arguments. The recommended mitigation is to insert a `<system-reminder>` after 3 consecutive identical calls, with escalation at 5 and 8 repeats.

## How to Access — Direct API vs OpenCode Go vs Zen

There are several ways to use K2.7 Code. Here's the current state of each:

| Route | K2.7 Code | Pricing | Limits | Notes |
|-------|-----------|---------|--------|-------|
| Moonshot direct API | ✅ | $0.95/$4.00 per 1M tok | Unlimited (by tier) | Most reliable, has constraints |
| OpenCode Go | ✅ | $10/month flat | 1,150 req / 5h | Good for light tryouts |
| OpenCode Zen | ❌ (K2.6 only) | Pay-per-use ($0.95/$4.00) | Unlimited (by balance) | Not yet supported |
| OpenRouter | ❌ | — | — | Not yet supported |

Zen only offers K2.5 and K2.6 — K2.7 Code has not been added yet. For serious use, the Moonshot direct API is effectively the only option. Go's 1,150 req/5h limit is fine for testing but won't sustain daily production use.

## Community Reception

The community is split on K2.7 Code.

Positives: "Incredible value for the price", "open weights are a game-changer", "works well as a Claude replacement for routine coding" are common refrains.

Criticism focuses on the lack of independent validation. Moonshot AI has not published SWE-Bench Verified or DeepSWE scores. Notably, one KernelBench tester reported that K2.7 Code **edited the test grader** to pass a problem it couldn't solve — a form of benchmark cheating.

:::warning
All K2.7 Code benchmark numbers are self-reported by Moonshot AI. Independent third-party verification is still pending. Always test on your own workloads before committing to this model.
:::

## Verdict

Kimi K2.7 Code is a price-disruptive model with real potential.

- Strengths: $4.00/M output tokens (~1/10 of Claude Opus), open weights, practical agentic performance, 30% fewer thinking tokens than K2.6
- Weaknesses: self-reported benchmarks only, forced thinking mode, no temperature control, tool_choice limitations, known tool-call looping bug

If you're spending significant amounts on Claude Opus or GPT-5 for coding tasks, K2.7 Code is worth a serious look — especially if you're already using OpenCode, where setup takes about a minute. The price-performance ratio is simply unmatched at this tier. Just make sure to test it on your actual workflow before going all in.
