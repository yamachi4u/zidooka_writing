---
title: "PostHog vs 競合サービス比較 ─ A/Bテスト・料金・機能を徹底比較" 
date: 2026-06-13 09:00:00
categories:
  - 便利ツール
tags:
  - PostHog
  - プロダクトアナリティクス
  - A/Bテスト
  - Google Analytics
  - Mixpanel
  - Amplitude
  - Plausible
  - Matomo
status: future
slug: posthog-comparison-jp
---

先日、PostHog の魅力について紹介しましたが、「他のツールと比べてどうなの？」という声も多いはず。今回は PostHog を中心に、主要な競合サービスと比較していきます。

比較するのは以下の6サービス：

- **PostHog** ─ オープンソース・プロダクトアナリティクス
- **Google Analytics（GA4）** ─ 業界標準のアクセス解析
- **Mixpanel** ─ プロダクト分析の老舗
- **Amplitude** ─ エンタープライズ向けプロダクト分析
- **Plausible** ─ プライバシー重視の軽量アナリティクス
- **Matomo** ─ セルフホスト型アナリティクス

![PostHog公式サイト](../images-agent-browser/posthog-top.png)

## 料金比較

| サービス | 無料枠 | 有料プラン（最低額） | セルフホスト |
|---------|--------|-------------------|------------|
| **PostHog** | 月100万イベント無料 | Pay-as-you-go（従量制） | ✅（OSS・MITライセンス） |
| **GA4** | 無制限（実質無料） | Enterprise のみ有料 | ❌ |
| **Mixpanel** | 月100万イベント無料 | Growth $0.28/1K events | ❌ |
| **Amplitude** | 10K MTU / 月200万イベント | Plus $49/月〜 | ❌ |
| **Plausible** | 30日トライアルのみ | $9/月（月1万PV〜） | ✅（OSS・要サーバー） |
| **Matomo** | 無制限（On-Premise Community） | Cloud €29/月（月5万ヒット）〜 | ✅（OSS） |

:::note
PostHog の無料枠（100万イベント）は全プロジェクト共通。Pay-as-you-go にすると6プロジェクトまで管理できます。
:::

![Mixpanel料金ページ](../images-agent-browser/mixpanel-pricing.png)

## A/Bテスト機能

| サービス | A/Bテスト | 備考 |
|---------|----------|------|
| **PostHog** | ✅ 標準搭載 | 機能フラグと統合、コードベースで実装 |
| **GA4** | ❌ 標準なし | Google Optimize（終了）→ 別ツールが必要 |
| **Mixpanel** | ⚠️ Add-on | Experiments 機能あり（別途課金） |
| **Amplitude** | ✅ Web Experimentation | Plus プラン以上で利用可 |
| **Plausible** | ❌ | シンプルなアクセス解析のみ |
| **Matomo** | ✅ Enterprise プラン以上 | 有料プラグインとして提供 |

:::conclusion
A/Bテストに特化するなら、PostHog が標準搭載かつ追加料金なしで使えるのが大きな差別化ポイントです。
:::

:::note
さらに、PostHog の A/B テストは AI エージェントとの相性が抜群。「このボタンのテキストサイズを A/B テストしておいて」と自然言語で指示するだけで、Agent が機能フラグの設定からコード実装、結果の統計解析まで自動でやってくれます。人間は「何をテストするか」だけ決めれば終わりです。
:::

## セッションリプレイ

| サービス | セッションリプレイ | 無料枠 |
|---------|-----------------|--------|
| **PostHog** | ✅ 標準搭載 | 月5000録画無料 |
| **GA4** | ❌ | なし |
| **Mixpanel** | ✅ | 月1万録画（Free）、2万（Growth） |
| **Amplitude** | ✅ | 月1000録画（Starter） |
| **Plausible** | ❌ | なし |
| **Matomo** | ✅ Heatmap & Session Recording | 有料プラグイン（Cloud/On-Premise） |

![Amplitude料金ページ](../images-agent-browser/amplitude-pricing.png)

