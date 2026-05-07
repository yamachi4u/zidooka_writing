---
title: "Windows Terminal Can Split Panes Too: Alt+Shift++ for Vertical, Alt+Shift+- for Horizontal"
categories:
  - PC
tags:
  - Windows Terminal
  - PowerShell
  - Windows
  - Shortcuts
status: publish
slug: windows-terminal-pane-split-shortcuts-en
featured_image: ../images/2026/03/windows-terminal-pane-split-20260325.png
---

Many people use Windows Terminal as a tabbed terminal and stop there. However, pane splitting is built in, and once you start using it, it becomes much easier to compare logs, keep one shell for commands, and another for output in the same tab.

In short, you can split panes immediately with the default shortcuts: `Alt+Shift++` for a vertical split and `Alt+Shift+-` for a horizontal split.

## Shortcuts worth remembering

| Action | Default shortcut | What it does |
| --- | --- | --- |
| Vertical split | `Alt+Shift++` | Opens a new pane to the right |
| Horizontal split | `Alt+Shift+-` | Opens a new pane below |
| Move between panes | `Alt` + arrow keys | Moves focus to a neighboring pane |
| Resize a pane | `Alt+Shift` + arrow keys | Resizes the focused pane |
| Close a pane | `Ctrl+Shift+W` | Closes the focused pane |

One small point of confusion: in Microsoft’s terminology, a “vertical split” means a vertical divider appears, so the new pane opens on the right. A “horizontal split” means a horizontal divider appears, so the new pane opens below.

## What it looks like

Here is a simple example with PowerShell split into two side-by-side panes inside the same tab.

![Windows Terminal with PowerShell split into two side-by-side panes](../images/2026/03/windows-terminal-pane-split-20260325.png)

This is often more practical than opening more tabs. You can keep your working shell on one side and run commands like `git status`, `npm run dev`, or other checks on the other side without constantly switching context.

## When this is actually useful

- You want to work on the left and monitor logs on the right.
- You want a server process on top and an input shell below.
- You want two views of the same project without flipping through tabs.
- You want to compare PowerShell and Command Prompt behavior side by side.

The main benefit is not just saving clicks. It is reducing context loss. You can see both outputs at once and stop losing track of which tab was doing what.

## You can also split with the mouse

If you do not remember the shortcuts, hold `Alt` and click the new tab button. Windows Terminal will auto-split the current tab. Keyboard shortcuts are faster, but this is an easy way to start using the feature.

## A few extra pane controls

After splitting, `Alt` + arrow keys lets you move focus between panes, so navigation stays fast. `Alt+Shift` + arrow keys lets you resize the selected pane, which is useful if you want a narrow log pane and a wider working pane.

If you forget a shortcut, open the command palette with `Ctrl+Shift+P` and search for pane-related commands there.

## Conclusion

:::conclusion
Windows Terminal supports pane splitting out of the box. If you remember `Alt+Shift++` and `Alt+Shift+-`, you can do parallel terminal work inside a single tab much more comfortably.
:::

References:
1. Panes in Windows Terminal
https://learn.microsoft.com/en-us/windows/terminal/panes
2. Command line arguments in Windows Terminal
https://learn.microsoft.com/en-us/windows/terminal/command-line-arguments
