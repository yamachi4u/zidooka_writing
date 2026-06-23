---
title: "Orchestrator + Child Models via OpenCode Serve — From One-Shot to Real Conversation"
slug: journal-orchestrator-child-model-serve-bridge-20260527-en
categories:
  - journal
  - AI
tags:
  - OpenCode Go
  - Codex
  - Workflow
  - AI
status: publish
---

## What We Wanted

Using AI for coding means you want smart models to think through problems. But if you use expensive models for everything, costs add up fast.

The natural solution: split the work.

```text
Orchestrator (GPT-5.5 / Claude Sonnet etc.)
  ├─ Design & planning
  ├─ Decision making
  └─ Quality review

Worker (DeepSeek V4 Flash / Kimi K2.6 etc.)
  ├─ File edits
  ├─ Log investigation
  └─ Simple tasks
```

The smart one decides what to do. The cheap one does the actual work. Makes sense.

## Why OpenCode Go

OpenCode Go is flat-rate. DeepSeek V4 Flash, Kimi K2.6 — all included, no extra per-token cost. Flash is especially cheap and great for throwing lots of small tasks at.

| Model | Rough monthly capacity |
|---|---|
| DeepSeek V4 Flash | ~158,000 requests |
| Kimi K2.6 | ~19,000 requests |
| GLM-5.1 | ~4,300 requests |

## The Problem: One-Shot Only

Calling OpenCode Go with `opencode run` works like this:

```text
Orchestrator → bash → opencode run "do this" → runs → exits
```

It fires once and forgets. No asking questions. No waiting. No back-and-forth.

This is fine for "do this, then do that" workflows. It works. We use it all the time. But you **cannot have a conversation** this way.

## The Fix: opencode serve + HTTP API

OpenCode has a `serve` mode. Run it in the background, and you can talk to it over HTTP.

```text
Orchestrator (Codex / Claude Code etc.)
  │  sends messages
  ▼
opencode serve (runs on your machine)
  │  keeps the conversation going
  ▼
Worker model
  ├─ Turn 1: "Read this file"
  ├─ Turn 2: "Now fix it"
  └─ Turn 3: "Show me the changes"
```

The trick is keeping **the same conversation session** open. Send messages to the same session ID, and the model remembers what you talked about.

## It Works

We tested this. Started `opencode serve`, sent 3 messages through the API:

```
Turn 1: "List the markdown files"
Turn 2: "Now filter to last 7 days"
Turn 3: "Count how many have 'journal' in the name"
```

**All three turns remembered the previous ones.** Context was preserved. 13 messages in one session, no problem.

## Handover: Setup Steps

Give these steps to your orchestrator (Codex, Claude Code, or any agent).

### 1. Start opencode serve

Open a terminal and run:

```powershell
opencode serve
```

(No special flags needed. Default settings work fine.)

### 2. Create a conversation

```powershell
$session = curl -X POST http://localhost:4096/session `
  -H "Content-Type: application/json" `
  -d '{"title": "my-task"}'
$sessionId = $session.id
```

### 3. Send a message

```powershell
$body = '{"parts": [{"type": "text", "text": "Analyze this file"}]}'
curl -X POST "http://localhost:4096/session/$sessionId/message" `
  -H "Content-Type: application/json" `
  -d $body
```

### 4. Keep talking

Same session ID, just keep sending.

```powershell
$body2 = '{"parts": [{"type": "text", "text": "Now fix it based on your analysis"}]}'
curl -X POST "http://localhost:4096/session/$sessionId/message" `
  -H "Content-Type: application/json" `
  -d $body2
```

### 5. Fork if needed

```powershell
# Fork (copies conversation, no parent link)
curl -X POST "http://localhost:4096/session/$parentId/fork"

# Create child (has parent link, empty conversation)
curl -X POST http://localhost:4096/session `
  -H "Content-Type: application/json" `
  -d "{`"title`":`"sub-task`",`"parentID`":`"$parentId`"}"
```

### 6. JS SDK (easier)

```powershell
npm install @opencode-ai/sdk
```

```typescript
import { createOpencodeClient } from "@opencode-ai/sdk"
const client = createOpencodeClient({ baseUrl: "http://localhost:4096" })

const session = await client.session.create({ body: { title: "task" } })
const id = session.id

await client.session.prompt({ path: { id }, body: { parts: [{ type: "text", text: "analyze" }] } })
await client.session.prompt({ path: { id }, body: { parts: [{ type: "text", text: "fix it" }] } })
```

### Telling Your Orchestrator

Add this to your AGENTS.md or system prompt:

> opencode serve runs on localhost:4096. To talk to a worker model, create a session with POST /session, then send messages to POST /session/:id/message. Use the same session ID to keep context.

## Not Everything Is Solved

The experiment proves the concept works. But there's still some work to do:

- A proper wrapper script would make this easier to use
- Need to handle cases where serve crashes
- Old sessions pile up — needs cleanup

## Bottom Line

```text
What we wanted: Orchestrator + cheap worker having conversations
Problem: opencode run is one-shot only
Solution: opencode serve + HTTP API bridges the gap
Result: 3-turn conversation confirmed. Context preserved.
```

OpenCode serve turns one-shot execution into real back-and-forth. If you want a smart model to supervise while cheap models do the grunt work, this is a practical way to make it happen.

:::conclusion
What opencode run could not do — conversational orchestrator + worker architecture — is achievable with opencode serve + HTTP API. Tested and confirmed with 3 turns of preserved context. Still needs wrapping for production use, but the technical path is clear.
:::
