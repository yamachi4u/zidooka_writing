---
title: "After Updating OpenCode: TypeError 'getComputedStyle' (Resolved by Restart)"
slug: "opencode-update-getcomputedstyle-typeerror-en"
status: "publish"
categories:
  - "AI"
  - "Windows / Desktop Errors"
tags:
  - "OpenCode"
  - "TypeError"
  - "getComputedStyle"
  - "Tauri"
  - "Troubleshooting"
  - "Windows"
---

# After Updating OpenCode: TypeError 'getComputedStyle' (Resolved by Restart)

Right after updating OpenCode (2026-01-17), I saw the following error:

```text
TypeError: Failed to execute 'getComputedStyle' on 'Window': parameter 1 is not of type 'Element'.
TypeError: parameter 1 is not of type 'Element'.
    at getComputedStyle$1 (http://tauri.localhost/assets/index-*.js:...)
    at isOverflowElement (...)
    at getOverflowAncestors (...)
    at autoUpdate (...)
```

【結論】In my case, restarting OpenCode once (closing and re-launching the app) made the error disappear.

I do not have stable repro steps yet, so I am keeping this as a short incident log.
