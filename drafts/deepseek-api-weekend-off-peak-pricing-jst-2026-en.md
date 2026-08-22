---
title: "DeepSeek API Makes Weekends Off-Peak All Day: JST Schedule from August 23, 2026"
status: publish
slug: deepseek-api-weekend-off-peak-pricing-jst-2026-en
categories:
  - AI
tags:
  - DeepSeek
  - API
  - API Cost
  - 2026
---

DeepSeek is changing its API peak and off-peak billing rules on August 23, 2026. Weekday time-based pricing will continue, but every hour of the weekend will be billed at the off-peak rate.

Converted to Japan Standard Time, the new rule takes effect at ==1:00 a.m. JST on Sunday, August 23, 2026==.

:::conclusion
DeepSeek API will apply off-peak rates throughout Saturdays and Sundays in Beijing Time. In Japan, the normal weekend discount window runs from 1:00 a.m. Saturday through 1:00 a.m. Monday.
:::

## What is changing?

The announcement mainly changes how weekend hours are classified rather than replacing the whole price table.

- Weekdays: the existing peak and off-peak rates continue
- Weekends: all hours are billed at the off-peak rate
- Day-of-week basis: Beijing Time, UTC+8
- Usage before the effective time: settled under the previous rules
- Continued use after the change: treated as acceptance of the revised pricing terms

The word "calls" in the announcement refers to API calls or API usage, not telephone or voice calls.

## Effective time in Japan

Beijing Time is one hour behind Japan Standard Time.

| Item | Beijing Time | Japan Standard Time |
| --- | --- | --- |
| New rule takes effect | Sun, Aug. 23, 2026 at 12:00 a.m. | Sun, Aug. 23, 2026 at 1:00 a.m. |
| Normal weekend window begins | Saturday at 12:00 a.m. | Saturday at 1:00 a.m. |
| Normal weekend window ends | Monday at 12:00 a.m. | Monday at 1:00 a.m. |

Because the rule starts on a Sunday, the first discounted weekend window under the revised policy runs from 1:00 a.m. JST on Sunday, August 23, through 1:00 a.m. JST on Monday, August 24. From the following weekend onward, the full window runs from 1:00 a.m. Saturday through 1:00 a.m. Monday in Japan.

:::warning
The rule follows weekends in Beijing Time, not the Japanese calendar boundary. In JST, midnight to 1:00 a.m. on Saturday is still Friday in Beijing, while midnight to 1:00 a.m. on Monday is still Sunday in Beijing.
:::

## Weekday peak hours in JST

DeepSeek's official pricing page lists peak hours as 01:00-04:00 UTC and 06:00-10:00 UTC. Converted to JST, the windows are:

| Classification | UTC | JST |
| --- | --- | --- |
| Peak window 1 | 01:00-04:00 | 10:00-13:00 |
| Peak window 2 | 06:00-10:00 | 15:00-19:00 |
| Off-peak | All other hours | All other hours |

Those peak windows apply only when the day is Monday through Friday in Beijing Time. When it is Saturday or Sunday in Beijing, the off-peak rate applies even during those hours.

## Current price difference

As of August 22, 2026, DeepSeek's official pricing page says off-peak rates are half of peak rates. The main prices below are in U.S. dollars per 1M tokens.

| Billing item | DeepSeek V4 Flash off-peak | V4 Flash peak | DeepSeek V4 Pro off-peak | V4 Pro peak |
| --- | ---: | ---: | ---: | ---: |
| Input, cache hit | $0.007 | $0.014 | $0.022 | $0.044 |
| Input, cache miss | $0.22 | $0.44 | $0.66 | $1.32 |
| Output | $0.66 | $1.32 | $1.98 | $3.96 |

Prices may change, so check the official table before planning significant usage.

## How to use the new rule

Workloads that can be scheduled for a weekend no longer need to avoid individual peak windows. Batch generation, evaluations, code generation, and data transformation can all be grouped into the weekend discount period.

On weekdays, avoiding 10:00-13:00 and 15:00-19:00 JST currently keeps usage in the off-peak windows. Remember that the day boundary is 1:00 a.m. JST because weekend eligibility is determined in Beijing Time.

## Summary

DeepSeek's revised API billing rule takes effect at 1:00 a.m. JST on Sunday, August 23, 2026.

The practical change is simple: weekends in Beijing Time are off-peak all day. For users in Japan, the normal discounted weekend window is 1:00 a.m. Saturday through 1:00 a.m. Monday.

References:
1. DeepSeek API Docs - Models & Pricing
https://api-docs.deepseek.com/quick_start/pricing
