---
title: "Googleビジネスプロフィールの口コミ見逃しゼロへ！自動通知ツール開発とAPIの罠"
date: "2026-03-17"
categories: ["開発事例"]
tags: ["GAS", "Google Business Profile", "API", "自動化", "affiliate"]
status: "draft"
author: "Zidooka Dev"
description: "口コミ返信漏れを防ぐGASツールを開発。Google My Business API (v4) が検索に出ない問題の解決策（ダイレクトリンク）も解説。"
featured_image: ../images/2026/gbp-tool-thumbnail.png
---

Googleマップの口コミ（Googleビジネスプロフィール）への返信漏れを防ぐため、未返信の口コミを検知して自動通知するツールをGoogle Apps Script (GAS) で開発しました。
その導入手順と、開発中にハマった「隠されたAPI」の罠について解説します。

:::note
弊社Zidookaでは、このようなGoogleビジネスプロフィール連携ツールの開発・カスタマイズも承っております。お気軽にご相談ください！
:::

## 開発の背景

Googleマップの口コミは集客に直結しますが、日々の業務に追われて返信を忘れてしまうことがよくあります。
そこで、**「月に一度自動でチェックし、未返信があればメールで教えてくれる」** シンプルなBOTを作成しました。

## 仕組み

1.  **Google Apps Script (GAS)** が定期的に起動（例：毎月3日）。
2.  **Google Business Profile API** を叩いて、全店舗の口コミを取得。
3.  返信がない（`reviewReply` が空）口コミを抽出。
4.  未返信リストを **スプレッドシートに記録** し、担当者に **メール通知**。

## ソースコード (匿名化済み)

以下は主要なロジック部分です。コピペして使えます。

### `Config.js`

```javascript
const CONFIG = {
  // 結果を記録するスプレッドシートID
  SPREADSHEET_ID: 'YOUR_SPREADSHEET_ID_HERE', 
  
  // 通知先メールアドレス
  RECIPIENT_EMAIL: 'your-email@example.com',
  
  // 実行する日付（毎月3日など）
  TRIGGER_DAY: 3,
  
  // API Endpoints
  API_ACCOUNT_URL: 'https://mybusinessaccountmanagement.googleapis.com/v1',
  API_INFO_URL: 'https://mybusinessbusinessinformation.googleapis.com/v1',
  API_REVIEW_URL: 'https://mybusiness.googleapis.com/v4'
};
```

### `Api.js` (ポイント部分)

Google Business Profile APIは現在過渡期で、v1とv4が混在しています。ここがポイントです。

```javascript
/**
 * Fetches data from API
 */
function callApi(fullUrl, method = 'GET', payload = null) {
  const options = {
    method: method,
    headers: {
      Authorization: `Bearer ${ScriptApp.getOAuthToken()}`,
      Accept: 'application/json',
      'Content-Type': 'application/json'
    },
    muteHttpExceptions: true
  };
  // ... (Standard fetch logic) ...
}

/**
 * Reviews are STILL in v4 (as of 2026)
 */
function getReviews(accountName, locationName) {
  // v1 Location Name: 'locations/12345'
  // v4 Review API Path: 'accounts/{accountId}/locations/{locationId}/reviews'
  
  const locId = locationName.split('/')[1];
  const v4LocationPath = `${accountName}/locations/${locId}`;
  const url = `${CONFIG.API_REVIEW_URL}/${v4LocationPath}/reviews`;
  
  // ... (Fetch logic) ...
}
```

## 最大のハマりポイント：APIが見つからない！

開発中、最も苦労したのは **「口コミを取得するAPIが見つからない・有効化できない」** という問題でした。

### 現象

GCPのコンソールで「Google My Business API」を検索しても出てきません。あるのは「Business Profile Performance API」だけ。しかし、Performance APIでは**個別の口コミテキストは取得できません**。

### 原因と解決策

:::warning
実は、口コミ取得に必要な **旧 v4 API (Google My Business API)** は、現在検索結果から隠されています。
:::

しかし、**ダイレクトリンクを知っていれば有効化できます**。これを知らないと、「403 Forbidden」エラーで永遠に詰まります。

:::step
解決策のリンク
:::

以下のリンクから直接アクセスし、プロジェクトを選択して「有効にする」を押してください。

<https://console.developers.google.com/apis/api/mybusiness.googleapis.com/overview>

## 導入手順サマリ

:::step
手順
:::

1.  **GCPプロジェクト作成**: Google Cloud Platformでプロジェクトを作ります。
2.  **API有効化**:
    *   `Google My Business Account Management API`
    *   `Google My Business Business Information API`
    *   **`Google My Business API` (上記の隠しリンクから！)**
3.  **GASプロジェクト作成**: スクリプトを書き、`appsscript.json` でOAuthスコープを設定。
4.  **紐付け**: GASの設定からGCPの「プロジェクト番号」を入力して紐付け。
5.  **初回実行**: 認証ポップアップを許可して完了！

:::conclusion
まとめ
:::

GoogleのAPIは頻繁に変更され、ドキュメントも複雑です。
「自社でやるのは難しそう…」と感じたら、ぜひZidookaにご相談ください。
口コミ管理だけでなく、MEO対策ダッシュボードの構築などもサポート可能です。
