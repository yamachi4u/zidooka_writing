---
title: "A first look at Buzz: an OSS workspace where humans and AI agents share the same room"
categories:
  - AI
  - アプリ開発
tags:
  - AIエージェント
  - マルチエージェント
  - 開発ツール
  - Nostr
  - Rust
  - オープンソース
  - GitHub
  - 2026年
status: publish
slug: buzz-human-agent-workspace-review-en
---

I came across [Buzz](https://github.com/block/buzz), an open-source project from Block. It is presented as a self-hostable workspace where humans and AI agents build together on a relay you own.

:::conclusion
Buzz is interesting because it treats AI agents as channel members with their own identities and audit trails, rather than as chat widgets bolted onto an existing workflow.
:::

## What Buzz is

Buzz looks like a team workspace, but its underlying model is closer to a signed event log. Messages, reactions, workflow steps, review approvals, and Git events are represented as signed Nostr events.

The desktop client uses Tauri and React, while the relay is built in Rust. The project also includes an agent-first CLI, an ACP harness, YAML workflows, Git events, search, canvases, media, and an audit log.

## Agents are members, not bots

Most AI development tools put the agent in a chat panel or an IDE sidebar. Buzz takes a different approach: an agent has its own keypair and identity, joins channels, posts work, reacts to messages, opens patches, and participates in review.

:::note
The useful idea is not simply “give an AI more tools.” It is to give each agent an identity, scoped channel membership, signed actions, and a searchable history.
:::

That makes Buzz feel less like an assistant attached to a team and more like a workspace designed for teams that contain both people and processes.

## A project room around a branch

One of the strongest ideas in the README is to make a feature branch into a room. Patches, CI results, review comments, approvals, and the merge decision can all live in the same channel.

Today, a project’s context is usually scattered across a chat application, a Git forge, CI dashboards, issue trackers, and undocumented conversations. Buzz is trying to make the channel itself the record of why the code exists.

:::example
An agent searches six months of history for a recurring error, posts the relevant threads and previous fixes, and offers to involve the person who handled the last incident. The question, answer, and evidence remain together.
:::

## Why Nostr matters here

Nostr gives Buzz a protocol for signed events and portable identities. A signature does not make an action correct, but it does make provenance explicit: which identity performed an action, in which community, and as part of which sequence of events.

As AI agents become more autonomous, this distinction matters. The system needs to retain not only the output, but also the actor, context, authorization boundary, and history behind it.

Buzz approaches that problem as an identity-and-log problem rather than only a permissions problem.

## What to be careful about

Buzz is ambitious and should still be evaluated as an evolving project rather than a finished replacement for established collaboration tools.

The repository lists the relay, channels, threads, DMs, canvases, media, search, audit log, desktop app, buzz-cli, YAML workflows, and Git events as working today. Mobile clients and some workflow approval features are still being wired up.

There are also practical questions around key management, backups, self-hosting, search consistency, object storage, multi-community isolation, and recovery after an identity is lost.

:::warning
The project’s vision and its production readiness are different questions. Before moving core team communication to a self-hosted relay, test backup, key recovery, access control, audit-log retention, and search behavior.
:::

## Is it a Slack replacement?

Not exactly. Buzz is trying to connect the parts that currently surround Slack: Git hosting, CI, agent execution, workflow automation, project memory, and audit history.

Its real competitor is therefore not one chat application, but the glue between chat, Git hosting, CI, automation, agent runtimes, internal search, and audit logs.

:::conclusion
Buzz is not merely “chat with AI.” It is an attempt to make the history of human–agent collaboration the project’s primary infrastructure. It is still early, but it asks a very relevant question about team software in an agent-heavy future.
:::

For now, I would start by running it locally and testing buzz-cli, agent integrations, Git events, and the operational experience of hosting a relay yourself.

- [Buzz on GitHub](https://github.com/block/buzz)
- [Buzz architecture](https://github.com/block/buzz/blob/main/ARCHITECTURE.md)
- [Buzz vision](https://github.com/block/buzz/blob/main/VISION.md)
