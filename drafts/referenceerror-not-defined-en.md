---
title: "ReferenceError: xxx is not defined – Causes and Fixes [GAS / JavaScript]"
categories:
  - gastips
featured_image: ../images/2025/image copy 21.png
---

When writing JavaScript or Google Apps Script (GAS), you may encounter an error like this:

```
ReferenceError: xxx is not defined
```

This article explains why this error occurs and how to fix it, with practical patterns that developers commonly encounter. We'll focus on the pain points where GAS and JavaScript beginners often stumble, with solutions to prevent recurrence.

## What Does ReferenceError: xxx is not defined Mean?

In short, this error means:

**"You tried to reference a variable or function that doesn't exist anywhere."**

In JavaScript, you must define variables and functions before using them.

```javascript
console.log(myVar);
```

If `myVar` is not defined at this point, you'll get:

```
ReferenceError: myVar is not defined
```

## Common Cause #1: Forgot to Declare the Variable

This is the most basic mistake.

### Example: Using a variable before declaring it

```javascript
Logger.log(userName);
const userName = "Taro";
```

In JavaScript, you must declare variables before using them.

### How to Fix It

- Always declare variables before using them
- Use `const` or `let`

```javascript
const userName = "Taro";
Logger.log(userName);
```

## Common Cause #2: Referencing Outside the Scope

Variables have a "usable range" called scope.

### Example: Referencing a variable defined inside a function from outside

```javascript
function test() {
  const message = "Hello";
}

Logger.log(message);
```

`message` can only be used inside the `test()` function.

### How to Fix It

- Be aware of variable scope
- If needed, declare variables outside the function

```javascript
const message = "Hello";

function test() {
  Logger.log(message);
}
```

## Common Cause #3: Typos

Simple, but extremely common.

### Example: Spelling mistake in variable name

```javascript
const userName = "Taro";
Logger.log(usrName);
```

`userName` is misspelled as `usrName`.

### How to Fix It

- Type variable names accurately
- Use copy & paste
- Use your editor's autocomplete feature

## Common Cause #4: GAS-Specific "Global Object Confusion"

GAS has different global objects compared to browser JavaScript.

### Example: Trying to use window or document

```javascript
window.alert("test");
document.getElementById("test");
```

GAS doesn't support `window` or `document`.

### How to Fix It

- In GAS, use `Browser.msgBox()` or `HtmlService`
- Don't use browser code directly

```javascript
Browser.msgBox("test");
```

## Common Cause #5: Forgot to Load Library or External Script

When using external libraries or scripts, you need to load them.

### Example: Trying to use jQuery without loading it

```javascript
$('#test').click();
```

`$` (jQuery) is not loaded.

### How to Fix It

- Load required libraries correctly
- In GAS, use the "Libraries" feature

## Common Cause #6: Asynchronous Timing Issues

This happens when you access a variable before it's defined.

### Example: Referencing before data is fetched

```javascript
let data;

fetch(url).then(response => {
  data = response;
});

Logger.log(data);
```

`data` is referenced before `fetch` completes.

### How to Fix It

- Use `await`
- Process inside the callback

```javascript
async function getData() {
  const response = await fetch(url);
  Logger.log(response);
}
```

## Basic Debugging Checklist

When you encounter `ReferenceError: xxx is not defined`, check these points in order:

1. Are the variable or function declared?
2. Are you using it within scope?
3. Are there any typos?
4. Are you using browser-specific objects?
5. Are required libraries loaded?
6. Is the timing of asynchronous operations correct?

These six points solve most cases.

## Best Practices to Prevent This Error

```javascript
// Declare variables before using them
const userName = "Taro";

// Be aware of scope
let globalVar = "global";

function test() {
  let localVar = "local";
  Logger.log(globalVar);
}

// Use editor autocomplete
// Prevent typos
```

## Summary

`ReferenceError: xxx is not defined` means **"you're trying to reference something that doesn't exist."**

In GAS especially, just understanding:

- Scope
- Differences from browsers
- Library loading

can drastically reduce how often this error occurs.

Read the error message carefully and check "which variable is not defined?" – that's the first step to solving it.
