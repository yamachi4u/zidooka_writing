---
title: "[Breaking] X API Effectively Eliminates Free Tier - Shifts to Pay-Per-Use Model (February 2026)"
date: 2026-02-10T10:00:00Z
categories:
  - SNS
  - general
tags:
  - X API
  - Twitter API
  - Pay-Per-Use
  - Developer
  - Monetization
status: publish
slug: x-api-free-tier-eliminated-20260210-en
description: "X (formerly Twitter) officially announced a new pay-per-use pricing model for developer APIs, moving away from fixed monthly fees. The free tier has been effectively eliminated."
---

# X API Effectively Eliminates Free Tier - Shifts to Pay-Per-Use Model

On February 6, 2026, X (formerly Twitter) officially announced a new pay-per-use model (Pay-Per-Use) for its developer API. This marks a departure from the previous fixed monthly fee structure ($200 or $5,000 per month) to a system where pre-purchased credits are consumed based on API requests.

:::conclusion
**The X API free tier has been effectively eliminated.** Following the termination of free access in February 2023, the pay-per-use model was officially introduced in February 2026. The barrier to API usage has significantly increased for individual developers and small-scale projects.
:::

## Key Changes in the Pay-Per-Use Model

### Pricing Structure Changes

Instead of the previous monthly fixed plans (Basic: $200/month, Pro: $5,000/month), a **pay-per-use system with pre-purchased credits** has been introduced. The unit price varies depending on the API endpoint:

| Operation | Price |
|-----------|-------|
| Reading posts | $0.005 per post |
| Accessing user data | $0.010 per user |
| Creating posts | $0.010 per request |

According to X's estimates, moderate monthly usage could cost around $215 (approximately $215 USD) per month.

:::warning
While X states that duplicate charges are generally avoided for fetching the same posts or users within the same day, they **do not guarantee complete protection** against such charges.
:::

### Exceptions for Free Access

:::note
Free scaled access is only available in the following cases:

- **"Public Utility apps" with high public benefit**
- Developers who previously used the free tier receive a **one-time $10 voucher**
:::

## Background and Timeline

### February 2023: End of Free Access

In February 2023, during the Twitter era, X announced the termination of free access to "Twitter API v2" and "Twitter API v1.1," consolidating them into paid plans.

### October 2025: Beta of Pay-Per-Use Model

Around October 2025, X began offering a closed beta of the Pay-Per-Use model, which was officially released in February 2026.

### Continuation of Fixed-Fee Plans

Existing Basic ($200/month) and Pro ($5,000/month) plans remain available for developers already subscribed. However, new developers will default to the pay-per-use model.

## Impact on Developers

:::warning
For individual developers, students, and small startups, API usage has become practically difficult. The development and operation of bots, research tools, and third-party apps have been significantly restricted.
:::

### Usage Management Features

To prevent overuse, the following settings are available:

- **Automatic credit top-up**: Automatically purchase credits when balance falls below a certain threshold
- **Spending limit**: Set a cap per billing cycle to stop requests when the limit is reached

## Related Links

- <https://developer.x.com/#pricing> - X Developer Platform
- <https://docs.x.com/x-api/getting-started/pricing> - X API Pricing
- <https://docs.x.com/x-api/fundamentals/post-cap> - Usage and Billing

## Summary

Following the termination of free access in 2023, X API transitioned to a pay-per-use model in February 2026. This has significantly raised the barrier to API usage for individual developers and small-scale projects.

:::conclusion
Except for high-public-benefit apps and former free-tier users, **new developers can no longer use the X API for free**. The pay-per-use model, charging from $0.005 per post read, is having a significant impact on the individual developer ecosystem.
:::