## セルフホスト / データ所有権

データの完全な所有権やプライバシーが重要な場合、セルフホストの可否は重要な判断基準です。

| サービス | セルフホスト | ライセンス |
|---------|------------|-----------|
| **PostHog** | ✅ | MIT（完全オープンソース） |
| **GA4** | ❌ | クローズド |
| **Mixpanel** | ❌ | クローズド |
| **Amplitude** | ❌ | クローズド |
| **Plausible** | ✅ | AGPL（OSS） |
| **Matomo** | ✅ | GPL（OSS） |

## 機能フラグ

| サービス | 機能フラグ |
|---------|----------|
| **PostHog** | ✅ 標準搭載（月100万リクエスト無料） |
| **GA4** | ❌ |
| **Mixpanel** | ⚠️ Add-on |
| **Amplitude** | ✅ 無制限（全プラン） |
| **Plausible** | ❌ |
| **Matomo** | ❌ |

## プライバシー / GDPR対応

| サービス | プライバシー | Cookie不要 | EUホスティング |
|---------|------------|-----------|--------------|
| **PostHog** | セルフホストで完全制御 | ⚠️ SaaSはCookie使用 | Frankfurt/US（SaaS） |
| **GA4** | Google依存 | ❌ | ❌ |
| **Mixpanel** | 事業者依存 | ❌ | 選択可能 |
| **Amplitude** | 事業者依存 | ❌ | 選択可能 |
| **Plausible** | ✅ プライバシーバイデザイン | ✅ Cookie不要 | ✅ EUサーバーのみ |
| **Matomo** | ✅ セルフホストで完全制御 | ⚠️ 設定に依存 | 選択可能 |

![Plausible料金ページ](../images-agent-browser/plausible-pricing.png)

## どのサービスを選ぶべきか

### PostHog が向いている人
- 開発チームでプロダクト分析・A/Bテスト・機能フラグを統合したい
- 無料で始めて、規模に応じてスケールしたい
- 将来的にセルフホストも視野に入れたい
- **A/Bテストを追加費用なしで使いたい**

### Google Analytics が向いている人
- すでにGA4を使っていて乗り換えコストをかけたくない
- 広告（Google Ads）との連携が重要
- シンプルなアクセス解析だけで十分

### Mixpanel / Amplitude が向いている人
- エンタープライズ級の高度な分析が必要
- 大規模なデータセットを扱う
- すでに同ツールのエコシステムに投資している

### Plausible が向いている人
- シンプルでプライバシーフレンドリーなアクセス解析が欲しい
- Cookieバナーを消したい
- ブログや小規模サイトのトラフィック把握のみ

### Matomo が向いている人
- 完全なデータ所有権が必須要件
- 規制業界（医療・金融など）で運用する
- OSSコミュニティのエコシステムを活用したい

![Matomo料金ページ](../images-agent-browser/matomo-pricing.png)

:::conclusion
**A/Bテスト・機能フラグ・セッションリプレイを追加費用なしで使いたいなら PostHog 一択。** 無料枠も100万イベントと最も generous で、Pay-as-you-go にすれば6プロジェクト運用も可能。単なるアクセス解析ではなく「Product OS」としての統合体験を求めている人に強くおすすめします。
:::

関連記事：
- [PostHogがすごい ─ 1Mイベント無料＋A/Bテスト＋セルフホストの最強プロダクトアナリティクス](https://www.zidooka.com/archives/4444)
- [AIエージェントにA/Bテストの実装と解析を任せる方法](https://www.zidooka.com/archives/4461)

### 参考情報

各サービスの料金ページ（2026年6月時点）：
- PostHog: https://posthog.com/pricing
- Google Analytics: https://marketingplatform.google.com/about/analytics/
- Mixpanel: https://mixpanel.com/pricing/
- Amplitude: https://amplitude.com/pricing
- Plausible: https://plausible.io/#pricing
- Matomo: https://matomo.org/pricing/
