---
title: "How to Fix “Exceeded maximum execution time” in Google Apps Script: A Real-World Guide"
date: 2025-12-22 14:00:00
categories: 
  - GAS
tags: 
  - Google Apps Script
  - GAS Error
  - Exceeded maximum execution time
  - Troubleshooting
status: publish
slug: gas-exceeded-maximum-execution-time-en
featured_image: ../images/2025/image copy 39.png
---

If you work with Google Apps Script (GAS), you have likely encountered this error notification in your inbox:

```
Exceeded maximum execution time
```

At first glance, this looks like a performance issue. You might think, "My code is too slow," or "I need to optimize my loops."

However, in many real-world production cases, **optimization is not the answer.**
This error is often a signal that your **execution strategy** needs to change.

In this article, I will share a real scenario where this error occurred, how I initially tried to fix it (and failed), and the specific design pattern that finally solved it.

## What is actually happening?

Google Apps Script has strict runtime limits (usually **6 minutes** for consumer accounts and standard triggers). If your script runs longer than this, Google kills the process mid-execution.

The tricky part is:
*   **Your code is likely valid.** There are no syntax errors.
*   **It works fine with small data.** It only breaks when the dataset grows.

## The Scenario: A Nightly Batch Job

Here is a common real-world example where this error strikes.

**The Task:**
Every night at 11:00 PM, a script runs to:
1.  Read a Google Sheet (5,000+ rows).
2.  Check each row for a specific condition.
3.  Send an email if the condition is met.

**The Code (That Failed):**

```javascript
function nightlyJob() {
  const sheet = SpreadsheetApp.getActive().getSheetByName('data');
  const values = sheet.getDataRange().getValues();

  // Loop through ALL rows
  for (let i = 1; i < values.length; i++) {
    const row = values[i];
    if (row[3] === 'PENDING') {
      // This takes time!
      GmailApp.sendEmail(row[1], 'Notification', 'Your status is pending...');
      
      // Mark as done
      sheet.getRange(i + 1, 4).setValue('DONE'); 
    }
  }
}
```

**The Problem:**
Sending an email takes time (e.g., 0.5 - 1 second). Writing back to the sheet takes time.
If you have 500 pending rows, `500 * 1 second = 500 seconds` (over 8 minutes).
The script hits the 6-minute mark and crashes.

## The Wrong Fixes (What I Tried First)

When I first saw this error, I tried these "optimizations":

1.  **"I'll just add `Utilities.sleep()` to pause it."**
    *   **Result:** Failed. Sleeping counts *towards* the execution time. It makes the script slower, not safer.
2.  **"I'll run it manually."**
    *   **Result:** It works once, but fails again the next night when I'm asleep.
3.  **"I'll try to make the loop faster."**
    *   **Result:** Failed. The bottleneck is the API calls (GmailApp, SpreadsheetApp), not the JavaScript loop itself.

## The Real Solution: "Batching" and "Resume"

The only reliable way to fix this is to stop trying to do everything in one run.
Instead, we change the logic to: **"Do as much as you can in 5 minutes, then stop and remember where you left off."**

### Step 1: Use Script Properties to Save Progress

We use `PropertiesService` to save the row index where the script stopped.

### Step 2: The "Batch" Pattern Code

Here is the corrected, robust version of the code:

```javascript
function nightlyJobSafe() {
  const props = PropertiesService.getScriptProperties();
  
  // 1. Read where we left off (default to row 1)
  let startRow = Number(props.getProperty('START_ROW')) || 1;
  
  const sheet = SpreadsheetApp.getActive().getSheetByName('data');
  const lastRow = sheet.getLastRow();
  
  // 2. Define a batch size (e.g., process 50 rows per run)
  // Or check the time elapsed using new Date()
  const BATCH_SIZE = 50; 
  const endRow = Math.min(startRow + BATCH_SIZE, lastRow);
  
  // If we are already done, reset and exit
  if (startRow > lastRow) {
    console.log('All done!');
    props.deleteProperty('START_ROW');
    return;
  }

  // 3. Process only the batch
  const range = sheet.getRange(startRow, 1, endRow - startRow + 1, 10);
  const values = range.getValues();

  for (let i = 0; i < values.length; i++) {
    const row = values[i];
    if (row[3] === 'PENDING') {
      GmailApp.sendEmail(row[1], 'Notification', '...');
      // In a real app, you might batch these updates too
      sheet.getRange(startRow + i, 4).setValue('DONE');
    }
  }

  // 4. Save the new start position for the next run
  props.setProperty('START_ROW', endRow + 1);
  
  // Optional: If there is more work, create a trigger to run again in 1 minute
  if (endRow < lastRow) {
    ScriptApp.newTrigger('nightlyJobSafe')
      .timeBased()
      .after(60 * 1000) // Run again in 1 minute
      .create();
  }
}
```

## Why This Works

1.  **It never times out:** It only runs for a short burst (processing 50 rows).
2.  **It is self-healing:** If it crashes, the `START_ROW` property tells it exactly where to resume next time.
3.  **It scales:** It doesn't matter if you have 1,000 rows or 100,000 rows. It will just take more "batches" to finish, but it will never hit the "Exceeded maximum execution time" error.

## Summary

If you see "Exceeded maximum execution time," do not waste time trying to micro-optimize your code speed.

**Change your strategy:**
*   **Don't** try to process 100% of the data in one go.
*   **Do** use `PropertiesService` to track progress.
*   **Do** split the work into small, safe chunks.

This shift from "All-at-once" to "Batch processing" is the key to writing professional, reliable Google Apps Scripts.

---

**For Developers: The Technical Solution**
This article focused on the strategic approach. If you are looking for the specific code implementation, technical explanation of the "6-minute wall," and a copy-paste batch processing template, please check the technical guide below:

👉 **[Technical Guide: Fixing GAS "Exceeded maximum execution time" with Batch Processing Patterns](https://www.zidooka.com/?p=2716)**
