---
title: "Vercel CLIでデプロイ時に「not a member of the team」エラーが出た場合の対処法"
categories:
  - vercel
tags:
  - Vercel
  - CLI
  - Error
  - Troubleshooting
status: publish
slug: vercel-cli-deploy-permission-error
date: 2026-01-13 14:36:00
featured_image: ../images-agent-browser/vercel_com_vertical.png
---

Vercel CLIを使ってデプロイしようとした際、以下のようなエラーメールが届き、デプロイに失敗することがあります。

> We’re writing to notify you that XXXXXXX@users.noreply.github.com attempted to deploy a commit to XXXXXXX's projects on Vercel through the Vercel CLI, but they are not a member of the team.

この記事では、このエラーの原因と解決策を簡潔にまとめます。

## エラー内容

以下のメッセージが表示（またはメール通知）されます。

```text
To resolve this issue, you can:
Upgrade to Pro and add them as a collaborator on your Vercel team
If the user is already a member of your Vercel team, ensure their GitHub account is connected to their Vercel account on their Authentication Settings page
...
```

要約すると、「デプロイしようとしたユーザーがチームメンバーとして認識されていない」という内容です。

## 原因と解決策

:::conclusion
Vercel CLIの認証情報が古い、または紐付けが正しくない可能性があります。一度ログアウトし、再ログインしてからデプロイし直してください。
:::

### 手順

:::step
1. **Vercel CLIからログアウトします。**
   ```bash
   vercel logout
   ```

2. **再度ログインします。**
   ```bash
   vercel login
   ```
   ブラウザが立ち上がり認証を求められるので、正しいアカウント（GitHub連携などがされているアカウント）でログインします。

3. **デプロイを再実行します。**
   ```bash
   vercel deploy
   ```
   ※または、該当のデプロイコマンド
:::

:::note
特に複数のVercelアカウントを使い分けている場合や、チームへの招待状況が変わった場合に発生しやすいようです。再ログインで紐付け情報をリフレッシュすることで解消します。
:::
