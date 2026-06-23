---
title: "Bing Webmaster Tools APIの使い方 ― 検索パフォーマンスデータをプログラムで取得する"
slug: bing-webmaster-api-guide
categories:
  - SEO
  - ツール集
tags:
  - Bing
  - Webmaster Tools
  - API
  - SEO
  - 検索エンジン
status: publish
---

Bing Webmaster ToolsにはAPIが用意されており、検索パフォーマンスデータ（インプレッション、クリック、掲載順位など）をプログラムから取得できます。本記事ではその概要と使い方を解説します。

:::note
Bing Webmaster Tools APIは2020年6月15日以降、Microsoft Services Agreementの下で提供されています。
:::

## APIでできること

Bing Webmaster Tools APIを使うと、以下のようなデータにプログラムからアクセスできます。

- **Rank & Traffic Stats** ― 日単位のインプレッション数・クリック数
- **Link Details** ― 被リンク情報
- **Keyword Details** ― キーワードごとの掲載順位・トラフィック
- **Crawl Stats** ― クロール統計（クロールされたページ数、クロールエラーなど）
- **URLとサイトマップの送信** ― URLの送信・サイトマップの追加

## 認証方法

認証方法は2種類あります。

### 1. API Key（簡易）

API KeyはBing Webmaster Toolsの設定画面から生成できます。

1. [Bing Webmaster Tools](https://www.bing.com/webmasters) にログイン
2. 右上の **Settings** → **API Access** を開く
3. 利用規約に同意し、**Generate API Key** をクリック

API Keyはユーザー単位で発行されるため、同じユーザーが所有する全サイトに共通で使えます。

:::warning
API Keyは第三者と共有しないでください。漏洩した場合は設定画面から削除して再生成できます。
:::

### 2. OAuth 2.0（推奨）

1. Bing Webmaster Toolsの Settings → API Access でOAuth Clientを登録
2. Client IDとClient Secretを発行
3. Authorization Code Flowでアクセストークンを取得
4. リフレッシュトークンで自動更新

スコープ:
- `webmaster.read` ― 読み取り専用アクセス
- `webmaster.manage` ― 読み書きアクセス

## エンドポイントとプロトコル

APIは3つのプロトコルに対応しています。

| プロトコル | ベースURL |
|-----------|-----------|
| SOAP | `https://ssl.bing.com/webmaster/api.svc/soap?apikey=KEY` |
| POX (XML) | `https://ssl.bing.com/webmaster/api.svc/pox/METHOD?apikey=KEY` |
| JSON | `https://ssl.bing.com/webmaster/api.svc/json/METHOD?apikey=KEY` |

JSONエンドポイントが最も扱いやすいです。

## 主なAPIメソッド

| メソッド | 説明 |
|----------|------|
| `GetRankAndTrafficStats` | サイト全体のトラフィック統計 |
| `GetQueryStats` | 上位クエリのトラフィック |
| `GetPageStats` | 上位ページのトラフィック |
| `GetKeywordStats` | キーワードの履歴統計 |
| `GetCrawlStats` | クロール統計 |
| `GetCrawlIssues` | クロールの問題一覧 |
| `GetUrlInfo` | 特定URLのインデックス情報 |
| `GetPageQueryStats` | 特定ページのクエリ別トラフィック |
| `GetQueryPageStats` | 特定クエリのページ別トラフィック |
| `SubmitUrl` | URLをBingに送信 |
| `SubmitUrlBatch` | URL一括送信 |
| `SubmitFeed` | サイトマップ送信 |

## 使用例（Node.js）

```javascript
const API_KEY = 'YOUR_API_KEY';
const SITE_URL = 'https://www.example.com/';

// トラフィック統計を取得
const res = await fetch(
  `https://ssl.bing.com/webmaster/api.svc/json/GetRankAndTrafficStats?apikey=${API_KEY}&siteUrl=${encodeURIComponent(SITE_URL)}`
);
const data = await res.json();
console.log(data);
```

OAuth 2.0を使う場合は、AuthorizationヘッダーにBearerトークンを指定します。

```javascript
const res = await fetch(
  'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrl',
  {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${ACCESS_TOKEN}`
    },
    body: JSON.stringify({
      siteUrl: 'https://www.example.com/',
      url: 'https://www.example.com/new-page'
    })
  }
);
```

## Google Search Console APIとの違い

| 項目 | Bing Webmaster API | Google Search Console API |
|------|-------------------|--------------------------|
| 認証 | API Key or OAuth 2.0 | OAuth 2.0（Service Account可） |
| プロトコル | SOAP / POX / JSON | REST（JSON） |
| SDK | .NET SDKのみ | 多数の言語対応 |
| データ粒度 | 日単位 | 日単位 |
| クエリデータ | あり | あり |
| クロールデータ | あり | なし |

## まとめ

Bing Webmaster Tools APIを使うと、検索パフォーマンスデータをプログラムで取得できるため、GA4やAdSenseのデータと統合した分析が可能になります。特にBing経由のトラフィックが多いサイトでは、定期的なデータ取得とレポート自動化に役立ちます。

:::conclusion
Bing Webmaster Tools APIはJSONエンドポイントを備えており、Node.jsから容易に利用できます。認証はAPI Key（簡易）またはOAuth 2.0（推奨）の2通り。Google Search Console APIと併用することで、検索チャネル全体をカバーした分析基盤を構築できます。
:::
