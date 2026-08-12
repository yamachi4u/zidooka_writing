---
title: "SKILL.md and DESIGN.md Resources for Making AI-Built Interfaces Feel Human"
categories:
  - WEB制作
tags:
  - AI Development
  - Frontend
  - Web Design
  - Claude Code
  - Codex
  - DESIGN.md
  - SKILL.md
status: publish
slug: anti-ai-design-skills-design-md-en
---

AI-generated interfaces often converge on the same visual language: purple or blue gradients, Inter-like sans-serif fonts, three-column card grids, soft shadows, and a centered hero. The code works, but the result feels interchangeable.

The problem is not simply that AI cannot design. Without explicit direction, it optimizes for a safe visual average. The practical solution is to store design decisions in `SKILL.md` and `DESIGN.md`, then make the agent read them before building and reviewing an interface.

:::conclusion
To reduce AI-like design, do not merely ask for something “less generic.” Write down the aesthetic direction, the patterns to avoid, the typography, color, spacing, component, and review rules that define the project.
:::

## SKILL.md resources worth using

- [Anthropic’s frontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design) makes the agent commit to a clear aesthetic direction before implementation.
- [Nutlope/hallmark](https://github.com/Nutlope/hallmark) focuses on auditing and redesigning AI-generated interfaces. Install it with `npx skills add https://github.com/Nutlope/hallmark --skill hallmark`.
- [pbakaus/impeccable](https://github.com/pbakaus/impeccable) provides iterative interface improvement, with a useful distinction between brand surfaces and product interfaces.
- [Vercel’s Web Interface Guidelines](https://github.com/vercel-labs/agent-skills/tree/main/skills/web-design-guidelines) is a practical final check for usability, accessibility, responsive behavior, and interaction details.

These resources solve different parts of the problem. Frontend design establishes direction, Hallmark critiques visual sameness, Impeccable helps refine an existing surface, and Vercel’s guidelines protect usability.

## What belongs in DESIGN.md?

[Google’s design.md project](https://github.com/google-labs-code/design.md) treats the file as persistent design memory. Store:

- the product purpose and audience
- the visual vocabulary and conceptual direction
- typefaces and typographic roles
- background, text, accent, and status colors
- spacing, radius, border, and shadow tokens
- component principles
- patterns to avoid
- image, icon, and motion rules
- accessibility minimums

The important part is not only the token values. Explain the rationale and the prohibitions. “Use a warm paper background and thin borders because this is an editorial workbench” is more useful than a list of hex codes.

## A practical workflow

:::step
1. Install a design skill such as `frontend-design` or `hallmark`.
2. Add a project-specific `DESIGN.md` at the repository root.
3. Choose one strong direction before writing UI code.
4. Replace “make it less AI-like” with concrete prohibitions.
5. Run an audit and accessibility review after implementation.
6. Have a human review hierarchy, typography, spacing, density, and details.
:::

A useful brief is concrete: “Build an editorial workbench inspired by a small print shop. Use a warm paper background, a wide serif display face, readable sans-serif body text, borders and whitespace instead of cards, and no purple gradients, glow, Inter, or oversized centered hero.” The agent can act on this. “Make it human” is too vague.

:::warning
Do not turn “avoid AI slop” into a demand for novelty at any cost. Distinctiveness comes from coherent decisions that serve the product, not from making every element loud.
:::

## Conclusion

Skills provide better starting habits. `DESIGN.md` provides project memory. Together they move an agent away from the visual median while keeping the result reviewable and consistent.

- Use `SKILL.md` for creation and audit behavior.
- Use `DESIGN.md` for project-specific aesthetic memory.
- Name the defaults you reject.
- Commit to one direction.
- Review accessibility and usability before shipping.

:::conclusion
AI is very good at producing the visual average. Your job is to document why this project should move away from that average, and in which direction.
:::

## References

- [Anthropic frontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design)
- [Nutlope/hallmark](https://github.com/Nutlope/hallmark)
- [pbakaus/impeccable](https://github.com/pbakaus/impeccable)
- [Vercel Web Interface Guidelines](https://github.com/vercel-labs/agent-skills/tree/main/skills/web-design-guidelines)
- [Google design.md](https://github.com/google-labs-code/design.md)
- [awesome-design-skills](https://github.com/bergside/awesome-design-skills)
