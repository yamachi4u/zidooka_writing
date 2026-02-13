---
title: "What Is the spawn EPERM Error? Causes and Fixes for Codex CLI, npm, and Node.js on Windows"
date: 2026-02-13 18:00:00
categories:
  - AI
tags:
  - Codex CLI
  - Node.js
  - npm
  - Troubleshooting
  - Windows
  - Error
status: publish
slug: spawn-eperm-error-nodejs-windows-en
featured_image: ../images/2026/spawn-eperm-error-thumbnail.png
---

If you have spent any time using Codex CLI or npm on Windows, chances are you have run into the dreaded `spawn EPERM` error. It stops your workflow cold, and the error message itself is not exactly beginner-friendly.

This article breaks down what `spawn EPERM` means, why it happens, and how to fix it step by step.

:::conclusion
`spawn EPERM` means "a child process could not be started because the operating system denied permission." Running as Administrator, adding antivirus exclusions, and clearing npm cache resolves the vast majority of cases.
:::

## What Does spawn EPERM Mean?

`spawn EPERM` is a Node.js internal error code. Breaking it down:

- **spawn**: Node.js is trying to launch a child process (another program)
- **EPERM**: "Error: Operation not PERMitted"

In plain English, the operating system rejected Node.js's attempt to start a program because of insufficient permissions.

A typical error message looks like this:

```
Error: spawn EPERM
    at ChildProcess.spawn (node:internal/child_process:413:11)
    ...
  errno: -4048,
  code: 'EPERM',
  syscall: 'spawn'
```

The `errno: -4048` value is Windows-specific. On macOS or Linux, you may see `-1` instead.

## Common Causes

There are five main reasons this error occurs.

### 1. Antivirus Software Is Locking Files

:::warning
Windows Defender and other antivirus programs can lock files during real-time scanning. When Node.js tries to access those files at the same moment, the OS returns EPERM.
:::

This is the most common cause, especially during `npm install` or Codex CLI sessions where many files inside `node_modules` are being created and accessed rapidly.

### 2. Insufficient Administrator Privileges

If your terminal is running under a standard user account, certain operations (writing to protected directories, installing global packages) may be blocked by Windows.

### 3. Another Process Has the File Locked

VS Code, a running dev server (`npm run dev`), or another terminal window may hold a lock on files that Node.js needs to access.

### 4. Corrupted npm Cache

A damaged npm cache can cause permission errors when npm tries to extract and place package files.

### 5. Codex CLI Sandbox Restrictions

The Codex CLI includes a sandbox feature that restricts child process spawning and file system access for safety. This is a known issue documented in GitHub Issues #7810 and #8343.

## How to Fix It

Try these solutions in order. Most users find that one of the first three fixes resolves the issue.

### Fix 1: Run Your Terminal as Administrator

:::step
1. Search for "PowerShell" or "Windows Terminal" in the Start menu
2. Right-click and select "Run as administrator"
3. Re-run your command in the elevated terminal
:::

```powershell
# In the elevated terminal
npm install
# or
codex --version
```

### Fix 2: Add Antivirus Exclusions for Node.js

:::step
1. Open "Windows Security"
2. Go to "Virus & threat protection" > "Manage settings"
3. Scroll to "Exclusions" and click "Add or remove exclusions"
4. Add the following paths
:::

Recommended exclusion paths:

```
C:\Users\<YourUsername>\AppData\Roaming\npm\
C:\Users\<YourUsername>\AppData\Local\npm-cache\
C:\Users\<YourUsername>\<ProjectFolder>\node_modules\
```

You can also add `node.exe` as a process exclusion for even broader protection.

### Fix 3: Close Programs That May Be Locking Files

:::step
1. Close VS Code or any other editor that has the project open
2. Stop any running dev servers with `Ctrl + C`
3. Re-run the command
:::

```powershell
# Stop the dev server (press Ctrl + C in its terminal)

# If Node.js processes are stuck, force-kill them
taskkill /f /im node.exe
```

:::warning
`taskkill /f /im node.exe` terminates all Node.js processes. Be careful if you have other Node.js tasks running.
:::

### Fix 4: Clear the npm Cache and Reinstall

```powershell
# Clear the npm cache
npm cache clean --force

# Delete node_modules and lock file, then reinstall
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json
npm install
```

### Fix 5: Disable the Codex CLI Sandbox

If you encounter `spawn EPERM` specifically within Codex CLI, the sandbox may be the bottleneck.

```powershell
# Launch with sandbox disabled
codex --sandbox none

# Or set it permanently in your config
# File: %UserProfile%\.codex\config.toml
```

```toml
# ~/.codex/config.toml
sandbox_mode = "danger-full-access"
```

:::warning
Disabling the sandbox gives Codex unrestricted access to your file system and network. Only use this on trusted projects.
:::

### Fix 6: Use WSL2 (Advanced)

For a more permanent workaround on Windows, you can run Codex CLI inside WSL2 (Windows Subsystem for Linux). GitHub Issue #7810 confirms that the error does not occur under WSL2.

```powershell
# Install WSL2 (if not already installed)
wsl --install

# Inside WSL2
wsl
npm install -g @openai/codex
codex
```

## Patterns Specific to Codex CLI

Codex CLI introduces a few unique `spawn EPERM` scenarios beyond typical npm usage.

### Pattern 1: Error Immediately on Startup

```
$ codex --version
Error: spawn EPERM
```

This has been reported with Node.js 24 and NVM on Windows. Switching to an LTS version (20.x or 22.x) often resolves it.

```powershell
# Switch Node.js version via NVM
nvm install 22
nvm use 22
```

### Pattern 2: Error During a Session

When Codex tries to execute a command internally and the sandbox blocks it. Use `--sandbox none` to work around this.

### Pattern 3: IPC Pipe Creation Failure

Inside the sandbox, tools like `tsx` may try to create an IPC (Inter-Process Communication) pipe, which the sandbox denies (Issue #8343). Relaxing the sandbox setting resolves this as well.

## Best Practices to Prevent spawn EPERM

:::note
Building these habits will significantly reduce how often you encounter this error.
:::

1. **Keep projects under `C:\Users\<YourUsername>`** - Avoid `Program Files` or `Windows` directories, which have stricter OS protections
2. **Use Node.js LTS versions** - Current (non-LTS) releases may have Windows compatibility gaps
3. **Configure antivirus exclusions for development directories** - `node_modules` contains thousands of files that trigger constant scanning
4. **Stop dev servers before running npm commands** - This prevents file lock conflicts
5. **Use NTFS-formatted drives** - exFAT and FAT32 do not support symlinks, which npm relies on

## Summary

:::conclusion
The `spawn EPERM` error means "permission denied when trying to start a child process." It is especially common on Windows due to antivirus scanning, file locking, and permission models. Running as Administrator, adding antivirus exclusions, and clearing the npm cache fix most cases. For Codex CLI users, relaxing the sandbox setting is an additional effective solution.
:::

## References
1. Node.js child_process Documentation
https://nodejs.org/api/child_process.html
2. GitHub Issue #7810: Codex CLI spawn EPERM on Windows
https://github.com/openai/codex/issues/7810
3. GitHub Issue #8343: spawn EPERM in sandbox (IPC pipe)
https://github.com/openai/codex/issues/8343
4. npm Documentation: Troubleshooting
https://docs.npmjs.com/common-errors
5. Microsoft: Windows Defender Exclusions
https://support.microsoft.com/en-us/windows/add-an-exclusion-to-windows-security
