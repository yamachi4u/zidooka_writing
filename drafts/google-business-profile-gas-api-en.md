---
title: "Complete Guide to Google Business Profile API with GAS: Application Process and Error Solutions"
date: 2026-02-14 20:00:00
categories:
  - GAS
tags:
  - Google Apps Script
  - Google Business Profile
  - API
  - GCP
  - Error Handling
status: publish
slug: google-business-profile-gas-api-guide-en
featured_image: ../images/gbp-error-429.png
---

Many developers want to automatically retrieve Google Business Profile data using Google Apps Script (GAS). However, API application is mandatory, and simply enabling the API won't work. This article explains the actual errors you'll encounter and how to resolve them, written for beginners.

## What is Google Business Profile API?

The Google Business Profile API (formerly Google My Business API) allows programmatic access to store information and performance data displayed on Google Maps.

**Conclusion:** While technically possible with GAS, API application approval is mandatory and not straightforward.

### Retrievable Data

- **Store Information**: Name, address, business hours, phone number
- **Performance Metrics**: Search views, map views, direction requests, phone calls
- **Review Data**: Review text, star ratings, posting dates
- **Photos**: Store photo management

## Can You Use GAS? Answer and Caveats

### Possible, But With Hurdles

**Key Point:** Technically feasible, but you must clear three gates:

1. **API Enablement in GCP**
2. **Explicit OAuth Scope Configuration**
3. **Google Application and Approval Wait (Biggest Hurdle)**

Especially #3, the "application approval," is mandatory, and the API is essentially unusable until approved.

## Errors You'll Encounter Immediately

### Error 1: 403 PERMISSION_DENIED (Insufficient Scopes)

```json
{
  "error": {
    "code": 403,
    "message": "Request had insufficient authentication scopes.",
    "status": "PERMISSION_DENIED"
  }
}
```

**Cause:** OAuth scopes don't include Business Profile permissions.

#### Solution

Add scopes to `appsscript.json`:

```json
{
  "timeZone": "Asia/Tokyo",
  "oauthScopes": [
    "https://www.googleapis.com/auth/business.manage",
    "https://www.googleapis.com/auth/spreadsheets",
    "https://www.googleapis.com/auth/script.external_request"
  ]
}
```

**Action:** Edit via "Project Settings" → "Show manifest file" in GAS editor.

### Error 2: 403 SERVICE_DISABLED (API Not Enabled)

```json
{
  "error": {
    "code": 403,
    "message": "My Business Account Management API has not been used in project XXXXX before or it is disabled.",
    "status": "PERMISSION_DENIED"
  }
}
```

**Cause:** API not enabled in GCP Console.

#### Solution

