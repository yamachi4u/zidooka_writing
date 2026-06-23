---
title: "When Codex Calls opencode run, OpenCode Cannot Ask Back"
slug: journal-opencode-interactive-vs-run-20260527-en
categories:
  - journal
  - AI
tags:
  - OpenCode Go
  - Codex
  - CLI
  - Workflow
  - AI
status: publish
---

When Codex runs `opencode run "task"` via bash, can OpenCode ask clarifying questions and wait for answers? The answer is no.

## How opencode run Works

`opencode run` is explicitly a non-interactive mode. It takes a prompt, processes it, writes the result to stdout, and exits. There is no stdin conversation.

This is by design. From the docs: "Run opencode in non-interactive mode... useful for scripting, automation, or when you want a quick answer without launching the full TUI."

When OpenCode encounters ambiguity:
- Guesses and proceeds, or returns an error
- Cannot ask clarifying questions
- Does not wait for follow-up input
- Exits immediately after producing output

## So What Is It Good For?

The value is not conversation but division of labor:

- Codex (expensive, powerful model) handles architecture, planning, review
- OpenCode Go Flash (cheap, fast model) handles localized execution

Codex unilaterally tells Flash "do this" via `opencode run` and gets back a result. No back-and-forth needed.

## The Alternative: opencode serve + HTTP API

You can build a conversational bridge by running OpenCode as a persistent server:

1. Start `opencode serve` in the background
2. The server exposes a REST API at `localhost:4096`
3. Send messages via `POST /session/:id/message` to create multi-turn conversations
4. The OpenCode JS SDK (`@opencode-ai/sdk`) provides a type-safe client for this

With this setup, Codex can use `webfetch` to communicate with OpenCode over HTTP instead of bash. Each API call maintains session state, enabling real back-and-forth.

However, Codex does not do this automatically. You would need to give Codex explicit instructions or write a wrapper script.

## OpenCode's Internal Subagents

OpenCode (in TUI mode) has built-in subagents like @general and @explore. The primary agent delegates tasks to subagents via the task tool. But this is internal to OpenCode and unrelated to Codex.

## Bottom Line

- `opencode run` from Codex via bash: one-shot, no conversation
- `opencode serve` + HTTP API: multi-turn possible but requires manual setup
- `opencode` (TUI): full conversation, for direct human use

The current Codex-to-OpenCode pattern works because conversation is unnecessary for the task at hand: cheap models execute, expensive models supervise.
