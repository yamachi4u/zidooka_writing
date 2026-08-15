---
title: "What Is Codex Computer History? Features, Setup, and Privacy"
categories:
  - AI
tags:
  - OpenAI
  - Codex
  - ChatGPT
  - Computer History
  - macOS
  - AI agents
status: publish
slug: codex-computer-history-guide-en
---

On August 13, 2026, OpenAI introduced Computer History, a new feature in the ChatGPT desktop app. It turns recent work on your Mac into a timeline and local memories that ChatGPT and Codex can reference.

:::conclusion
Computer History summarizes activity from approved apps and websites into a searchable work history for ChatGPT and Codex. As of August 15, 2026, it is available in the macOS ChatGPT desktop app for ChatGPT Pro, Business, and Enterprise users.
:::

## What Computer History can do

Once enabled, Computer History organizes recent activity from approved apps and websites into a timeline. You can ask questions such as:

- “What was I working on before my last break?”
- “Where is the proposal document I looked at earlier?”
- “List the tasks I worked on today and their status.”
- “Summarize yesterday's work for standup.”

The history can also help identify the original source, such as a file, Slack conversation, or Google Doc. Computer History acts as a map of your recent work; ChatGPT or Codex can then read the relevant source directly when access is available.

When it recognizes a repeated workflow, a timeline item may suggest turning it into a reusable Skill or Automation.

## Computer History vs. Computer Use and Memories

Computer History and Computer Use are separate features.

| Feature | Purpose |
| --- | --- |
| Computer History | Summarizes past activity into a timeline and memories |
| Computer Use | Lets ChatGPT or Codex see, click, and type in applications |
| Memories | Reuses preferences, workflows, tech stacks, and other context across chats |

Computer History requires Memories. However, it does not operate applications by itself in the way Computer Use does.

## What if you only use Codex CLI in the terminal?

Codex CLI alone cannot turn on Computer History or collect activity from other apps and websites. Starting collection, choosing source apps, and viewing the timeline are features of the ChatGPT desktop app on macOS.

Codex CLI does have a separate local Memories feature. It can turn useful context from previous CLI sessions—such as a tech stack, workflow, or repository convention—into local memories and reuse them in future sessions. In an interactive CLI session, use `/memories` to control whether the current chat may use existing memories or contribute to future ones.

If the feature is not enabled, add the following to `~/.codex/config.toml`:

```toml
[features]
memories = true
```

| Setup | Computer History | Local Codex Memories |
| --- | --- | --- |
| Codex CLI only | Not available | Available |
| macOS desktop app only | Available on an eligible plan | Available |
| Desktop app and CLI on the same Mac | Desktop app collects activity | CLI can use the same local memory system |
| Codex CLI on Windows or Linux | Not currently available | Available |

When the desktop app and Codex CLI run on the same Mac, use the same `CODEX_HOME`—normally `~/.codex`—and have Memories enabled, the official documentation taken together indicates that the CLI can use local memories generated from Computer History as context. The desktop app is still required to start collection and manage the Computer History timeline.

:::note
For a terminal-only workflow, the available feature is local Memories generated from Codex CLI's own chats. It does not collect cross-application activity like Computer History.
:::

### The desktop app effectively needs to keep running in the background

Turning Computer History on once does not mean you can quit the desktop app permanently and let Codex CLI continue collecting activity. The CLI can use local memories that have already been generated, but the desktop app is responsible for collecting new interaction events.

OpenAI's documentation does not explicitly describe behavior after the app is fully quit. It does, however, place collection status, Pause, and Resume controls in the ChatGPT menu-bar process on macOS. In practice, this means the ChatGPT desktop app process needs to remain active in the background for ongoing collection.

On macOS, closing a window is different from quitting an application. If the ChatGPT icon remains in the menu bar, the background process is still running. After a full `Quit`, Codex CLI does not become the collector for other applications. Existing memory files remain available, but it is safest to expect no new Computer History entries until the desktop app runs again.

:::warning
The effective background-process requirement is a drawback for users concerned about CPU, memory, or battery usage. A CLI-only setup avoids that resident desktop-app cost, but it is limited to local Memories derived from Codex CLI's own work rather than computer-wide activity history.
:::

## Availability and requirements

According to OpenAI's documentation as of August 15, 2026, Computer History requires:

- The ChatGPT desktop app on macOS
- ChatGPT Pro, Business, or Enterprise
- An individual opt-in by the user
- Memories to be enabled
- Administrator access approval before individual opt-in in Business and Enterprise workspaces

