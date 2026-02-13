---
title: "Moltbot/Moltworker disconnected (1008): Invalid or missing token Error - Solutions"
date: 2026-02-15 09:00:00
categories:
  - AI系エラー
tags:
  - Moltbot
  - Moltworker
  - Cloudflare Workers
  - AI Gateway
  - Token
  - Error
  - Troubleshooting
  - Error Resolution
status: publish
slug: moltbot-disconnected-1008-error-en
---

# Moltbot/Moltworker disconnected (1008): Invalid or missing token Error - Solutions

When deploying Moltworker on Cloudflare Workers with AI Gateway, you may encounter the error `disconnected (1008): Invalid or missing token`. This error occurs due to authentication token issues. Here's a detailed explanation of the causes and multiple solutions.

:::note
**Target Environment**
- Cloudflare Workers with Containers
- Cloudflare AI Gateway
- Moltworker / Moltbot
:::

## Main Error Symptoms

When this error occurs, you may see the following symptoms:

- Workers.dev URL shows "Waiting for Moltworker to load" and doesn't progress
- `_admin` page shows "ProcessExitedBeforeReadyError: Process exited with code 1 before becoming ready"
- Authentication errors when integrating with Telegram Bot
- Adding `?token=` to the URL doesn't resolve the issue

## Solution 1: Reconfigure the Token

The most basic solution is to reconfigure the token.

1. Access Cloudflare Dashboard
2. Go to **Workers & Pages** → Select **Moltworker**
3. Check **Settings** → **Variables and Secrets**
4. Verify that `MOLTBOT_GATEWAY_TOKEN` is correctly set
5. Delete the value, re-enter it, and click **Save and Deploy**

:::warning
**Token Format Notes**
- Long tokens (like `openssl rand -hex 32`) may not work
- Simple password formats of 8-16 characters may work better
- If using special characters, verify they are correctly entered
:::

## Solution 2: Delete Container and Redeploy

If reconfiguring the token doesn't work, an old container process may still be running.

1. Go to https://dash.cloudflare.com/?to=/:account/workers/containers
2. Select the **moltbot-sandbox-sandbox** container
3. Click **Delete container** from the three-dot menu
4. Run `npm run deploy` locally to redeploy

:::warning
**Note**
Deleting the container will completely stop it. After redeploying, a new container should start within a few minutes. If it has been stopped for a long time, you may need to clear the build cache.

How to clear build cache:
- Go to Workers & Pages settings → **Build cache** → **Clear cache**
:::

## Solution 3: Re-authenticate via AI Gateway

Re-authenticating through the AI Gateway management console may resolve the issue.

1. Access the AI Gateway Control UI
2. Open the **Gateway Access** section
3. Re-enter your password
4. Click **Connect** to reconnect

## Solution 4: Fix Configuration Files

This error may occur if the config file contains invalid settings.

:::example
**Settings That Need Fixing (start-moltbot.sh)**

```bash
# Problematic configuration
config.channels.telegram.dm = config.channels.telegram.dm || {};

# Delete or comment out this line
```

Also check _config.yml and config.json for invalid keys.
:::

## Solution 5: For R2 Users

If you're using Cloudflare R2, existing cache files may be causing the issue.

1. Access the R2 dashboard
2. Open the clawdbot-related bucket
3. Delete the `clawdbot.json` file
4. Restart Moltworker

## Root Cause and Future Updates

According to GitHub issues, the root cause of this error was that Moltworker didn't reliably kill the old moltbot process when redeploying with a new token, causing the old process to occupy the port.

The following commits released in February 2026 fixed the gateway token injection into WebSocket requests for Cloudflare Access users:

- `fix: inject gateway token into WebSocket requests for CF Access users` (73acb8a)
- Verified fix (d694bd3)
- Additional fix (f6f23ef)

:::conclusion
**Summary of Solutions**

1. Re-enter token (try a simpler format)
2. Delete container and redeploy
3. Re-authenticate via AI Gateway
4. Check config files for invalid settings
5. For R2 users, delete clawdbot.json

If none of the above resolve the issue, please update to the latest version from GitHub. This issue has been resolved in the commits mentioned above.
:::
