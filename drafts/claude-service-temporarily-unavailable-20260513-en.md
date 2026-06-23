---
title: "What Does Claude’s “Service is temporarily unavailable. You can try again.” Error Mean?"
slug: claude-service-temporarily-unavailable-20260513-en
categories:
  - AI系エラー
tags:
  - Claude
  - Anthropic
  - AI
  - Error
  - Outage
status: publish
featured_image: "C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-13 211111.png"
---

When using Claude, you may see a warning at the top of the page that says:

![Claude service temporarily unavailable screenshot](C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-13 211111.png)

> Service is temporarily unavailable. You can try again.

In Japanese, the same warning may appear as:

> 現在ご利用いただけません。後ほど再度お試しください。

:::conclusion
This usually points to a temporary Claude-side availability issue or elevated error rate. It does not necessarily mean your browser, computer, or Claude account is broken.
:::

## What does this error mean?

`Service is temporarily unavailable. You can try again.` appears when Claude cannot handle the request normally for a short period of time.

In practical terms, it is closer to a service availability or backend error than a problem with the prompt you typed. The Claude web app may be overloaded, degraded, or affected by an incident.

## Status on May 13, 2026

Anthropic’s official Claude Status page listed an incident on May 13, 2026 titled `Claude.ai is experiencing elevated error rates`.

According to the status page, Anthropic started investigating at 11:25 UTC and moved the incident to monitoring at 11:48 UTC after seeing recovery.

Reference:

- [Claude Status](https://status.claude.com/)

:::note
The status page changes over time. By the time you read this, the incident may already be marked as resolved.
:::

## What should you check first?

The fastest check is the official status page.

1. Open [Claude Status](https://status.claude.com/).
2. Check whether `claude.ai` has an active incident or degraded performance.
3. Check the web app separately from Claude Code or the API.
4. Wait a few minutes and retry.

If the official status page shows an active Claude.ai incident, changing local settings is unlikely to fix the root cause.

## What can you do?

Try these steps in order:

1. Wait a few minutes and try again.
2. Reload the page.
3. Open Claude in a new tab or private/incognito window.
4. Sign out and sign back in.
5. Temporarily disable VPN or proxy settings if you use them.
6. If the task is urgent, use another AI service temporarily, such as ChatGPT, Gemini, or Perplexity.

:::warning
If Claude Status shows an active incident, avoid making major local changes such as resetting your browser profile or recreating your account. Waiting for service recovery is usually the safer move.
:::

## Is this an account ban or usage limit?

Not usually.

This exact wording does not, by itself, indicate an account suspension or message limit. Usage-limit messages usually mention limits, remaining messages, usage, or plan restrictions more directly.

That said, if only your account keeps seeing the same error for a long time after the status page is normal, try signing out, using another browser, or changing networks.

## Bottom line

Claude’s `Service is temporarily unavailable. You can try again.` message is usually a temporary Claude-side error.

Check the official Claude Status page first. If there is an active incident, wait for recovery and use another AI tool temporarily if the work is urgent.
