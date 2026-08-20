---
title: "What Is a GitHub Machine Account? The Extra Free Account for Automation"
categories:
  - WEB制作
tags:
  - GitHub
  - GitHub Free
  - machine account
  - Automation
status: publish
slug: github-machine-account-en
---

GitHub's Terms of Service allow an additional free machine account alongside a regular free Personal Account.

So what exactly is a machine account?

The key point is that ==it is not an ordinary second personal identity. It is an account dedicated exclusively to automated tasks==.

## Definition

GitHub defines a machine account as an account created by a human who accepts the Terms, provides a valid email address, and remains responsible for what the account does. The account is used exclusively for automated tasks.

A useful way to think about it is this: your Personal Account represents you, while a machine account represents an automated process that you control.

## Typical uses

GitHub's documentation describes machine users in scenarios where a server needs access to multiple repositories. Common examples include:

- deployment users
- CI/CD or external servers accessing several repositories
- bots that create issues, pull requests, or commits
- integrations that need a separate GitHub identity

The machine account can be added as a collaborator, outside collaborator, or Organization member and granted only the permissions it needs.

## Not the same as GitHub Actions

A machine account is not GitHub Actions.

GitHub Actions can often perform repository automation using GitHub's own execution environment and credentials such as `GITHUB_TOKEN`. If your automation runs entirely inside GitHub Actions, a separate machine account may not be necessary.

Machine accounts become more relevant when an external server, long-running bot, or other system needs its own GitHub identity and access to multiple repositories.

## How many free machine accounts are allowed?

GitHub's current Terms say that one person or legal entity may generally maintain no more than one free account. However, you may additionally maintain ==one free machine account== for automation.

In practical terms:

- Personal Account: one free account
- machine account: one additional free account

:::warning
A machine account is not a loophole for maintaining another everyday personal identity, a separate work persona, or an anonymous second account. Its purpose is automation.
:::

## Can account creation itself be automated?

No. GitHub does not permit accounts to be registered by bots or other automated methods.

A human must create the machine account and accept responsibility for it. After that, the account may be used exclusively by automated processes.

## Machine user and service account

GitHub documentation also uses terms such as "machine user" and, in some contexts, "service account." These refer to the same general pattern: a GitHub identity used by automation rather than by a person as their everyday account.

## Summary

:::conclusion
A GitHub machine account is an automation-only GitHub account created and controlled by a human. It is useful for bots, deployment systems, servers, and integrations that need their own identity. GitHub permits one additional free machine account alongside a free Personal Account.
:::

## References

- GitHub Terms of Service: https://docs.github.com/en/site-policy/github-terms/github-terms-of-service
- GitHub Docs, Managing deploy keys: https://docs.github.com/en/authentication/connecting-to-github-with-ssh/managing-deploy-keys
- GitHub Docs, Switching between accounts: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/switching-between-accounts
