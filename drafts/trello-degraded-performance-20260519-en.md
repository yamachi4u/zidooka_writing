---
title: "What Does Trello’s “Some Issues Are Occurring” Banner Mean?"
slug: trello-degraded-performance-20260519-en
categories:
  - エラーについて
tags:
  - Trello
  - Atlassian
  - Outage
  - Error
  - Status
status: publish
featured_image: "C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-19 093652.png"
---

When opening Trello, you may see a brown banner at the top of the page saying that some issues are occurring.

![Trello degraded performance banner](C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-19 093652.png)

In Japanese, the banner says:

> 現在、いくつかの問題が発生しています。早急な解決に向け対応しておりますのでしばらくお待ちください。

:::conclusion
This banner usually means Trello is experiencing a service-side incident or degraded performance. Before changing your browser or account settings, check the official Trello status page.
:::

## What is happening?

As of the morning of May 19, 2026 in Japan, Trello’s official status page lists an incident titled `Trello - degraded performance`.

The status page explains that affected areas may include comments, card activity views, attachments, Power-Ups, board exports, the home page updates view, search, and some card loading behavior.

In the May 18, 2026 18:45 EDT update, Trello explained that a database optimization issue left some partitions without indexes, causing queries against those partitions to time out. That update corresponds to around 7:45 AM on May 19, 2026 in Japan.

Reference:

- [Trello Status](https://trello.status.atlassian.com/)

:::note
Status pages change over time. By the time you read this, the incident may already be resolved or the affected features may have changed.
:::

## What should you check first?

Start with the official status page.

1. Open [Trello Status](https://trello.status.atlassian.com/).
2. Check whether `Trello.com` or `API` is marked as `Degraded Performance`.
3. Look at the latest incident update time.
4. Compare your symptoms with the affected areas listed in the incident.

If Trello’s own status page shows an active incident, major local troubleshooting is unlikely to fix the root cause.

## What can you do?

If you need to keep working, use a conservative workflow.

1. Reload the page.
2. Wait a few minutes and try again.
3. Keep a temporary note of important edits outside Trello.
4. Recheck comments and attachments after recovery.
5. Use Slack, email, or a shared document for urgent communication.

:::warning
During an active Trello incident, avoid rushing card or list moves and copies if they are not urgent. Wait for recovery, then confirm that the expected changes are visible.
:::

## How to tell whether it is only your environment

If Trello Status shows no active incident but you still see the same error, then check your local environment.

1. Open Trello in another browser.
2. Try a private or incognito window.
3. Temporarily disable VPN or proxy settings.
4. Check whether your company or school network is blocking Trello.
5. Sign out of Trello and sign back in.

However, if the official status page has an active incident at the same time, it is more likely to be a Trello-side issue.

## Bottom line

Trello’s “some issues are occurring” banner is a service status warning. It does not necessarily mean your browser, computer, or Trello account is broken.

Check [Trello Status](https://trello.status.atlassian.com/) first, then compare the listed impact with what you are seeing. If Trello is degraded, use another communication channel temporarily and verify your Trello changes again after recovery.
