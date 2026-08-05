---
title: "How to Paste Screenshot File Paths (Not Image Data) into AI Coding Agents on Windows"
categories:
  - AI
tags:
  - Windows
  - ShareX
  - AI Tools
  - Productivity
  - vibe-coding
  - Workflow
status: publish
slug: windows-screenshot-path-ai-agent-en
---

When working with AI coding tools like Codex, OpenCode, or Claude, you often want to show them a screenshot. But on Windows, screenshots get copied to the clipboard as **image data** — not a file path. CLI-based AI agents can't read raw image data, so they can't access the file.

## The Problem

Windows screenshots (Win+Shift+S, PrintScreen) copy bitmap image data to the clipboard. When you paste into an AI tool, you see the image in the chat UI. But CLI agents like Codex or OpenCode need a **file path** (e.g., `C:\Users\...\screenshot.png`) so they can open and read the file directly.

The ideal flow:

1. Take a screenshot
2. Automatically save it as a file
3. Copy the **file path** to clipboard (not the image data)
4. Paste into AI tool — the path text tells the agent where the file is

## Solution: Use ShareX

The easiest solution is **ShareX**, a free open-source screenshot tool. It has a built-in "Copy file path to clipboard" feature.

### Setup

1. Download ShareX from [getsharex.com](https://getsharex.com) and install
2. Right-click the ShareX icon in the system tray → **Task settings** (gear icon)
3. Go to the **After capture** tab and check:
   - ☑ **"Save image to file"**
   - ☑ **"Copy file path to clipboard"** ← the key feature
   - ☐ **"Copy image to clipboard"** (uncheck this)
4. Go to **"Keyboard shortcuts"** and assign a hotkey (e.g., PrintScreen → "Region capture")

:::note
Make sure "Save image to file" and "Copy file path to clipboard" are both enabled. If "Copy image to clipboard" is left checked, some apps may paste the image data instead of the path, depending on the target application.
:::

### Usage

Press your hotkey, select a region. ShareX automatically:

1. Saves a PNG to your chosen folder
2. Copies the full file path to clipboard (e.g., `C:\Users\You\Documents\ShareX\Screenshots\2026-07-11_14-30-00.png`)

Just Ctrl+V into your AI tool. The path appears as text, and the agent reads the file directly.

### Customizing Filenames

In Task settings > Save tab, you can change the filename pattern:

- `%yyyy-%mm-%dd_%hh-%mm-%ss.png` → `2026-07-11_14-30-00.png`
- `screenshot_%yy%mm%dd_%hh%mm%ss.png` → `screenshot_260711_143000.png`

## Alternative: PowerShell + AutoHotkey

If you'd rather not install ShareX, you can use a standalone PowerShell script. Save this as `save-clipboard-image.ps1`:

```powershell
Add-Type -AssemblyName System.Windows.Forms, System.Drawing
$img = [System.Windows.Forms.Clipboard]::GetImage()
if ($img) {
    $dir = "$env:USERPROFILE\Desktop\Screenshots"
    if (!(Test-Path $dir)) { New-Item -ItemType Directory -Path $dir -Force | Out-Null }
    $path = Join-Path $dir "screenshot_$(Get-Date -Format 'yyyy-MM-dd_HH-mm-ss').png"
    $img.Save($path, [System.Drawing.Imaging.ImageFormat]::Png)
    $img.Dispose()
    Set-Clipboard $path
}
```

To trigger it with a hotkey, use AutoHotkey v2:

```autohotkey
^+s::RunWait("powershell -NoProfile -STA -File C:\path\to\save-clipboard-image.ps1")
```

Pressing Ctrl+Shift+S saves the clipboard image to `Desktop\Screenshots\` and copies the path back to clipboard.

:::note
`[System.Windows.Forms.Clipboard]::GetImage()` requires PowerShell to run in **STA mode** (`-STA` flag). PowerShell 7+ defaults to MTA, so always include `-STA`.
:::

That said, ShareX is much easier to set up and more reliable.

## What About PowerToys Advanced Paste?

Microsoft PowerToys "Advanced Paste" (Win+Shift+V) has "Paste as file", but it pastes a **file object** into File Explorer, not a text path. It doesn't help with AI agents that need a text file path.

## Summary

| Method | Ease of Setup | Reliability |
|--------|--------------|-------------|
| **ShareX** (recommended) | ★★★ Easy | ★★★ Reliable |
| PowerShell + AutoHotkey | ★★ Moderate | ★★ Good |
| PowerToys Advanced Paste | — Not suitable | — |
| Windows Snipping Tool | — Not supported | — |

If you work with AI coding agents, just install ShareX, check "Copy file path to clipboard", and you're done.
