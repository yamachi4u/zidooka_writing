---
title: "AI Agent Harnesses You Can Extend and Grow: Hermes, OpenCode, Claude Code, and Goose"
categories:
  - AI
tags:
  - OpenCode
  - AI Agent
  - Agent
  - Claude
  - Open Source
  - Comparison
  - Automation
status: publish
slug: extensible-ai-agent-harness-comparison-en
---

:::conclusion
If you're looking for an AI agent harness you can grow over time, the field splits into three main directions. OpenCode, Claude Code, and Goose let you add features through Skills, Plugins, or MCP without touching the core. Hermes goes a step further: the agent itself creates and improves skills from its own experience. The real question isn't which one has the most features, but who does the growing, you or the agent.
:::

## It started with Hermes's learning loop

When I revisited Hermes Agent from an OpenCode user's perspective last time, the thing that stuck with me most was the ability to auto-generate skills from experience.

```text
Complete a complex task → save the steps as a skill → reuse that flow next time → the skill improves itself with each use
```

That "the harness grows on its own" experience is rare in other tools. So this time I dug into the extension mechanisms of each harness, focusing on ones you can actually grow.

## Conclusion: two ways to extend, "add manually" and "learn automatically"

Based on what I checked as of August 2026, four harnesses stand out for extensibility.

| Tool | License | Main extension path | Who does the extending |
|---|---|---|---|
| Hermes Agent | MIT | Skills / Plugins / MCP / learning loop | **The agent learns automatically** |
| OpenCode | MIT | Plugins / Custom Tools / MCP / LSP | The user adds manually |
| Claude Code | Commercial | Plugins / Skills / MCP / Subagents | The user adds manually |
| Goose | Apache-2.0 | MCP / Extensions | The user adds manually |

"Extending a harness" turns out to be a mix of these two approaches.

## 1. Hermes Agent — the agent writes its own skills

Hermes (NousResearch, MIT) has four extension mechanisms.

- **Skills**: drop a `SKILL.md` into `~/.hermes/skills/`. Compatible with the agentskills.io open standard. Skills use progressive disclosure, loading full content only when needed to keep token use low.
- **Plugins**: add custom tools and hooks with `plugin.yaml` plus Python. `ctx.register_tool()` and `ctx.register_hook()` add tools, lifecycle hooks, and slash commands. Plugins come in four kinds (general / memory / context engine / model provider).
- **MCP**: declare servers under `mcp_servers` in `config.yaml` to connect external tools.
- **Learning loop**: this is the differentiator. Through the `skill_manage` tool, the agent creates and updates its own skills after complex tasks, or when the user corrects its approach. The `/learn` command can even turn existing docs or procedures into a skill automatically.

In short, Hermes has moved past "the user adds features" into "the agent remembers its own procedures."

```yaml
# ~/.hermes/plugins/hello-world/plugin.yaml
name: hello-world
version: "1.0"
description: A minimal example plugin
```

```python
# ~/.hermes/plugins/hello-world/__init__.py
def register(ctx):
    def handle_hello(params, **kwargs):
        return json.dumps({"success": True, "greeting": "Hello!"})
    ctx.register_tool(
        name="hello_world", toolset="hello_world",
        schema=schema, handler=handle_hello,
    )
```

Plugins are opt-in by default (`hermes plugins enable <name>`), a safe-by-default design.

## 2. OpenCode — type-safe plugins that hook into events

OpenCode (MIT) is a terminal-first coding harness that stands out for hooking into events through plugins.

- Drop a JS/TS file into `.opencode/plugins/` (project) or `~/.config/opencode/plugins/` (global).
- Add an npm package to the `plugin` field in `opencode.json` and it auto-installs.
- Subscribe to a rich set of events: `tool.execute.before` / `tool.execute.after`, `session.*`, `file.edited`, and more.
- **Custom Tools**: the `tool()` helper from `@opencode-ai/plugin` adds schema-validated custom tools the LLM can call directly.
- MCP, LSP, and Agent Skills are all supported.

