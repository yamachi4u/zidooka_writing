# Analytics TODO

更新日: 2026-03-26 JST

## 目的

GA4 / GSC まわりの調査と改善を、単発メモではなく継続タスクとして追えるようにするための TODO です。

## In Progress

### `(not set)` 原因調査

- [x] `landing page = (not set)` の基本内訳確認
- [x] `source / medium`、`browser`、`device` 切り分け
- [x] 対象URLが単一ページではないことを確認
- [x] 実サイトの GA 実装が `gtag` 直貼りであることを確認
- [x] 軽い debug probe を本番反映
- [x] 2026-03-27 以降に debug event を GA4 で回収
- [x] `hidden / prerender / bfcache` 由来かを一次判定
- [ ] 必要なら恒久対策を決める

関連:
- [20260326_ga4_not_set_investigation.md](C:/Users/user/Documents/zidooka_writing/daily/20260326_ga4_not_set_investigation.md)
- [20260414_seo_actions_and_debug.md](C:/Users/user/Documents/zidooka_writing/daily/20260414_seo_actions_and_debug.md)

## Next

### key event 整理

- [ ] 主要CV候補を決める
  - 相談フォーム送信
  - メールクリック
  - 主要CTAクリック
- [ ] 既存イベントの取得状況を確認する
- [ ] GA4 管理画面で key event にする対象を決める
- [ ] 必要ならイベント実装を追加する
- [ ] 週次で見る簡易レポートの軸を決める

## Backlog

### レポート運用

- [ ] `(not set)` を毎回別枠で見る簡易レポートを固定化
- [ ] `landing page x source / medium` の定点確認を週次化
- [ ] `key event` ベースで上位記事を見直せる形にする

### コンテンツ改善

- [ ] 上位記事の内部リンク強化を継続
- [ ] CTA の文言と配置を上位記事で揃える
- [ ] 流入は強いが回遊が弱い記事の優先修正

## Done

- [x] `/archives/149` の上部広告整理と補足追記
- [x] `/archives/209` の導入整理と崩れ文言修正
- [x] `(not set)` 調査メモ作成
- [x] debug probe の本番投入
