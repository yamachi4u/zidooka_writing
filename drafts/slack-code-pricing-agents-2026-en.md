---
title: "Can You Use Slack Code on the Free Plan? Pricing, Agent Billing, and APIs Explained"
categories:
  - AI
tags:
  - Slack
  - Slack Code
  - AI Agent
  - AI Coding
  - Claude Code
  - Devin
status: publish
slug: slack-code-pricing-agents-api-2026-en
---

Slack introduced Slack Code on August 20, 2026: project-specific code channels where teams can bring in coding agents such as Claude Code or Devin and review conversations, diffs, previews, feedback, and approvals in one place.

The immediate questions are whether Slack Free is enough, who pays for the coding agent, and how API billing works.

:::conclusion
At launch, Slack Code is described as available on any Slack plan, including Free. That does not mean Slack pays for Claude Code, Devin, GitHub Copilot, Vercel Agent, or other external agent usage. The safest model is to treat Slack as the collaboration/orchestration layer and the connected agent as a separately billed service with its own subscription, quota, credits, or API charges.
:::

## What is Slack Code?

When a coding task starts, a coding agent can spin up a dedicated code channel. The team can follow the agent's work, inspect code diffs, see live HTML previews, give feedback, and approve work before it ships. The channels are designed to archive automatically after completion and retain an audit trail.

Launch partners include Claude Code, Devin, Vercel Agent, and GitHub Copilot.

## Does it work on Slack Free?

Surprisingly, yes at the Slack Code feature level. Launch coverage states that Slack Code is available on “any Slack plan.”

Normal Free-plan limits still apply. Slack's current pricing page lists 90 days of message history and up to 10 apps for Free workspaces. Those limits may matter if code channels become part of your long-term engineering record.

There is also an important distinction: Slack Code availability is not the same as building your own native Slack AI agent. Slack's developer documentation says developing and using some Agents & AI Apps capabilities requires a paid plan. Developers without one can use a fully featured Developer Program sandbox for free.

## Who pays for Claude Code or Devin?

Slack Code looks primarily like a common collaboration surface for third-party coding agents. At launch, public information does not describe a single Slack-managed token pool that pays for all partner-agent compute.

In practice, the connected agent's own commercial terms matter. Devin, for example, has limited Free usage plus paid tiers and usage quotas/credits. Claude Code usage is likewise tied to Anthropic's subscription or organizational billing arrangements.

:::warning
“Slack Free + Slack Code” does not mean “Claude Code or Devin for free.” Keep Slack workspace pricing separate from coding-agent pricing.
:::

## What about APIs?

It helps to separate two layers.

### Slack APIs

Conventional Slack apps can use HTTP APIs, webhooks, and third-party integrations on Free within Slack's applicable platform limits. Slack's platform overview lists non-workflow apps and third-party API/webhook integrations with a minimum plan of Free.

Native Agents & AI Apps capabilities are different: Slack's developer docs note that some AI-agent features require a paid plan, while the Developer Program offers a free sandbox for development.

### Agent and LLM usage

The compute used by Claude Code, Devin, or another coding agent follows that provider's own billing model. Depending on the product, that may mean a subscription quota, usage credits, or API charges.

Slack Code does not by itself make that compute free.

## Why this could be useful for solo development

A potentially attractive setup is: Slack Free for the workspace, Slack Code as the coding interface, and an existing paid coding-agent account for execution.

That could make a phone-first workflow practical: send a task in Slack, let the agent work on the repository, inspect diffs or previews, then approve the result without living in a desktop IDE.

Because Slack Code has just launched, details such as partner authentication, whether every existing individual subscription can be reused directly, whether an API key is required, and whether any partner adds Slack-specific charges still need to be checked per integration.

## Bottom line

- Slack Code itself is announced for all Slack plans, including Free.
- Normal Slack Free limits still apply.
- Third-party coding-agent usage is a separate cost layer.
- Conventional Slack API integrations can work on Free.
- Building a native Slack Agents & AI Apps experience may require a paid plan.
- Partner-specific billing details should be checked before connecting an agent.

The useful mental model is that Slack Code is not primarily a new AI model subscription. It is a collaborative UI and orchestration layer for coding agents.

## Sources

- Slack Pricing Plans: https://slack.com/pricing
- Slack Developer Docs, Developing an agent: https://docs.slack.dev/ai/developing-agents/
- Slack Platform overview: https://api.slack.com/automation/
- The Verge, “Slack is launching collaborative vibe-coding channels” (2026-08-20)
