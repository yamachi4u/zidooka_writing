---
title: "TypeError: xxx is not a function – Causes and Fixes [GAS / JavaScript]"
categories:
  - gastips
featured_image: ../images/2025/image copy 21.png
---

When writing JavaScript or Google Apps Script (GAS), you may encounter an error like this:

```
TypeError: xxx is not a function
```

This article explains why this error occurs and how to fix it, with practical patterns that developers commonly encounter. We'll focus on the pain points where GAS and JavaScript beginners often stumble, with solutions to prevent recurrence.

## What Does TypeError: xxx is not a function Mean?

In short, this error means:

**"You tried to call something as a function, but it isn't actually a function."**

In JavaScript, the following syntax calls a function:

```javascript
xxx();
```

However, if `xxx` is not a function but rather some other value (a string, number, array, object, undefined, etc.), this error occurs.

## Common Cause #1: Overwriting a Function with a Variable

This is one of the most frequent causes.

### Example: Defining a variable with the same name as a function

```javascript
function getData() {
  return "data";
}

getData = "hello";
getData();
```

In this case, `getData` has become a string partway through.
When you execute `getData()`, you get:

```
TypeError: getData is not a function
```

### How to Fix It

- Never use the same name for a function and a variable
- Use `const` / `let` to maintain scope awareness

```javascript
const result = getData();
```

## Common Cause #2: Mistaking an Object Property for a Function

### Example: You thought it was a function, but it's a value

```javascript
const user = {
  name: "Taro",
  age: 20
};

user.name();
```

`name` is a string, so it cannot be called as a function.

### How to Fix It

- Before adding `()`, ask yourself "is this really a function?"
- Check the type with logging

```javascript
Logger.log(typeof user.name);
```

## Common Cause #3: Calling Arrays or Objects as Functions

### Example: Calling an array like a function

```javascript
const list = [1, 2, 3];
list();
```

### Example: Calling an object like a function

```javascript
const data = {};
data();
```

Both are typical causes of `xxx is not a function`.

### How to Fix It

- Don't add `()` to arrays or objects
- If you want to call a method, use `.methodName()`

```javascript
list.push(4);
```

## Common Cause #4: GAS-Specific "Return Value Confusion"

In GAS, return values often aren't functions.

### Example: Calling the result of getValues() as a function

```javascript
const values = sheet.getRange("A1:A5").getValues();
values();
```

The return value of `getValues()` is an array.

### How to Fix It

- Check the return type in the GAS official reference
- Verify with `typeof` or `Array.isArray()`

```javascript
Logger.log(Array.isArray(values));
```

## Common Cause #5: Function is Undefined When Called

### Example: An expected function doesn't exist

```javascript
doSomething();
```

In reality, `doSomething` is never defined anywhere, or it's not assigned in a conditional branch.

### How to Fix It

- Add logging before execution
- Verify the function is actually defined

```javascript
Logger.log(typeof doSomething);
```

## Basic Debugging Checklist

When you encounter `TypeError: xxx is not a function`, check these points in order:

1. Is `xxx` actually a function?
2. Have you overwritten it as a variable somewhere?
3. Are you trying to call an array, object, or string?
4. Did you misunderstand the return type?
5. Is it defined outside the current scope?

These five points alone solve most cases.

## Best Practices to Prevent This Error

```javascript
// Separate function names from variable names
const / let for scope awareness

// Be mindful of return types
const result = getData();

// Make type-checking a habit
Logger.log(typeof fn);

// Use guard clauses
if (typeof fn === "function") {
  fn();
}
```

Guard clauses like this are very effective.

## Summary

`TypeError: xxx is not a function` occurs because of **assuming something is a function when it isn't**.

In GAS especially, just being aware of:

- Return value types
- Object structure
- Variable overwriting

can drastically reduce how often this error occurs.

Don't get confused by the error message itself. The key to solving it is calmly asking: "What is this `xxx` that I'm trying to call?"
