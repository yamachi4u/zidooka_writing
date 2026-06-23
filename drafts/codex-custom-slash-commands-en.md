---
title: "Can You Register Custom Slash Commands in Codex?"
categories:
  - AI
tags:
  - Codex
  - AI Agents
  - AGENTS.md
  - Slash Commands
status: publish
slug: codex-custom-slash-commands-en
---

When using Codex, it is natural to want short commands for frequent workflows.

For example, you might want a quick trigger that tells the agent to switch to a specific project directory or follow a repeated workflow.

So can you register your own slash commands, such as `/custom`, through `AGENTS.md`?

Based on normal Codex behavior, the practical answer appears to be no.

:::conclusion
Custom slash commands are intercepted by the Codex UI or CLI before the message reaches the agent. That means `AGENTS.md` cannot register a new `/custom` command by itself.
:::

## Why AGENTS.md Cannot Add Slash Commands

`AGENTS.md` is read by the agent after the user message reaches the model.

Slash commands are different. Text that starts with `/` is handled earlier by the Codex interface or CLI as an application-level command.

If the command is not supported, the interface reports it as unrecognized before the agent can interpret it as ordinary text.

The rough flow looks like this:

:::step
1. The user types something like `/custom`.
2. The Codex UI or CLI interprets it as a slash command.
3. If the command is unsupported, it fails at that layer.
4. The agent never receives it as a normal instruction.
:::

Because of that, writing “when the user says `/custom`, do X” in `AGENTS.md` does not work as a true custom command.

## What Does Work: Plain-Text Shortcuts

Plain-text shortcuts can still work well.

For example:

```markdown
## Directory Shortcuts
- If the user says `project-a`, use `C:\path\to\project-a` as the default working directory.
```

In this case, `project-a` is not a slash command. It is just normal text, so it reaches the agent.

The agent can then use `AGENTS.md` as a routing rule and treat that phrase as a project shortcut.

:::note
If you want custom workflow triggers, use plain words or short phrases rather than `/...` commands.
:::

## What Does Not Work

`AGENTS.md` can guide the agent’s behavior, but it does not extend the Codex UI or CLI command registry.

That means it is not the right place to:

- add new `/xxx` commands
- override built-in slash command behavior
- force unsupported slash commands to pass through as normal text

Unless Codex exposes a formal extension mechanism for slash commands, user-side `AGENTS.md` rules should be treated as agent instructions, not UI command definitions.

## A Practical Alternative

A reliable pattern is to define short plain-text triggers.

:::example
Use phrases like `project-a`, `open the writing folder`, or `start the invoice workflow` instead of custom slash commands.
:::

This works because the phrase reaches the agent as normal user input.

It can be useful for:

- default project directories
- daily coordination files
- publishing workflows
- validation routines
- Git or no-Git project rules

The key is to design the shortcut as a normal phrase the agent can read.

## Summary

Custom slash commands are tempting, but they are handled before the agent sees the message.

`AGENTS.md` can define useful workflow shortcuts, but not new slash commands.

:::conclusion
You probably cannot register arbitrary Codex slash commands through `AGENTS.md`. Use plain-text shortcuts instead.
:::
