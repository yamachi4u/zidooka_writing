---
title: "An OpenCode User Revisits Hermes Agent: It's Come Further Than I Expected"
categories:
  - AI
tags:
  - AI Agent
  - Agent
  - OpenCode
  - Open Source
  - Comparison
  - Memory
  - Subagents
  - Automation
status: draft
slug: hermes-agent-opencode-view-en
---

:::conclusion
As an OpenCode user, I recently took another look at Hermes Agent. My honest reaction: it's come much further than I expected. But the conclusion isn't a simple switch. For coding, OpenCode stays. For memory, resident automation, and multi-channel access, Hermes makes sense. Using both, split by use case, is what works best for me.
:::

## How It Started

A friend and I were talking about agents the other day. I've been building with OpenCode for a while now. It's a solid coding harness, and I'd decided it was enough.

Then he mentioned Hermes Agent. The name rang a bell, but my mental model was stuck at "a CLI chatbot." I decided to check the official site.

## What Surprised Me First: Memory and the Self-Improvement Loop

The first thing on the page was "The Agent That Grows With You." The idea that an agent can grow is the whole pitch.

Hermes keeps persistent memory. It writes what it learns to `MEMORY.md` and `USER.md`, so knowledge survives across sessions. After complex work, it auto-generates skills. Then it improves those skills through use. It can even search its own past conversations with FTS5.

OpenCode has skills and AGENTS.md too. But those organize context for a development project. Hermes is aiming at something different: an agent that gets smarter the longer you use it. It remembers how it solved a problem last time.

## The Scope Outside Development Is Impressive

The breadth of features also stood out.

- A desktop app (macOS / Windows / Linux)
- Web search, browser automation, image generation, text-to-speech
- Telegram, Discord, Slack, WhatsApp, Signal, Email, plus CLI
- Scheduling in natural language
- Delegation to subagents with parallel execution
- Sandboxes: local, Docker, SSH, Singularity, Modal

That's a long way from "a chatbot." There's voice mode and a "Hey Hermes" wake word too.

The multi-channel support is the big differentiator for a resident agent. Shut your laptop and the gateway keeps running, so you can send instructions from Telegram. OpenCode's rhythm is "open a terminal and work." Hermes's rhythm is "ask from your phone, get an answer." That difference matters more than it sounds.

## Model Freedom and Licensing

Supported models are broad: OpenRouter, OpenAI, Nous Portal, or your own endpoint. You switch with `hermes model`, no lock-in.

The license is MIT. You can read the code and fork it. That also feels familiar to OpenCode users.

One note on versions: the cadence is fast. From June to August 2026 it went 0.15 → 0.16 → 0.17 → 0.18 → 0.19 → 0.20, roughly every two weeks. The landing page and the GitHub Releases list can briefly get out of sync. The latest, v0.20.0, shipped on August 3, 2026.

## OpenCode vs Hermes, Roughly

| Aspect | OpenCode | Hermes Agent |
|--------|----------|--------------|
| Strength | Coding work | Resident automation and memory |
| Surfaces | Terminal / IDE | CLI, desktop, messaging apps |
| Memory | Project-scoped | Persistent across sessions |
| Automation | Snippets and commands | Natural-language schedules + gateway |
| Channels | Local-focused | Telegram, Slack, WhatsApp, etc. |
| Sandboxes | Local-first | Docker, SSH, Singularity, Modal |

For editing files and running tests mid-sprint, OpenCode is still ahead. It's fast, and the editor integration is natural.

Hermes wins outside development. Morning reports, nightly backups, answering questions over Slack. If you want an agent that works while you sleep, Hermes is the better pick.

## What I'll Actually Do

This isn't an either/or call. The roles are different.

- Implementation, debugging, refactoring → OpenCode
- Scheduling, resident operation, channels, long-term memory → Hermes

I plan to keep OpenCode for development and use Hermes for daily routines. Revisiting it, I honestly thought, "it's come this far." Next time I open it, it'll probably have grown again.

:::conclusion
Hermes Agent has committed to the "agent that grows with you" direction: persistent memory, self-improving skills, multi-channel access, scheduled runs, and a variety of sandboxes. It's MIT-licensed, and you pick your own models. OpenCode remains a solid coding harness. So it's not a migration — it's using each where it fits.
:::

## References

1. [Hermes Agent official site](<https://hermes-agent.nousresearch.com/>)
2. [Features Overview](<https://hermes-agent.nousresearch.com/docs/user-guide/features/overview/>)
3. [NousResearch/hermes-agent (GitHub)](<https://github.com/NousResearch/hermes-agent>)
4. [Releases](<https://github.com/NousResearch/hermes-agent/releases>)
