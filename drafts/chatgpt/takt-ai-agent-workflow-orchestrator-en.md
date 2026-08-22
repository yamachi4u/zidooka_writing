---
title: "What TAKT does: enforcing an AI coding workflow around Codex and Claude Code"
categories:
  - AI
  - アプリ開発
tags:
  - TAKT
  - AI agents
  - AI coding
  - multi-agent
  - Codex
  - Claude
  - CLI
  - Git
  - YAML
  - open source
  - 2026
status: publish
slug: takt-ai-agent-workflow-orchestrator-en
---

I came across [nrslib/takt](https://github.com/nrslib/takt) on GitHub. Its name does not immediately reveal what it does, so the simplest explanation is this: TAKT is not a replacement for Codex or Claude Code. It is an open-source CLI that uses those tools as workers while managing the sequence of planning, implementation, review, and repair around them.

:::conclusion
TAKT does not merely prompt an AI to “remember to review the code.” It lets you define a workflow in YAML in which the task cannot complete until it passes the required review path. The AI writes code; TAKT decides which step runs next.
:::

## An execution engine for AI development processes

A typical AI coding session asks one long-running agent to clarify requirements, implement a change, test it, review its own work, and fix any problems. As the session grows, early constraints can be forgotten, implementation and review responsibilities can blur, and a review step can disappear entirely.

TAKT moves that process outside the agent. A workflow can enforce a sequence such as:

1. A Planner clarifies requirements and proposes an approach.
2. A Coder edits the repository.
3. A Reviewer examines the change.
4. A failed review routes the task back to the Coder.
5. Approval completes the workflow.
6. Questions that require judgment return to a human.

Each step can have its own persona, instructions, knowledge, edit permissions, policy, and output contract. You can invoke one model in several distinct roles or assign different providers—such as Codex and Claude—to different steps.

## YAML decides what happens next

A minimal workflow looks conceptually like this:

```yaml
name: plan-implement-review
initial_step: plan
max_steps: 10

steps:
  - name: plan
    persona: planner
    edit: false
    rules:
      - condition: Planning complete
        next: implement

  - name: implement
    persona: coder
    edit: true
    rules:
      - condition: Implementation complete
        next: review

  - name: review
    persona: reviewer
    edit: false
    rules:
      - condition: Approved
        next: COMPLETE
      - condition: Needs fix
        next: implement
```

The important difference is that the agent is not free to declare the entire process finished. The workflow routes the result. A review finding sends the task back to implementation; approval leads to `COMPLETE`.

:::note
Files such as `CLAUDE.md`, `AGENTS.md`, and reusable skills tell an agent how it should behave. TAKT adds runtime control over which steps must run and under what conditions the process loops, stops, or asks a human.
:::

## How it is used

TAKT is a local CLI that runs in a Git repository, not a hosted coding service. The core flow is straightforward:

```bash
npm install -g takt

# Discuss and refine a task, then confirm it with /go
takt

# Run queued tasks
takt run

# Inspect diffs, add instructions, merge, retry, or clean up
takt list
```

Running `takt` starts an interactive conversation that helps turn a request into a task instruction. After `/go`, the task can be placed in the queue. `takt run` normally creates an isolated Git worktree for each queued task and executes the selected planning, implementation, review, and repair loop inside it.

After execution, `takt list` provides actions for viewing the diff, adding follow-up instructions, merging, retrying, requeuing, or deleting the task branch. With GitHub CLI available, `takt add #12` can turn an Issue and its comments into a queued task. The long-running `takt watch` command can automatically process pending tasks.

## Why this is useful

### 1. Reviews are harder to skip

A workflow can require a separate review role and route rejected work back to implementation. This reduces the need for a person to keep saying “now test it,” “review the diff,” and “fix that finding” from the sidelines.

### 2. Context can be separated by role

The Planner can receive requirements, the Coder can receive implementation context, and the Reviewer can receive the diff and evaluation criteria. That is easier to control than putting every instruction and artifact into one ever-growing conversation.

### 3. Queued tasks are isolated

TAKT uses Git worktrees for queued tasks by default, reducing the risk that several changes collide in the same working directory. This makes it a natural fit for processing a sequence of GitHub Issues.

### 4. The execution path is traceable

Step results, sessions, traces, and reports are stored as run artifacts under locations such as `.takt/runs/`. You can inspect which instruction produced a change, which review rejected it, and why it returned to a repair step.

:::example
Instead of “implement this Issue,” a reusable workflow can mean: clarify acceptance criteria, implement, run a security review, run a testing review, repair findings, and perform a final readiness check. The more often a repository repeats the same process, the more valuable this becomes.
:::

## Supported AI providers

As of August 22, 2026, the README lists support for Claude Code, Claude Agent SDK, OpenAI Codex SDK, OpenCode SDK, Pi SDK, DeepSeek Harness SDK, Cursor Agent, GitHub Copilot CLI, and Kiro CLI.

SDK-based providers such as `codex`, `claude-sdk`, `opencode`, and `pi` can run without a separate external CLI when the required credentials are available. CLI-based providers require their corresponding tools. TAKT can also route different steps to different providers or models.

:::warning
TAKT itself is MIT-licensed open-source software, but it does not make model usage free. A multi-step planning, implementation, review, and repair workflow may consume more tokens, time, and API budget than one direct agent run. Provider pricing and terms still apply.
:::

## Requirements

TAKT 0.60.0 declares Node.js 22.22.0 or later in its `package.json`. The documentation recommends starting in a Git repository with at least one commit.

A practical setup requires:

- Node.js 22.22.0 or later
- A Git repository with at least one commit
- Credentials or the CLI for the selected AI provider
- GitHub CLI for GitHub Issue integration
- GitLab CLI for GitLab Issue and merge request integration

## Where it fits—and where it does not

TAKT is a good fit when a repository needs to repeat the same multi-step process:

- continuously implementing Issues
- separating implementation and review roles
- making tests or reviews a completion gate
- isolating concurrent tasks in worktrees
- retaining execution and decision history
- assigning different AI providers to different roles

It is less attractive for a tiny edit, an exploratory conversation that changes direction constantly, or a task where minimizing model cost matters more than enforcing a process. The YAML workflow itself also becomes something the team must design, review, and maintain.

:::conclusion
TAKT is not “a smarter coding model.” It is an attempt to turn AI coding from an informal chat into a reusable development process. It looks particularly useful for queuing many Issues and insisting that each one passes a defined review loop. For a first trial, use a lightweight `simple` or `*-mini` workflow in one existing repository and compare the result with running Codex or Claude Code directly.
:::

As of August 22, 2026, the repository's `package.json` reports version 0.60.0, while the GitHub Releases page contains no formal release entries. The project is moving quickly, so check the current README and configuration guide before adopting it.

- [TAKT README](https://github.com/nrslib/takt/blob/main/docs/README.ja.md)
- [TAKT tutorial](https://github.com/nrslib/takt/blob/main/docs/tutorial.ja.md)
- [TAKT Workflow Guide](https://github.com/nrslib/takt/blob/main/docs/workflows.ja.md)
- [TAKT package.json](https://github.com/nrslib/takt/blob/main/package.json)
- [TAKT MIT license](https://github.com/nrslib/takt/blob/main/LICENSE)
