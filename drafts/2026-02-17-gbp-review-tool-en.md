---
title: "Zero Missed Reviews: Building a Google Business Profile Alert Tool"
date: "2026-03-17"
categories: ["Case Study"]
tags: ["GAS", "Google Business Profile", "API", "Automation", "affiliate"]
status: "draft"
author: "Zidooka Dev"
description: "How we built a GAS tool to alert unreplied reviews, and solved the 'hidden v4 API' trap."
featured_image: ../images/2026/gbp-tool-thumbnail.png
---

Responding to Google Maps reviews is crucial for customer engagement, but it's easy to forget amidst daily operations.
We developed a Google Apps Script (GAS) tool to detect unreplied reviews on Google Business Profile (formerly Google My Business) and automatically send email alerts.

This article explains the setup procedure and the "hidden API" trap we encountered during development.

:::note
At Zidooka, we specialize in developing and customizing Google Business Profile integration tools. Feel free to contact us!
:::

## Background

We wanted a simple bot that **automatically checks once a month and emails the manager if there are any unreplied reviews.**

## How it works

1.  **Google Apps Script (GAS)** runs on a schedule (e.g., the 3rd of every month).
2.  It calls the **Google Business Profile API** to fetch reviews for all locations.
3.  It filters for reviews that have no reply (`reviewReply` is empty).
4.  It logs the unreplied list to a **Google Sheet** and sends an **email notification**.

## Source Code (Anonymized)

Here is the core logic. You can copy and paste this.

### `Config.js`

```javascript
const CONFIG = {
  // Spreadsheet ID for logging
  SPREADSHEET_ID: 'YOUR_SPREADSHEET_ID_HERE', 
  
  // Notification email recipient
  RECIPIENT_EMAIL: 'your-email@example.com',
  
  // Trigger Day (e.g., 3rd of the month)
  TRIGGER_DAY: 3,
  
  // API Endpoints
  API_ACCOUNT_URL: 'https://mybusinessaccountmanagement.googleapis.com/v1',
  API_INFO_URL: 'https://mybusinessbusinessinformation.googleapis.com/v1',
  API_REVIEW_URL: 'https://mybusiness.googleapis.com/v4'
};
```

### `Api.js` (The tricky part)

The Google Business Profile API is currently in transition, mixing v1 and v4 endpoints. This is key.

```javascript
/**
 * Fetches data from API
 */
function callApi(fullUrl, method = 'GET', payload = null) {
  const options = {
    method: method,
    headers: {
      Authorization: `Bearer ${ScriptApp.getOAuthToken()}`,
      Accept: 'application/json',
      'Content-Type': 'application/json'
    },
    muteHttpExceptions: true
  };
  // ... (Standard fetch logic) ...
}

/**
 * Reviews are STILL in v4 (as of 2026)
 */
function getReviews(accountName, locationName) {
  // v1 Location Name: 'locations/12345'
  // v4 Review API Path: 'accounts/{accountId}/locations/{locationId}/reviews'
  
  const locId = locationName.split('/')[1];
  const v4LocationPath = `${accountName}/locations/${locId}`;
  const url = `${CONFIG.API_REVIEW_URL}/${v4LocationPath}/reviews`;
  
  // ... (Fetch logic) ...
}
```

## The Biggest Trap: "Missing" API

The biggest struggle during development was **"Cannot find or enable the API to fetch reviews."**

### Symptom

When searching for "Google My Business API" in the GCP console, it doesn't show up. Only "Business Profile Performance API" appears. However, the Performance API **cannot fetch individual review texts**.

### Cause & Solution

:::warning
The **legacy v4 API (Google My Business API)** needed for reviews is hidden from search results.
:::

However, **it can be enabled if you know the direct link.** Without this, you will be stuck with "403 Forbidden" errors forever.

:::step
The Solution Link
:::

You must access this link directly, select your project, and click "Enable".

<https://console.developers.google.com/apis/api/mybusiness.googleapis.com/overview>

## Setup Summary

:::step
Procedure
:::

1.  **Create GCP Project**: Create a project in Google Cloud Platform.
2.  **Enable APIs**:
    *   `Google My Business Account Management API`
    *   `Google My Business Business Information API`
    *   **`Google My Business API` (Use the hidden link above!)**
3.  **Create GAS Project**: Write the script and set OAuth scopes in `appsscript.json`.
4.  **Link**: Link the GCP "Project Number" in GAS settings.
5.  **First Run**: Authorize the OAuth popup and you're done!

:::conclusion
Summary
:::

Google APIs change frequently and documentation can be complex.
If you feel "this looks too hard to do in-house...", please contact Zidooka.
We can support not only review management but also building custom MEO dashboards.
