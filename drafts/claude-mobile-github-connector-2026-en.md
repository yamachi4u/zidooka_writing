---
title: "How to Use GitHub from the Claude App: What to Do When the GitHub Connector Is Missing on Mobile"
categories:
  - AI
tags:
  - Claude
  - GitHub
  - MCP
  - Claude Code
  - Android
status: publish
slug: claude-mobile-github-connector-2026-en
---

Using GitHub from the Claude mobile app is possible in 2026, but the product surface is confusing because several different GitHub integrations coexist.

:::conclusion
If you want Claude to manage GitHub issues, pull requests, commits, and repository searches, the main option is now the official GitHub MCP connector. Anthropic's Connectors Directory explicitly lists Claude Mobile support and Read & Write capabilities.

However, some users do not see GitHub in the mobile app's Connectors list. In that case, connecting GitHub first from the web version of Claude and then using the remote connector from mobile is the most practical workaround.
:::

## There are effectively three GitHub integrations in Claude

### 1. The older “Add from GitHub” integration

In chats and Projects, Claude can import repository files through the “+” menu and “Add from GitHub.” This is useful for codebase context, explanations, and review.

Anthropic's documentation says this integration retrieves only file names and file contents from a specific branch. It does not retrieve commit history, pull requests, or other repository metadata.

So this is primarily a way to let Claude read code, not a general GitHub control layer.

### 2. GitHub MCP Connector

This is now the more capable option.

Anthropic's Connectors Directory lists “GitHub MCP / The Official GitHub MCP Server” as available in Claude, Claude Desktop, Claude Mobile, Claude Code, and the Claude API.

Its capability is listed as “Read & write.” Example use cases include reading recent commits, managing issues, reviewing pull requests, and searching repository code.

Conceptually, the architecture is:

`Claude app → GitHub MCP → GitHub API`

In other words, the connector gives Claude GitHub tools rather than merely copying repository files into the conversation context.

### 3. Claude Code's GitHub integration

If the goal is to actually modify code, run tests, create commits, push branches, and open pull requests, Claude Code is the stronger tool.

Claude Code on the web performs git operations through a sandboxed proxy. Claude Mobile also provides a Code surface for accounts with Claude Code access, and a connected GitHub repository and branch can be selected when starting a session.

Anthropic also documents deep links such as:

`claude://code/new?repo=owner%2Frepo&branch=main`

A useful distinction is that GitHub MCP focuses on GitHub API operations, while Claude Code acts as a development agent that can check out a repository, edit and execute code, and then return changes through git.

## Why might GitHub be missing from the mobile Connectors list?

This is the confusing part.

Anthropic's documentation says remote connectors are available across web, mobile, desktop, Cowork, and Claude Code. GitHub is also explicitly used as an example of a remote connector, and the GitHub MCP directory page lists Claude Mobile as a supported surface.

At the same time, community reports from August 2026 describe GitHub disappearing from the iOS or Android Connectors list while remaining available through the Claude web settings.

That suggests a mismatch between the documented capability and the current mobile UI exposure.

:::warning
If GitHub does not appear in the mobile app's Connectors list, that does not necessarily mean Claude Mobile lacks GitHub support. Official documentation still lists it as supported. UI rollout, account state, or product-surface differences may be involved.
:::

## The most reliable setup right now

### For GitHub MCP

1. Open `claude.ai` in a desktop or mobile browser.
2. Open Settings → Connectors.
3. Find GitHub / GitHub MCP.
4. Authenticate through GitHub OAuth.
5. Reopen the Claude mobile app.
6. Ask Claude to list issues, inspect a pull request, search a repository, and so on.

Because remote connectors are account-level integrations, the key is to establish the connection on the web even if the mobile app does not expose the setup UI.

### For actual code changes

Use the Code tab in the Claude app.

Once GitHub is connected, select a repository and branch and start a Claude Code session. For accounts with Claude Code access, the cloud session can work on the repository without requiring a local computer to remain online.

## Which option should you use?

A simple rule of thumb:

- Want Claude to read repository files for discussion → Add from GitHub
- Want Claude to manage issues, PRs, commits, or repository searches → GitHub MCP
- Want Claude to modify code, run tests, commit, push, and create PRs → Claude Code

For the general request “control GitHub from the Claude app,” GitHub MCP or Claude Code is the relevant answer in 2026.

## Summary

Claude Mobile can work with GitHub, and the official GitHub MCP connector is listed as Read & Write capable.

The current complication is that GitHub may not appear in the mobile app's connector picker. When that happens, connecting it first through the Claude web app is the most sensible workaround. It is also important to distinguish the older read-only-style repository import, the GitHub MCP tool layer, and the full Claude Code development workflow.

## References

- Anthropic Connectors Directory, GitHub MCP: https://claude.com/connectors/github
- Claude Help Center, Use the GitHub integration: https://support.claude.com/en/articles/10167454-use-the-github-integration
- Claude Help Center, Use connectors to extend Claude's capabilities: https://support.claude.com/en/articles/11176164-use-connectors-to-extend-claude-s-capabilities
- Claude Help Center, When to use desktop and web connectors: https://support.claude.com/en/articles/11725091-when-to-use-desktop-and-web-connectors
- Claude Help Center, Open the Claude mobile app with a link: https://support.claude.com/en/articles/14898120-open-the-claude-mobile-app-with-a-link
