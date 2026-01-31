---
title: "OpenCodeアップデート後に TypeError: getComputedStyle のエラーが出た件（再起動で直ったログ）"
slug: "opencode-update-getcomputedstyle-typeerror-jp"
status: "publish"
categories:
  - "AI"
  - "便利ツール"
tags:
  - "OpenCode"
  - "TypeError"
  - "getComputedStyle"
  - "Tauri"
  - "Troubleshooting"
---

# OpenCodeアップデート後に TypeError: getComputedStyle のエラーが出た件（再起動で直ったログ）

OpenCode をアップデートした直後（2026-01-17）に、以下のエラーが出ました。

```text
TypeError: Failed to execute 'getComputedStyle' on 'Window': parameter 1 is not of type 'Element'.
TypeError: parameter 1 is not of type 'Element'.
    at getComputedStyle$1 (http://tauri.localhost/assets/index-*.js:...)
    at isOverflowElement (...)
    at getOverflowAncestors (...)
    at autoUpdate (...)
```

【結論】この件は OpenCode を一度リスタート（アプリ再起動）したら消えました。

現時点では再現条件が不明なので、同じ表示になったらまず再起動を試す、というログとして残しておきます。
