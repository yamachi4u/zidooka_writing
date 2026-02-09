---
title: "Complete Guide: Fixing gogcli Auth Issues from OAuth Setup to Test Users"
date: 2026-02-02 12:00:00
categories:
  - Uncategorized
tags:
  - gogcli
  - Google Cloud
  - OAuth
  - Troubleshooting
status: draft
slug: gogcli-oauth-setup-flow-en
---

## Problem: Can't Run gog auth login

After installing gogcli (Google CLI tool), running `gog auth login` shows this error:

> OAuth credentials not configured. Run: gog auth credentials <file>

## Solution 1: Configure OAuth Credentials

You need to set up the OAuth client JSON file obtained from Google Cloud Console.

### Steps

1. Create an OAuth client for "Desktop app" in [Google Cloud Console](https://console.cloud.google.com/)
2. Download the JSON file
3. Configure it with this command:

```bash
gog auth credentials ~/Downloads/client_secret_xxxx.json
```

## Next Problem: Access Blocked Error

After configuring OAuth, running `gog auth login` again shows a different error:

> Access blocked: gog-cli-auth has not completed the Google verification process  
> This app is currently in testing mode and can only be accessed by testers approved by the developer.

![Error Screen](../images/gogcli-oauth-error.png)

## Solution 2: Add Test Users

You need to add your email address as a **test user** in Google Cloud Console.

### Steps

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to **APIs & Services** → **OAuth consent screen**
3. Confirm the app is in **Testing** mode
4. Scroll down to the **Test users** section
5. Click **Add users**
6. Enter your email address
7. Click **Save**

![OAuth Consent Screen Settings](../images/gogcli-oauth-settings.png)

8. Wait a few minutes, then retry `gog auth login`

Now you should be able to log in successfully!

## Summary

gogcli authentication requires a 2-step setup:
1. **Configure OAuth credentials** (JSON file)
2. **Add test users** (Google Cloud Console)

For personal use, keeping the app in "Testing" mode and adding your email as a test user is the simplest approach.

## Related Articles

- [gogcli Installation Guide](/gogcli-install-guide-en/)
- [Google Cloud OAuth Consent Screen Setup](https://support.google.com/cloud/answer/10311615)
