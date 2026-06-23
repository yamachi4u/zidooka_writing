---
title: "Calling OpenCode from Another Harness: CLI run, serve, SDK, and ACP"
categories:
  - AI
tags:
  - OpenCode
  - CLI
  - SDK
  - ACP
  - AI
  - Automation
  - CI/CD
status: publish
slug: opencode-harness-integration-en
featured_image: ../images/2026/05/opencode-harness-thumbnail-en.png
---

Running `opencode` in the terminal is already powerful, but many workflows call for automation: triggering it from a CI pipeline, embedding it in another application, or using it inside an editor.

OpenCode supports **four integration patterns** for this purpose, and all of them accept `-m` for model selection and `--dir` for the working directory.

:::conclusion
You can call OpenCode from another harness via CLI run, serve + REST API, JS/TS SDK, or ACP (stdin JSON-RPC). Choose the one that fits your architecture.
:::

![OpenCode harness integration flow diagram](../images/2026/05/opencode-harness-flow.svg)

## 1. CLI run — The simplest non-interactive mode

Best for shell scripts, cron jobs, and CI/CD pipelines where you just want to fire a prompt and get the result.

```powershell
opencode run "Explain how closures work in JavaScript" -m anthropic/claude-sonnet-4-5 --dir /myproject
```

`opencode run` executes the prompt, streams the AI response to stdout, and exits. The return value is the text output.

:::note
Add `--format json` to get JSON event output, which is easier to parse in scripts.
:::

### Key options

| Option | Short | Description |
|--------|-------|-------------|
| `--model` | `-m` | Model in `provider/model` format |
| `--dir` | | Working directory |
| `--format` | | `json` for JSON event output |
| `--continue` | `-c` | Continue last session |
| `--session` | `-s` | Specify session ID |
| `--agent` | | Specify agent |
| `--file` | `-f` | Attach files |
| `--attach` | | Connect to running server |

### Use cases

- CI/CD pipeline code generation and review
- Batch processing (daily reports, etc.)
- Cron-based scheduled tasks
- One-shot scripting tasks

:::step
`opencode run "message" -m model --dir /path` is all you need for one-shot AI tasks from scripts.
:::

## 2. serve + REST API — Persistent server mode

Start an HTTP server and interact with OpenCode through a REST API.

```powershell
# Start the server (keep running in background)
opencode serve --port 4096
```

The server exposes endpoints at `http://localhost:4096`, with an OpenAPI 3.1 spec available at `/doc`.

### Key endpoints

```powershell
# Create a session
curl -X POST http://localhost:4096/session -H "Content-Type: application/json" -d '{"title":"test"}'

# Send a message and wait for response
curl -X POST http://localhost:4096/session/{id}/message -H "Content-Type: application/json" `
  -d '{"parts":[{"type":"text","text":"Hello"}],"model":{"providerID":"anthropic","modelID":"claude-sonnet-4-5"}}'
```

:::note
Subscribe to the `/event` SSE endpoint for real-time tool execution progress and status updates.
:::

### Security

Set `OPENCODE_SERVER_PASSWORD` to enable HTTP Basic auth. Essential for production deployments.

### Use cases

- Web application backends
- Chatbot server-side logic
- Multi-session management dashboards
- Centralized remote management

## 3. JS/TS SDK — The most full-featured

The official `@opencode-ai/sdk` package provides type-safe programmatic access from Node.js.

```bash
npm install @opencode-ai/sdk
```

```typescript
import { createOpencode } from "@opencode-ai/sdk";

const { client } = await createOpencode({
  config: { model: "anthropic/claude-sonnet-4-5" }
});

const session = await client.session.create({
  body: { title: "Code review" }
});

const result = await client.session.prompt({
  path: { id: session.data.id! },
  body: {
    parts: [{ type: "text", text: "Review src/index.ts for bugs" }]
  }
});

console.log(result.data.info.title);
```

### Structured output

You can request validated JSON output by specifying a JSON schema:

```typescript
const result = await client.session.prompt({
  path: { id: sessionId },
  body: {
    parts: [{ type: "text", text: "List all bugs in this code" }],
    format: {
      type: "json_schema",
      schema: {
        type: "object",
        properties: {
          bugs: {
            type: "array",
            items: {
              type: "object",
              properties: {
                file: { type: "string" },
                line: { type: "number" },
                description: { type: "string" },
                severity: { type: "string", enum: ["low", "medium", "high"] }
              },
              required: ["file", "description", "severity"]
            }
          }
        },
        required: ["bugs"]
      }
    }
  }
});
```

:::note
Use `noReply: true` to inject context without triggering an AI response. Useful in plugin development.
:::

### Use cases

- Deep Node.js application integration
- Structured data extraction and analysis
- Multi-agent orchestration
- Custom tool integration

## 4. ACP (Agent Client Protocol) — Editor and stdin integration

ACP is a standardized protocol for communication between editors and AI agents. Running `opencode acp` starts a JSON-RPC session over stdin/stdout.

```powershell
opencode acp --cwd /path/to/project
```

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

:::note
ACP supports all OpenCode features: file operations, terminal execution, MCP servers, custom tools, and agents.
:::

### Use cases

- Zed, JetBrains, Neovim editor integration
- Custom IDE plugin development
- Inter-process communication (IPC) tool integration

## Comparison summary

| Aspect | CLI run | serve+REST | SDK | ACP |
|--------|---------|------------|-----|-----|
| Startup | Single process | Background server | In-code startup | Editor-launched |
| Return type | stdout / JSON | JSON + SSE | Typed JS objects | nd-JSON |
| Learning curve | Low (CLI only) | Medium (REST) | Medium (JS) | Low (config) |
| Concurrent sessions | Single | Multiple | Multiple | Editor-dependent |
| Model selection | `-m` | In request body | In code | Config file |

:::conclusion
- **Shell scripts / CI/CD**: `opencode run` is sufficient
- **Web app integration**: `opencode serve` + REST API
- **Native Node.js integration**: `@opencode-ai/sdk`
- **Built-in editor AI**: `opencode acp`
- All patterns support `-m` for model selection and `--dir` for working directory
:::
