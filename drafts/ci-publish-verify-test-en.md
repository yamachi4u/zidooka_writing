---
title: "CI Auto-Publish Test (verification article)"
categories:
  - general
tags:
  - CI/CD
status: publish
slug: ci-publish-verify-test-en
---

:::conclusion
This is a test article for verifying the publish-on-merge workflow in GitHub Actions. Its purpose is to confirm that the real publishing flow (create PR → merge → auto-publish to WordPress) works correctly.
:::

## What this verifies

When a PR is merged, the `publish-on-merge` job in `.github/workflows/publish-article.yml` runs, and `drafts/*-ja.md` plus its matching `-en.md` are posted to WordPress via post-pair.

Once verified, this mechanism will let the ChatGPT connector or external agents submit articles as PRs and publish them just by merging.

## Note

- This article is expected to be deleted after verification
- Categories and tags use existing ones

## References

1. [GitHub Actions Documentation](<https://docs.github.com/actions>)
