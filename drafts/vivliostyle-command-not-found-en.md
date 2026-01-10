---
title: "Troubleshooting “vivliostyle not found” Errors — How to Install and Configure Vivliostyle CLI"
date: 2026-01-02 19:35:00
categories: 
  - Windows / Desktop Errors
  - Uncategorized
tags: 
  - Vivliostyle
  - CLI
  - PowerShell
  - Node.js
  - npm
  - Troubleshooting
status: publish
slug: vivliostyle-command-not-found-en
featured_image: ../images/202601/vivliostyle-error.png
---

### Intro
If you encountered the error:

```
vivliostyle : The term 'vivliostyle' is not recognized as the name of a cmdlet,
function, script file, or operable program.
```

when running `vivliostyle --version`, it means that Vivliostyle CLI is not installed or not in your system’s PATH. This article explains why this happens and how to fix it.

### What is Vivliostyle CLI?
Vivliostyle CLI is a Node.js-based command-line tool for CSS-powered typesetting workflows, including PDF generation and previews. It must be installed with npm.

### Why is the command not found?
Here are the typical reasons:

1.  **Node.js is not installed**
    You need Node.js to run npm and install packages.
2.  **Vivliostyle CLI is not installed globally**
    Running `npm install -g @vivliostyle/cli` registers `vivliostyle` as a CLI command.
3.  **npm global binaries folder isn’t in PATH**
    On Windows, the npm global bin folder must be in your PATH.

### Steps to fix (Windows / PowerShell)

:::step
**Step 1: Install Node.js**
Install Node.js from [nodejs.org](https://nodejs.org).
:::

:::step
**Step 2: Install Vivliostyle CLI**
Run the following command in PowerShell:
```powershell
npm install -g @vivliostyle/cli
```
:::

:::step
**Step 3: Ensure PATH includes npm global bin**
Verify version:
```powershell
vivliostyle --version
```
:::

:::note
**Using npx as alternative**
You can test without installing with:
```powershell
npx @vivliostyle/cli --version
```
:::

### Conclusion
The error simply means the CLI is not recognized by your system. Setting up Node.js and installing Vivliostyle globally will fix it.
