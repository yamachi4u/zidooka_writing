---
title: "What Is the New AI Assistant Channel in GA4?"
status: publish
slug: ga4-ai-assistant-human-click
tags:
  - GA4
  - Google Analytics
  - AI
  - analytics
---

GA4 has recently started showing a channel called AI Assistant. Does this mean that a site has been specially integrated with ChatGPT or Gemini?

Not exactly. AI Assistant is a new classification in Google Analytics' default channel group. It is applied automatically when Google recognizes traffic coming from an AI service.

:::conclusion
The AI Assistant channel represents measurable visits referred by recognized AI services. It does not mean that the site has an API integration with those services, or that an AI crawler visited the site.
:::

## How GA4 identifies it

When someone opens a site through a link in an AI service, the browser may send a referrer indicating the previous page. GA4 can use that information to classify the visit as AI Assistant, with the medium ai-assistant and campaign (ai-assistant).

Google's current documentation gives ChatGPT, Gemini, DeepSeek, Copilot, and Grok as examples. The recognized sources and definitions may change over time.

## Did an AI visit the site?

There are two different things that are easy to confuse.

A normal AI crawler fetching HTML does not necessarily appear in GA4. GA4 records traffic when its measurement tag runs and sends events such as a page view. A crawler that only downloads HTML usually does not behave like a normal browser executing the site's analytics code.

By contrast, if someone clicks a link from an AI assistant and opens the site, that visit may be recorded as AI Assistant traffic. GA4 alone cannot tell whether the click was made by a human or by an automated AI agent operating a browser.

:::note
GA4 tells us that a measurable visit had a recognized AI service as its referrer. It does not directly prove that an AI crawled the site, learned from it, or that a human made the click.
:::

## How to see the source

In the Traffic acquisition report, filter the channel to AI Assistant and switch the dimension to Session source / medium. You may see chatgpt.com / ai-assistant, gemini.google.com / ai-assistant, or claude.ai / ai-assistant.

## Important limitations

- Visits from AI apps may lose the referrer and appear as Direct
- Copying and pasting a URL also removes the original referrer
- Google AI Overviews and AI Mode are classified as Organic Search, not AI Assistant
- Unrecognized AI services may remain under Referral or another channel
- The new classification may not be applied retroactively to historical data

:::warning
A small AI Assistant number does not prove that a site receives little AI traffic. GA4 only shows visits for which both the referrer and analytics measurement were available.
:::

## Summary

The new GA4 channel is Google's way of separating some AI-referred clicks from ordinary referrals.

The most precise interpretation is not AI visits, but measurable site visits referred by an AI service. To understand the full picture, compare AI Assistant with Referral, Direct, Organic Search, server logs, and, where relevant, Search Console.

References: [Google Analytics default channel group](https://support.google.com/analytics/answer/9756891) and [What's new in Google Analytics](https://support.google.com/analytics/answer/9164320).
