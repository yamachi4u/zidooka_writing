---
title: "Fixing GAS 'Exceeded maximum execution time' Error: How to Break the 6-Minute Wall"
slug: "gas-exceeded-maximum-execution-time-en"
status: "future"
date: "2025-12-13T09:00:00"
categories: 
  - "Google Apps Script"
  - "Troubleshooting"
tags: 
  - "GAS"
  - "Timeout"
  - "DriveApp"
  - "Optimization"
featured_image: "http://www.zidooka.com/wp-content/uploads/2025/12/6af991af5a4842d25f325abb320dbf8f.png"
---

# Conclusion: You Hit the GAS "6-Minute Rule"

Hello, this is ZIDOOKA!
If you maintain Google Apps Script (GAS) projects, you might have received this dreaded email notification:

**"Exceeded maximum execution time"**

![GAS Timeout Error](http://www.zidooka.com/wp-content/uploads/2025/12/6af991af5a4842d25f325abb320dbf8f.png)

If you are a beginner, you might panic and think, "Is my code wrong?" But don't worry. **It's not a syntax error.**

This simply means your script ran longer than **Google's time limit (6 minutes for free accounts)**, so Google forcibly stopped it, saying, "Time's up!"

## My Experience: It Died When Files Piled Up

I actually struggled with this error recently.
I had built a script to organize files in Google Drive. **At first, it finished in seconds, but one day it started throwing errors.**

The cause was simple: **"The number of files had grown enormously during operation."**
The code was correct, but the volume of data to process had increased, making it impossible to finish within the time limit.

In this article, I will introduce the **Standard Solution** and the **Ultimate Weapon** to overcome this "6-Minute Wall."

# Solution 1: [The Standard] Reduce the Workload with Search Queries

The first thing to suspect is: "Am I looping unnecessarily?"
Especially when using `DriveApp`, code like this is very dangerous:

```javascript
// ❌ Bad Example: Getting ALL files and then filtering with 'if'
function processAllFiles() {
  // This fetches tens of thousands of unrelated files!
  const files = DriveApp.getFiles(); 
  
  while (files.hasNext()) {
    const file = files.next();
    // Checking the name here wastes precious time
    if (file.getName().includes("Invoice")) {
       // Process...
    }
  }
}
```

With this approach, 6 minutes will pass before you even reach the file you want.
Instead, use `DriveApp.searchFiles` to ask Google to find **only the necessary files from the start**.

```javascript
// ✅ Good Example: Get only files that match the condition
function processRecentFiles() {
  // Example: I want only "Spreadsheets" updated "Today"
  const today = new Date();
  const dateString = Utilities.formatDate(today, Session.getScriptTimeZone(), "yyyy-MM-dd");
  
  // Filter with a search query (like SQL)
  // mimeType = Spreadsheet
  // modifiedDate > Today
  const params = `mimeType = 'application/vnd.google-apps.spreadsheet' and modifiedDate > '${dateString}'`;
  
  const files = DriveApp.searchFiles(params);
  
  while (files.hasNext()) {
    const file = files.next();
    console.log(file.getName()); // Only target files come here!
  }
}
```

Just by doing this, the target reduces from "All Files (Thousands)" to "Today's Files (A few)", and the process finishes instantly.
**Basically, this "Filtering" solves the problem in most cases.**

# Solution 2: [The Ultimate Weapon] Resume with "ContinuationToken"

"I filtered it, but there are still thousands of files..."
"I need to backup ALL files, so I can't filter..."

In such cases, this is your **Ultimate Weapon**.
We create a mechanism to **"work for 5 minutes, save the current spot, take a break, and resume later."**

Google Drive has a built-in feature called `ContinuationToken` (like a bookmark).

## Implementation Concept

1.  Start a stopwatch when the script begins.
2.  Check "How many minutes passed?" after processing each file.
3.  If 5 minutes have passed, **save a ticket (token) saying "Next start from here"** and intentionally stop the script.
4.  Set a trigger (alarm clock) saying **"Wake me up in 1 minute."**

```javascript
function processLargeAmountOfFiles() {
  // Get script properties (storage)
  const scriptProperties = PropertiesService.getScriptProperties();
  
  // 1. Check if there is a "continuation"
  const token = scriptProperties.getProperty('CONTINUATION_TOKEN');
  let files;
  
  if (token) {
    // If there is a token, resume from there!
    files = DriveApp.continueFileIterator(token);
  } else {
    // If not, start from scratch
    files = DriveApp.getFiles(); 
  }
  
  const startTime = new Date().getTime(); // Start stopwatch
  
  while (files.hasNext()) {
    const file = files.next();
    
    // --- Heavy processing goes here ---
    console.log('Processing: ' + file.getName());
    // ----------------------------------
    
    // 2. Check elapsed time (e.g., stop after 5 mins = 300,000ms)
    const currentTime = new Date().getTime();
    if (currentTime - startTime > 300000) {
      
      // 3. Issue and save a token for "Next time start here"
      const newToken = files.getContinuationToken();
      scriptProperties.setProperty('CONTINUATION_TOKEN', newToken);
      
      // 4. Set a trigger to re-run itself in 1 minute
      ScriptApp.newTrigger('processLargeAmountOfFiles')
        .timeBased()
        .after(1 * 60 * 1000) // 1 minute later
        .create();
        
      console.log('Time limit reached. Resuming automatically in 1 minute.');
      return; // Exit for now!
    }
  }
  
  // Cleanup token and triggers when fully done
  scriptProperties.deleteProperty('CONTINUATION_TOKEN');
  clearTriggers('processLargeAmountOfFiles'); // *Need a separate function to clear triggers
  console.log('All processing complete.');
}
```

This code is a bit complex, but with this, you can theoretically process millions of files forever, taking a break every 6 minutes.

# Summary

If you see "Exceeded maximum execution time," don't panic. Follow these steps:

1.  **[Basic]** Stop using `DriveApp.getFiles()` and try to narrow down targets with `searchFiles`.
2.  **[Ultimate]** If you really can't reduce the volume, use `ContinuationToken` to split the execution.

Especially for beginners, just learning step 1 (Filtering) will dramatically improve your script's speed and stability!
