---
title: "Auto-start OpenCode Web at Login on Windows (Fixed Port)"
date: 2026-01-26 13:00:00
categories:
  - Windows
tags:
  - PowerShell
  - CLI
  - Tooling
  - Automation
status: publish
slug: opencode-web-autostart-windows-en
---

This guide shows how to auto-start `opencode web` at login so it always runs on a fixed port. It keeps the workflow in your browser while the CLI handles the server.

## Why this is worth doing

- You can use your normal browser tabs and notifications.
- Local file operations stay available inside the web UI.
- It avoids heavyweight GUI apps if you prefer a lighter flow.

![OpenCode web in the browser](../images/opencode-web-browser.png)

## Prerequisite

Confirm the CLI binary exists. The GUI app (`OpenCode.exe`) is separate from the CLI (`opencode-cli.exe`).

```powershell
Test-Path "$env:LOCALAPPDATA\OpenCode\opencode-cli.exe"
```

If it returns `True`, you are ready.

## Auto-start at login (Startup folder)

1. Press `Win + R` and run `shell:startup`.
2. Right-click the folder → New → Shortcut.
3. For the location, paste the command below.

```text
"C:\Users\user\AppData\Local\OpenCode\opencode-cli.exe" web --port 4096 --hostname 127.0.0.1
```

4. Name it `opencode-web` or any label you like.

![Shortcut wizard](../images/opencode-web-shortcut-wizard.png)

This launches the server at login. To use a different port, replace `--port 4096` with your preferred number.

## Bookmark the URL

With a fixed port you can bookmark the page and open it instantly.

```text
http://127.0.0.1:4096/
```

## If it does not start

If another process is already using the port, OpenCode will fail to bind.

```powershell
Get-NetTCPConnection -LocalPort 4096 | Format-Table -AutoSize
```

If a process appears, switch to another port and update both the shortcut and the bookmark URL.

## Disable auto-start

Remove the shortcut from the `shell:startup` folder.
