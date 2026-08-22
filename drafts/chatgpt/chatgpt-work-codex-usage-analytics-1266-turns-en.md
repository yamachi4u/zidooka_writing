---
title: "1,266 ChatGPT Work and Codex Turns in a Month—and 92% of the Weekly Limit Was Still Left"
slug: chatgpt-work-codex-usage-analytics-1266-turns-en
status: publish
categories:
  - ChatGPT
tags:
  - ChatGPT Work
  - Codex
  - OpenAI
  - usage limits
  - rate limits
  - AI agents
---

After using ChatGPT Work and Codex heavily, I opened the analytics dashboard and found a surprising result.

The previous month contained ==1,266 turns==. These were not merely short question-and-answer exchanges. The workload included long-document and multi-file analysis, web research, file generation, code execution, GitHub operations, external-service actions, and end-to-end publishing workflows.

Despite that activity, the dashboard said: “Weekly usage limit: 92% remaining.” The account had no additional credit balance and no recorded credit usage.

:::conclusion
Even after 1,266 turns in one month, 92% of the current weekly allowance remained at the time of measurement. Turn count alone does not show how close an account is to its limit.
:::

## I initially misread 92% as “used”

The dashboard displays “92%” prominently, so my first reaction was that I had consumed 92% of the weekly allowance.

The actual label said 92% remaining. Only about 8% of the current weekly allowance had been consumed.

The dashboard showed the following snapshot:

| Item | Displayed value |
| --- | --- |
| Plan | ChatGPT Plus |
| Reporting period | Previous month |
| Turns | 1,266 |
| Weekly usage limit | 92% remaining |
| Additional credits | 0 |
| Credit usage events | 0 |
| Models used | GPT-5.6 Luna, Sol, Sol-WM, and Terra |
| Surfaces used | Desktop App, CLI, Web, and Mobile |

The difference between perceived usage and the remaining allowance was substantial.

## Work and Codex share one usage allowance

OpenAI’s official pricing page states that ChatGPT Work and Codex share the same pricing, credits, and usage limits.

Work tasks inside the ChatGPT app and Codex tasks in the desktop app, CLI, IDE, or cloud therefore draw from the same general agentic usage budget.

:::note
This does not mean that every ordinary ChatGPT conversation uses the same allowance. The shared budget described here concerns ChatGPT Work and Codex agent activity.
:::

## How 1,266 turns and 92% remaining can both be true

The two numbers cannot be compared directly.

### They cover different periods

The 1,266-turn total covered roughly one month, from July 24 through August 22. The 92% figure described the current weekly allowance.

The monthly chart includes activity from earlier weekly periods that have already reset. A high monthly turn count can therefore coexist with a high remaining percentage in the current week.

### Turns do not have a fixed cost

Usage is not counted as one identical unit per message. OpenAI explains that available message counts vary with the model, the size and complexity of the task, and whether the work runs locally or in the cloud.

A short response and a long-running task that retains extensive context and processes many files do not necessarily consume the same amount.

### Model choice changes efficiency

GPT-5.6 Luna is optimized for lightweight, high-volume work, Terra for everyday production tasks, and Sol for the most difficult reasoning and complex work.

This account used several models rather than routing every turn through the heaviest option. That model mix may help explain why the turn count was high while current weekly consumption remained low.

## This does not prove that twelve times more work is available

With 92% remaining, a simple calculation might suggest that the account could repeat the week’s workload roughly twelve more times. That conclusion is not reliable.

Consumption can change when:

- A different model is selected
- Files or conversation context become larger
- More long-running cloud work is performed
- Higher-cost features such as image generation are used
- A five-hour window or an additional weekly limit becomes relevant

:::warning
Do not convert turn count and remaining percentage into an exact number of tasks left. Usage is weighted by model, tokens, context, reasoning, and tools.
:::

## The Plus plan still appears unusually cost-effective

The workload behind this measurement was much heavier than ordinary chat. To protect private details, it can be summarized only in general terms:

- Reading, comparing, and verifying many source materials
- Drafting and repeatedly revising long documents
- Researching current information on the web
- Generating files in multiple formats
- Executing code and checking its output
- Managing files, branches, and pull requests on GitHub
- Acting through connected external services
- Linking drafting, review, and publication into one workflow
- Moving among desktop, web, mobile, and CLI surfaces

This is outcome-oriented delegation: the user describes what should be completed, and the agent handles much of the intermediate process.

Results will vary because the limit is not a fixed number of turns. Nevertheless, seeing 92% of the weekly allowance remain after this pattern of use suggests that the Plus allowance may be considerably larger than many users expect.

## Check analytics instead of relying on intuition

The Codex analytics dashboard reports:

- Remaining weekly allowance
- Scheduled reset time
- Additional credits
- Usage by Desktop App, CLI, Web, and Mobile
- Turn counts by model
- Plugin calls
- Skills used

If usage feels high, the dashboard is more informative than intuition or raw turn counts. In a Codex CLI session, `/status` can also show the remaining allowance.

## Conclusion

Work and Codex do not have separate usage allowances; they share one. Even so, this real-world snapshot showed 1,266 turns across approximately one month, including substantial agent workloads, while 92% of the current weekly allowance remained.

The key is that turn count is not equivalent to quota consumption, and a monthly turn total does not cover the same period as the current weekly balance.

:::conclusion
Heavy-feeling usage may still leave substantial capacity. Before assuming that the limit is close, check the Work and Codex analytics dashboard.
:::

## References

- [OpenAI: ChatGPT Work and Codex pricing and usage limits](https://learn.chatgpt.com/docs/pricing)
- [OpenAI: Codex speed settings and credit consumption](https://learn.chatgpt.com/docs/agent-configuration/speed)
