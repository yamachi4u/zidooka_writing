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

GitHub is a remote connector, so once connected it is designed to work across web, mobile, desktop, Cowork, and Claude Code. However, Anthropic's help documentation also says that installing connectors from mobile is currently in beta and that Claude Desktop and the web are the primary paths for adding custom connectors. If GitHub is missing from the mobile connector list, connect it from the web or desktop first.
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

GitHub and similar cloud services use remote connectors. Anthropic says remote connectors work across the Claude web app, mobile apps, desktop, Cowork, and Claude Code, and once connected they are available everywhere without additional setup.

At the same time, Anthropic explicitly says that installing connectors from mobile is currently in beta, while Claude Desktop and the web are the primary routes for adding custom connectors.

That distinction matters: “usable on mobile” does not necessarily mean “reliably installable from the mobile UI.” If GitHub is missing from the app's connector list, the safest interpretation is that the mobile setup surface is still evolving rather than that GitHub itself is unsupported.

:::warning
If GitHub does not appear in the mobile app's Connectors list, that does not mean Claude Mobile lacks GitHub support. The official GitHub MCP connector lists Claude Mobile support, and remote connectors are designed to work across Claude surfaces. The initial connection is simply more reliable from the web or desktop while mobile installation remains in beta.
:::

## The most reliable setup right now

### For GitHub MCP

1. Open `claude.ai` on a PC browser.
2. Open Connectors → Manage connectors, or Settings → Customize → Connectors.
3. Find GitHub / GitHub MCP and select Connect.
4. Approve access on GitHub's authorization screen.
5. Reopen the Claude mobile app.
6. Ask Claude to list issues, inspect a pull request, search a repository, and so on.

Because remote connectors are shared across Claude surfaces once connected, the practical approach is to perform the initial setup on web or desktop and then use the same GitHub connection from mobile.

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

The key detail is that GitHub is a remote connector that becomes available across web, mobile, desktop, Cowork, and Claude Code once connected, while installing connectors from mobile is still in beta. If GitHub is missing on mobile, establish the connection first from the web or desktop.

It is also important to distinguish the older repository import feature, the GitHub MCP tool layer, and the full Claude Code development workflow.

## References

- Anthropic Connectors Directory, GitHub MCP: https://claude.com/connectors/github
- Claude Help Center, Use the GitHub integration: https://support.claude.com/en/articles/10167454-use-the-github-integration
- Claude Help Center, Use connectors to extend Claude's capabilities: https://support.claude.com/en/articles/11176164-use-connectors-to-extend-claude-s-capabilities
- Claude Help Center, When to use desktop and web connectors: https://support.claude.com/en/articles/11725091-when-to-use-desktop-and-web-connectors
- Claude Help Center, Open the Claude mobile app with a link: https://support.claude.com/en/articles/14898120-open-the-claude-mobile-app-with-a-link
