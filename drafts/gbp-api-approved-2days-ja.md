---
title: "GoogleビジネスプロフィールAPI申請が2日で承認された話"
date: 2026-02-17 12:00:00
categories: 
  - GAS
tags: 
  - Google Business Profile
  - API
  - Google Apps Script
  - GCP
status: publish
slug: gbp-api-approved-2days
featured_image: ../images/2026/gbp-api-approved-2days.png
---

先日公開した「[GoogleビジネスプロフィールAPIでデータ取得する方法と申請手順を完全解説](https://www.zidooka.com/archives/3736)」ですが、実際に自分も申請してみたところ、**2日で承認されました**。

:::conclusion
2月9日（日）に申請 → 2月11日（火）に承認メール到着。自社店舗の分析目的で申請すれば、意外と早く通る可能性があります。
:::

## 申請から承認までのタイムライン

| 日付 | 出来事 |
|------|--------|
| 2月9日（日） | 申請フォームから送信 |
| 2月11日（火） | 承認メール到着 |

日曜日に申請して火曜日に承認という、非常にスピーディーな対応でした。審査期間は「3〜5営業日」とされていますが、実際には2日（しかも日曜含む）でした。

## 申請時に記入した内容

申請フォームの主要な項目と、実際に入力した内容を公開します。

### 基本情報

- **プロジェクト名**: ZIDOOKA内部ツール開発
- **使用目的**: 自社店舗のパフォーマンスデータ分析
- **店舗数**: 約20店舗

### 補足説明（英語全文）

```
We operate approximately 20 physical business locations under a single Google Business Profile account, and we fully own and manage these locations.

We plan to use the Business Profile API exclusively to retrieve performance insights such as search views, map views, customer actions, reviews, and ratings for internal analytics and reporting purposes.

The data will be used only within our company for business analysis and decision-making. We will not provide this data to third parties, resell it, or expose it in any public-facing product or service.

The implementation is based on Google Apps Script using OAuth 2.0, and the data will be stored internally in Google Sheets and visualized via Looker Studio.
```

:::note
「宣伝目的」は選択せず、「分析・内部利用」を強調しました。自社店舗のデータ取得であれば、承認率が高いようです。
:::

## 承認メールの内容

2月11日に届いた承認メールの要約です。

> Congratulations! Your project has been approved to use the Google Business Profile API!
> 
> To find and enable the API, log in to Developers Console and in the search box enter Google Business Profile API.

メールには以下の情報も記載されていました。

- **サポートページ**へのリンク
- **ポリシー確認**のお願い
- Googleとのパートナーシップを示唆する表現は避けるよう注意喚起

## 承認後の対応

承認メールを受け取った後、すぐに以下を行いました。

1. **GCPコンソールでAPI有効化の確認**
2. **GASでの接続テスト**
3. **店舗データの取得テスト**

:::step
承認後の実装手順については、[GoogleビジネスプロフィールAPIでデータ取得する方法と申請手順を完全解説](https://www.zidooka.com/archives/3736)を参照してください。GASでの具体的なコード例も掲載しています。
:::

## 申請が通りやすい条件

今回の体験と、これまでの知見から、申請がスムーズに通る条件をまとめました。

| 項目 | 推奨 | 避けるべき |
|------|------|------------|
| **使用目的** | 自社店舗の分析・管理 | 競合店舗のデータ収集 |
| **データの扱い** | 社内利用のみ | 第三者提供・SaaS販売 |
| **店舗所有** | 実際に管理権限がある | 管理権限のない店舗 |
| **Webサイト** | 運営実績がある | 存在しない・作成中 |

## まとめ

:::conclusion
自社店舗の分析目的であれば、GoogleビジネスプロフィールAPIの申請は2〜3日で承認される可能性があります。必要な情報を正直に記入し、内部利用であることを明確に伝えましょう。
:::

これでGASを使った店舗データの自動化が可能になりました。次はLooker Studioとの連携でダッシュボード化を進めていきます。

---

**関連記事**

- [【GAS】GoogleビジネスプロフィールAPIでデータ取得する方法と申請手順を完全解説](https://www.zidooka.com/archives/3736)
- [Complete Guide to Google Business Profile API with GAS (English)](https://www.zidooka.com/archives/3741)
