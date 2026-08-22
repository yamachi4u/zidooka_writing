# GPTWORK Guidelines

Scope: Supplemental operating rules for ChatGPT Work and for article work under `drafts/chatgpt/`. The repository-wide rules in `AGENTS.md` remain authoritative.

## Required Reading

Before researching, drafting, editing, or publishing:

1. Read `AGENTS.md`.
2. Read `PIPELINE_MANUAL.md`.
3. Read `docs/snippets/emphasis.md`.
4. Re-check any task-specific instructions in the target draft or issue.

## Default Article Workflow

1. Inspect the repository instructions, existing categories, related drafts, and current publication state.
2. Research from primary or official sources. Verify facts that may have changed.
3. Unless the user explicitly requests one language, create separate Japanese and English drafts in `drafts/chatgpt/`.
4. Follow the frontmatter, block, URL, language, and scheduling rules in `AGENTS.md` and `PIPELINE_MANUAL.md`.
5. Add a useful visual when it materially improves the article and the rights conditions in `AGENTS.md` are satisfied.
6. Validate both drafts, local image paths, links, and paired metadata before opening a pull request.
7. Merge only when the user has requested publication or otherwise authorized the merge.
8. After publication, verify the live Japanese and English URLs, the rendered article body, and every uploaded image.

## Visuals

- Prefer screenshots captured directly from official public pages, documentation, or public source-code repository pages.
- Use a signed-out public view when possible. Capture only the viewport or crop needed for the article.
- Link to the original page near the screenshot and use descriptive, localized alt text.
- Never expose private account information, private repositories, credentials, tokens, notifications, emails, analytics, or unrelated personal details.
- If reuse rights, license conditions, or the quotation basis are unclear, do not use the third-party image. Create an original diagram or thumbnail, or publish without the image.
- A visual must serve the explanation; do not add one only for decoration.
- After publishing, confirm that the WordPress image loads successfully and has non-zero natural dimensions.

## Evidence And Freshness

- Prefer primary documentation, release notes, repositories, and official announcements over summaries.
- Put source links close to the claims they support.
- Clearly separate sourced facts from interpretation or inference.
- For fast-changing details such as versions, prices, supported providers, or service limits, include an as-of date when useful.
- Do not copy substantial source text. Summarize in original language and quote only the minimum necessary.

## Privacy And Public-Repository Safety

- Assume every committed file, pull request, branch name, and commit message may become public.
- Do not commit secrets, credentials, private URLs, unpublished account data, exact private identifiers, or personal details that are not necessary for the article.
- Anonymize examples drawn from private work or conversations.
- Do not connect a public article to another private or pseudonymous project unless the user explicitly requests it and publication is safe.
- Before opening a pull request, scan the changed files for accidental private data and unrelated context.

## Git And Publication Safety

- Start from the latest `main` commit and keep each branch focused on one logical change.
- Preserve unrelated user changes.
- Use a pull request for article publication and for durable workflow changes.
- Before merging, inspect the changed filenames and compare result.
- After merging an article, check the publication workflow and the final WordPress pages. Do not report success until the result is verified.

## Maintaining These Instructions

The user has authorized ChatGPT Work to improve `AGENTS.md` and `GPTWORK.md` when a durable, reusable workflow lesson emerges.

- Add only stable rules that will help future tasks; do not record one-off task facts.
- Keep instructions concise, non-conflicting, and safe for a public repository.
- Never add personal data, secrets, private project clues, or hidden conversation context.
- Update the more specific file when possible: repository-wide rules belong in `AGENTS.md`; ChatGPT Work execution details belong here.
- Mention material instruction changes in the pull request and in the final handoff.
