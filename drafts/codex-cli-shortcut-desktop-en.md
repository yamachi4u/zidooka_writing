---
title: How to Launch OpenAI Codex CLI Instantly from Desktop (Shortcut Guide)
slug: codex-cli-shortcut-desktop-en
date: 2026-01-22
category: AI
tags: [Codex, OpenAI, PowerShell, Windows, CLI]
---

If you are using **OpenAI Codex** via the CLI (Command Line Interface), having to open a terminal and type the command manually every time can be a hassle. This guide shows you how to create a desktop shortcut that launches Codex instantly in a ready-to-use environment.

We recommend using the **PowerShell** method for better stability and configuration options.

> **Note:** This article specifically targets OpenAI Codex users. For a general guide on creating shortcuts for ANY CLI tool (npm, python scripts, etc.), please see our general guide: [Launch CLI Tools Instantly from Your Desktop](https://www.zidooka.com/archives/3524).

## The Best Way: PowerShell Shortcut

This method is cleaner because it keeps the actual script hidden in your Documents folder, leaving only a neat shortcut on your desktop.

### One-Step Setup Script

You can set this up instantly by running the following commands in PowerShell. This script automatically:

1.  Creates a hidden directory for the script (`Documents\_tools\codex`).
2.  Generates a `.ps1` launcher script configured for Codex.
3.  Creates a shortcut on your Desktop (`Codex.lnk`).

Copy and paste the following block into your PowerShell terminal:

```powershell
# Destination (Kept out of sight)
$baseDir = Join-Path $HOME "Documents\_tools\codex"
$scriptPath = Join-Path $baseDir "run-codex.ps1"

# Create Directory
New-Item -ItemType Directory -Force -Path $baseDir | Out-Null

# Create the launcher ps1
@'
# Set your preferred working directory here
Set-Location "$HOME"
# Run Codex
codex
'@ | Set-Content -Encoding UTF8 $scriptPath

# Create the Desktop Shortcut
$desktop = [Environment]::GetFolderPath("Desktop")
$shortcutPath = Join-Path $desktop "Codex.lnk"

$wsh = New-Object -ComObject WScript.Shell
$shortcut = $wsh.CreateShortcut($shortcutPath)
$shortcut.TargetPath = "powershell.exe"
# -NoExit keeps the window open so you can interact with Codex
$shortcut.Arguments = "-NoExit -ExecutionPolicy Bypass -File `"$scriptPath`""
$shortcut.WorkingDirectory = $HOME
$shortcut.IconLocation = "$env:SystemRoot\System32\WindowsPowerShell\v1.0\powershell.exe"
$shortcut.Save()

Write-Host "Done: Codex shortcut created on Desktop."
```

### What Happens Next?

1.  **Paste & Run:** You might see a warning about pasting multiple lines. Click "Paste anyway".
    ![PowerShell Execution](../images/202601/image%20copy%2019.png)
2.  **Check Desktop:** A new shortcut named **Codex** will appear.
    ![Desktop Icon](../images/202601/image%20copy%2021.png)
3.  **Launch:** Double-click it. PowerShell will open, Codex will start, and the window will stay open (`-NoExit`) for your session.

## Alternative: CMD (Batch File)

If you prefer a simple batch file, create a text file named `RunCodex.bat` on your desktop with the following content:

```batch
@echo off
cd /d "%USERPROFILE%"
call codex
cmd /k
```

For most users, the PowerShell method is superior as it handles modern Windows environments better.
