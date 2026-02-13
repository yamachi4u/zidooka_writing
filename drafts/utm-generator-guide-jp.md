---
title: "UTM生成ツールの使い方 - 入力候補・短縮URL・QRコードまで一気に作成"
date: 2026-03-10 09:00:00
categories:
  - 便利ツール
tags:
  - UTM
  - URL短縮
  - QRコード
  - マーケティング
  - GA4
  - is.gd
featured_image: ../images/2026/utm-generator-guide-thumbnail.png
status: publish
slug: utm-generator-guide-jp
---

Google Analyticsでキャンペーンの流入元を正しく計測するには、UTMパラメータ付きのURLを作成する必要があります。しかし手動でパラメータを組み立てると、 `&` の付け忘れやパラメータ名のタイプミスが起こりがちです。

ZIDOOKA Toolsの[UTM生成ツール](https://tools.zidooka.com/jp/utmtools/generator)は、フォームに入力するだけでUTM付きURLを自動生成し、そのまま短縮URLやQRコードの作成まで一画面で完結できるツールです。

## UTM生成ツールの特徴

### すべての入力欄が任意

一般的なUTMビルダーでは `utm_source`、`utm_medium`、`utm_campaign` の3項目が必須とされています。しかし実際の運用では、まず `utm_source` だけ付けてテストしたいケースや、 `utm_campaign` だけで施策を区別したいケースもあります。

このツールではすべての入力欄を任意にしています。URLだけ入力して1つのパラメータだけ追加することも、6項目すべてを埋めることも自由にできます。

### 入力候補（datalist）で素早く入力

各パラメータのフィールドには、よく使われる値が入力候補として登録されています。

:::example
- utm_source: google, facebook, twitter, instagram, linkedin, youtube, tiktok, newsletter, line, qrcode など16種類
- utm_medium: cpc, organic, social, email, referral, display, affiliate, banner, video, push など14種類
- utm_campaign: spring_sale, summer_campaign, product_launch, holiday_promo など12種類
- utm_term, utm_content にもそれぞれ候補を用意
:::

入力欄をクリックすると候補が表示されるので、選択するだけで入力が完了します。もちろん候補にない値を自由に入力することもできます。

### リアルタイムURL生成

入力内容が変わるたびにURLがリアルタイムで更新されます。生成ボタンを押す操作は不要で、URLフィールドには常に最新の結果が表示されます。

## 短縮URL機能

生成されたURLはUTMパラメータが付くため長くなりがちです。「オプション」セクションの「is.gdで短縮URLを生成する」にチェックを入れると、is.gdのAPIを使って短縮URLが自動生成されます。

:::note
is.gdは無料で使える短縮URLサービスです。生成した短縮URLは削除できないため、テスト用URLの短縮には注意してください。
:::

短縮URLもワンクリックでクリップボードにコピーできます。

## QRコードの生成

UTM生成ツールにはQRコードプレビュー機能が組み込まれています。URLを入力するとQRコードが自動で表示されます。

- サイズはS（150px）/ M（200px）/ L（300px）/ XL（400px）から選択できます
- 前景色と背景色をカラーピッカーで変更できます
- PNGファイルとしてダウンロードできます

さらに詳細な設定が必要な場合は「QRコードを生成」ボタンを押すと、[QRコード生成ツール](https://tools.zidooka.com/jp/utmtools/qr-generator)にURLがそのまま引き継がれます。QRコード生成ツールでは誤り訂正レベルの選択など追加のカスタマイズが可能です。

## 実際の使い方

春のセールキャンペーン用のURLを作成する例で手順を説明します。

:::step
1. 「ウェブサイトURL」にランディングページのURLを入力する（例：`example.com/sale`）
2. utm_source の入力欄で `google` を選択する
3. utm_medium で `cpc` を選択する
4. utm_campaign に `spring_sale_2026` と入力する
5. 「生成されたURL」セクションに完成したURLが表示される
6. 「コピー」ボタンでクリップボードにコピーする
:::

必要に応じて短縮URLの生成やQRコードのダウンロードを追加で行います。

## パラメータの命名規則

UTMパラメータは自由に値を設定できますが、チーム内で命名規則を統一しておくとGA4でのデータ分析がスムーズになります。

| 項目 | 推奨ルール | 例 |
|---|---|---|
| 区切り文字 | アンダースコア `_` またはハイフン `-` | spring_sale |
| 大文字小文字 | すべて小文字に統一 | google（Googleではなく） |
| 日付の表記 | YYYYMM または YYYY を末尾に | spring_sale_202603 |
| スペース | `+` またはアンダースコアで置換 | running+shoes |

:::warning
GA4ではパラメータの大文字小文字が区別されます。`Google` と `google` は別の値として集計されるため、小文字に統一するのがおすすめです。
:::

## まとめ

UTM生成ツールを使えば、パラメータの付け忘れやタイプミスを防ぎながら、短縮URL・QRコード作成まで一画面で完結できます。入力候補を活用すれば初めての方でも迷わず操作できます。

[UTM生成ツールを開く](https://tools.zidooka.com/jp/utmtools/generator)

UTM生成ツール：
<https://tools.zidooka.com/jp/utmtools/generator>

## 参考リンク

1. Google アナリティクス ヘルプ: カスタム URL でキャンペーン データを収集する
https://support.google.com/analytics/answer/1033863

2. is.gd URL Shortener
https://is.gd/
