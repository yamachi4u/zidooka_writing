---
title: "ChatGPT WorkからZidooka！へ投稿できるか試してみた"
categories:
  - general
tags:
  - ChatGPT
  - GitHub Actions
  - WordPress
status: publish
slug: chatgpt-work-zidooka-publish-test-20260808-ja
---

:::conclusion
この記事は、ChatGPT WorkからGitHubへ記事を渡し、PRのマージをきっかけにZidooka！へ自動投稿できるか確認するための実地テストです。
:::

## 今回試している流れ

今回の検証では、ChatGPTとの会話から日本語版・英語版の原稿を作成し、GitHubのfeature branchへ追加しました。その後、PRを作成し、マージ時にGitHub Actionsの `publish-on-merge` を起動します。

公開処理では、リポジトリに登録されたWordPress接続情報をGitHub Actionsが利用し、`post-pair` で日本語版と英語版をまとめて投稿します。

## ここまでに確認できたこと

- ChatGPTからGitHub Issueを作成できる
- ChatGPTからリポジトリのファイルと執筆規則を確認できる
- ChatGPTから記事用ブランチと日英の原稿を作成できる
- PRのマージをWordPress自動投稿の起点にできる

:::note
公開URLが確認できれば、スマホでアイデアをまとめ、GitHub IssueやPRへ渡し、後工程のエージェントが記事化・公開する導線の基礎が整ったことになります。
:::

## 検証後

この記事は連携確認用です。動作確認が終わったら、必要に応じて削除または検証記録として再編集します。
