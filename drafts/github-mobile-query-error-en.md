---
title: "Why \"Something went wrong...\" Always Appears on GitHub Mobile (And Why You Can Ignore It)"
slug: "github-mobile-query-error-ignore-en"
status: "draft"
categories: 
  - "general"
  - "Network / Access Errors"
tags: 
  - "GitHub"
  - "GitHub Mobile"
  - "Error"
  - "Troubleshooting"
featured_image: "../images/2025/github-mobile-query-error.png"
---

## Introduction

When navigating folder structures in the GitHub Mobile app (iOS / Android), you might encounter this error message almost every time:

> **Something went wrong while executing your query.**
> This may be the result of a timeout, or it could be a GitHub bug.
> Please include 'XXXX:XXXX...' when reporting this issue.

Despite the alarming message, **the content usually appears normally after a split second or a quick reload**.

You might wonder, "Is my internet connection bad?" or "Is the repository broken?"
The short answer is: **No. This is a known bug (or quirk) in the GitHub app, and you can safely ignore it.**

![GitHub Mobile Error](../images/2025/github-mobile-query-error.png)

## The Cause: GraphQL Timeouts and Optimistic UI

The root cause lies in how the GitHub Mobile app fetches data.

1.  **Heavy Queries**: When you open a folder, the app sends a complex "GraphQL query" to fetch everything at once: the file list (tree), latest commit info, permissions, etc.
2.  **The 10-Second Limit**: GitHub's API has a strict limit where queries taking longer than 10 seconds are timed out. Deep folder structures or repositories with many files often hit this limit.
3.  **Premature Error Display**: The app immediately reports the initial failure to the user, resulting in the red error banner.
4.  **Silent Success**: In the background, the app retries or fetches the data in chunks. This second attempt usually succeeds, which is why the files suddenly appear despite the error message.

## A Known Issue Since 2022

This behavior has been reported in the GitHub Community since at least 2022.

[Something went wrong while executing your query. This may be the result of a timeout #24631](https://github.com/orgs/community/discussions/24631)

GitHub Support has acknowledged that this is due to query timeouts and the need to break down requests. However, because **the data is not corrupted** and **the content eventually loads**, it remains a low-priority issue that hasn't been fixed.

## Solution: Just Ignore It

The best solution is to **ignore it**.

*   ❌ Reinstalling the app won't fix it.
*   ❌ Switching to better Wi-Fi won't fix it.
*   ❌ Changing repository settings won't fix it.

This error does not mean your repository is broken or that you lack permissions.
Just think of it as the app being a bit too impatient. Give it a second, and your files will be there.

## Summary

*   The error when navigating folders in GitHub Mobile is a **known bug/quirk**.
*   It's caused by API timeouts and the app's UI timing.
*   The data loads correctly in the background, so **you can ignore the error**.

It's annoying, but harmless.
