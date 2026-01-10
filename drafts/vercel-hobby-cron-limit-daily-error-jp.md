---
title: "Vercel HobbyプランでCronが動かない？「Hobby accounts are limited to daily cron jobs」の対処法"
date: 2025-12-26 10:10:00
categories: 
  - vercel
tags: 
  - Vercel
  - Cron
  - Troubleshooting
  - Hobby Plan
status: publish
slug: vercel-hobby-cron-limit-daily-error-jp
featured_image: ../images/2025/vercel-hobby-cron-limit.png
---

VercelでCron Jobsを設定したところ、以下のようなエラーが表示されてデプロイに失敗することがあります。

```
Hobby accounts are limited to daily cron jobs.
This cron expression (0 21,9 * * *) would run more than once per day.
```

結論から言うと、これは**無料（Hobby）プランの仕様による制限**です。
この記事では、「なぜこのエラーが出るのか」と「どう直せばいいのか」を、実際の `vercel.json` の例付きで解説します。

:::conclusion
**結論：いま起きていること**
Hobbyプランでは、Cron Jobsは**「1日1回」**しか実行できません。
エラーが出ているCron式（例：`0 21,9 * * *`）は1日2回以上実行する設定になっているため、制限に引っかかっています。
:::

## 1. エラーの原因：無料プランは「1日1回まで」

VercelのHobby（無料）プランには、Cron Jobsに関して明確な制限があります。

- **Cronの実行は1日1回まで**
- **1日に2回以上実行されるCron式は使用不可**

そのため、例えば以下のような設定はエラーになります。

```json
"schedule": "0 21,9 * * *"
```

これは「毎日 9時と21時の**2回**」実行する設定なので、無料プランでは許可されません。

## 2. 解決策：Cron式を「1日1回」にする

無料プランのまま解決するには、Cron式を「1日1回だけ実行されるもの」に修正する必要があります。

### OKな設定例

例えば、「毎日21時に1回だけ」なら問題ありません。

```json
"schedule": "0 21 * * *"
```

### 実際に動く vercel.json の例

私が最終的に使用してエラーが解消された設定ファイルは以下の通りです。

```json
{
  "functions": {
    "api/*.js": {
      "maxDuration": 10
    }
  },
  "crons": [
    {
      "path": "/api/feed",
      "schedule": "0 21 * * *"
    }
  ]
}
```

**ポイント:**
- `schedule`: `0 21 * * *` （1日1回なのでOK）
- `path`: `/api/feed` （実行したいAPIエンドポイント）

この設定であれば、Hobbyプランでも問題なくデプロイ可能です。

---

## 3. 「どうしても1日2回以上実行したい」場合は？

無料プランのCron機能ではどうやっても1日1回が限界です。
もし1日複数回の実行が必要な場合は、以下のいずれかの方法を検討してください。

### 方法A：Proプランにアップグレードする
Proプラン（有料）にすれば、Cron Jobsの制限が緩和され、1日複数回の実行が可能になります。

### 方法B：外部のCronサービスを使う（おすすめ）
VercelのCron機能を使わず、**外部からVercelのAPIを叩く**形にすれば、回数制限は関係ありません。

- **GitHub Actions**: 定期実行ワークフローを作る（無料枠あり）
- **Google Cloud Scheduler**: HTTPトリガーで叩く
- **GAS (Google Apps Script)**: トリガー機能で `UrlFetchApp` を実行

:::note
外部から叩く場合は、APIエンドポイントが公開されている必要があります。セキュリティが気になる場合は、リクエストヘッダーで簡易的な認証を入れるなどの工夫が必要です。
:::

---

## 4. Cronが動いているか確認する方法

設定が正しくても、本当に動いているか不安な場合はログを確認しましょう。

1. Vercel Dashboardを開く
2. 対象のプロジェクトを選択
3. **Logs** タブを開く
4. 設定した時間（例：21時前後）に `GET /api/feed` などのログが出ていれば成功です

## まとめ

- エラーの原因は**「1日2回以上動くCron式」**を設定していること。
- Hobbyプランでは**「1日1回」**（例：`0 21 * * *`）に修正すれば解決する。
- 複数回実行したいなら、Proプランにするか、GitHub Actionsなどの外部サービスを利用する。

VercelのCron Jobsは手軽で便利ですが、Hobbyプランの制限には注意しましょう。
