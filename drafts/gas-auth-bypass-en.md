---
title: "[GAS] How to Fix \"Google hasn't verified this app\" (Authorization Guide)"
date: 2025-12-13 10:00:00
categories: 
  - GAStips
tags: 
  - GAS
  - Google Apps Script
  - Troubleshooting
status: publish
slug: gas-auth-bypass-en
---

When you run a Google Apps Script (GAS) for the first time, or when you add new permissions (such as access to Gmail or Sheets), you will see an **"Authorization required"** popup.

As you proceed, you will encounter a scary warning screen saying **"Google hasn't verified this app"**, often labeling it as "unsafe". Many users stop here, fearing they've done something wrong.

This simply means **"Google hasn't reviewed your script as an official app"**. If it's a script you wrote yourself, **it is safe to proceed**.

This article explains the steps to bypass this warning screen and execute your script, with screenshots.

## Step 1: Review Permissions

When you run the script, you will first see this dialog.
Click **"Review permissions"**.

![Authorization required](../images/2025/gas-auth-01-required.png)

## Step 2: Choose an Account

Select the Google account you want to use to run the script.

![Choose an account](../images/2025/gas-auth-02-choose-account.png)

## Step 3: Bypass the Warning Screen (Important)

Here, you will see a screen with a red alert icon saying **"Google hasn't verified this app"**.
You might be tempted to click the blue "Back to safety" button, but **do not click it** (it will cancel the execution).

Instead, click the **"Advanced"** link in the bottom left corner.

![Click Advanced](../images/2025/gas-auth-03-advanced.png)

## Step 4: Go to the "Unsafe" Page

Once the advanced details open, a link will appear at the bottom.
Click **"Go to [Project Name] (unsafe)"**.

*Note: It says "unsafe", but this just means it hasn't been verified by Google. If it's your own script, it is safe to proceed.*

![Go to unsafe page](../images/2025/gas-auth-04-unsafe.png)

## Step 5: Allow Access

Finally, you will see what the script is requesting access to (e.g., Edit access to Spreadsheets).
Review the permissions and click **"Allow"** at the bottom.

![Click Allow](../images/2025/gas-auth-05-allow.png)

## Done

Your script will now execute.
You only need to do this authorization process once (unless you add new permissions to the script later).

Don't be afraid of the warning—just follow the path: **Advanced -> Go to (unsafe) -> Allow**.
