---
created: 2026-07-15
status: running
verify_date: 2026-08-12
title: X error article search-intent separation
---

## 判断

検索意図が重なっていたX/Twitterエラー記事を、総合ハブと特定ユーザーケースに明確化した。記事統合やリダイレクトは行わず、titleとexcerptを検索クエリに合わせて変更した。

## 根拠

GSC 2026-06-17〜2026-07-15:

- `/archives/3017`: 38,110 impressions / 325 clicks / CTR 0.85% / position 7.95
- `/archives/4154`: 6,017 impressions / 21 clicks / CTR 0.35% / position 8.67
- 3017は「ポストを読み込めません 特定の人」でCTR 2.79%・4.75位だが、広いクエリではCTR 0.2〜0.3%。
- 4154は「現在ポストを取得できません」「X 検索 ポストを読み込めません」など総合的な検索意図で表示されていた。

## 変更

- 3017: 特定の人・削除・鍵垢・凍結の見分け方にtitle/excerptを限定。
- 4154: 「ポストを読み込めません」「現在ポストを取得できません」の総合ハブとしてtitle/excerptを明確化。
- 本番HTMLの重複SEOタグを修正し、description 3個→1個、canonical複数→1個へ正常化。
- 両URLをIndexNowへ通知。

## 期待する効果

- 3017の特定ユーザー系クエリ順位・CTRを維持しつつ、広いクエリの誤マッチを減らす。
- 4154の総合エラー系CTRを改善する。
- 重複メタタグをなくし、検索エンジンへ一意のtitle/description/canonicalを渡す。

## 検証日

2026-08-12。過去28日と変更後28日をページ別・クエリ別に比較する。

## 成功基準

- 3017の「特定の人」系CTRが2.5%以上を維持。
- 4154のページCTRが0.35%から0.70%以上へ改善、またはクリック数が同程度の表示回数で増加。
- description/canonicalが引き続き各1個。

## 結果（事後記入）

-
