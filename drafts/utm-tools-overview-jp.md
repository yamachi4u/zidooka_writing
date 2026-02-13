---
title: "UTMパラメータの基本と無料ツール3種の使い方ガイド"
date: 2026-02-24 09:00:00
categories:
  - 便利ツール
tags:
  - UTM
  - マーケティング
  - Google Analytics
  - GA4
  - QRコード
  - トラッキング
featured_image: ../images/2026/utm-tools-overview-thumbnail.png
status: publish
slug: utm-tools-overview-jp
---

マーケティング施策のアクセス解析で「このユーザーはどこから来たのか」を正確に把握するには、UTMパラメータの活用が欠かせません。ところが、毎回URLを手動で組み立てたり、パラメータの綴りを間違えたりした経験のある方も多いのではないでしょうか。

【結論】ZIDOOKA Toolsに追加した3つの無料UTMツール（生成・QRコード・解析）を使えば、UTM付きURLの作成から検証までブラウザだけで完結します。

## UTMパラメータとは

UTMパラメータ（Urchin Tracking Module）は、URLの末尾に `?utm_source=google&utm_medium=cpc` のような形式で付与するクエリ文字列です。Google AnalyticsなどのアクセスツールがこのパラメータをGA4の「トラフィック獲得」レポートに反映します。

主要なパラメータは以下の5つです。

| パラメータ | 用途 | 入力例 |
|---|---|---|
| utm_source | 流入元の名前 | google, facebook, newsletter |
| utm_medium | メディアの種類 | cpc, email, social |
| utm_campaign | キャンペーン名 | spring_sale, product_launch |
| utm_term | 検索キーワード | running+shoes |
| utm_content | 広告バリエーション | sidebar_banner, text_link |

加えてGA4では `utm_id`（キャンペーンID）も利用できます。どのパラメータを使うかは施策次第で、すべてを埋める必要はありません。

## ZIDOOKA Tools のUTMツール3種

今回リリースしたのは次の3つのツールです。すべてブラウザ上で動作し、データはサーバーに送信されません。

### 1. [UTM生成ツール](https://tools.zidooka.com/jp/utmtools/generator)

URLとパラメータを入力するだけでUTM付きURLを即座に生成します。

- 各フィールドに入力候補（datalist）を用意しているので、よく使う値をすぐに選べます
- is.gdによる短縮URL生成にも対応しています
- 生成したURLからそのままQRコードのプレビュー・ダウンロードが可能です
- 「QRコードを生成」ボタンを押すと、QRコード生成ツールにURLを引き継いで詳細設定に移れます

:::note
すべての入力欄は任意です。必要な項目だけ入力してURLを作成できます。
:::

### 2. [QRコード生成ツール](https://tools.zidooka.com/jp/utmtools/qr-generator)

URL以外にもテキスト、メール、電話番号、SMS、WiFi接続情報、vCard（連絡先）など7種類のQRコードを作成できます。

- 種類を選択するとサンプルデータが自動入力され、すぐにプレビューを確認できます
- サイズ（S/M/L/XL）、前景色・背景色、誤り訂正レベルのカスタマイズに対応しています
- UTM付きURLを貼り付けると自動検出バナーが表示され、トラッキング対応QRコードであることを視覚的に確認できます

### 3. [UTM解析ツール](https://tools.zidooka.com/jp/utmtools/parser)

既存のUTM付きURLを貼り付けると、含まれているパラメータを一覧で表示します。

- 各パラメータの値を個別にコピーできます
- UTM以外のクエリパラメータも別セクションに表示されます
- サンプルURLの読み込み機能もあるので、ツールの動作をすぐに確認できます

## 3ツールの連携フロー

これら3つのツールは連携して使うことで効果を発揮します。

:::step
1. UTM生成ツールでキャンペーン用のURLを作成する
2. 「QRコードを生成」ボタンでQRコード生成ツールにURLを引き継ぐ
3. サイズや色を調整してQRコードをPNGダウンロードする
4. 印刷物やPOPに貼り付けて配布する
5. 受け取ったURLの確認が必要な場合はUTM解析ツールに貼り付けて検証する
:::

## 活用シーン

UTMパラメータは特にオフライン施策とオンライン計測を橋渡しする場面で力を発揮します。

- チラシやポスターにQRコードを印刷して `utm_source=flyer` で流入を計測する
- 名刺にQRコードを配置して `utm_source=business_card` で効果を把握する
- 店頭POPに `utm_source=store_pop` を設定して来店施策のWeb流入を追跡する
- イベント配布物に `utm_campaign=event_2026` を設定してイベント別の効果を比較する

## まとめ

UTMパラメータの管理は地味な作業ですが、正確なデータを得るための基盤です。手動でURLを組み立てる手間やタイプミスのリスクを減らすために、ぜひ[ZIDOOKAのUTMツール](https://tools.zidooka.com/jp/utmtools/)を活用してみてください。

UTMツール一覧ページ：
<https://tools.zidooka.com/jp/utmtools/>

## 参考リンク

1. Google アナリティクス ヘルプ: カスタム URL でキャンペーン データを収集する
https://support.google.com/analytics/answer/1033863

2. Google アナリティクス ヘルプ: トラフィック ソースのディメンション
https://support.google.com/analytics/answer/9143382
