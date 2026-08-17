---
title: "How to Make AI-Written Japanese Sound Natural: Bringing natural-japanese into Zidooka"
slug: natural-japanese-agent-skill-en
status: publish
categories: [AI]
tags: [natural-japanese, Agent Skill, Japanese, lint, SudachiPy, writing]
---

AI-generated articles are often grammatically correct but strangely uniform. Paragraphs follow the same rhythm, explanations repeat themselves, and the result feels more translated than written. The `natural-japanese` project by coji takes that problem seriously.

It is an Agent Skill for writing and revising clear Japanese work documents, blogs, and essays. More importantly, it is not just a list of “AI-sounding” phrases. It combines pre-writing design, generation-time constraints, and post-writing inspection into one workflow.

## The key idea

The most interesting principle is: machines detect; people or AI decide.

AI is not very good at recognizing its own stylistic habits. `lint.py` therefore detects forbidden or formulaic phrases, repetitive rhythms, homogeneous paragraph structures, and English-like Japanese syntax. But a detected phrase is not automatically wrong. The writer decides whether to revise it or keep it for a reason.

That separation matters. A rigid ban list would make writing artificial in a different way. A better workflow is to let the machine point to suspicious areas and let the writer judge them in context.

## A writing constitution

The skill includes twelve writing principles for the generation stage: lead with the conclusion, make headings carry a message, explain specialist terms in context, and avoid repeating the same structure too many times.

This is stronger than removing AI-like phrases after the fact. Uniform paragraphs and predictable headings are structural problems. They are easier to prevent by deciding the reader, the main message, and the outline before drafting.

## What should and should not be automated

Formulaic phrases can be detected mechanically. Readability is different. Word order, punctuation, sentence weight, and the distance between subject and predicate are difficult to reduce to universal thresholds. `natural-japanese` uses automation to point out likely trouble spots while leaving the final judgment to the writer or AI.

That restraint is a strength. It does not pretend that one score can fully represent readable prose.

## How Zidooka can use it

Zidooka publishes Japanese and English articles through a GitHub-based workflow. A practical integration would have three layers:

1. Add pre-writing metadata: target reader, primary message, reader takeaway, section messages, and places where the English version may differ.
2. Run `lint.py`, `outline.py`, and `terms.py` before publication, initially as warnings rather than hard CI failures.
3. Keep the facts and claims aligned across languages without forcing a sentence-by-sentence translation.

The first step is especially useful for a bilingual site. Japanese and English versions can share an argument while still using introductions and explanations natural to each readership.

## Getting started

The repository README suggests:

```bash
npx skills add coji/natural-japanese
```

The inspection scripts can also be run directly:

```bash
uv run skills/natural-japanese/scripts/lint.py path/to/draft.md
uv run skills/natural-japanese/scripts/outline.py path/to/draft.md
uv run skills/natural-japanese/scripts/terms.py path/to/draft.md
```

`lint.py` does not rewrite the document automatically. It reports suspicious patterns and leaves the editorial decision to the writer.

## Conclusion

`natural-japanese` is not merely a prompt that tells AI to “write more naturally.” It is a writing process: design, constrain, inspect, and revise.

For Zidooka, the best starting point is a small article-design template plus warning-only linting before publication. Later, GitHub Actions could preserve the findings and before/after diffs as artifacts. That would make it easier to publish more often without allowing every article to settle into the same AI-shaped prose.

Reference: [coji/natural-japanese](https://github.com/coji/natural-japanese)
