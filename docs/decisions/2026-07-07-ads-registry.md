---
created: 2026-07-07
status: running
verify_date: 2026-07-21
title: 広告一元管理（inc/ads.php プレースメント台帳）導入
---

## 判断
分散していた広告実装（AdSense×3箇所 + A8×2系統）を `inc/ads.php` の
プレースメント台帳 + 共通レンダラに集約。設定はWP管理画面
[外観 > Ads Settings] のJSONでデプロイなしに上書き可能にした。

## 変更内容
- `inc/ads.php` 新設: 台帳（placements / a8_offers）、共通レンダラ、
  AdSenseローダー、A8本文内挿入フィルタ、PostHog計測、管理画面
- `functions.php`: 旧広告コード4ブロック撤去（ローダー / クリック計測 /
  xserverバナー / サイドバーバナー群）
- `single.php` / `front-page.php`: 直書き `<ins>` を `zidooka_render_ad()` 呼び出しに置換
- `style.css`: `.zdk-ad` 共通スタイル（ラベル、CLS対策のmin-height、未充填枠の自動折りたたみ）
- `assets/single.css`: 本文タイポグラフィ改善（20px→17/18px、見出し階層、表の横スクロール）
- `scripts/check-a8-links.mjs` + `npm run ads:check`: A8リンク死活チェック
- `data/ads/a8/`: 月次成果CSVの置き場を新設

## 挙動の変更点（意図的）
1. 本文内A8バナーに「広告」ラベル追加（ステマ規制対応。従来はラベルなし）
2. 段落10未満の記事では本文内バナーを1回に制限（従来は3・5段落目に同一バナー2回）
3. 未充填AdSense枠（白枠）を自動で畳む
4. PostHogイベント統一: `ad_impression` / `ad_click` / `ad_unfilled`
   （旧 `banner_exposure` / `banner_click` は廃止。7/7以前のデータは旧名で参照）
5. AdSenseクリックのプロキシを click リスナー → iframe フォーカス検知に変更
   （cross-origin iframe では click がほぼ拾えていなかった）

## 根拠
- 同一スロットID・実装分散・計測不統一のままでは配置A/Bの効果測定ができない
  （docs/ADS_MANAGEMENT.md P1〜P6）
- 管理画面JSONにより、配置ON/OFF・スロット差し替え・案件差し替えがデプロイなしで可能になる

## 期待する効果
- PostHogで placement 別の impression / click / unfilled が取れるようになる
- ステマ規制リスクの解消
- 未充填白枠の折りたたみとCLS対策で閲覧体験改善

## 検証日
2026-07-21（2週間後）: PostHogで `ad_impression` の placement 別内訳と
`ad_unfilled` 率を確認。AdSense収益が導入前2週比で大きく落ちていないか確認。

## 結果（事後記入）
-
