---
title: "What Is tlmgr? A Practical Guide to the TeX Live Package Manager"
categories:
  - PC
tags:
  - LaTeX
  - TeX Live
  - tlmgr
  - TinyTeX
  - CI
status: publish
slug: tlmgr-tex-live-package-manager-en
---

If you work with LaTeX long enough, you will eventually encounter the `tlmgr` command. The name is not especially descriptive, but its role is straightforward.

:::conclusion
`tlmgr` is the package and configuration manager included with TeX Live. Conceptually, it plays a role similar to `pip` for Python, `npm` for Node.js, or `apt` on Debian-based Linux systems, but it manages TeX Live packages rather than the whole operating system.
:::

## What does tlmgr mean?

`tlmgr` is the command-line name of TeX Live Manager. According to the TeX Users Group documentation, it manages packages and configuration options in an existing TeX Live installation.

TeX Live is more than the LaTeX engine itself. It is a distribution containing a large collection of packages, fonts, engines, and helper tools. `tlmgr` is the utility used to manage that collection after installation.

Typical tasks include:

- installing packages
- updating packages
- removing packages
- checking installed packages
- searching for packages or files
- changing TeX Live repository and configuration settings

It is separate from operating-system package managers such as `apt` or Homebrew. `tlmgr` manages TeX Live itself.

## Common tlmgr commands

### Install a package

```bash
tlmgr install geometry
```

You can install several packages at once:

```bash
tlmgr install geometry fancyhdr xcolor
```

A common use case is fixing a LaTeX compilation error caused by a missing `.sty` file.

:::example
If a document requires `geometry.sty`, the corresponding TeX Live package can be installed with:

```bash
tlmgr install geometry
```
:::

### Check for updates

```bash
tlmgr update --list
```

To update `tlmgr` itself and then update installed TeX Live packages:

```bash
tlmgr update --self
tlmgr update --all
```

`--self` updates the TeX Live Manager itself, while `--all` updates the rest of the installed packages that have newer versions available.

### Search for packages or files

```bash
tlmgr search geometry
```

If you know the missing filename but do not know which package provides it, you can search the TeX Live database:

```bash
tlmgr search --global --file t2aenc.def
```

This is useful when a build log identifies a missing file but does not tell you which package to install.

## TinyTeX also uses tlmgr

TinyTeX is a lightweight LaTeX distribution based on TeX Live. Its documentation explicitly presents `tlmgr` as the main command-line tool non-R users need for package management.

TinyTeX is designed around installing only the packages that are actually needed. That makes it a natural fit for adding missing packages later with `tlmgr`.

However, TinyTeX is distributed in multiple bundle sizes. A minimal bundle contains very few packages, while larger prebuilt bundles already contain many commonly used packages.

:::note
The exact set of preinstalled packages depends on the TinyTeX bundle. An explicit `tlmgr install` step is not always necessary.
:::

## Should CI run tlmgr install every time?

LaTeX workflows in GitHub Actions and other CI systems often contain commands such as:

```bash
tlmgr install geometry fancyhdr xcolor
```

This is reliable when the packages are missing, but it can be redundant if the selected TeX Live or TinyTeX bundle already includes them. Each unnecessary installation step adds network traffic and build time.

Consider a generic CI pipeline that compiles a document with XeLaTeX. It may initially install several packages defensively. If testing shows that the selected prebuilt TinyTeX bundle already contains everything required, those extra `tlmgr install` commands can be removed.

:::step
To simplify a CI build, first remove the extra `tlmgr install` step and run a real compilation. If the document builds successfully, the installation was redundant. If the build fails, add only the packages shown as missing in the log.
:::

This approach keeps the environment smaller and reduces work performed on every clean CI runner.

## When tlmgr is not found

If you see `tlmgr: command not found`, or PowerShell reports that `tlmgr` is not recognized, common causes include:

- TeX Live or TinyTeX is not installed
- TeX Live is installed but its binary directory is not on `PATH`
- a CI job calls `tlmgr` before its TeX setup step
- a shell change prevents the updated `PATH` from being inherited

CI environments are especially prone to the last two problems. A setup action may successfully install TeX Live, but a later command can still fail if it runs in a shell that does not see the path modification.

:::warning
Do not immediately reinstall TeX Live just because `tlmgr` is not found. Check `PATH`, the active shell, and the ordering of setup steps first.
:::

## tlmgr vs. apt, Homebrew, pip, and npm

These tools operate at different layers.

| Tool | Main scope |
| --- | --- |
| `apt` | Debian / Ubuntu system packages |
| Homebrew | Applications and CLI tools, mainly on macOS |
| `pip` | Python packages |
| `npm` | Node.js packages |
| `tlmgr` | TeX Live packages and configuration |

If TeX Live was installed through a Linux distribution's package manager, the relationship between system packages and `tlmgr` can be more complicated. Distribution-managed TeX Live installations and upstream TeX Live installations do not always follow the same maintenance model, so mixing package-management methods without checking the platform documentation is best avoided.

## Summary

:::conclusion
The simplest mental model is that `tlmgr` is the package manager for the components used by TeX Live and LaTeX. You may rarely need it during normal writing, but it becomes important when resolving missing `.sty` files, maintaining TinyTeX, or optimizing LaTeX builds in CI.
:::

For everyday use, these three commands cover much of what you need:

```bash
tlmgr install <package>
tlmgr update --list
tlmgr search <keyword>
```

For CI, avoid installing packages "just in case" when your selected TeX Live or TinyTeX bundle already provides them. Test the actual build and install only what is missing.

## References

- [TeX Live 2026 Guide - tlmgr: Managing your installation](https://www.tug.org/texlive/doc/texlive-en/texlive-en.html)
- [tlmgr - the native TeX Live Manager](https://tug.org/texlive/doc/tlmgr.html)
- [TinyTeX documentation](https://yihui.org/tinytex/)
