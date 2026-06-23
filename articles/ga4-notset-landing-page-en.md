---
title: "Why GA4 Shows '(not set)' as a Landing Page — 6 Causes and Fixes"
slug: ga4-notset-landing-page-en
status: publish
categories:
  - Google / GA4 / Apps Script Errors
  - SEO
tags:
  - GA4
  - Google Analytics
  - landing page
  - not set
date: 2026-06-08
---

In Google Analytics 4 reports, landing pages sometimes display as `(not set)`. Sessions are counted, but the entry page is unknown.

This article categorizes the causes into six patterns with diagnosis steps and solutions.

## Cause 1: Bot / Crawler Traffic

GA4 cannot fully exclude bot traffic. If you see sessions with URL parameters like `xbridge3=true`, `loader_name=forest`, `need_sec_link=1`, bots are likely the cause:

```
/simple-metronome?use_xbridge3=true&loader_name=forest&need_sec_link=1&sec_link_scene=im&theme=light
```

These URLs are characteristic of SEO crawlers, proxy services, and automated testing tools. They partially execute JavaScript, firing `session_start` but not completing `page_view`.

**Fix:**
- Enable bot filtering in GA4 admin: Data Streams → Google Tag → toggle "Enable bot filtering"
- Block known crawler user agents server-side
- Ensure `gtag('config', 'G-MEASUREMENT_ID', { send_page_view: true })` is properly set

## Cause 2: Missing `page_location` on Custom Events

GA4 determines landing pages from the `page_location` parameter of the `page_view` event. If custom events fire without `page_location`, and `page_view` doesn't fire in that session, the landing page becomes `(not set)`.

**Fix:**
- Always include `page_location` when sending custom events:

```js
gtag('event', 'custom_event', {
  page_location: window.location.href,
});
```

- Pay special attention to click-based events that could fire before `page_view` completes.

## Cause 3: Service Worker Interference

When a Service Worker serves pages from cache, external scripts like gtag.js may load late or fail. This creates sessions where `session_start` fires but `page_view` does not.

**Fix:**
- Confirm that `IGNORE_ORIGINS` includes `www.googletagmanager.com` and `www.google-analytics.com`
- Consider checking `navigator.onLine` before loading gtag.js in offline-first strategies

## Cause 4: Social Crawlers / Scrapers With Malformed URLs

Some sessions show landing pages with Japanese text concatenated to URLs:

```
/jp/calendar/taian）also refer to
```

These come from SNS crawlers (LINE, X/Twitter) or scrapers that fail to extract URLs correctly from text. These sessions almost always have zero page views.

**Fix:**
- Apply a GA4 filter to exclude sessions with `page_view = 0`
- Add a regex filter to exclude landing pages containing non-URL characters

## Cause 5: Invalid gtag Config Parameters

Passing unsupported parameters to `gtag('config', ...)` does not cause errors but is silently ignored:

```js
gtag('config', 'G-MEASUREMENT_ID', {
  enhanced_measurement: true,
});
```

`enhanced_measurement` is a property-level setting configured in the GA4 admin, not a gtag parameter.

**Fix:**
- Configure Enhanced Measurement in GA4 admin: Data Streams → your web stream → Enhanced Measurement toggle
- Only pass valid parameters to `gtag('config', ...)`

## Cause 6: Measurement Protocol Misconfiguration

When sending events server-side via Measurement Protocol without `page_location` or `page_title`, landing pages appear as `(not set)`.

**Fix:**
- Always include `page_location` in Measurement Protocol payloads
- Test with `debug_mode: true` and verify in GA4 DebugView

## Diagnosis Steps

1. **Identify affected sessions in GA4**: Go to Acquisition → Landing Page, click the `(not set)` row. Add "Page Title" as a secondary dimension.
2. **Analyze URL parameter patterns**: Look for common parameters among `(not set)` sessions. Parameters like `xbridge3`, `loader_name`, `need_sec_link` indicate bot traffic.
3. **Inspect Service Worker behavior**: Chrome DevTools → Application → Service Workers. Verify caching strategy does not block gtag.js.
4. **Audit custom event implementations**: Find all `gtag('event', ...)` calls in your codebase. Check each for the `page_location` parameter.

:::conclusion
The `(not set)` landing page issue rarely has a single cause. Excluding bot traffic and consistently including `page_location` are the most effective measures. While eliminating it completely is difficult, identifying and addressing each cause will significantly improve report quality.
:::
