---
title: "Hallmark: A Design Skill for Avoiding AI-Looking Websites"
categories:
  - Web Development
tags:
  - Design
  - Generative AI
  - Coding AI
  - Agent
  - Developer Tools
status: publish
slug: hallmark-anti-ai-slop-design-skill-en
---

When an AI builds a website, it can produce a convincing interface almost instantly. But after a few generations, the results often begin to look alike: purple or blue gradients, rounded cards, familiar hero sections, and the unmistakable feeling of an AI-generated template.

Hallmark calls this problem AI slop. It is a design skill for Claude Code, Cursor, and Codex. Instead of simply asking an AI to make a polished interface, Hallmark tries to keep the output from falling back into familiar AI patterns by varying the structure, theme, typography, and visual direction.

:::conclusion
Hallmark is interesting because it makes an AI question its usual design defaults.
:::

## What Hallmark does

According to its official repository, Hallmark chooses a macrostructure and visual theme based on the brief, then runs multiple checks and a pre-output self-critique. It includes 21 themes and aims to make different briefs produce genuinely different shapes rather than simple color variations.

- Choose a macrostructure that fits the page purpose
- Avoid familiar design patterns
- Build typography and color around the selected direction
- Inspect the result for AI-like defaults before returning the code

The README describes 57 slop-test gates and a pre-emit self-critique. Its value is that questioning common patterns becomes part of the generation process.

## audit, redesign, and study

Hallmark is not limited to creating new interfaces. It also provides verbs for examining and rebuilding existing designs.

### audit
This audits existing code against AI-design anti-patterns without editing it. It produces a punch list for review.
:::step
Start with hallmark audit target to identify which parts of the current site make it feel generic.
:::

### redesign
This keeps the copy, information architecture, and brand direction while rebuilding the structure. It is more than applying a new color theme.

### study
This extracts the DNA of a design from a screenshot or URL. It can analyze macrostructure, type pairing, and color anchors, and optionally emit a portable design.md for another AI tool. The goal is not pixel-level copying.

## Installation

The official installation command is:

    npx skills add nutlope/hallmark

For a manual installation, Codex users can place SKILL.md and references/ in ~/.codex/skills/hallmark/ for personal use, or .codex/skills/hallmark/ for a project-scoped skill.

:::warning
Installing a design skill does not guarantee an original result. If the brief is vague, Hallmark can still produce a vague design.
:::

## Not AI-looking is not just decoration

Avoiding AI-looking design is sometimes reduced to using an unusual font or a surprising color. That can easily turn into another form of AI-generated novelty.

Hallmark intervenes before decoration: who is the page for, what should visitors understand first, in what order should information appear, and what makes the service specific?

:::note
AI-looking design is not caused by gradients and rounded corners alone. It often comes from returning to a safe structure without examining the page purpose.
:::

## Limitations

Hallmark is useful, but it does not automate design judgment completely. The definition of AI slop is not universal, and passing 57 checks does not mean that a site communicates well. Novelty is not the same as originality.

Hallmark therefore works best as a constraint that prevents an AI from taking the easiest route, not as a replacement for a designer.

## Conclusion

As AI makes web development faster, the problem of homogenous output becomes more visible. Hallmark offers a lightweight way to intervene through a reusable skill.

:::conclusion
When asking AI to design, we do not only need to give it more freedom. We also need to narrow the path back to the same defaults.
:::

References: [Hallmark on GitHub](https://github.com/Nutlope/hallmark), [Hallmark official demo](https://www.usehallmark.com/)