It is not available with an API key or Amazon Bedrock. Initial availability also excludes the European Economic Area, Switzerland, and the United Kingdom.

:::warning
The official availability list currently does not include Windows, Linux, or ChatGPT Plus. If the setting is missing, check the operating system, plan, and workspace policy first.
:::

## How to turn on Computer History

:::step
1. Open the ChatGPT desktop app on macOS.
2. Open `Settings`, then select `Computer history` under `Integrations`.
3. Select `Turn on` and review the privacy, permissions, and local-storage information.
4. Enable Memories if prompted.
5. Choose which apps and websites may contribute, then complete any macOS permission prompts.
:::

Business and Enterprise members need workspace access from an administrator first. Administrator approval only makes the option available; it does not turn collection on for members.

Computer History does not require Screen Recording permission.

## What gets recorded

Computer History creates an interaction-event stream from approved apps and websites. Events may include clicks, typing, keyboard shortcuts, app switches, and context exposed through the macOS accessibility system.

It does not capture:

- Screenshots
- Screen recordings
- Microphone input
- System audio
- Private-browsing activity

Computer History replaces the earlier Chronicle research preview, but it is a rebuilt system rather than a rename. Chronicle used screenshots; Computer History uses interaction events.

## Control which apps and websites are included

Under `Settings > Computer history > Permissions`, you can use either approach:

- `Exclude these apps / websites` blocks selected sources while allowing other supported sources.
- `Include only these apps / websites` allows only the sources you explicitly select.

You can also exclude an app from future history by selecting its icon in a timeline item. Permission changes affect future history only, so existing items must be deleted separately.

The ChatGPT icon in the macOS menu bar shows what Computer History is collecting and provides `Pause` and `Resume` controls. Turning Computer History off stops future collection.

:::warning
OpenAI instructs users to obtain prior express consent before including communications with other people. Consider excluding apps that contain sensitive health, financial, authentication, or personal information.
:::

## Where the data is stored

Interaction events are temporarily stored on the Mac for up to 48 hours. ChatGPT and Codex periodically summarize them into local Markdown memory files. The typical location is:

```text
~/.codex/memories/extensions/skysight/
```

You can read and edit these files. They are not encrypted by Computer History, and other software running as the same macOS user may be able to access them.

OpenAI processes temporary event files on its servers to create the memories. Its documentation says these files are not retained after processing, unless legally required, and are not used for training. When a memory is later used in a chat, however, relevant content may become part of that chat and may be used to improve models if the user's ChatGPT data controls allow it.

## Review or delete history

Open `Settings > Computer history > History` to review summaries grouped by date and time. An item can show its summary, contributing apps, and a suggested Skill or Automation. You can reveal the corresponding local memory file in Finder or delete the item.

You can also clear the last 10 minutes, hour, day, or all history. Clearing history deletes the relevant events and memories and cannot be undone.

## Risks and practical precautions

Because Computer History can ingest context from apps and websites, it increases prompt-injection risk. A malicious webpage could contain instructions that ChatGPT or Codex mistakes for legitimate guidance.

The feature also uses tokens when it summarizes activity and generates memories. The documentation does not specify a separate quota for this usage.

:::note
A cautious starting point is to use `Include only` and approve just the apps needed for research or development. Exclude communications, health, finance, password-management, and other sensitive apps, and pause collection when it is not useful.
:::

## Troubleshooting

If the option exists but Computer History does not start:

1. Confirm that Memories is enabled.
2. Open `Settings > Computer history` and select `Finish setup`, `Resume`, or `Try again`, depending on the displayed state.
3. Quit and reopen the ChatGPT desktop app if the issue remains.

If the setting itself is missing, verify macOS, plan eligibility, and—on Business or Enterprise—the administrator's workspace setting.

## Summary

Computer History is more than a browser history. It turns activity across approved apps into a work record that can be searched in natural language. It could be particularly useful for resuming interrupted tasks, preparing work summaries, and converting repeated routines into Skills.

That convenience comes with sensitive context and prompt-injection risks. Limiting sources, pausing collection, and regularly reviewing the timeline should be part of the normal setup.

## Official references

- [Computer History documentation](https://learn.chatgpt.com/docs/customization/computer-history)
- [Memories documentation](https://learn.chatgpt.com/docs/customization/memories)
- [ChatGPT & Codex changelog](https://learn.chatgpt.com/docs/changelog)
