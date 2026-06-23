---
title: "Trying DeepSeek V4 Flash on OpenCode Go: Very Useful, but No Image Paste Is a Real Limitation"
slug: journal-deepseek-v4-flash-opencode-go-20260512-en
date: 2026-05-12 00:35:00
categories:
  - journal
tags:
  - DeepSeek
  - OpenCode
  - AI
  - CLI
status: publish
featured_image: ../images/2026/05/opencode-go-contract.png
---

I have been using DeepSeek V4 Flash through OpenCode Go.

The short version: it is very good. I would not call it literally unlimited, but for ordinary coding work, investigation, refactoring, and small implementation tasks, it is almost frighteningly usable.

:::conclusion
DeepSeek V4 Flash is extremely useful on OpenCode Go. The main downside is that I cannot paste images into it. My current setup is to use GPT-5.5 as the commander and DeepSeek V4 Flash as a fast execution model.
:::

## What feels good

First, it feels fast.

For tasks that do not need a heavier model, I can send a lot of work to DeepSeek V4 Flash: small code changes, reading logs, organizing files, breaking down TODOs, and understanding existing code.

OpenCode Go model listings describe DeepSeek V4 Flash as a text-input model with a very large 1M-token-class context window. The pricing is also low enough that it makes sense to use it repeatedly for small tasks.

It works especially well for:

- reading code quickly
- checking diffs
- proposing small fixes
- summarizing long logs
- acting as a worker model under a stronger coordinator

For that kind of work, it is seriously practical.

## The weak point: no image paste

The biggest downside for me is image input.

There are many times when I want to paste a screenshot and say, “look at this.” UI bugs, error screens, admin panels, charts, settings pages. Not being able to send the image directly is inconvenient.

From the public model listings I checked, DeepSeek V4 Flash on OpenCode Go is listed as a text input / text output model.

So the right expectation is: strong for text, code, and logs; not the model I would use when the task depends on reading a screenshot.

## How I use it

Right now, I use GPT-5.5 as the commander.

GPT-5.5 handles the big decisions, multi-agent coordination, final judgment, and anything that needs image understanding. DeepSeek V4 Flash handles the fast execution work through OpenCode Go.

That split works very well.

GPT-5.5 is the coordinator. DeepSeek V4 Flash is the fast worker. That is the mental model that fits my workflow.

## Quick research notes

Public listings describe DeepSeek V4 Flash as a 284B total / 13B active MoE model with a 1M-token-class context window and low input/output pricing.

OpenRouter lists `deepseek/deepseek-v4-flash` with a 1,048,576-token context window, $0.14/M input, and $0.28/M output.

The OpenCode Go listing describes DeepSeek V4 Flash as `deepseek-v4-flash` / `deepseek-flash`, with text input, text output, tool calling, reasoning, and structured output support.

References:

- OpenRouter: https://openrouter.ai/deepseek/deepseek-v4-flash
- whichllm OpenCode Go listing: https://whichllm.io/models/opencode-go-deepseek-v4-flash
- whichllm DeepSeek listing: https://whichllm.io/models/deepseek-deepseek-v4-flash

## Bottom line

DeepSeek V4 Flash is very usable.

But the lack of image paste is a clear limitation. If I need to show a screenshot, I still use GPT-5.5 or another image-capable model.

For text, code, and logs, though, DeepSeek V4 Flash on OpenCode Go is a strong everyday worker.

My current setup:

- Commander: GPT-5.5
- Worker: DeepSeek V4 Flash
- Image review: an image-capable model

That combination is working very well.
