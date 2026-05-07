---
title: "[GAS] OAuth screen changed? Why you now see \"Google hasn't verified this app\" and \"currently being tested\""
categories:
  - GAStips
tags:
  - GAS
  - Google Apps Script
  - OAuth
  - Google Cloud
  - Troubleshooting
status: publish
slug: gas-oauth-screen-changed-en
---

When authorizing a Google Apps Script (GAS) project or another Google OAuth app, you may no longer see the old `Advanced` and `Go to {app} (unsafe)` flow.

Instead, you might now get a screen like this:

> Google hasn't verified this app  
> You've been given access to an app that's currently being tested.

The buttons may also be different, such as `Continue` and `Back to safety`. The wording has changed, but the meaning is usually similar: the OAuth app is still in ==Testing== and your Google account has been added as a ==test user==.

## Short answer

If this is your own GAS project, or a tool from a developer you know and trust, `Continue` can be the correct action. If you don't know who built the app, do not continue.

Google's official documentation says that apps in `Testing` are limited to test users, show a warning before consent, and expire authorizations after seven days.

- [Manage App Audience](https://support.google.com/cloud/answer/15549945?hl=en-GB)
- [OAuth Client Verification for Apps Script](https://developers.google.com/apps-script/guides/client-verification)

## Why the screen looks different now

Google has been moving OAuth setup under the `Google Auth platform`, where `Audience` and `Publishing status` now control whether an app is in `Testing` or `In production`.

The official help pages explain that an app in `Testing` shows a warning message before a listed test user can authorize it. Based on that documentation and the current UI wording, this new `currently being tested` screen is best understood as the newer tester warning flow.

That sentence is an inference from Google's documentation plus the observed UI, not a direct quote from Google.

## When this screen usually appears

1. The OAuth app is set to `External`
2. Its publishing status is `Testing`
3. Your Google account was added under `Test users`
4. The app requests scopes that trigger the tester warning flow

## When it is reasonable to click Continue

- You built the GAS project yourself
- The tool is from your own team or a known developer
- The requested permissions make sense for what the script does
- You just added your account as a test user in Google Cloud

## When you should not continue

- You don't know who made the app
- The link came unexpectedly by email or DM
- The scopes look broader than the app's actual purpose
- There is no documentation or trustworthy source for the tool

## What developers should do

If you want to reduce or eventually remove this warning, review the OAuth setup in Google Cloud Console:

1. Open `Google Auth platform`
2. Check `Audience`
3. If the app is still in `Testing`, manage the `Test users` list
4. If the app is ready for wider use, move toward `In production`
5. If you request sensitive or restricted scopes, prepare for verification

Relevant Google docs:

- [Configure the OAuth consent screen and choose scopes](https://developers.google.com/workspace/guides/configure-oauth-consent)
- [When is verification not needed](https://support.google.com/cloud/answer/13464323?hl=en)

## Why you may need to re-authorize after 7 days

This is one of the most confusing parts. Google's help documentation states that authorizations from test users expire after seven days when the app is in `Testing`. If the app requested offline access, the refresh token also expires.

So if your script works, then asks you to sign in again a week later, that may be expected behavior rather than a bug.

## Is it a bug that the old Advanced link is gone?

Not necessarily. Some users used to see the old `Advanced` -> `unsafe` flow, but now see a `Continue`-based tester warning instead.

The UI changed, but the underlying idea is still roughly the same:

- the app is unverified
- access is limited during testing
- Google wants the user to acknowledge the risk before proceeding

## Related

- [How to Fix "Google hasn't verified this app" (Authorization Guide)](https://www.zidooka.com/archives/2039)
