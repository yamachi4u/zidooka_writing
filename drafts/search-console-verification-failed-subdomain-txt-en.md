---
title: "How to Fix “Ownership Verification Failed” in Google Search Console (Subdomain TXT Method)"
date: 2026-01-02 22:05:00
categories: 
  - SEO
  - Google / GA4 / Apps Script Errors
tags: 
  - Search Console
  - DNS
  - TXT Record
  - Troubleshooting
status: publish
slug: search-console-verification-failed-subdomain-txt-en
featured_image: ../images/image.png
---

When adding a subdomain to Google Search Console, you may see an error such as “Ownership verification failed” or “TXT record not found.” This guide explains the correct approach using placeholders only, applicable to any domain or environment.

## Conclusion: Patience is Key

When verifying a subdomain, the TXT record must be added **to that specific subdomain**. If the record is correct, the only remaining action is to **wait for DNS propagation and Google’s verification check**.

:::warning
Repeated changes usually make things worse, not better.
:::

## Why Waiting Is Necessary

TXT verification consists of two steps:

1. The DNS provider publishes the TXT record
2. Google checks that record

These steps are not synchronized. Even after DNS propagation, Google may take time before rechecking the record.

## Correct Setup Example (Placeholder)

Target subdomain:
* `sub.example.com`

DNS settings:

| Type | Host | Value | TTL |
| --- | --- | --- | --- |
| `TXT` | `sub` | `google-site-verification=...` | Default |

:::note
**Important:**
* Do not place the record at the root (`@`).
* In most DNS providers, enter only `sub` for the host, not the full `sub.example.com`.
:::

## Common Mistakes

Avoid these actions as they disrupt the verification process:

* ❌ Adding the TXT record to the root domain
* ❌ Using the full domain name in the host field incorrectly
* ❌ Using an outdated verification token
* ❌ Repeatedly deleting and re-adding the record

## How Long to Wait

Typical propagation times:

* **Minutes to 30 minutes:** Fast DNS providers
* **1–6 hours:** Common cases
* **Up to 24 hours:** Rare cases

Avoid clicking “Verify” repeatedly during this period.

## If Verification Still Fails

After 24 hours, consider:

1. **Switching to HTML file verification**
2. **Adding a new TXT record without removing the existing one**

:::conclusion
For subdomain verification in Search Console:
1. Add the TXT record to the correct subdomain
2. Do not touch the settings afterward
3. Wait patiently

This approach resolves most verification issues.
:::
