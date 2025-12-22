---
title: "GitHub Copilot CLI Error: 'Sorry, you have exceeded your copilot token usage' — Causes and Fixes"
date: 2025-12-18T09:00:00
categories: 
  - AI
  - Copilot &amp; VS Code Errors
tags: 
  - GitHub Copilot
  - CLI
  - VS Code
slug: github-copilot-cli-token-usage-en
---

When working with GitHub Copilot CLI or in VS Code, you might suddenly encounter the following error:

```
sorry, you have exceeded your copilot token usage.
please review our terms of service.
```

Seeing this message while on a paid plan can be confusing. "Wait, is my subscription invalid?" "Did I run out of credits?"

In this article, I will explain what this error really means and how to fix it practically.

## Conclusion: It's Not a Subscription Issue, It's a Temporary Rate Limit

First of all, **this message does not mean your account is suspended or your contract has expired.**

In most cases, **you have simply hit a temporary token usage (rate) limit on the GitHub Copilot side.**

In fact, there are numerous reports in GitHub Copilot CLI Issues and Community Discussions from paid users who suddenly see this while working normally.

## Why Do You Hit the Token Limit?

Copilot is often thought of as "unlimited use for a monthly fee," but in reality, the following limits exist:

### 1. Consuming a Large Amount of Tokens in a Short Time

In Copilot CLI or Agent mode, doing the following can consume a massive amount of tokens quickly:

*   Throwing entire large files at it
*   Sending long contexts repeatedly
*   Running continuous command completions/generations

If this "instantaneous usage" exceeds a certain threshold, you will be temporarily blocked.

### 2. There is a "Real-time Limit" Separate from Monthly Caps

Even if your usage bar in the UI shows you have quota left, internal backend counters, model-specific limits, or independent quotas for CLI/Agent can cause a discrepancy between what you see and the actual limits. This is why you might get blocked even when you think you still have capacity.

### 3. Copilot CLI is Particularly Prone to Limits

Compared to standard VS Code completions, Copilot CLI tends to hit limits more easily because:

*   The amount of information per request is larger
*   It often queries automatically and repeatedly

Similar reports are continuously made in GitHub Copilot CLI Issue #793.

```json
Model call failed: {"message":"Sorry, you have exceeded your Copilot token usage. Please review our Terms of Service.","code":"rate_limited"}
```

## Immediate Fixes

### 1. Wait a While (Most Important)

This limit is almost certainly temporary.

*   10 minutes to a few tens of minutes
*   At most a few hours

In the vast majority of cases, it will restore itself just by waiting. Take a break and don't panic.

### 2. Restart Your Editor or CLI

Resetting the internal session can sometimes make the token go through again or clear the error. "Restarting just in case" is surprisingly effective.

### 3. Reduce the Context You Send at Once

Being mindful of the following can help prevent recurrence:

*   Don't pass huge files as is
*   Paste only diffs or necessary parts
*   Don't over-rely on the Agent for everything

Especially when using the CLI, breaking down your prompts into smaller pieces is key.

### 4. Switch Modes

Sometimes switching the mode can help:

*   Agent → Ask
*   CLI → Editor Completion

## Summary

*   This error is not a contract error.
*   The cause is a temporary token/rate limit.
*   It is particularly common when using Copilot CLI / Agent.
*   Waiting, restarting, and lightening your prompts are effective solutions.

The more heavily you use Copilot, the more likely you are to encounter this error, so just think of it as a sign to "calm down and take a break."

:::note
This error might be more frequent with Gemini 3 Pro.
:::

---

:::note
**Japanese Version:**
[GitHub Copilot CLIで「sorry, you have exceeded your copilot token usage」と出たときの原因と対処法](https://www.zidooka.com/?p=2544)
:::
