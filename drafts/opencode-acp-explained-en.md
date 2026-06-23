---
title: "What is ACP in OpenCode? The Universal Protocol for Editor-Agent Integration"
categories:
  - AI
tags:
  - OpenCode
  - ACP
  - Agent Client Protocol
  - AI
  - Zed
  - Editor
  - Development
status: publish
slug: opencode-acp-explained-en
featured_image: ../images/2026/05/opencode-acp-thumbnail-en.png
---

OpenCode includes an `opencode acp` command. What is it for and what can you do with it? Here is the full picture.

:::conclusion
ACP (Agent Client Protocol) is an open standard that decouples code editors from AI coding agents. Created by Zed Industries, it is now supported by 30+ agents including OpenCode, Gemini CLI, Claude Agent, and Codex CLI. It works across Zed, JetBrains, VS Code, Neovim, Emacs, and many more editors.
:::

## What is ACP?

ACP stands for **Agent Client Protocol** — an open protocol that standardizes communication between code editors (clients) and AI coding agents (servers).

The specification is open source on GitHub ([agentclientprotocol/agent-client-protocol](https://github.com/agentclientprotocol/agent-client-protocol)) with over 3,200 stars as of May 2026.

ACP was inspired by LSP (Language Server Protocol). Just as LSP lets any editor use any language server, ACP lets any editor use any AI coding agent.

:::note
ACP was initiated by Zed Industries but is now a community-driven protocol with participants including JetBrains, Google, Anthropic, and OpenAI.
:::

ACP communicates via JSON-RPC over stdio. Editors launch agents as subprocesses and exchange messages through standard input/output. Remote connections via HTTP and WebSocket are planned for future versions.

## OpenCode and ACP

To start OpenCode as an ACP agent:

```powershell
opencode acp --cwd /path/to/project
```

This launches OpenCode as an ACP-compatible subprocess that communicates with your editor over JSON-RPC.

### Editor configuration examples

**Zed** (`~/.config/zed/settings.json`):

```json
{
  "agent_servers": {
    "OpenCode": {
      "command": "opencode",
      "args": ["acp"]
    }
  }
}
```

**JetBrains IDE** (`acp.json`):

```json
{
  "agent_servers": {
    "OpenCode": {
      "command": "/absolute/path/bin/opencode",
      "args": ["acp"]
    }
  }
}
```

**Neovim + CodeCompanion.nvim**:

```lua
require("codecompanion").setup({
  interactions = {
    chat = {
      adapter = {
        name = "opencode",
        model = "claude-sonnet-4",
      },
    },
  },
})
```

### OpenCode features available over ACP

Nearly all OpenCode features work over ACP:

- File read/write/edit operations
- Terminal command execution
- MCP servers (external tool integration)
- Custom tools and slash commands
- Project `AGENTS.md` rule resolution
- Custom formatters and linters
- Agents and permissions system

:::warning
The `/undo` and `/redo` slash commands are not yet supported over ACP.
:::

## ACP-compatible agents (selected)

Over 30 agents support ACP as of May 2026.

| Agent | Creator | Link |
|-------|---------|------|
| Gemini CLI | Google | [github.com/google-gemini/gemini-cli](https://github.com/google-gemini/gemini-cli) |
| Claude Agent | Anthropic | [via ACP adapter](https://github.com/zed-industries/claude-agent-acp) |
| Codex CLI | OpenAI | [via ACP adapter](https://github.com/zed-industries/codex-acp) |
| GitHub Copilot | GitHub | [public preview](https://github.blog/changelog/2026-01-28-acp-support-in-copilot-cli-is-now-in-public-preview/) |
| Cursor | Cursor | [cursor.com/docs/cli/acp](https://cursor.com/docs/cli/acp) |
| Goose | Square/Block | [ACP client support](https://block.github.io/goose/docs/guides/acp-clients) |
| Cline | Cline | [cline.bot](https://cline.bot/) |
| Qwen Code | Alibaba | [github.com/QwenLM/qwen-code](https://github.com/QwenLM/qwen-code) |
| Junie | JetBrains | [junie.jetbrains.com](https://junie.jetbrains.com/) |
| Augment Code | Augment | [docs.augmentcode.com/cli/acp](https://docs.augmentcode.com/cli/acp) |
| Mistral Vibe | Mistral AI | [github.com/mistralai/mistral-vibe](https://github.com/mistralai/mistral-vibe) |
| OpenHands | All Hands AI | [ACP support](https://docs.openhands.dev/openhands/usage/run-openhands/acp) |

Others include Aider (in progress), Docker cagent, Kimi CLI (Moonshot AI), Kiro CLI, Factory Droid, and more.

Full list: <https://agentclientprotocol.com/overview/agents>

## ACP-compatible clients (editors and tools)

### Major editors

- **Zed** — native support (ACP originator) [docs](https://zed.dev/docs/ai/external-agents)
- **JetBrains IDE** — IntelliJ, WebStorm, PyCharm, and all products [help](https://www.jetbrains.com/help/ai-assistant/acp.html)
- **VS Code** — via [ACP Client extension](https://github.com/formulahendry/vscode-acp)
- **Neovim** — three plugins:
  - [CodeCompanion.nvim](https://github.com/olimorris/codecompanion.nvim)
  - [avante.nvim](https://github.com/yetone/avante.nvim)
  - [agentic.nvim](https://github.com/carlos-algms/agentic.nvim)
- **Emacs** — [agent-shell.el](https://github.com/xenodium/agent-shell)
- **Obsidian** — [Agent Client plugin](https://github.com/RAIT-09/obsidian-agent-client)
- **Unity** — multiple ACP plugins available

### CLI and desktop tools

- **Jockey** — multi-agent orchestrator combining Claude Code, Gemini CLI, and Codex CLI via ACP [github.com/recailai/jockey](https://github.com/recailai/jockey)
- **acpx CLI** — terminal ACP client [github.com/openclaw/acpx](https://github.com/openclaw/acpx)
- **ACP UI** — cross-platform GUI [github.com/formulahendry/acp-ui](https://github.com/formulahendry/acp-ui)
- **Toad** — terminal agent interface [batrachian.ai](https://www.batrachian.ai/)

### Chat integrations

Bridges exist to invoke ACP agents from chat platforms:

- [OpenACP](https://github.com/Open-ACP/OpenACP) — self-hosted bridge for Telegram, Discord, Slack
- [Telegram ACP Bot](https://github.com/mgaitan/telegram-acp-bot) — Telegram integration
- [WeChat ACP](https://github.com/formulahendry/wechat-acp) — WeChat integration

### Framework integrations

- **LangChain / LangGraph** — via [Deep Agents ACP](https://docs.langchain.com/oss/python/deepagents/acp)
- **LlamaIndex** — via [workflows-acp](https://github.com/AstraBert/workflows-acp) adapter
- **Koog** (JetBrains) — built-in support

:::note
Mobile ACP clients are emerging too: Agmente (iOS), Ferngeist (Android), Happy (iOS/Android/Web), and Mobvibe.
:::

## Why ACP matters

### 1. Freedom to mix editors and agents

Before ACP, each agent was tied to specific interfaces. With ACP, you can use any agent in any editor. Want OpenCode in Zed? Claude Agent in JetBrains? Gemini CLI in Neovim? All possible.

### 2. Easy agent switching

Switch agents based on the task without changing your editor. Change a config file and you are using a different agent with the same workflow.

### 3. Growing ecosystem

A common protocol means new agents and clients can be built once and work everywhere. Editor developers implement one protocol and gain access to 30+ agents instantly.

## Real-world use cases

### Case 1: Zed + OpenCode for terminal-free AI development

Connect OpenCode to Zed via ACP and ask the AI questions or generate code directly in the editor. No separate TUI window needed.

### Case 2: JetBrains + multiple agents

Register both OpenCode and Gemini CLI in IntelliJ IDEA via ACP. Use OpenCode for everyday coding and Gemini CLI for large-scale refactoring.

### Case 3: Jockey for parallel multi-agent execution

Jockey lets you run Claude Code, Gemini CLI, and Codex CLI simultaneously against the same project. Assign code review to Claude Code and test generation to Gemini CLI in parallel.

### Case 4: Chat-based AI via Discord/Slack

With OpenACP or similar bridges, your team can ask OpenCode to review a PR directly from a Slack channel — no need to open an editor.

:::conclusion
ACP (Agent Client Protocol) removes the barrier between editors and AI agents. OpenCode supports it via the `opencode acp` command and works with Zed, JetBrains, Neovim, VS Code, and many more. With 30+ agents and a rapidly growing client ecosystem, ACP is becoming the standard for editor-agent integration.
:::

### References

- ACP official site: <https://agentclientprotocol.com>
- ACP spec repository: <https://github.com/agentclientprotocol/agent-client-protocol>
- ACP progress report (Zed Blog): <https://zed.dev/blog/acp-progress-report>
- OpenCode ACP docs: <https://opencode.ai/docs/acp>
