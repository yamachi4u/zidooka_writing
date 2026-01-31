---
title: "OpenCode CLI (TUI) Not Launching? Fixing the \"GUI Opens Instead\" Issue on Windows"
slug: "opencode-cli-tui-troubleshooting-windows-en"
status: "publish"
categories: 
  - "AI"
  - "Troubleshooting"
tags: 
  - "OpenCode"
  - "CLI"
  - "TUI"
  - "Windows"
  - "PATH"
featured_image: "../images-agent-browser/opencode-official.png"
---

# OpenCode CLI (TUI) Not Launching? Fixing the "GUI Opens Instead" Issue on Windows

![OpenCode Official Site](../images-agent-browser/opencode-official.png)

If you've installed OpenCode on Windows and are puzzled why the terminal command isn't working as expected, you're not alone. I encountered a situation where the CLI wouldn't start, or worse, it opened the GUI instead. Here is how to fix it.

**Key Takeaway:** To use the Terminal User Interface (TUI), you must run the `opencode-cli` command, not `opencode`. You might also need to add it to your PATH manually.

## 🚨 The Issue

After running the `.exe` installer on Windows, I faced two main problems:

1.  **Command Not Found**: Typing `opencode` in the terminal resulted in an error.
2.  **Wrong Mode**: Even after the command was recognized, it launched the GUI window instead of the expected TUI (text-based interface inside the terminal).

## 🔍 The Cause

Upon investigation, I found two causes:

-   **PATH Not Set**: The installer didn't automatically add the executable's location to the system `PATH` variable.
-   **Different Executables**: The installation folder contains two distinct executables for different purposes:
    -   `OpenCode.exe`: The GUI version (opens a standard window).
    -   `opencode-cli.exe`: The TUI version (runs inside the active terminal).

Running just `opencode` often triggers the GUI version or fails if the path isn't set.

## ✁EThe Solution

You don't need to reinstall. Just follow these steps:

### 1. Add to PATH
Locate the OpenCode installation folder and add that path to your user environment variables (`PATH`). This ensures you can call the command from any directory.

### 2. Use the Correct Command
This is the critical part. Distinguish between the commands:

-   **To open GUI:**
    ```powershell
    opencode
    ```
-   **To run TUI (Terminal):**
    ```powershell
    opencode-cli
    ```

**Action:** If you want the terminal experience, always execute `opencode-cli`.

This simple switch should allow you to run OpenCode smoothly from your Windows terminal.

