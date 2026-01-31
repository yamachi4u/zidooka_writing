---
title: "Add VS Code to Windows Right-Click Menu with a Single PowerShell Command"
date: 2026-01-20 12:00:00
categories: 
  - PC
tags: 
  - VSCode
  - PowerShell
  - Windows
  - Registry
status: draft
slug: vscode-right-click-powershell-en
featured_image: ../images/vscode-right-click-menu.png
---

You can add "Open with VS Code" to your Windows Explorer right-click menu by simply running a PowerShell command. No manual registry editing required.

## What This Does

Adds "Open with VS Code" to the Explorer context menu for files and folders.

![VS Code in right-click menu](https://www.zidooka.com/wp-content/uploads/2025/02/image-3.png)

:::note
For manual registry editing instructions, see:
[How to Open VS Code from Windows Right-Click Menu](https://www.zidooka.com/archives/209)
:::

## The Command (Copy & Paste)

Open PowerShell and paste the following command:

```powershell
# Dynamically detect VS Code path (search PATH → fallback to default location)
$codePath = (Get-Command code -ErrorAction SilentlyContinue).Source
if ($codePath) {
    # Resolve Code.exe path from code.cmd
    $code = (Get-Item $codePath).Directory.Parent.FullName + "\Code.exe"
} else {
    # Fallback: standard install path
    $code = "$env:LOCALAPPDATA\Programs\Microsoft VS Code\Code.exe"
}

# Verify existence
if (-not (Test-Path $code)) {
    Write-Host "Error: VS Code not found at: $code" -ForegroundColor Red
    return
}
Write-Host "VS Code path: $code" -ForegroundColor Cyan

# Add to file context menu
New-Item -Path "HKCU:\Software\Classes\*\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\*\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%1`""

# Add to folder context menu
New-Item -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\Directory\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%V`""

# Add to folder background context menu
New-Item -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%V`""

Write-Host "Done! Restart Explorer to apply changes." -ForegroundColor Green
```

Restart Explorer (or close and reopen it) to see the changes.

## Benefits

- **No admin rights required** - Writes to user registry (HKCU)
- **Auto-detects install location** - Finds VS Code from PATH automatically
- **No manual path entry** - Uses environment variables
- **Icon included** - Shows VS Code icon in the menu

## To Remove

Run this command to remove the menu entries:

```powershell
Remove-Item "HKCU:\Software\Classes\*\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item "HKCU:\Software\Classes\Directory\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "Removed!" -ForegroundColor Green
```

## How It Works

This command adds entries to the Windows registry:

| Registry Path | Target |
| --- | --- |
| `HKCU:\Software\Classes\*\shell\VSCode` | Right-click on files |
| `HKCU:\Software\Classes\Directory\shell\VSCode` | Right-click on folders |
| `HKCU:\Software\Classes\Directory\Background\shell\VSCode` | Right-click on empty space in folder |

:::note
`HKCU` (HKEY_CURRENT_USER) is the per-user registry hive. No admin rights needed, but settings apply only to the current user.
:::

## Summary

:::conclusion
A single PowerShell command adds VS Code to your right-click menu. No need to manually navigate the registry editor, reducing the risk of mistakes.
:::

## Related

- [How to Open VS Code from Windows Right-Click Menu](https://www.zidooka.com/archives/209) - Manual setup guide
