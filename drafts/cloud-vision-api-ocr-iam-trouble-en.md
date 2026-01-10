---
title: "Why Cloud Vision API OCR Fails Due to IAM and the Full Record of How I Finally Got It Working"
date: 2025-12-17 10:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - IAM
  - GCP
  - Troubleshooting
status: publish
slug: cloud-vision-api-ocr-iam-trouble-en
featured_image: ../images/2025/twitterposttool/cloud-vision-api-ocr.png
---

When attempting to execute OCR using the Cloud Vision API, I faced a situation where the process would not proceed at all due to IAM settings.

Even though I was following the official documentation, the following error appeared repeatedly:

`INVALID_ARGUMENT: Role roles/vision.user is not supported for this resource.`

In this article, I will organize the following based on my actual experience:

1.  Why this error occurs
2.  Why it doesn't work even when following the official guide
3.  The decision I finally took to "reliably make OCR work"

## What I Attempted to Do

*   OCR using Cloud Vision API
*   Targets were images and PDFs
*   Execution from local environment (VS Code)
*   Purpose was one-off processing/verification

This is not a special configuration, but a use case that many people will likely go through at least once.

## Settings Performed According to Official Documentation

### 1. Enable Cloud Vision API

I enabled the Cloud Vision API in the project and confirmed its enabled status.

At this point, the API side preparation is fine.

### 2. Create a Service Account

I created a dedicated service account for OCR execution.

### 3. Attempted to Grant roles/vision.user

The official documentation instructs to grant Cloud Vision API User (`roles/vision.user`) to the service account.

Therefore, I attempted to grant the corresponding role from IAM (GUI / CLI).

## The Problem that Occurred

However, the result was the same no matter how many times I tried.

*   **GUI**: The role does not appear in search results.
*   **CLI**: An error occurs when specifying the role.

The error displayed is as follows:

`Role roles/vision.user is not supported for this resource.`

The API is enabled, and the service account is correct.
Yet, **the role cannot be assigned**.

## Why Does This Problem Occur?

This behavior is considered to be due to GCP's specifications or state dependencies rather than a configuration mistake.

Specifically, due to factors such as:

*   Project creation timing
*   Presence of Organization Policies or IAM Conditions
*   Role exposure state (GUI / CLI)

There are cases where `roles/vision.user` cannot be directly bound to a project.

Moreover, this state is a very easy point to get stuck on because:

*   There is no clear explanation in the official documentation.
*   The cause is difficult to understand from the error message.

## The Decision I Finally Took

In conclusion, I stopped obsessing over `roles/vision.user`.

Instead, treating it as a verification use case, I granted:

*   A role executable at the project level
*   A role containing the necessary permissions for API usage

Upon doing so, OCR became executable without issues.

### Why Was This Decision Acceptable?

What is important in executing Cloud Vision API OCR is:

1.  The API is enabled.
2.  It is a correctly authenticated service account.
3.  It has execution permissions within the project.

For one-off processing or verification purposes, there are cases where the decision to prioritize "making it work reliably" is more realistic than strictly separating roles.

## Actual Configuration (Overview)

*   **API**: Cloud Vision API (Enabled)
*   **Authentication**: Service Account Key (JSON)
*   **Execution Environment**: Local (VS Code)
*   **OCR**: Normal operation for both Images/PDFs

With this configuration, the OCR process completed without issues.

## To Those Stuck at the Same Place

*   Even if `roles/vision.user` cannot be assigned, it is not necessarily a dead end.
*   The error may be due to the project's state, not a configuration mistake.
*   If it is for verification or one-off use, there are scenes where a pragmatic decision is necessary.

If you absolutely want to manage IAM strictly, you can also consider methods such as:

*   Creating a dedicated project for OCR.
*   Organizing roles later.

## Summary

*   There are cases where Cloud Vision API IAM settings do not proceed according to the official guide.
*   Situations where `roles/vision.user` cannot be granted do exist.
*   OCR can be executed without issues by prioritizing making it work.
*   In practice, it is necessary to distinguish between "correctness" and "working settings".

I hope this article serves as material for judgment for those who are stuck at the same place.

---

### Cloud Vision OCR Series Articles

This article is part of a series on setting up and operating OCR using Cloud Vision API.

*   **[Summary] Cloud Vision OCR x CLI x GCP Setup & Troubleshooting Log](/archives/ocr-series-summary-en)**
*   **Setup:** [Why Cloud Vision API OCR Failed with IAM and How I Finally Got It Working](/archives/cloud-vision-api-ocr-iam-trouble-en)
*   **Operation:** [Why Running Cloud Vision OCR via CLI Was the Easiest Method](/archives/cloud-vision-ocr-cli-best-practice-en)
*   **Workflow:** [Why GCP x VSC x CLI is the Correct Route for Frequent OCR](/archives/frequent-ocr-gcp-vsc-cli-workflow-en)
*   **Tools:** [Explanation of Practical Tools Created to Run Cloud Vision OCR via CLI](/archives/cloud-vision-ocr-cli-tool-en)
*   **Code:** [Free Code: Minimal Script to Safely Run Cloud Vision OCR via CLI](/archives/cloud-vision-ocr-minimal-script-en)
*   **Extra:** [When Stuck in GCP GUI, Escaping to Cloud Shell + CUI Was Faster (Failure Story)](/archives/gcp-gui-vs-cloud-shell-failure-story-en)
