---
title: "【GAS】claspの「push」しか使ってないの？「clasp deploy」でWebアプリのデプロイまで自動化できる話"
slug: gas-clasp-deploy-web-app
date: 2026-01-14 10:00:00
categories: 
  - GAS
  - GAS Tips
  - 便利ツール
tags: 
  - Google Apps Script
  - clasp
  - deploy
  - WebApp
status: publish
featured_image: ../images/clasp-deploy-guide.png
---

Google Apps Script (GAS) の開発で `clasp` を使っている人は多いですが、ほとんどの人が **`clasp push`** で止まっているのではないでしょうか？

実は私自身もそうだったのですが、**`clasp deploy`** コマンドを使うと、**「新しいデプロイの作成」と「デプロイIDの取得」までCLIだけで完結**できます。

ブラウザを開いて「デプロイ」ボタンをポチポチ押す作業から解放されましょう。

## `clasp deploy` でできること

基本的に、GASエディタ上の「新しいデプロイ」ボタンと同じ操作がコマンド一発で可能です。

1. 新しいデプロイメント（本番環境）を作成する
2. そのデプロイID（Deployment ID）を取得する
3. バージョン管理されたスナップショットを公開する

### 基本コマンド

```bash
# コードをアップロード
clasp push

# 新しいバージョンを作成（スナップショット）
clasp version "Ver 1.0.0 Release"

# そのバージョンを指定してデプロイ
clasp deploy -V 1 -d "Initial Deploy"
```

これだけで、新しい Web App URL や API エンドポイントが即座に発行されます。

## Web App URL は変わる？変わらない？

ここが最大の注意点です。

### `clasp deploy` = 新規作成（URLが変わる）

`clasp deploy` を実行するたびに、**新しい Deployment ID が発行されます。**
つまり、WebアプリのURL（`.../exec`）も毎回変わってしまいます。

- **用途**: テスト環境を毎回使い捨てで作る場合や、クライアントごとに別のURLを発行したい場合。

### `clasp redeploy` = 更新（URLそのまま）

既存の Webアプリ URL を維持したまま、中身のバージョンだけを更新したい場合は、**`clasp redeploy`** を使います。

```bash
# 既存のデプロイIDを指定して更新
clasp redeploy <DeploymentID> -V <NewVersionNumber>
```

これを知らないと、「デプロイするたびにURLが変わってしまい、毎回クライアントに連絡しなおし」という地獄を見ることになります。

## 実践的ワークフロー

CLIだけで完結させるなら、以下のフローが最強です。

### 1. 初回デプロイ（URL発行）

```bash
clasp push
clasp version "First Release"
clasp deploy -V 1 -d "Production"
# ここで出力される Deployment ID をメモしておく！
```

### 2. 更新デプロイ（URL維持）

```bash
clasp push
clasp version "fix bug"
# → Created version 2 と表示されたら...

# バージョン2を既存IDに適用
clasp redeploy <DeploymentID> -V 2
```

## `appsscript.json` の設定を忘れずに

Webアプリとして公開するには、`clasp` 実行前に、ローカルの `appsscript.json` で `webapp` 設定をしておく必要があります。

```json
{
  "timeZone": "Asia/Tokyo",
  "dependencies": {},
  "webapp": {
    "access": "ANYONE",
    "executeAs": "USER_DEPLOYING"
  },
  "exceptionLogging": "STACKDRIVER"
}
```

これをやっておけば、`clasp deploy` した瞬間に「誰でもアクセス可能なWebアプリ」が爆誕します。

## まとめ

- 【結論】`clasp push` だけじゃもったいない。`version` → `deploy` までやれば完全自動化が可能。
- 【注意】`clasp deploy` は毎回新しいURLを作る。URLを変えたくなければ `clasp redeploy` を使うこと。
- 【ポイント】Webアプリ設定は `appsscript.json` で管理できる。

これでCI/CDパイプラインからGASを自動デプロイする夢が広がりますね！
