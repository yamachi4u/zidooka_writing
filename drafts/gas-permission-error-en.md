---
title: "How to Fix 'The script does not have permission to perform that action' in GAS"
slug: gas-permission-error-en
date: 2025-12-17 12:30:00
categories: 
  - gas-errors
  - gas
  - google-errors
tags: 
  - GAS
  - Google Apps Script
  - Error
  - Permission
status: publish
featured_image: ../images/gas-permission-error.png
parent: gas-permission-error-jp
---

When using Google Apps Script (GAS), you may suddenly see the error:

`The script does not have permission to perform that action`

This error means that **processing is blocked due to insufficient script privileges**.

At first glance, it is difficult to understand, but the occurrence patterns are almost fixed.

In this article, we will organize and explain:

*   Specific code examples where this error occurs
*   Why it becomes a permission error
*   Correct isolation procedure in practice

## Typical code examples where this error occurs

`The script does not have permission to perform that action` occurs the moment you access a specific Google service.

The following are examples with a particularly high encounter rate in practice.

### When operating Drive (Most frequent)

```javascript
function testDrive() {
  const files = DriveApp.getFiles();
  while (files.hasNext()) {
    Logger.log(files.next().getName());
  }
}
```

**Situations where the error occurs**

*   First execution
*   Trigger execution
*   Immediately after copying a script created by someone else

**Cause**

*   Access permission to Google Drive is unapproved

Code using `DriveApp` must be executed manually once to pass permission approval.

### When operating Gmail / Calendar

```javascript
function readMail() {
  const threads = GmailApp.search('is:unread');
  Logger.log(threads.length);
}

function createEvent() {
  CalendarApp.getDefaultCalendar()
    .createEvent('test', new Date(), new Date());
}
```

**Points**

*   Gmail / Calendar have strong permission requirements
*   Likely to error during trigger execution

If approval is missing even once, it stops with this error.

### When using external API (UrlFetch)

```javascript
function fetchApi() {
  const res = UrlFetchApp.fetch('https://api.example.com');
  Logger.log(res.getContentText());
}
```

**Cause**

*   External access permission is unapproved
*   Web app / Trigger execution

If you have never executed it from the editor, this error will almost certainly occur.

### When trying to operate someone else's Drive file

```javascript
function openOtherFile() {
  const ss = SpreadsheetApp.openById('Someone else's Spreadsheet ID');
  Logger.log(ss.getName());
}
```

**Cause**

*   No edit permission
*   Insufficient privileges for Shared Drive

This is the intended behavior, and there is no way to avoid it on the code side.

### Cases where it suddenly appears in trigger execution

```javascript
function triggerFunc() {
  DriveApp.createFile('test.txt', 'hello');
}
```

**Common causes**

*   Trigger creator's permissions deleted
*   Script owner change
*   Old trigger remaining after copying

In this case, solving it involves deleting and recreating the trigger.

### Example appearing in Web App (doGet / doPost)

```javascript
function doGet() {
  const files = DriveApp.getFiles();
  return ContentService.createTextOutput('ok');
}
```

**Cause**

*   Execution user is "User accessing the web app"
*   Anonymous access has no Drive permissions

In Web Apps, mistakes in execution user settings are very common.

### When using Advanced Service (Drive API, etc.)

```javascript
function listFiles() {
  const files = Drive.Files.list();
  Logger.log(files);
}
```

**Cause**

*   Advanced Service not enabled
*   Insufficient OAuth scope

Service enablement and re-approval are mandatory.

## Common points of causes for this error

What is common in all cases is that:

**The script is trying to execute an operation that exceeds its authority.**

It is almost never the case that the way the code is written is wrong.

## Isolation procedure in practice

If an error occurs, check in the following order:

1.  Which line is the error occurring on?
2.  What service is being used on that line?
3.  Does that service require permission?
4.  Whose authority is it being executed with?

If you organize these 4 points, you can definitely identify the cause.

## Summary

`The script does not have permission to perform that action` is a very common permission error in Google Apps Script.

In many cases, it is resolved simply by:

*   Re-approval by manual execution
*   Recreating the trigger
*   Reviewing execution user settings

Once you get used to GAS errors, you will be able to judge immediately: **"Ah, it's permissions."**
