---
title: "Moltbot/Moltworkerでdisconnected (1008): Invalid or missing token エラーの解決法"
date: 2026-02-15 09:00:00
categories:
  - AI系エラー
tags:
  - Moltbot
  - Moltworker
  - Cloudflare Workers
  - AI Gateway
  - Token
  - エラー
  - Troubleshooting
status: publish
slug: moltbot-disconnected-1008-error
---

# Moltbot/Moltworkerでdisconnected (1008): Invalid or missing token エラーの解決法

Cloudflare WorkersでMoltworkerを、AI Gatewayを使ってデプロイしようとした際に、`disconnected (1008): Invalid or missing token`というエラーに遭遇することがあります。このエラーは認証トークンの問題で発生します。今回は、このエラーの原因と複数の解決法を詳しく解説します。

:::note
**対象環境**
- Cloudflare Workers with Containers
- Cloudflare AI Gateway
- Moltworker / Moltbot
:::

## エラーの主な症状

このエラーが発生すると、以下のような症状が見られます：

-  workers.devのURLにアクセスしても「Waiting for Moltworker to load」から進まない
-  `_admin`ページで「ProcessExitedBeforeReadyError: Process exited with code 1 before becoming ready」と表示される
-  Telegram Botとの連携時に認証エラーになる
-  URLに`?token=`を追加しても解決しない

## 解決法その1：トークンの再設定

最も基本的な解決法は、トークンを再度設定し直すことです。

1. Cloudflare Dashboardにアクセス
2. **Workers & Pages** → **Moltworker**を選択
3. **Settings** → **Variables and Secrets**を確認
4. `MOLTBOT_GATEWAY_TOKEN`の値が正しく設定されているか確認
5. 値を削除して再度入力し、**Save and Deploy**をクリック

:::warning
**トークン形式の注意点**
- 長いトークン（openssl rand -hex 32など）が動作しない場合があります
- 8〜16文字程度のシンプルなパスワード形式が有効なケースがあります
- 特殊記号を含む場合は正しく入力されているか確認してください
:::

## 解決法その2：コンテナの削除と再デプロイ

トークンを再設定しても解決しない場合は、古いコンテナプロセスが残っている可能性があります。

1. https://dash.cloudflare.com/?to=/:account/workers/containersにアクセス
2. **moltbot-sandbox-sandbox**コンテナを選択
3. 右上の三点メニューから**Delete container**をクリック
4. ローカルで`npm run deploy`を実行して再デプロイ

:::warning
**注意**
コンテナを削除すると完全に停止します。再デプロイ後、数分程度で新しいコンテナが起動します。長い間停止していた場合は、ビルドキャッシュをクリアする必要があるかもしれません。

ビルドキャッシュのクリア方法：
- Workers & Pagesの設定 → **Build cache** → **Clear cache**
:::

## 解決法その3：AI Gatewayでの再認証

AI Gatewayの管理画面から再認証することで解決するケースがあります。

1. AI GatewayのControl UIにアクセス
2. **Gateway Access**セクションを開く
3. 使用しているパスワードを再入力
4. **Connect**をクリックして再接続

## 解決法その4：設定ファイルの修正

configファイルに不正な設定があると、このエラーが発生することがあります。

:::example
**修正が必要な設定例（start-moltbot.sh）**

```bash
# 問題のある設定
config.channels.telegram.dm = config.channels.telegram.dm || {};

# この行を削除またはコメントアウト
```

_config.ymlやconfig.json أيضاً同样的に、不正なキーがないか確認してください。
:::

## 解決法その5：R2を使用している場合

Cloudflare R2を使用している場合、既存のキャッシュファイルが問題を起こしている可能性があります。

1. R2ダッシュボードにアクセス
2. clawdbot 관련バケットを開く
3. `clawdbot.json`ファイルを削除
4. Moltworkerを再起動

## 根本的な原因と今後の展望

このエラーの根本的な原因ですが、GitHubのIssueによると、Moltworkerが古い moltbot プロセスを確実に終了させずに новый トークンで再デプロイすると、古いプロセスがポートを占領したままになる仕様上の問題があったとのことです。

2026年2月にリリースされた以下のコミットで、Cloudflare Access пользователя向けのWebSocketリクエストへのトークン注入が修正されています。

- `fix: inject gateway token into WebSocket requests for CF Access users` (73acb8a)
- Verified fix (d694bd3)
- Additional fix (f6f23ef)

:::conclusion
**解決法のまとめ**

1. トークンの再入力（シンプルな形式试试）
2. コンテナの削除と再デプロイ
3. AI Gatewayでの再認証
4. configファイルの不正設定を確認
5. R2ユーザーはclawdbot.jsonを削除

上記すべてを試しても解決しない場合は、GitHubの最新バージョンにアップデートしてください。上記コミット以降、この問題は解決されています。
:::
