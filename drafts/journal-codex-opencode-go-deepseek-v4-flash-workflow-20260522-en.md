---
title: "My Favorite Workflow Now: Plan in Codex, Execute with DeepSeek V4 Flash on OpenCode Go"
slug: journal-codex-opencode-go-deepseek-v4-flash-workflow-20260522-en
categories:
  - journal
tags:
  - Codex
  - OpenCode
  - DeepSeek
  - AI
  - CLI
status: publish
featured_image: ../images/2026/05/opencode-go-contract.png
---

My AI coding workflow has started to settle into a pattern.

The short version: I let a GPT-5.5-class model in Codex do the planning, then call DeepSeek V4 Flash through OpenCode Go with an explicit model choice and let it move the work forward quickly.

That setup feels very good.

:::conclusion
Use the stronger model for planning and judgment. Use the fast, cheap model for execution. Calling DeepSeek V4 Flash on OpenCode Go from a Codex-led workflow has become one of the most practical setups for me.
:::

![Workflow diagram: Codex plans, OpenCode Go dispatches, and DeepSeek V4 Flash executes](../images/2026/05/codex-opencode-go-deepseek-flash-workflow.png)

## Why it works

I would not call DeepSeek V4 Flash literally unlimited.

But in my own usage, it feels close enough to unlimited that I stop worrying about each request. Small fixes, file checks, log reading, diff review, simple implementation tasks, draft cleanup. I can keep sending these tasks without much psychological cost.

At the same time, I do not think Flash is the strongest model for seeing the whole picture.

That is fine. Large design decisions, multi-file judgment, recovery strategy after a failed attempt, and task decomposition are not where I want to rely on it alone.

So I split the roles.

## Codex as the coordinator

My current workflow looks like this:

1. Let a GPT-5.5-class model in Codex read the situation
2. Ask it to decide the order of work
3. Break the work into smaller execution units
4. Call DeepSeek V4 Flash through OpenCode Go with a model selection
5. Review the result back in Codex and decide the next step

In other words, Codex is the coordinator, while OpenCode Go plus DeepSeek V4 Flash is the execution layer.

That combination works extremely well.

Heavy judgment stays with the stronger model. High-volume execution goes to Flash. The result is lighter than running the strongest model for everything, and more stable than asking the cheaper model to own the whole workflow.

## What it looks like in practice

This can sound abstract, so here is a more concrete example.

Suppose I want to fix a WordPress article publishing tool.

First, I ask Codex to understand the repository. It checks the entry point, the publishing logic, the image upload flow, and the parts that are risky to touch. At this stage, visibility matters more than speed.

Then Codex breaks the work into smaller tasks:

- Add Frontmatter validation before publishing
- Make image upload logs easier to read
- Investigate why an existing test is failing
- Update only the README instructions

Those smaller tasks are what I send to DeepSeek V4 Flash through OpenCode Go with an explicit model choice.

Flash moves quickly on the local task. It fixes one thing, reads one log, proposes one diff, or updates one file. Then I bring the result back to Codex and ask whether the overall change still makes sense.

That loop makes the workflow easier to picture: plan with Codex, execute with Flash, inspect with Codex, then repeat.

## Where Flash fits best

DeepSeek V4 Flash works best when the task needs throughput more than deep global judgment.

- Reading a specific part of an existing codebase
- Summarizing error logs
- Suggesting small fixes
- Producing files in a repeated format
- Listing likely causes of a failing test
- Cleaning up article or note drafts

For these tasks, speed matters.

The right expectation is not “one perfect answer.” It is “keep moving, then let the stronger model inspect the result.”

## Why I do not use Flash alone

This is not a “Flash handles everything” workflow.

My impression is that Flash is strong locally. It is good with the file in front of it, the log in front of it, the change in front of it. But when the work depends on the larger goal or consistency across several decisions, I still want a stronger model watching the overall direction.

For example, I prefer Codex to keep ownership of decisions like:

- How far the fix should go
- Which files should be left untouched
- How to fit the existing design
- What level of testing is enough
- Whether the final state is ready to publish or deliver

Those decisions need visibility more than speed.

## The real value

The best part of this setup is that I am no longer ranking models only by “smartness.”

I am assigning roles.

DeepSeek V4 Flash may not be the best commander. But it is fast, light, and easy to call many times. In real work, that matters a lot.

Codex plans. Flash executes. Codex reviews. Then the loop continues.

That loop makes AI coding feel much more practical.

## Bottom line

My current setup is:

- Planning and judgment: Codex / GPT-5.5-class model
- Execution and throughput: OpenCode Go / DeepSeek V4 Flash
- Final review: back in Codex

DeepSeek V4 Flash can lose to stronger models when the task requires a broad view. But its speed and availability make it extremely useful.

So I do not treat Flash as the model that should own everything. I treat it as a fast execution model under a stronger coordinator.

That is the workflow that feels best right now.
