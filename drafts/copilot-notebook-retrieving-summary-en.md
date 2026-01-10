---
title: "VS Code Copilot Stuck on 'Retrieving Notebook summary': Why Waiting Won't Fix It"
date: 2026-01-07 12:00:00
categories: 
  - Copilot &amp; VS Code Errors
tags: 
  - GitHub Copilot
  - VS Code
  - Jupyter Notebook
  - Error
  - Troubleshooting
status: publish
slug: copilot-notebook-retrieving-summary-en
featured_image: ../images/202601/image copy 10.png
---

## VS Code Copilot gets stuck on “Retrieving Notebook summary…” when using Jupyter Notebooks

When using GitHub Copilot Chat with Jupyter Notebooks (.ipynb) in VS Code, you may encounter a situation where Copilot displays “Retrieving Notebook summary…” indefinitely and never responds. This article explains why it happens and how to avoid it in practice.

---

### Conclusion

At the moment, GitHub Copilot Chat is not stable when used directly with Jupyter Notebooks. The most reliable workaround is to convert notebooks into Python scripts (.py) and use Copilot on those files instead.

---

### What happens

Typical symptoms include:

* Copilot Chat shows “Retrieving Notebook summary…”
* The message never disappears
* Copilot becomes completely unresponsive

Waiting does not resolve the issue.

---

### Why it happens

Copilot attempts to summarize the entire notebook as context. Large notebooks, image outputs, or extensive cell results can cause the internal summarization process to stall indefinitely.

---

### Practical workaround

Disable Notebook Context if needed, clear all outputs, but for consistent stability, convert notebooks to .py files and split logic across scripts. Copilot performs significantly better on plain Python files.

---

### Final note

Until Copilot’s notebook support matures, separating experimentation (Notebook) and implementation (Python scripts) is the safest development strategy.

---

### Reference Links

1. GitHub Copilot issue: Copilot Chat stuck retrieving notebook summary
   [https://github.com/microsoft/vscode-copilot/issues/1175](https://github.com/microsoft/vscode-copilot/issues/1175)

2. VS Code Jupyter integration documentation
   [https://code.visualstudio.com/docs/datascience/jupyter](https://code.visualstudio.com/docs/datascience/jupyter)

3. GitHub Copilot documentation
   [https://docs.github.com/en/copilot](https://docs.github.com/en/copilot)
