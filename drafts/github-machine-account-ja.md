---
title: "GitHubのmachine accountとは？無料で追加できる自動化専用アカウントを整理"
categories:
  - WEB制作
tags:
  - GitHub
  - GitHub Free
  - machine account
  - 自動化
status: publish
slug: github-machine-account
---

GitHubの利用規約を読むと、通常の無料Personal Accountとは別に、==無料のmachine accountを1つまで持てる==と書かれています。

では、このmachine accountとは何でしょうか。

結論からいうと、==人間が日常的に使う第2アカウントではなく、ボットやサーバーなどの自動処理専用アカウント==です。

## machine accountの定義

GitHub Terms of Serviceでは、machine accountは人間が作成し、その人間が規約に同意し、行動に責任を負うアカウントとされています。用途は「自動化されたタスクの実行」に限定されています。

つまり、アカウント自体は通常のGitHubアカウントと似ていますが、使い道が明確に限定されています。

:::conclusion
通常の無料Personal Accountが「自分自身のGitHubアカウント」なら、machine accountは「自分が管理する自動処理用のGitHubユーザー」と考えるとわかりやすいです。
:::

## 何に使うのか

GitHubの公式ドキュメントでは、サーバーが複数リポジトリへアクセスするケースなどでmachine userを使う例が紹介されています。

たとえば、次のような用途です。

- デプロイ専用ユーザー
- CI/CDや外部サーバーから複数リポジトリへアクセスする自動処理
- ボットによるIssue・Pull Request・コミット操作
- サービス間連携で独立したGitHubユーザーが必要なケース

machine accountをリポジトリのCollaboratorやOrganizationのメンバーとして追加し、そのアカウントに必要な権限だけ与える設計ができます。

## GitHub Actionsとは別物

machine accountはGitHub Actionsそのものではありません。

GitHub ActionsはGitHub側の実行環境と`GITHUB_TOKEN`などを使って処理できます。そのため、GitHub Actionsだけで完結する自動化なら、machine accountが不要な場合も多いです。

一方、GitHub外のサーバーや継続稼働するボットなどが、独立したユーザーとして複数リポジトリへアクセスしたい場合にはmachine accountが候補になります。

## 無料で何個持てる？

GitHubの現行Terms of Serviceでは、1人または1法人が維持できる通常の無料アカウントは原則1つです。

ただし、その無料Personal Accountに加えて、==無料のmachine accountを1つまで追加で維持できます==。

つまり、規約上認められている無料構成は、おおむね次の形です。

- Personal Account：1つ
- machine account：追加で1つまで

:::warning
machine accountは「仕事用」「匿名用」「別人格用」など、普通の第2Personal Accountとして使える抜け道ではありません。自動化専用であることが条件です。
:::

## 作成も自動化してよい？

これは不可です。GitHubは、ボットなどによって自動的にアカウントを登録することを認めていません。

machine accountも、責任を負う人間が通常どおり作成する必要があります。その後、そのアカウントを自動処理専用として利用します。

## machine user / service accountという呼び方もある

GitHub Docsでは文脈によって「machine user」や「service account」という表現も登場します。

たとえばアカウント切り替え機能の説明では、Personal Accountとservice account（machine userとも呼ばれる）を切り替えるケースが示されています。

実務上は、いずれも「人間本人とは別に、自動処理の主体として用意したGitHubアカウント」という理解でよいでしょう。

## まとめ

:::conclusion
GitHubのmachine accountは、無料で追加できる単なる第2アカウントではありません。人間が責任を持って作成・管理し、ボット、デプロイ、サーバー連携などの自動処理だけに使う専用アカウントです。無料Personal Accountとは別に1つまで維持できます。
:::

## 参考

- GitHub Terms of Service: https://docs.github.com/en/site-policy/github-terms/github-terms-of-service
- GitHub Docs「Managing deploy keys」: https://docs.github.com/en/authentication/connecting-to-github-with-ssh/managing-deploy-keys
- GitHub Docs「Switching between accounts」: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/switching-between-accounts
