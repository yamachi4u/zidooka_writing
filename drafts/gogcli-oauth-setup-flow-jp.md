---
title: "gogcliでauthできない！OAuth設定からテストユーザー追加までの流れ"
date: 2026-02-02 12:00:00
categories:
  - そのほか
tags:
  - gogcli
  - Google Cloud
  - OAuth
  - トラブルシューティング
status: draft
slug: gogcli-oauth-setup-flow
---

## 問題：gog auth login ができない

gogcli（Google CLIツール）をインストール後、`gog auth login` を実行したら以下のエラーが表示された：

> OAuth credentials not configured. Run: gog auth credentials <file>

## 解決策その1：OAuth認証情報の設定

Google Cloud Consoleで取得したOAuthクライアントJSONファイルを設定する必要がある。

### 手順

1. [Google Cloud Console](https://console.cloud.google.com/) で「デスクトップアプリ」用のOAuthクライアントを作成
2. JSONファイルをダウンロード
3. 以下のコマンドで設定：

```bash
gog auth credentials ~/Downloads/client_secret_xxxx.json
```

## 次の問題：アクセスをブロックエラー

OAuth設定後、再度 `gog auth login` を実行したら今度は別のエラー：

> アクセスをブロック: gog-cli-auth は Google の審査プロセスを完了していません  
> このアプリは現在テスト中で、デベロッパーに承認されたテスターのみがアクセスできます。

![エラー画面](../images/gogcli-oauth-error.png)

## 解決策その2：テストユーザーの追加

Google Cloud Consoleで自分のメールアドレスを**テストユーザー**として追加する必要がある。

### 手順

1. [Google Cloud Console](https://console.cloud.google.com/) にアクセス
2. **APIとサービス** → **OAuth同意画面** を選択
3. **テスト**モードになっていることを確認
4. **テストユーザー**セクションまでスクロール
5. **ユーザーを追加** をクリック
6. 使用するメールアドレスを入力
7. **保存** をクリック

![OAuth同意画面の設定](../images/gogcli-oauth-settings.png)

8. 数分待ってから、再度 `gog auth login` を実行

これで無事ログインできるようになる！

## まとめ

gogcliの認証は2段階の設定が必要：
1. **OAuth認証情報の設定**（JSONファイル）
2. **テストユーザーの追加**（Google Cloud Console）

個人利用の場合は「テスト」モードのままで、自分のメールをテストユーザーとして追加するのが最も簡単。

## 関連記事

- [gogcliインストールガイド](/gogcli-install-guide/)
- [Google Cloud OAuth同意画面の設定](https://support.google.com/cloud/answer/10311615)