1. Check GCP project number in GAS "Project Settings"
2. Open [Google Cloud Console](https://console.cloud.google.com/)
3. Select the project
4. "APIs & Services" → "Library"
5. Search and enable:
   - **My Business Account Management API**
   - **Business Profile Performance API**

![GCP API Enable Screen](../images/gbp-api-enable.png)

### Error 3: 429 RESOURCE_EXHAUSTED (Biggest Hurdle)

```json
{
  "error": {
    "code": 429,
    "message": "Quota exceeded for quota metric 'Requests' and limit 'Requests per minute' of service 'mybusinessaccountmanagement.googleapis.com' for consumer 'project_number:XXXXX'.",
    "status": "RESOURCE_EXHAUSTED",
    "details": [
      {
        "quota_limit_value": "0"
      }
    ]
  }
}
```

![429 Error Screen](../images/gbp-error-429.png)

**Caution:** This is the most critical error. Note `quota_limit_value: 0`.

#### What This Error Means

- API is enabled
- Authentication passed
- **But quota is 0, so not even 1 request is allowed**

**Conclusion:** This API requires "application approval" and cannot be used without Google's authorization.

## API Application Process

### Why Application is Required

Google Business Profile API handles business data and uses review-based approval to prevent:

- Scraping prevention
- Unauthorized competitor data collection
- SaaS resale restrictions

**Key Point:** If your purpose is managing and analyzing your own stores, approval is almost certain.

### Application Form Location

Official application form:
https://support.google.com/business/contact/api_default

### Application Form Examples

#### Important Selections

| Field | Answer |
|-------|--------|
| **API Purpose** | Local insights analysis and reporting |
| **Collaboration Method** | Updates managed by us, but data retrieval coordinated with customers |
| **Store Type** | Chain/Franchise (10+ locations) |

**Caution:** Avoid selecting "promotional purposes." Emphasize analysis and internal use.

#### Supplemental Explanation (Ready to Use)

```
We operate approximately 20 physical business locations under a single Google Business Profile account, and we fully own and manage these locations.

We plan to use the Business Profile API exclusively to retrieve performance insights such as search views, map views, customer actions, reviews, and ratings for internal analytics and reporting purposes.

The data will be used only within our company for business analysis and decision-making. We will not provide this data to third parties, resell it, or expose it in any public-facing product or service.

The implementation is based on Google Apps Script using OAuth 2.0, and the data will be stored internally in Google Sheets and visualized via Looker Studio.
```

### Approval Timeframe

- **Fastest**: Same day ~ 2 business days
- **Typical**: 3-5 business days
- **With additional questions**: 1 week~

**Action:** Be patient. Plan your implementation while waiting for approval email.

## Implementation Examples After Approval

### Minimal Connection Test Code

```javascript
function testGBPConnection() {
  const url = "https://mybusinessaccountmanagement.googleapis.com/v1/accounts";
  const res = UrlFetchApp.fetch(url, {
    headers: {
      Authorization: "Bearer " + ScriptApp.getOAuthToken()
    },
    muteHttpExceptions: true
  });

  Logger.log(res.getResponseCode()); // 200 = success
  Logger.log(res.getContentText());
}
```

### Retrieve Location List

```javascript
function listLocations() {
  // Get accounts
  const accountsUrl = "https://mybusinessaccountmanagement.googleapis.com/v1/accounts";
  const accountsRes = UrlFetchApp.fetch(accountsUrl, {
    headers: { Authorization: "Bearer " + ScriptApp.getOAuthToken() }
  });
  const accounts = JSON.parse(accountsRes.getContentText()).accounts;
  const accountName = accounts[0].name;

  // Get locations
  const locationsUrl = `https://mybusinessbusinessinformation.googleapis.com/v1/${accountName}/locations?readMask=name,title,storeCode`;
  const locationsRes = UrlFetchApp.fetch(locationsUrl, {
    headers: { Authorization: "Bearer " + ScriptApp.getOAuthToken() }
  });
  
  const locations = JSON.parse(locationsRes.getContentText()).locations || [];
  
  // Output to spreadsheet
  const sheet = SpreadsheetApp.getActive().getSheetByName("locations") || 
                SpreadsheetApp.getActive().insertSheet("locations");
  sheet.clear();
  sheet.appendRow(["Location Name", "Title", "Store Code"]);
  
  locations.forEach(loc => {
    sheet.appendRow([loc.name, loc.title, loc.storeCode || ""]);
  });
}
```

### Retrieve Performance Data

```javascript
function fetchPerformanceData() {
  const sheet = SpreadsheetApp.getActive().getSheetByName("locations");
  const data = sheet.getDataRange().getValues();
  data.shift(); // Remove header

  const endDate = new Date();
  const startDate = new Date();
  startDate.setDate(endDate.getDate() - 30); // Last 30 days

  const resultSheet = SpreadsheetApp.getActive().getSheetByName("performance") || 
                      SpreadsheetApp.getActive().insertSheet("performance");
  resultSheet.clear();
  resultSheet.appendRow(["Date", "Location", "Metric", "Value"]);

  data.forEach(row => {
    const locationName = row[0];
    
    const request = {
      locationNames: [locationName],
      basicRequest: {
        timeRange: {
          startTime: startDate.toISOString(),
          endTime: endDate.toISOString()
        },
        metricRequests: [
          { metric: "SEARCH_VIEWS" },
          { metric: "MAP_VIEWS" },
          { metric: "WEBSITE_CLICKS" }
        ]
      }
    };

    const url = "https://businessprofileperformance.googleapis.com/v1/locations:reportInsights";
    const res = UrlFetchApp.fetch(url, {
      method: "post",
      headers: { Authorization: "Bearer " + ScriptApp.getOAuthToken() },
      contentType: "application/json",
      payload: JSON.stringify(request)
    });

    const response = JSON.parse(res.getContentText());
    // Write data to sheet (implementation omitted)
  });
}
```

## Frequently Asked Questions

### Q1: Can applications be rejected?

**Action:** The following cases may result in rejection or hold:

- Purpose is competitor store data collection
- SaaS product resale purpose
- Company website doesn't exist
- Attempting to access stores without actual management rights

For own-store analysis purposes, approval is almost certain.

### Q2: Can I develop before approval?

**Conclusion:** You can proceed with spreadsheet design and Looker Studio dashboard design.

It's practical to develop API call portions with dummy data and connect to production after approval.

### Q3: Is it free to use?

For typical usage (dozens of stores, daily updates), the free tier is sufficient.

**Caution:** Be mindful of quota limits with high-volume requests or high-frequency updates.

### Q4: Why doesn't it appear in Advanced Google Services?

Business Profile API doesn't show in GAS "Advanced Google Services" list.

**Action:** Implement by calling REST API directly with `UrlFetchApp`.

## Multi-Store Management Use Cases

### Looker Studio Integration

1. Periodic data retrieval to spreadsheet via GAS
2. Set spreadsheet as Looker Studio data source
3. Visualize with dashboard

### Trigger Setup

```javascript
function setupDailyTrigger() {
  ScriptApp.newTrigger("fetchPerformanceData")
    .timeBased()
    .everyDays(1)
    .atHour(6) // Run at 6 AM daily
    .create();
}
```

## Summary

Key points for using Google Business Profile API with GAS:

**Conclusion:** Technically possible, but API application approval is mandatory. Once approved, it becomes a powerful automation tool.

### Implementation Flow

1. **Enable API in GCP** (5 minutes)
2. **Add scopes to appsscript.json** (5 minutes)
3. **Submit application to Google** (30 minutes)
4. **Wait for approval** (Several days ~ 1 week)
5. **Start implementation and operation** (1-2 days)

### What Becomes Possible After Approval

- Centralized multi-store data management
- Automatic daily performance retrieval
- Looker Studio visualization
- Automatic review monitoring

### Recommended For

- Companies operating multiple stores
- Those wanting to measure Google Maps effectiveness
- Those needing per-store KPI comparisons
- Those wanting freedom from manual data checking

**Caution:** Application is once per company. Fill out carefully.

## References

1. Google Business Profile API Documentation
https://developers.google.com/my-business
2. API Access Request Form
https://support.google.com/business/contact/api_default
3. Google Cloud Console
https://console.cloud.google.com/
4. GAS OAuth Scopes Reference
https://developers.google.com/apps-script/guides/services/authorization
