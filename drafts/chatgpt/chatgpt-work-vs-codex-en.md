---
title: "ChatGPT Work vs. Codex: Is Work Just Codex with a Reset Button?"
slug: chatgpt-work-vs-codex-en
status: publish
categories:
  - ChatGPT
tags:
  - ChatGPT Work
  - Codex
  - OpenAI
  - AI agents
  - cloud environments
  - mobile workflows
---

ChatGPT Work feels remarkably similar to Codex. It can read files, research the web, run programs, create documents and spreadsheets, and connect to GitHub or other services. A task can continue in the cloud even when the user's own PC is off.

That makes a tempting description possible: Work is Codex running in an OpenAI-managed computer that eventually gets reset.

The experience is close, but the product distinction is different.

:::conclusion
The main difference between Work and Codex is not whether the environment is temporary. Work is oriented toward research, analysis, documents, spreadsheets, presentations, and everyday workflows. Codex is oriented toward repositories, code, tests, and reproducible software work.
:::

## How OpenAI defines them

OpenAI's official glossary defines ChatGPT Work as the agent in ChatGPT for research, analysis, and finished artifacts such as documents, presentations, and spreadsheets. It defines Codex as OpenAI's coding agent for software development.

The practical distinction looks like this:

| Perspective | ChatGPT Work | Codex |
| --- | --- | --- |
| Primary goal | Turn information and tasks into finished work | Build or change software and reproducible processes |
| Typical inputs | PDFs, documents, spreadsheets, email, the web, connected services | Git repositories, code, configuration, tests, and logs |
| Typical outputs | Reports, Word files, PDFs, decks, spreadsheets, completed workflows | Code changes, scripts, test results, diffs, and pull requests |
| Natural integrations | Google Drive, Slack, Gmail, and other plugins | GitHub, local folders, the CLI, IDEs, and cloud environments |
| Typical prompt | “Create a report from these materials” | “Fix this repository and run the tests” |

This is an orientation rather than a hard boundary. Work can run code, and Codex can create articles, research reports, CSV files, and PDFs.

OpenAI explicitly says that people who already use Codex for non-coding work can remain in Codex or move to Work. Work provides the same core capabilities through an experience designed for everyday work.

## “It resets” is not the Work-versus-Codex distinction

This is the most important correction.

Work can run locally or in the cloud. In the desktop app, `Work locally` uses files and apps on the user's computer. Cloud work can keep running after the desktop app closes or the computer turns off, and the same chat can continue from the web or a phone.

Codex also has local, worktree, and cloud modes. Codex cloud checks out a GitHub repository in an OpenAI-managed environment, runs its setup, and performs the task remotely.

There are therefore two separate questions:

1. **What kind of agent should organize the task?** Work for general finished work, or Codex for repository-centered development.
2. **Where should it run?** On the user's computer, or in an OpenAI-managed cloud environment.

:::note
“A disposable computer that appears whenever I need Codex” is a useful description of the cloud Work experience. It describes the execution mode, however, not the definition of Work itself.
:::

## Why Work still feels like Codex

They share much of the same core capability.

Work can use approved files, plugins, and tools to retrieve information, create finished files, and run workflows. Codex can also read and write files, execute terminal commands, and use browsers or plugins.

They also share usage. As of August 2026, OpenAI's pricing page says that ChatGPT Work and Codex use the same pricing, credits, and usage limits inside ChatGPT.

Because the underlying tools overlap, many tasks can be given to either product. The difference is what kind of work each experience is designed to organize.

## A practical rule for choosing

### Choose Work when the job is mainly about:

- Reading several PDFs and producing a comparison
- Turning research into a Word file or PDF
- Cleaning a spreadsheet and adding charts or summaries
- Creating a slide deck
- Coordinating across Gmail, Slack, or Google Drive
- Monitoring or refreshing information over time
- Producing something primarily meant for a person to read or use

### Choose Codex when the job is mainly about:

- Understanding and modifying an existing repository
- Writing code and running tests
- Managing Git diffs, branches, and pull requests
- Following repository instructions such as `AGENTS.md`
- Making an analysis rerunnable as a script
- Reproducing dependencies and environments
- Producing working code or a traceable repository change

:::example
“Analyze this survey spreadsheet and create a report with charts” naturally fits Work. “Store the analysis in a repository so the report can be regenerated from the same inputs” naturally fits Codex.
:::

## Why the phone experience matters

The striking part of Work is that Codex-style delegation now applies to general knowledge work.

Traditional work from a phone often means remotely controlling a desktop interface and clicking tiny buttons. Work lets the user describe the outcome instead of reproducing every screen operation.

For example:

> Turn this conversation into Japanese and English articles. Verify the claims with official sources, follow the existing ZIDOOKA rules, add the files to GitHub, and publish them after merge.

Work can connect research, drafting, file creation, and GitHub actions. GitHub Actions can then execute the finalized publishing procedure. The phone becomes a device for delegation, approval, and review rather than a miniature desktop.

Anything needed again should still be stored somewhere durable. Articles, code, settings, and analysis data should go to GitHub, ChatGPT Library, Google Drive, WordPress, or another persistent destination instead of remaining only in a temporary cloud workspace.

## Why the same ChatGPT can appear to have different capabilities

In practice, users may see one session update a site successfully while another session cannot. This usually does not mean that the model itself has suddenly become less capable. The available execution environment and management tools may simply be different.

A regular Chat interface is centered on conversation and connected connectors. It may be able to read or create individual GitHub files, but it does not necessarily provide a checked-out repository, a command-line environment, site-management tools, or an end-to-end publishing pipeline.

Work can, when the relevant tools are enabled, provide a temporary working environment in which the agent can check out a repository, run commands, inspect generated files, and connect the result to a site deployment workflow. That is why the same account and similar prompt can produce very different results.

| Aspect | Chat | Work |
| --- | --- | --- |
| Center | Conversation and connectors | Conversation plus an execution environment |
| GitHub | File-level operations | Repository-based work is easier |
| Commands | Not always available | May run inside the working environment |
| Site updates | Requires the relevant management tools | Can connect to site management and publishing |
| Best use | Questions, discussion, limited file operations | Delegating research, implementation, and publication |

The practical lesson is simple: check which mode is active, which GitHub and site tools are enabled, and whether a command-capable working environment is available. The interface is only the entry point; the actual capability depends on the tools and environment connected to it.

## Summary

Work is neither a reduced version of Codex nor simply Codex with a credit system. It applies overlapping execution capabilities to research, documents, spreadsheets, external services, and everyday workflows.

Codex remains the more natural choice when the work is structured around a repository, tests, diffs, and reproducibility.

:::conclusion
In one sentence: Work is a general-purpose agent for producing finished work, while Codex is a development agent organized around repositories. Local versus cloud execution is a separate decision.
:::

## References

- [Get started with ChatGPT Work](https://learn.chatgpt.com/docs/get-started-with-work)
- [OpenAI glossary](https://learn.chatgpt.com/docs/glossary)
- [Codex cloud](https://learn.chatgpt.com/docs/cloud)
- [ChatGPT Work and Codex pricing and usage](https://learn.chatgpt.com/docs/pricing)
- [Choosing Work or Codex for productivity tasks](https://learn.chatgpt.com/use-cases/collections/productivity-and-collaboration)

<!-- automated-publish-trigger -->
