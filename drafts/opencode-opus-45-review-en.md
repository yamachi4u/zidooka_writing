---
title: "Running Claude Opus 4.5 with OpenCode — Avoiding \"Subscription Anxiety\" via GitHub Copilot"
slug: "opencode-opus-45-review-en"
status: "publish"
categories: 
  - "AI"
  - "Tools"
tags: 
  - "OpenCode"
  - "Claude"
  - "Opus 4.5"
  - "GitHub Copilot"
  - "AI Agents"
featured_image: "../images/image copy 8.png"
---

# Running Claude Opus 4.5 with OpenCode — Avoiding "Subscription Anxiety" via GitHub Copilot

![Claude Opus 4.5 verified connection](../images/image%20copy%208.png)

**Key Takeaway:** If you're hesitant about committing to a new expensive AI subscription like Claude Code, **combining OpenCode with GitHub Copilot** might be the most rational and cost-effective choice right now.

## Why This Setup? (My Stance on AI Costs)

While tools like "Claude Code" are trending, I honestly feel a bit of **"subscription anxiety"** regarding fixed, high-cost monthly fees.

If you work on a project basis, paying for a subscription during months with no work feels wasteful.
My ideal approach is to think: "**Allocate X% of the project fee to AI expenses.**"

From that perspective, running Claude Opus 4.5 via **OpenCode**—which leverages your existing GitHub Copilot license or pay-as-you-go models—felt like a very logical move.

## Installation via npm

You can download the installer from the official site, but for Windows users comfortable with terminals, npm is faster.

[OpenCode Download](https://opencode.ai/download)

```powershell
npm i -g opencode-ai
```

It took about 2–3 minutes to install, but I've gotten so used to waiting for AI responses lately that I didn't mind waiting at all (lol).

## Surprise GUI and Free Models

I expected a terminal-based tool, but it launched a full GUI initialization process.

![OpenCode initialization screen](../images/image%20copy%204.png)

What surprised me most was that **models like GLM-4.7 are available for free**.
It seems these are selectable by default in the GUI version. That's actually pretty amazing.

![GLM-4.7 shown as Free](../images/image%20copy%205.png)

## Summoning Opus via GitHub Copilot

My main goal was Claude Opus 4.5.
I selected **GitHub Copilot** from "Connect Provider".

![Connect Provider screen](../images/image%20copy%206.png)

Authorization was handled smoothly via the browser.

![GitHub Authorization screen](../images/image%20copy%207.png)

## Claude Opus 4.5: Connection Confirmed!

After setup, I selected Claude Opus 4.5 from the model list and started a chat.
It worked perfectly.

![Response from Opus 4.5](../images/image%20copy%208.png)

## Conclusion

**"I'm scared of more subscriptions, but I want to use the latest models."**

For such demanding needs, OpenCode seems to be a solid answer.
Especially for developers who already have a GitHub Copilot subscription, this tool feels like an excellent way to access Opus-class models without additional fixed costs (leveraging your existing plan).

---
**Environment**: Windows 11 / OpenCode (As of Jan 15, 2026)
