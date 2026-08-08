---
title: "Testing Publishing from ChatGPT Work to Zidooka!"
categories:
  - general
tags:
  - ChatGPT
  - GitHub Actions
  - WordPress
status: publish
slug: chatgpt-work-zidooka-publish-test-20260808-en
---

:::conclusion
This article is a live test of whether ChatGPT Work can send article drafts to GitHub and publish them automatically to Zidooka! when a pull request is merged.
:::

## Workflow under test

For this verification, ChatGPT created separate Japanese and English drafts from a conversation and added them to a feature branch on GitHub. A pull request is then created, and merging it triggers the `publish-on-merge` GitHub Actions job.

The publishing job uses the WordPress connection settings stored in GitHub Actions and runs `post-pair` to publish both language versions together.

## What has been confirmed so far

- ChatGPT can create a GitHub Issue
- ChatGPT can read repository files and writing rules
- ChatGPT can create an article branch and bilingual drafts
- A pull request merge can act as the trigger for automatic WordPress publishing

:::note
Once the public URLs are confirmed, this establishes the foundation for a workflow in which ideas captured on a phone move through GitHub Issues or pull requests and are later processed and published by an agent.
:::

## After verification

This is a connectivity test article. After the test, it may be deleted or revised into a permanent record of the workflow.
