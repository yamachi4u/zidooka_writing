---
title: "SyntaxError: Unexpected token – Causes and Fixes [GAS / JavaScript]"
categories:
  - gastips
featured_image: ../images/image copy 21.png
---

When writing JavaScript or Google Apps Script (GAS), you may encounter an error like this:

```
SyntaxError: Unexpected token
```

This article explains why this error occurs and how to fix it, with practical patterns that developers commonly encounter. We'll focus on the pain points where GAS and JavaScript beginners often stumble, with solutions to prevent recurrence.

## What Does SyntaxError: Unexpected token Mean?

In short, this error means:

**"A grammatically unexpected symbol or character was found."**

JavaScript's parser checks the syntax before executing code. When an unexpected symbol (token) appears, this error occurs.

```
SyntaxError: Unexpected token '{'
```

It often shows which specific symbol is problematic.

## Common Cause #1: Forgot to Close Brackets or Parentheses

This is one of the most frequent causes.

### Example: Missing closing brace

```javascript
function test() {
  Logger.log("test");
```

Missing `}`.

### Example: Unclosed parenthesis

```javascript
Logger.log("Hello"
```

Missing `)`.

### How to Fix It

- Use your editor's syntax highlighting
- Use bracket-matching features
- Get in the habit of closing brackets immediately

```javascript
function test() {
  Logger.log("test");
}
```

## Common Cause #2: Comma or Semicolon Mistakes

### Example: Extra comma at the end of an array

```javascript
const list = [1, 2, 3,];
```

The trailing comma can cause problems in some environments (older browsers or certain GAS contexts).

### Example: Missing comma between object properties

```javascript
const user = {
  name: "Taro"
  age: 20
};
```

A comma is needed after `name`.

### How to Fix It

- Check delimiters in objects and arrays
- Don't forget commas

```javascript
const user = {
  name: "Taro",
  age: 20
};
```

## Common Cause #3: Using Reserved Words as Variable Names

JavaScript has reserved words that cannot be used.

### Example: Using reserved words as variable names

```javascript
const function = "test";
const class = "myClass";
```

`function` and `class` are reserved words.

### How to Fix It

- Avoid reserved words
- Use alternatives like `func` or `className`

```javascript
const func = "test";
const className = "myClass";
```

## Common Cause #4: Unclosed String Quotes

### Example: String ending prematurely

```javascript
const message = "Hello World;
Logger.log(message);
```

Missing closing `"`.

### How to Fix It

- Always close strings with matching quotes
- Use editor autocomplete

```javascript
const message = "Hello World";
Logger.log(message);
```

## Common Cause #5: Comment Syntax Errors

### Example: Unclosed multi-line comment

```javascript
/* This is a comment
Logger.log("test");
```

Needs to be closed with `*/`.

### How to Fix It

- Write comments with correct syntax
- Multi-line comments: `/* ... */`
- Single-line comments: `//`

```javascript
/* This is a comment */
Logger.log("test");
```

## Common Cause #6: JSON Syntax Errors

GAS often handles JSON, where syntax errors are common.

### Example: JSON keys without quotes

```javascript
const data = {
  name: "Taro"
};
```

This is a JavaScript object, not JSON. In JSON:

```javascript
const jsonString = '{"name": "Taro"}';
```

### How to Fix It

- When using JSON.parse(), write proper JSON format
- Quote keys as strings

```javascript
const data = JSON.parse('{"name": "Taro"}');
```

## Basic Debugging Checklist

When you encounter `SyntaxError: Unexpected token`, check these points in order:

1. Are all brackets and parentheses properly closed?
2. Are commas and semicolons correctly placed?
3. Are you using reserved words as variable names?
4. Are string quotes closed?
5. Is comment syntax correct?
6. Is JSON syntax correct?

These six points solve most cases.

## Best Practices to Prevent This Error

```javascript
// Use editor syntax highlighting
// Use auto-formatting features

// Close brackets immediately
function test() {
  // Write code here
}

// Don't forget commas
const user = {
  name: "Taro",
  age: 20
};
```

## Summary

`SyntaxError: Unexpected token` means **"there's a grammatically incorrect symbol."**

In GAS especially, just being aware of:

- Matching brackets and parentheses
- JSON syntax
- String quotes

can drastically reduce how often this error occurs.

The error message shows "which symbol is problematic," so carefully checking the code around that symbol is the quickest path to a solution.
