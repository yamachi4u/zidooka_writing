---
title: "[GAS] Fix Exception: Cannot call SpreadsheetApp.getUi() from this context"
slug: gas-getui-context-error-en
date: 2026-01-13 11:00:00
categories: 
  - GAS
  - GAS Tips
tags: 
  - troubleshooting
  - tips
  - Google Apps Script
  - GAS
  - Google Sheets
  - Error
status: publish
featured_image: ../images/gas-getui-context-error.png
---

When automating Google Sheets with Google Apps Script (GAS), you may encounter the error **"Exception: Cannot call SpreadsheetApp.getUi() from this context."**
This error is common when moving from manual execution to automated triggers.

Here is why it happens and how to fix it properly.

## What the Error Means

```
Exception: Cannot call SpreadsheetApp.getUi() from this context.
```

This error means: **"You cannot use UI elements (alerts, dialogs, menus) in the current execution method."**

User Interfaces like `alert`, `prompt`, or `toast` require an active user session—meaning a human looking at the spreadsheet. If the script runs in the background, there is no one to show the alert to.

## Common Causes

If you see this error in your logs (e.g., `Execution started ...`), your script was likely triggered by one of the following:

- **Time-driven triggers** (e.g., runs every hour)
- **onEdit / onChange triggers** (simple triggers have limitations)
- **Web Apps** (`doGet` / `doPost`)
- **API Executions**
- **Drive Triggers**

In these background contexts, `SpreadsheetApp.getUi()` is strictly forbidden.

### Typical Problematic Code

You likely have code similar to this running in a trigger:

```javascript
// ❌ FAILS in triggers
SpreadsheetApp.getUi().alert('Finished!');
```

```javascript
// ❌ FAILS in triggers
const ui = SpreadsheetApp.getUi();
ui.alert('Error occurred');
```

## Solutions

### 1. Stop Using UI (Most Secure)

For automated triggers, the best solution is to remove all UI interactions. Background processes should run silently as much as possible.

**Replace alerts with Logs:**

```javascript
// ✅ OK for triggers
Logger.log('Process finished');
console.log('Process finished');
```

**Or write the status to a cell:**

```javascript
// ✅ OK for triggers
sheet.getRange('A1').setValue('Status: Finished');
```

### 2. Separate Core Logic from UI (Recommended)

This is the cleanest architecture. Split your code into a "Core" function (logic) and a "Manual" function (UI).

```javascript
/**
 * Core function: Contains logic only. No UI calls.
 * Use this for TRIGGERS.
 */
function exportFileListToSheetCore() {
  // Logic to list files, write to sheet, etc.
  console.log('Core logic started...');
  // ...
  console.log('Core logic finished.');
}

/**
 * Wrapper function: Calls core logic and shows UI.
 * Use this for MANUAL execution (buttons/menus).
 */
function exportFileListToSheetManual() {
  exportFileListToSheetCore();
  SpreadsheetApp.getUi().alert('Process Completed!');
}
```

- **Time-driven Trigger**: Set it to run `exportFileListToSheetCore`.
- **Button/Menu**: Assign it to `exportFileListToSheetManual`.

This separation allows you to reuse the same logic for both automation and manual operation without errors.

### 3. Conditional Branching (Advanced)

If you must use a single function for both, wrap the UI call in a `try-catch` block to suppress the error in background contexts.

```javascript
function safeAlert(message) {
  try {
    SpreadsheetApp.getUi().alert(message);
  } catch (e) {
    // If UI is unavailable (trigger execution), log instead
    console.log('Background execution (no UI): ' + message);
  }
}
```

> 【Conclusion】While this works, **Solution 2 (Separation)** is architecturally better and easier to maintain.

## Summary

- 【Conclusion】`getUi()` cannot be used in triggers/automation.
- 【Point】UI is for "Human Operation" only.
- 【Fix】Separate your business logic from your UI logic.

Check your code around the line number reported in the error log (e.g., `mainFunction.gs:2445`) and remove or isolate the `getUi()` call using the methods above.
