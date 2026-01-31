---
title: "What is Ralph Driven Development? A New AI Agent Loop Methodology Explained"
date: 2026-01-19 10:00:00
categories: 
  - AI
tags: 
  - AI
  - Claude
  - OpenCode
  - Agent
  - Automation
  - Git
status: publish
slug: ralph-driven-development-en
featured_image: ../images-agent-browser/ghuntley-ralph.png
---

AI can now write code, but the "one-shot completion" approach has significant limitations. AI forgets context, misinterprets instructions, and sometimes goes off the rails.

**Ralph Driven Development (RDD)** offers one solution to these problems.

**Key Takeaway:** Ralph is a development methodology that executes AI tasks one at a time, iterating while managing state through git.

## What is Ralph?

![Geoffrey Huntley's blog on Ralph Driven Development](../images-agent-browser/ghuntley-ralph.png)

Ralph is a development approach that runs AI coding agents in a **loop**, iterating until all tasks are complete. The name comes from "Ralph Wiggum," the Simpsons character.

In its purest form, Ralph is expressed as a simple Bash loop:

```bash
while :; do cat PROMPT.md | claude-code ; done
```

This looks simple, but this "infinite loop" is the essence of Ralph.

## Why Ralph is Needed

Traditional AI coding has these problems:

- **Context loss**: AI "forgets" during long conversations
- **Bulk processing fragility**: Large tasks assigned at once often break midway
- **Tracking difficulty**: Hard to know what AI actually did
- **Rollback challenges**: Unclear where to revert when problems occur

Ralph solves these problems **through structure**.

## The Basic Ralph Loop

Ralph's operation is extremely simple:

1. **Read the Plan file** - A task list like `plan.md`
2. **Pick ONE task** - Only one incomplete task
3. **Implement it** - Complete only that task
4. **Git commit** - Record changes in version control
5. **Repeat** - Loop steps 1-4 until all tasks are done

**Key Point:** AI operates on a "1 task = 1 commit" basis. This makes rollbacks easy when things fail.

## Context is Managed by Git

Ralph's innovative approach is that it **doesn't rely on the model's context for memory**.

In traditional AI chat, conversation history becomes the model's context. But this has limits. Ralph externalizes memory:

- **Git history**: Past commits record "what was done"
- **progress.txt**: Learnings appended after each iteration
- **AGENTS.md**: Accumulated operational knowledge

Each loop iteration starts with **fresh context**, reading necessary information from files.

## opencode-ralph: OpenCode Implementation

![opencode-ralph repository](../images-agent-browser/opencode-ralph-repo.png)

[opencode-ralph](https://github.com/hona/opencode-ralph) implements Ralph using the OpenCode SDK and OpenTUI.

### Installation

```bash
# Global install with Bun
bun install -g @hona/ralph-cli

# Run in any project directory
ralph
```

### Main Options

| Option | Default | Description |
|--------|---------|-------------|
| `--plan, -p` | `plan.md` | Plan file path |
| `--model, -m` | `opencode/claude-opus-4-5` | Model to use |
| `--reset, -r` | `false` | Reset state |

### Keybindings

- `p` key: Pause/resume
- `q` or `Ctrl+C`: Quit

## snarktank/ralph: The Original Implementation

![snarktank/ralph repository](../images-agent-browser/snarktank-ralph-repo.png)

[snarktank/ralph](https://github.com/snarktank/ralph) is a Ralph implementation using Amp CLI, with over 4,800 stars.

It uses PRD (Product Requirement Document) format for task management, tracking completion status in JSON format.

### Basic Workflow

1. Create a PRD (Markdown format)
2. Convert to JSON using Ralph skill
3. Run `./scripts/ralph/ralph.sh`
4. Auto-loop until all tasks complete

## Writing plan.md

Ralph's success depends heavily on **plan file quality**.

```markdown
# Project Plan

## Phase 1: Setup
- [ ] Initialize project with bun init
- [ ] Add TypeScript configuration
- [ ] Create src/index.ts entry point

## Phase 2: Core Features
- [ ] Implement user authentication
- [ ] Add database connection
```

**Caution:** Keep tasks small and isolated. Instead of "add authentication feature," break it down into "create login form" and "implement password validation."

### Plan File Guidelines

- Size each task to complete in 1 commit
- Order by dependencies
- Use `- [ ]` checkbox format
- More detail is better (1000+ lines is normal)

## The Role of AGENTS.md

Ralph learns from failures. `AGENTS.md` records those learnings.

```markdown
# AGENTS.md

## Build
- Run `bun install` before `bun run dev`

## Pitfalls
- Never import from `solid-js`, use `@opentui/solid`
```

When Ralph makes a mistake, you add a "sign" there. The next iteration reads those signs and avoids the same errors.

**Action:** When Ralph repeats the same mistake, add an explicit rule to AGENTS.md.

## Who is This For?

Ralph isn't for everyone.

**Good fit:**
- You develop with git as a foundation
- You distrust AI's "one-shot answers"
- You value task management and history
- You want to seriously adopt agent-based AI

**Not a good fit:**
- You just want finished code fast
- You don't understand git workflow
- Incremental work feels tedious

## What Ralph Represents

Ralph doesn't promise "AI will get smarter." Quite the opposite.

> AI forgets. Therefore, humans must design the structure.

This is an extremely realistic stance. It's about **containing AI's weaknesses (context loss, runaway behavior) through structure**, not tools.

:::conclusion
Ralph Driven Development is a framework for treating AI agents as "reliable workers." Combined with tools like OpenCode or Amp, it enables iterative and traceable automated development.
:::

## References

- [Geoffrey Huntley - Ralph Wiggum as a "software engineer"](https://ghuntley.com/ralph/)
- [opencode-ralph (GitHub)](https://github.com/hona/opencode-ralph)
- [snarktank/ralph (GitHub)](https://github.com/snarktank/ralph)
- [Luke Parker - Stop Chatting with AI, Start Loops](https://lukeparker.dev/stop-chatting-with-ai-start-loops-ralph-driven-development)
