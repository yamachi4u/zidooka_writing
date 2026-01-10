---
title: "Fix \"ModuleNotFoundError: No module named 'plotly'\" in Python - Even after pip install"
date: 2025-12-22 10:00:00
categories: 
  - Python Errors
tags: 
  - Python
  - plotly
  - ModuleNotFoundError
  - Troubleshooting
status: publish
slug: plotly-module-not-found-en
featured_image: ../images/2025/image copy 36.png
---

When trying to use the **plotly** data visualization library in Python, you might encounter the following error that stops your script:

```
ModuleNotFoundError: No module named 'plotly'
```

"Wait, I just ran `pip install plotly`!"
"It says `Requirement already satisfied`, but it still won't work!"

If you are facing this situation, you are not alone.
While this error can mean you simply forgot to install it, in many cases, it means **the place where you installed it** and **the place where you are running it** are different.

In this article, we will explain the **real cause** of this error and **how to fix it** for different environments.

## Conclusion: Why does this error occur?

Here is the conclusion. 90% of the time, the cause is this:

**The Python you used with `pip` and the Python running your code are different.**

You likely have multiple versions of Python installed on your PC, and:
- You ran `pip install` on Python A.
- You ran `import plotly` on Python B.

This "mismatch" is very common, especially if you use **VS Code**, **Anaconda**, or **Jupyter Notebook**.

---

## 1. The Basics: Check Installation

If you haven't installed it at all yet, simply installing it will fix it.

### Run in Terminal (Command Prompt)
```bash
pip install plotly
```

If you need to specify Python 3 on Mac or Linux:
```bash
pip3 install plotly
```

After installation, run the following code. If no error appears, you are good to go.
```python
import plotly
print("plotly is installed")
```

If this doesn't fix it, read on.

---

## 2. Error persists even after "pip install"

If you get `Requirement already satisfied` but still see `ModuleNotFoundError` when running your script, it is an **environment mismatch**.

### How to check: Verify Python and pip paths

Run these commands in your terminal to compare the paths.

**Mac / Linux:**
```bash
which python
which pip
```

**Windows:**
```powershell
where python
where pip
```

If these two paths point to completely different folders, that's the cause.
For example, "pip is installing to Anaconda's Python, but you are running the standard Python."

### The Fix
The most reliable way is to specify the full path of the Python executable you want to use.

```bash
# Example: Install to the Python that the 'python' command refers to
python -m pip install plotly
```
By typing `python -m pip`, you ensure that `pip` runs for "the exact Python that runs when you type `python`".

---

## 3. Error in VS Code

In VS Code, you can select the **Python Interpreter** from the bottom right of the screen (or Command Palette).

1. Press `Ctrl + Shift + P` (Mac: `Cmd + Shift + P`).
2. Type `Python: Select Interpreter` and select it.
3. Choose the **Python environment where you installed plotly**.

A common trap is installing it globally, but VS Code is using a virtual environment (venv) it created automatically.

---

## 4. Error in Jupyter Notebook

Jupyter Notebook (or JupyterLab) often runs on a kernel independent of your terminal.
"I ran `pip install` in the terminal but it doesn't work in Notebook" is caused by this.

### The Fix: Install inside a Notebook Cell

The most reliable method is to run the installation command **inside a Notebook cell**.

```python
import sys
!{sys.executable} -m pip install plotly
```

If you just write `!pip install plotly`, it might use a different pip than the one the Notebook kernel is using.
Using `{sys.executable}` forces the installation into **"the exact Python running this Notebook"**.

---

## 5. Forgot to Activate Virtual Environment (venv)

If you use `venv` for your projects, forgetting to activate it will make your shell use the global environment.

**Windows:**
```powershell
.\venv\Scripts\activate
pip install plotly
```

**Mac / Linux:**
```bash
source venv/bin/activate
pip install plotly
```

Make sure you see `(venv)` at the beginning of your terminal line before installing.

---

## Summary

If you see `ModuleNotFoundError: No module named 'plotly'`, don't panic. Check these steps:

1. **Run `pip install plotly` first.**
2. **If that fails, run `python -m pip install plotly`.**
3. **In VS Code, check the selected Interpreter.**
4. **In Jupyter, run `!{sys.executable} -m pip ...`.**

When "it should be there but it's not," suspecting **"where did I put it?"** is the shortcut to the solution.
