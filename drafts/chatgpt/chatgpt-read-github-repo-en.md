---
title: "ChatGPT Can Now Read GitHub Repositories — It Reads AGENTS.md Like a Coding Agent"
categories:
  - AI
  - ChatGPT
tags:
  - ChatGPT
  - github
  - Codex
  - OpenCode
  - AI Agent
  - AGENTS.md
status: publish
slug: chatgpt-read-github-repo-en
---

:::conclusion
ChatGPT now connects to GitHub and reads code, READMEs, and docs straight from your repositories. It answers with citations, but it's read-only. No pushes, no PRs, no local execution. The real payoff is AGENTS.md: ChatGPT can now read the same project playbook that coding agents like Codex CLI and OpenCode use.
:::

## What Changed

Connect GitHub from Settings → Apps in ChatGPT, pick which repositories to share, and you're done. It takes a few minutes. ChatGPT then pulls live data from your repos and reasons over it in real time. Answers come back with the relevant snippets cited.

You'll find the feature in Deep Research, Agent Mode (Codex), and apps with sync or file search. Plan availability varies. Plus users, for example, may not see the GitHub app in standard Chat.

## The AGENTS.md Demo

AGENTS.md sits at the repo root. It's the playbook AI coding agents follow: build commands, test flow, naming rules. Codex CLI reads it first thing. OpenCode does too. Now ChatGPT reads the same file.

I tried it myself. I connected one of my repositories, asked how to set up the dev environment, and ChatGPT quoted my own AGENTS.md back at me. No more digging through README sections to guess what matters.

:::example
"How do I run the tests in this repo?"
"The README doesn't mention it — can you figure it out from the CI config?"
:::

The meaning goes beyond the demo. When ChatGPT reads AGENTS.md, it shares the same baseline understanding of a project that other AI agents use. One playbook, understood everywhere.

## Current Limits

The limits are worth knowing.

:::warning
Read-only for now. ChatGPT can't push code, open PRs, or update your repository. Writing is Codex's job.
:::

- **No local .env access**: ChatGPT searches GitHub's index, not your machine. Uncommitted local secrets and .env files never leave your PC
- **No local execution**: ChatGPT reads and answers. It doesn't run your code
- **No file-name search**: you search by repository, not by individual file
- **Sync lag**: repositories can take around five minutes to appear. Private and newly created repos need access configuration
- **Admin approval**: a GitHub admin can block a repo from being connected

There's a privacy angle too. Individual plans may use your content to improve models if the "Improve the model for everyone" setting is on. Business offerings are excluded by default.

## Wrap-up

ChatGPT reading GitHub isn't just "more files available." The useful part is that AGENTS.md works the same everywhere. ChatGPT, Codex, and OpenCode can all start from the same baseline.

Still read-only. Still cloud-side. But for asking questions about a codebase you don't know, it's genuinely practical. Connect one repo and ask it about your AGENTS.md — you'll feel the difference right away.

## References

1. OpenAI Help Center, "Connecting GitHub to ChatGPT": <https://help.openai.com/en/articles/11145903-connecting-github-to-chatgpt-deep-research>
2. OpenAI Help Center, "Using Codex with your ChatGPT plan": <https://help.openai.com/en/articles/11369540-using-codex-with-your-chatgpt-plan>
3. GitHub Apps, "ChatGPT Codex Connector": <https://github.com/apps/chatgpt-codex-connector>
4. OpenAI Developer Platform, "Codex": <https://developers.openai.com/codex/>
