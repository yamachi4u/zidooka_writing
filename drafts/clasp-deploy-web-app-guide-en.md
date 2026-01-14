---
title: "[GAS] Stop Just Pushing: Automate Web App Releases with 'clasp deploy'"
slug: gas-clasp-deploy-web-app-en
date: 2026-01-14 10:00:00
categories: 
  - GAS
  - GAS Tips
  - ツール集
tags: 
  - Google Apps Script
  - clasp
  - deploy
  - WebApp
status: publish
featured_image: ../images/clasp-deploy-guide.png
---

Many developers use `clasp` for Google Apps Script (GAS) development, but most stop at **`clasp push`**.

I used to be one of them, until I realized that **`clasp deploy`** allows you to **create deployments and get Deployment IDs entirely from the CLI**.

Stop clicking the "New Deployment" button in your browser. It's time to automate.

## What `clasp deploy` Can Do

Essentially, it performs the same action as the "New Deployment" button in the GAS editor, but from your terminal.

1. Create a new Deployment (Production environment).
2. Retrieve the Deployment ID immediately.
3. Publish a versioned snapshot.

### Basic Commands

```bash
# Upload your code
clasp push

# Create a new version (Snapshot)
clasp version "Ver 1.0.0 Release"

# Deploy that specific version
clasp deploy -V 1 -d "Initial Deploy"
```

Just like that, a new Web App URL or API endpoint is generated instantly.

## Does the Web App URL Change?

This is the most critical point.

### `clasp deploy` = Create NEW (URL Changes)

Every time you run `clasp deploy`, **a new Deployment ID is generated.**
This means your Web App URL (`.../exec`) will change every time.

- **Use Case**: Creating disposable test environments or issuing unique URLs for different clients.

### `clasp redeploy` = UPDATE (URL Stays)

If you want to keep the existing Web App URL but update the content to a new version, use **`clasp redeploy`**.

```bash
# Update an existing deployment ID to a new version
clasp redeploy <DeploymentID> -V <NewVersionNumber>
```

If you don't use this, you'll end up in dependency hell, having to notify your users of a new URL every time you fix a bug.

## Practical Workflow

Here is the robust workflow for CLI-only management:

### 1. Initial Deployment (Issue URL)

```bash
clasp push
clasp version "First Release"
clasp deploy -V 1 -d "Production"
# Write down the Deployment ID output here!
```

### 2. Update Deployment (Keep URL)

```bash
clasp push
clasp version "fix bug"
# → Created version 2

# Apply Version 2 to the existing ID
clasp redeploy <DeploymentID> -V 2
```

## Don't Forget `appsscript.json`

To publish as a Web App, defining the `webapp` configuration in your local `appsscript.json` is mandatory before deploying.

```json
{
  "timeZone": "Asia/Tokyo",
  "dependencies": {},
  "webapp": {
    "access": "ANYONE",
    "executeAs": "USER_DEPLOYING"
  },
  "exceptionLogging": "STACKDRIVER"
}
```

With this, the moment you run `clasp deploy`, a publicly accessible Web App is born.

## Summary

- 【Conclusion】Don't just `push`. Use `version` → `deploy` to fully automate releases.
- 【Caution】`clasp deploy` generates a new URL every time. Use `clasp redeploy` to keep the same URL.
- 【Point】Manage Web App settings (permissions) via `appsscript.json`.

This opens up the possibility of full CI/CD pipelines for your Google Apps Script projects!