```ts
// .opencode/plugins/custom-tools.ts
import { type Plugin, tool } from "@opencode-ai/plugin"
export const CustomToolsPlugin: Plugin = async (ctx) => {
  return {
    tool: {
      mytool: tool({
        description: "This is a custom tool",
        args: { foo: tool.schema.string() },
        async execute(args, context) { return `Hello ${args.foo}` },
      }),
    },
  }
}
```

From my own experience: a plugin tool with the same name as a built-in takes precedence, so overriding existing behavior is straightforward. Plugins install via Bun and their dependencies are cached under `~/.cache/opencode/`.

## 3. Claude Code — the most polished MCP story

Claude Code (Anthropic, commercial) centers its extension story on MCP (Model Context Protocol).

- `claude mcp add --transport http <name> <url>` connects an external tool in one line.
- Three scopes: local / project / user. Commit `.mcp.json` to your repo to share with the team.
- A plugin system (`/plugin install`) exists too, and official plugins like `mcp-server-dev` can scaffold an MCP server for you.
- The Anthropic Directory is full of ready connectors: GitHub, Sentry, databases, and more.

```bash
# Example: connect GitHub's MCP server
claude mcp add --transport http github https://api.githubcopilot.com/mcp/ \
  --header "Authorization: Bearer YOUR_GITHUB_PAT"
```

MCP servers run as external processes, so you never touch the harness code. This is the cleanest example of "add tools outside the core." The downside: it's commercial, so you can't modify the harness itself. The upside is the largest MCP ecosystem of the four.

## 4. Goose — everything goes through MCP extensions

Goose (Linux Foundation / AAIF, Apache-2.0) is the local agent originally built by Block. Its extension path is a single one: MCP extensions.

- Desktop / CLI / API form factors.
- Covers research, writing, automation, and data analysis, not just coding.
- You widen its abilities by adding MCP servers as extensions.

The philosophy is simple: don't build tools yourself, delegate everything to external MCP servers.

## 5. Frameworks aren't harnesses

Pydantic AI, CrewAI, and LangGraph are extensible too, but they're frameworks, not harnesses. They're the building blocks, not the daily driver. For someone who wants to "grow a harness," the framing is different enough that I'd keep them separate.

## How to choose

- **Want the harness to learn on its own** → Hermes. It auto-creates and improves skills from experience.
- **Want to add tools to a dev harness quickly** → OpenCode. Type-safe plugins and event hooks.
- **Want the richest MCP connectors** → Claude Code. The biggest ecosystem.
- **Want simplicity, external tools only** → Goose. MCP extensions are the whole story.

:::note
"Extensible" has levels, and that's the takeaway here. Adding features without touching the core (OpenCode / Claude Code / Goose) is one level. The agent learning and saving its own procedures (Hermes) is another. Over time, that gap in day-to-day convenience only widens.
:::

For me, the setup that fits best right now is OpenCode for daily coding and Hermes for resident automation. Rather than picking one harness, combining ones with different extension philosophies is the pragmatic answer in 2026.

## References

1. [Hermes Agent — Features Overview](<https://hermes-agent.nousresearch.com/docs/user-guide/features/overview/>)
2. [Hermes Agent — Plugins](<https://hermes-agent.nousresearch.com/docs/user-guide/features/plugins/>)
3. [Hermes Agent — Skills System](<https://hermes-agent.nousresearch.com/docs/user-guide/features/skills/>)
4. [OpenCode — Plugins](<https://opencode.ai/docs/plugins/>)
5. [OpenCode — Custom Tools](<https://opencode.ai/docs/custom-tools/>)
6. [Claude Code — Connect via MCP](<https://code.claude.com/docs/en/mcp>)
7. [Goose — GitHub](<https://github.com/block/goose>)
