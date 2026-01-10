---
title: "The Unexpected Downside I Found Using Lolipop: How Xserver Looks Different When You Include Backup Costs"
slug: "lolipop-xserver-backup-comparison-en"
status: "publish"
categories: 
  - "WordPress"
tags: 
  - "Web Hosting"
  - "Xserver"
  - "Lolipop"
  - "Backup"
  - "Comparison"
featured_image: "../images/2025/lolipop-waf-domain-settings.png"
---

# The Unexpected Downside I Found Using Lolipop: How Xserver Looks Different When You Include Backup Costs

![Thumbnail](../images/2025/lolipop-waf-domain-settings.png)

When choosing a web hosting service, it's easy to focus solely on the monthly price tag.
I initially thought, "Lolipop should be enough for a personal site."

Indeed, Lolipop is cheap.
However, when I actually used it and compared it calmly with Xserver **including backup costs**, I noticed something important for the first time.

This article is not about determining which is "better."
It's a record organized with the premise that evaluation changes depending on usage.

## The Baseline: How Much Does Xserver Cost?

First, let's check Xserver's cheapest plan as our baseline.

**Xserver (Standard Plan)**

- Monthly: ~990 yen (with long-term contract)
- WordPress: Standard support
- **Daily automatic backup: Included as standard**
- Self-restore via admin panel
- Additional options: Not required

What's crucial here is that
"backup" and "self-restore" are **included from the start** at this price.

In other words,
**it's priced with the assumption that you can recover immediately even if you break something.**

## Lolipop: The Gap Between "Display Price" and Actual Operating Costs

On the other hand, Lolipop's monthly fees look significantly cheaper.

- Light: ~264 yen
- Standard: ~495 yen
- High Speed: ~550 yen

At this point,
I had the impression that
"Xserver costs about twice as much."

Honestly, at this stage, I wasn't really aware of any downsides.

## The Unexpected Downside I Found: Backup Wasn't a "Given"

What I realized during actual operation was that
**backup is not a standard assumption with Lolipop.**

- **Light/Standard**: No automatic backup
- **High Speed and above**: Automatic backup available
  - However, restoration basically requires support request
  - If you want to restore/download yourself
    → **7-generation backup option (~440 yen/month) required**

This is when I first felt,
"The conditions change in actual operation."

## What Happens When You Line Up Prices Including Backup?

Let's compare with the same conditions as Xserver.

**Lolipop (High Speed + Backup)**

- High Speed: 550 yen
- Backup Option: 440 yen
- → **Total: ~990 yen/month**

**Xserver (Standard)**

- Monthly: ~990 yen
- Backup: Included as standard
- Restore: Immediately available from admin panel

**The moment the prices aligned, my evaluation axis changed.**

The premise that "Lolipop is cheap" becomes clear that it's only valid
"when you don't add backup."

## However, There Are Cases Where Lolipop Is Definitely Cheaper

This is important, so let me be clear.
**There are actual use cases where Lolipop is clearly cheaper.**

For example:

- Backup is not necessary for now
- Willing to rebuild from scratch if needed
- Self-managed backup with plugins like UpdraftPlus is sufficient
- Testing/experimental sites
- Low-update-frequency, small-scale sites

For such purposes,

- Light (264 yen/month)
- Standard (495 yen/month)

can be operated, and
it's a fact that this is overwhelmingly cheaper than Xserver.

**If you prioritize "cheapness" with a "compromise mindset," Lolipop is rational.**

## The Difference Appears "The Moment You Can't Compromise"

However, what I actually felt while using it was:

- Articles started accumulating
- Configuration changes became more frequent
- Recovery after breaking something became troublesome

Around this point,
**the compromise of "I don't need backup" stops working.**

At that timing, when you reconsider:

- I want to properly set up backup
- I want to be able to restore immediately

**The price of Lolipop + options aligns with Xserver.**

This was
**the "unexpected downside" I realized only after using it.**

## Conclusion: Take the Cheapness or Take the Assumption?

When organized, the division is quite clear.

**Use it cheap with compromises**
→ Lolipop

**Operate with the assumption you can recover from mistakes**
→ Xserver

It's not about which is "correct."
**It's a difference in where you compromise.**

However,
the moment you need to add backup options,
**Xserver looks simpler and clearer.**

This was something hard to notice from just comparing specs beforehand,
and something I understood only after actually using it.

---

**Related Articles:**
- [WordPress 403 Forbidden Solutions](https://www.zidooka.com/)
- [Lolipop WAF Configuration Troubleshooting](https://www.zidooka.com/)
