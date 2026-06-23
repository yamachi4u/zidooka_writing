---
title: "PostHog Error Tracking and Surveys: Free Tools You're Not Using Yet"
categories:
  - 便利ツール
tags:
  - PostHog
  - Error Tracking
  - Surveys
  - Product Analytics
status: publish
slug: posthog-error-tracking-surveys-en
---

## PostHog Has More to Offer

We've covered PostHog's product analytics, A/B testing, and session replay. But two more features are easy to miss:

**Error Tracking** and **Surveys**.

## Error Tracking — Connect Errors to Sessions

PostHog's Error Tracking automatically collects JavaScript errors from your frontend.

Setup is a one-line addition to your `posthog-js` init:

```js
posthog.init('YOUR_KEY', {
  ...
  capture_exceptions: true,
});
```

Unhandled JS errors will now flow into PostHog automatically. Add a React Error Boundary to capture caught exceptions as `$exception` events.

:::note
Error Tracking includes 100K exceptions per month for free. For indie devs and small sites, that's more than enough.
:::

![PostHog Error Tracking dashboard](../images-agent-browser/posthog-error-tracking.png)

### Why It Matters

Sentry is the go-to for error monitoring — but if you're already using PostHog, you don't need it. **Everything lives in one dashboard:** error lists, aggregations, and session replay integration.

You can ask:
- "What was the user doing right before this error?"
- "How many times is this error happening this month?"
- "Which browser/OS is most affected?"

All from PostHog.

## Surveys — Ask Your Users Directly

PostHog Surveys lets you show on-site feedback forms without writing code. Just create a survey in the PostHog dashboard, and `posthog-js` handles the display.

![PostHog Surveys dashboard](../images-agent-browser/posthog-surveys.png)

### How to Set It Up

1. PostHog dashboard → Surveys → New Survey
2. Choose question type (rating, multiple choice, open text)
3. Set targeting conditions (URL, user properties, rollout %)
4. Publish

That's it. The survey appears on your specified pages automatically.

:::note
The free tier includes 1,500 responses per month. More than enough for most small to medium sites.
:::

## Summary

PostHog's Error Tracking and Surveys let you skip additional tools and keep everything in one place.

- **Error Tracking**: One line of config, no Sentry needed.
- **Surveys**: No-code feedback forms for direct user insights.

:::conclusion
Product analytics, A/B testing, session replay, error tracking, and surveys — all free up to 1M events/month. PostHog is the strongest all-in-one stack for indie developers right now.
:::

Related:
- [PostHog: 1M Free Events, A/B Testing, Session Replay, and Self-Hosting](https://www.zidooka.com/archives/4447)
- [PostHog vs Competitors: A/B Testing, Pricing, and Features Compared](https://www.zidooka.com/archives/4489)
