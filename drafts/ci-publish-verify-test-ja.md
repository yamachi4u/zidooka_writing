---
title: "CI自動公開テスト（この記事は検証用です）"
categories:
  - general
tags:
  - CI/CD
status: publish
slug: ci-publish-verify-test-ja
---

:::conclusion
この記事は GitHub Actions の publish-on-merge ワークフローを検証するためのテスト記事です。実際の公開フロー（PR 作成 → マージ → WordPress 自動公開）が正しく動くことを確認する目的で作られています。
:::

## 検証内容

PR をマージすると、`.github/workflows/publish-article.yml` の publish-on-merge ジョブが走り、`drafts/*-ja.md` と対応する `-en.md` が post-pair で WordPress に投稿されます。

この仕組みが検証できたら、将来は ChatGPT コネクタや外部エージェントが記事を PR として提出し、マージするだけで公開できるようになります。

## 注意

- この記事自体は検証後に削除する想定です
- カテゴリやタグは既存のものを使っています

## 参考

1. [GitHub Actions ドキュメント](<https://docs.github.com/actions>)
