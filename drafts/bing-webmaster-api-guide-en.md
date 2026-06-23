---
title: "Bing Webmaster Tools API — How to Access Search Performance Data Programmatically"
slug: bing-webmaster-api-guide-en
categories:
  - SEO
tags:
  - Bing
  - Webmaster Tools
  - API
  - SEO
  - Search Engine
status: publish
---

Bing Webmaster Tools provides a full-featured API that lets you access search performance data (impressions, clicks, rankings, and more) programmatically. This article covers the API basics and how to get started.

:::note
As of June 15, 2020, use of the Bing Webmaster API is governed by the Microsoft Services Agreement.
:::

## What You Can Do with the API

The Bing Webmaster Tools API gives programmatic access to the following data:

- **Rank & Traffic Stats** — Daily impressions and clicks
- **Link Details** — Inbound link information
- **Keyword Details** — Keyword-level rankings and traffic
- **Crawl Stats** — Crawl volume and errors
- **URL & Sitemap Submission** — Submit URLs and sitemaps to Bing

## Authentication

Two authentication methods are available:

### 1. API Key (Quick Start)

Generate an API key from your Bing Webmaster Tools settings:

1. Sign in to [Bing Webmaster Tools](https://www.bing.com/webmasters)
2. Click **Settings** (top right) → **API Access**
3. Accept the terms and click **Generate API Key**

Each API key is user-scoped — one key works across all sites owned by the same user.

:::warning
Never share your API key. If compromised, delete and regenerate it from the settings page.
:::

### 2. OAuth 2.0 (Recommended)

1. Register an OAuth client under Settings → API Access
2. Obtain a Client ID and Client Secret
3. Use the Authorization Code Flow to get an access token
4. Refresh tokens enable automatic renewal

Available scopes:
- `webmaster.read` — Read-only access
- `webmaster.manage` — Read and write access

## Endpoints and Protocols

The API supports three protocols:

| Protocol | Base URL |
|----------|----------|
| SOAP | `https://ssl.bing.com/webmaster/api.svc/soap?apikey=KEY` |
| POX (XML) | `https://ssl.bing.com/webmaster/api.svc/pox/METHOD?apikey=KEY` |
| JSON | `https://ssl.bing.com/webmaster/api.svc/json/METHOD?apikey=KEY` |

The JSON endpoint is the easiest to work with from modern applications.

## Key API Methods

| Method | Description |
|--------|-------------|
| `GetRankAndTrafficStats` | Site-wide traffic statistics |
| `GetQueryStats` | Top queries with traffic data |
| `GetPageStats` | Top pages with traffic data |
| `GetKeywordStats` | Keyword historical statistics |
| `GetCrawlStats` | Crawl statistics |
| `GetCrawlIssues` | Crawl issues list |
| `GetUrlInfo` | Index info for a specific URL |
| `GetPageQueryStats` | Per-page query traffic |
| `GetQueryPageStats` | Per-query page traffic |
| `SubmitUrl` | Submit a URL to Bing |
| `SubmitUrlBatch` | Batch URL submission |
| `SubmitFeed` | Submit a sitemap |

## Usage Examples (Node.js)

### API Key

```javascript
const API_KEY = 'YOUR_API_KEY';
const SITE_URL = 'https://www.example.com/';

const res = await fetch(
  `https://ssl.bing.com/webmaster/api.svc/json/GetRankAndTrafficStats?apikey=${API_KEY}&siteUrl=${encodeURIComponent(SITE_URL)}`
);
const data = await res.json();
console.log(data);
```

### OAuth 2.0

```javascript
const res = await fetch(
  'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrl',
  {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${ACCESS_TOKEN}`
    },
    body: JSON.stringify({
      siteUrl: 'https://www.example.com/',
      url: 'https://www.example.com/new-page'
    })
  }
);
```

## Comparison with Google Search Console API

| Feature | Bing Webmaster API | Google Search Console API |
|---------|-------------------|--------------------------|
| Auth | API Key or OAuth 2.0 | OAuth 2.0 (Service Account) |
| Protocol | SOAP / POX / JSON | REST (JSON) |
| SDK | .NET only | Multi-language |
| Data granularity | Daily | Daily |
| Query data | Yes | Yes |
| Crawl data | Yes | No |

## Conclusion

The Bing Webmaster Tools API provides a JSON endpoint that is easy to consume from Node.js and other modern runtimes. It offers two authentication paths — API Key for simplicity and OAuth 2.0 for production use. When combined with the Google Search Console API, you can build a unified search analytics pipeline covering both major search engines.

:::conclusion
The Bing Webmaster Tools API is a practical, production-ready way to access Bing search performance data programmatically. Its JSON endpoint makes it accessible from any language, and when used alongside GA4 and AdSense APIs, it enables a complete cross-channel analytics stack.
:::
