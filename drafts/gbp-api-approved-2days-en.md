---
title: "Google Business Profile API Approved in 2 Days: My Application Experience"
date: 2026-02-17 12:00:00
categories: 
  - GAS
tags: 
  - Google Business Profile
  - API
  - Google Apps Script
  - GCP
status: publish
slug: gbp-api-approved-2days-en
featured_image: ../images/2026/gbp-api-approved-2days-en.png
---

I recently published a [complete guide on Google Business Profile API with GAS](https://www.zidooka.com/archives/3741), and I decided to apply for the API access myself. To my surprise, **my application was approved in just 2 days**.

:::conclusion
Applied on Sunday, February 9 → Approval email received on Tuesday, February 11. If you're applying for internal analysis of your own business locations, the approval process can be surprisingly fast.
:::

## Timeline: Application to Approval

| Date | Event |
|------|-------|
| Feb 9 (Sun) | Submitted application form |
| Feb 11 (Tue) | Approval email received |

Despite the documented review period of "3-5 business days," my application was approved in just 2 days, even including a Sunday.

## What I Submitted

Here are the main fields from the application form and what I actually entered.

### Basic Information

- **Project Name**: ZIDOOKA Internal Tool Development
- **Use Case**: Performance data analysis for own business locations
- **Number of Locations**: Approximately 20 stores

### Supplemental Explanation (Full Text)

```
We operate approximately 20 physical business locations under a single Google Business Profile account, and we fully own and manage these locations.

We plan to use the Business Profile API exclusively to retrieve performance insights such as search views, map views, customer actions, reviews, and ratings for internal analytics and reporting purposes.

The data will be used only within our company for business analysis and decision-making. We will not provide this data to third parties, resell it, or expose it in any public-facing product or service.

The implementation is based on Google Apps Script using OAuth 2.0, and the data will be stored internally in Google Sheets and visualized via Looker Studio.
```

:::note
I avoided selecting "promotional purposes" and emphasized "analysis and internal use." Approval rates appear to be higher when requesting access for your own business data.
:::

## The Approval Email

Here's a summary of the approval email I received on February 11.

> Congratulations! Your project has been approved to use the Google Business Profile API!
> 
> To find and enable the API, log in to Developers Console and in the search box enter Google Business Profile API.

The email also included:

- Link to the **support page**
- Request to **review policies**
- Warning to avoid implying partnership with Google

## What I Did After Approval

Immediately after receiving the approval email, I took the following actions:

1. **Verified API enablement in GCP Console**
2. **Tested connection with GAS**
3. **Tested location data retrieval**

:::step
For the implementation steps after approval, refer to [Complete Guide to Google Business Profile API with GAS](https://www.zidooka.com/archives/3741), which includes specific code examples for GAS.
:::

## Conditions for Smooth Approval

Based on my experience and previous knowledge, here are the conditions that lead to smooth approval.

| Factor | Recommended | Avoid |
|--------|-------------|-------|
| **Purpose** | Analysis and management of own locations | Competitor data collection |
| **Data Handling** | Internal use only | Third-party sharing or SaaS resale |
| **Location Ownership** | Actual management rights | Locations without management rights |
| **Website** | Established operational history | Non-existent or under construction |

## Summary

:::conclusion
For internal analysis of your own business locations, Google Business Profile API applications can be approved within 2-3 days. Be honest with your information and clearly communicate that the data will be used internally only.
:::

With this approval, I can now automate store data retrieval using GAS. Next, I'll work on dashboard integration with Looker Studio.

---

**Related Articles**

- [Complete Guide to Google Business Profile API with GAS: Application Process and Error Solutions](https://www.zidooka.com/archives/3741)
- [【GAS】GoogleビジネスプロフィールAPIでデータ取得する方法と申請手順を完全解説 (Japanese)](https://www.zidooka.com/archives/3736)
