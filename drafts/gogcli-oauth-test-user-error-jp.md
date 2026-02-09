---
title: "gogcliで「アクセスをブロック」エラーが出たときの対処法"
date: 2026-02-02 12:00:00
categories:
  - そのほか
tags:
  - gogcli
  - Google Cloud
  - OAuth
  - トラブルシューティング
status: draft
slug: gogcli-oauth-test-user-error
---

## 問題

gogcli（Google CLIツール）で `gog auth login` または `gog auth add <email>` を実行したとき、以下のエラーが表示される：

> アクセスをブロック: gog-cli-auth は Google の審査プロセスを完了していません  
> このアプリは現在テスト中で、デベロッパーに承認されたテスターのみがアクセスできます。

![エラー画面](../images/gogcli-oauth-error.png)

## 原因

Google Cloud Consoleで作成したOAuthクライアントが「**テスト**」モードのままになっているため。テストモードでは、明示的に許可したテストユーザーのみがアクセスできる。

## 解決策

Google Cloud Consoleで自分のメールアドレスを**テストユーザー**として追加する。

### 手順

1. [Google Cloud Console](https://console.cloud.google.com/) にアクセス
2. **APIとサービス** → **OAuth同意画面** を選択
3. **テスト**モードになっていることを確認

![OAuth同意画面の設定](../images/gogcli-oauth-settings.png)

4. **テストユーザー**セクションまでスクロール
5. **ユーザーを追加** をクリック
6. 使用するメールアドレス（例：`your-email@gmail.com`）を入力
7. **保存** をクリック
8. 数分待ってから、再度 `gog auth login` を実行

## 補足

- 本番環境（公開ステータス）に切り替えることでも解決できるが、審査プロセスが必要になる場合がある
- 個人利用や小規模な利用の場合は、テストユーザーの設定で十分

## 関連記事

- [gogcliインストールガイド](/gogcli-install-guide/)
- [Google Cloud OAuth同意画面の設定](https://support.google.com/cloud/answer/10311615)
