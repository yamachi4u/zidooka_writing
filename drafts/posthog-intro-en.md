---
title: "PostHog: 1M Free Events, A/B Testing, Session Replay, and Self-Hosting — The Ultimate Product Analytics Stack"
categories:
  - 便利ツール
tags:
  - PostHog
  - Product Analytics
  - A/B Testing
  - Self-Hosting
featured_image: ../images-agent-browser/posthog-top.png
status: publish
slug: posthog-intro-en
---

## What is PostHog?

PostHog is an open-source product analytics platform that functions as a complete "Product OS." It bundles multiple capabilities into a single tool:

- Pageview and event analytics
- A/B testing (integrated with feature flags)
- Session replay
- Funnel and trend analysis
- Feature flags

It also supports **self-hosting**, giving you full control over your data — a major advantage for privacy-conscious teams or those needing custom deployment.

:::note
PostHog is an open-source project available on GitHub. Self-hosting removes event limits entirely and keeps all data on your own infrastructure.
:::

![PostHog official site](../images-agent-browser/posthog-top.png)

## The Free Tier is Incredible

PostHog's cloud offering is **free for up to 1 million events per month**.

For small to medium sites, that's more than enough. Most indie devs and early-stage startups won't ever need to pay.

### Pay-as-you-go Unlocks 6 Projects

Upgrading to **Pay-as-you-go** pricing unlocks **6 projects** under a single account.

The 1M free event pool is shared across all projects. If you run 2-3 sites and want to run A/B tests on each, this setup is incredibly cost-effective.

:::conclusion
For multi-site operators, the Pay-as-you-go plan with 6 projects sharing 1M free events offers unbeatable value.
:::

## A/B Testing Made Simple

PostHog's A/B testing works through feature flags. Call `posthog.getFeatureFlag()` in your code, and the platform handles the experiment group assignment automatically. Results are visualized in real-time on the dashboard.

No need for a separate A/B testing tool like Google Optimize — analytics and experimentation live in the same place.

## Experience with benri-tools

Here's what it felt like integrating PostHog into benri-tools (a collection of utility tools):

- **Easy setup**: Just install the npm package and add the snippet
- **Quick A/B testing**: Feature flag integration is intuitive
- **Clean dashboard**: Events and session flows are easy to read
- **Generous free tier**: Virtually no worry about hitting the 1M limit

:::warning
Self-hosting comes with server operational costs. For small sites, the SaaS free tier is more than adequate — start there.
:::

Related:
- [Let AI Agents Handle A/B Test Implementation and Analysis](https://www.zidooka.com/archives/4463)

## Summary

PostHog is a unified platform for product analytics, A/B testing, session replay, and feature flags — a true Product OS.

- Free up to 1M events/month
- 6 projects on Pay-as-you-go
- Self-hostable
- Easy A/B testing

Give it a try on the free cloud tier if you're curious.
