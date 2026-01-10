---
id: 2874
title: "Why Copy-Pasting Rhino-Era Code is Dangerous: 5 \"Old GAS Styles\" That Break in V8 [For Beginners]"
date: 2025-12-23
thumbnail: images/2025/gas-rhino-deprecation-warning.png
categories: 
  - GAS Tips
slug: "gas-rhino-dangerous-code-examples-en"
---

When researching Google Apps Script (GAS), you might copy-paste code from blogs, Stack Overflow, or old documentation.

However, you need to be careful.

That code might be written in the **Rhino era style**.
And **Rhino support will end on January 31, 2026**.
In V8, there are writing styles that "will break if left as is".

In this article, I will explain 5 Rhino-era writing styles that are likely to break when switching to V8, including "why it's bad" and "how to fix it".

## Premise: Why is "Rhino-era code" dangerous?

:::note
Rhino had:

*   Old JavaScript specifications
*   Unique behaviors (deviating from current standards)
*   Many writing styles that "just worked" loosely

On the other hand, V8 is:

*   Strict about JavaScript standard specifications
*   Stops ambiguous code as errors
*   Works with the same feeling as a browser (Chrome)
:::

In other words,

**Code that "happened to work" in Rhino**
**will "normally result in an error" in V8.**

## Bad Example 1: Using `for each ... in`

### ❌ Common code in the Rhino era

:::example
```javascript
for each (var row in values) {
  Logger.log(row);
}
```
:::

### Why is it bad?

`for each ... in` is not a standard JavaScript specification.
It is a Rhino-specific extension.

👉 **In V8, it is completely unsupported, so it causes an error.**

### ✅ Correct writing in V8

:::example
```javascript
for (var row of values) {
  console.log(row);
}
```

Or

```javascript
values.forEach(function(row) {
  console.log(row);
});
```
:::

## Bad Example 2: Scope-dependent code assuming `var`

### ❌ Code common in the Rhino era

:::example
```javascript
for (var i = 0; i < 5; i++) {
  setTimeout(function() {
    Logger.log(i);
  }, 1000);
}
```
:::

### What happens?

In Rhino, there were cases where it worked "somewhat as expected", but
in V8, the behavior becomes clear, such as **all outputting the same value (5)**.

### Why is it bad?

`var` has function scope.
In V8, this behavior is evaluated more strictly, and bugs become apparent.

### ✅ Correct answer in V8

:::example
```javascript
for (let i = 0; i < 5; i++) {
  setTimeout(function() {
    console.log(i);
  }, 1000);
}
```
:::

👉 **In V8, using `let` / `const` is the standard.**

## Bad Example 3: Using reserved words for variable names

### ❌ Example that works in Rhino

:::example
```javascript
var class = 'test';
var default = 1;
```
:::

### Why is it bad?

`class` and `default` are JavaScript reserved words.

*   Rhino: Silently allowed it
*   V8: **Immediate error**

### ✅ Fix example in V8

:::example
```javascript
var className = 'test';
var defaultValue = 1;
```
:::

👉 **Do not use reserved words for variable names.**
Older articles often contain this landmine.

## Bad Example 4: Using `Date.getYear()`

### ❌ Code common in the Rhino era

:::example
```javascript
var year = new Date().getYear();
```
:::

### What is bad?

`getYear()` is a method with quite quirky specifications.

*   2025 → returns `125`
*   Often used without noticing in Rhino
*   In V8, it works "according to spec", so the year becomes strange.

### ✅ Correct writing

:::example
```javascript
var year = new Date().getFullYear();
```
:::

👉 This is a representative example where actual harm is likely to occur in V8 migration.

## Bad Example 5: Implicit global variables

### ❌ Code that passed in Rhino

:::example
```javascript
function test() {
  x = 10;
}
```
:::

### Why is it bad?

Variables without `var` / `let` / `const` are:

*   Rhino: Treated as implicit globals
*   V8: Error or unexpected behavior

### ✅ Correct answer in V8

:::example
```javascript
function test() {
  let x = 10;
}
```
:::

👉 **In V8, "undeclared variables" are a source of accidents.**

## Why "Copy-Paste Code" is the Most Dangerous

1.  The person who wrote it doesn't understand the behavior
2.  It's hard to tell if it's written assuming Rhino
3.  You assume "it's OK because it's working"

:::warning
And the moment you switch to V8,

*   Errors occur
*   Triggers stop
*   Automated processes go silent

**Accidents surface** in these forms.
:::

## ZIDOOKA! Summary

:::conclusion
*   Rhino-era code "working" does not mean it is "correct"
*   V8 is faithful to JavaScript standards

Especially dangerous are:

1.  `for each`
2.  `var` dependency
3.  Reserved word variables
4.  `getYear()`
5.  Implicit globals

V8 migration often doesn't require rewriting code, but
**you should always suspect "old copy-pastes".**
:::

---

**【Summary Article】**
Here is the complete guide summarizing all information about "Rhino Deprecation / V8 Migration" including this article.
👉 **[Complete Guide to GAS Rhino Deprecation & V8 Migration](https://www.zidooka.com/archives/2880)**
