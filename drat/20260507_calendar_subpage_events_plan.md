---
title: カレンダー下層ページ 改善計画（痛みベース＋GA4イベント設計）
created: 2026-05-07
tags: [calendar, GA4, custom-events, UX]
---

# カレンダー下層ページ：痛み→GA4イベント設計

## 前提：この計画が対象とするページ

- 月別ビュー: `/jp/calendar/2026/05` など（年月別）
- 個別吉日解説ページ: `/jp/calendar/taian`, `/jp/calendar/ichiryumanbai`, `/jp/calendar/tensha` など
- トップページ（`/jp/calendar`）は別エージェント担当のため対象外

---

## ユーザーの痛みと仮説

### Pain A：「日程を決めたいけど迷っている」

**ユーザー像**: 引越し・結婚・開業など具体的な予定があり、「縁起の良い日」を基準に日程を決めたい
**来訪パス**: Google/Bingで `2026年5月 引越し 吉日` などで月別ビューに流入
**本当に欲しいもの**: 「◯◯をするなら、△△の日がベスト」という確定的なアドバイス

**GA4検証イベント**:
- `calendar_purpose_filter_used` — 用途フィルタが使われたか？（痛みの顕在化）
- `calendar_date_detail_opened` — 特定の日付をクリックして詳細を見たか？（比較行動）
- `calendar_date_detail_to` — from→to の日付間移動（複数日を比較している）

### Pain B：「この吉日が何かわからない」

**ユーザー像**: カレンダーに「大安」と書いてあるが意味がわからない、あるいは検索で個別解説ページに流入
**来訪パス**: 「大安 意味」「一粒万倍日 とは」などの検索クエリ
**本当に欲しいもの**: 簡潔で信頼できる解説＋「自分ごと」としての活用例

**GA4検証イベント**:
- `calendar_glossary_entry` — 該当月ビュー上のラベル（大安など）から解説を開いたか
- `calendar_glossary_scroll_depth` — 解説ページをどこまで読んだか
- `calendar_glossary_cta_click` — 解説ページからカレンダーに戻って日程確認したか

### Pain C：「吉日が重なる日がどれだけ特別かわからない」

**ユーザー像**: 大安と一粒万倍日が重なる日を見つけたが「これってどれくらいレア？」と疑問
**来訪パス**: 月別ビューで重複日を発見、あるいは `2026年 大安 一粒万倍日 重なる` で検索
**本当に欲しいもの**: 重複の希少性を可視化した情報

**GA4検証イベント**:
- `calendar_overlap_detail` — 重複日の詳細を開いたか
- `calendar_overlap_share` — 重複日をSNSで共有したか（価値を感じているシグナル）

### Pain D：「良い日がわかったけど、次に何をすれば？」

**ユーザー像**: 引越しなら「大安の日がベスト」と知ったが、その後何をすれば？業者手配？役所手続き？
**来訪パス**: 記事→カレンダーへの導線、または月別ビューで用途フィルタ使用後
**本当に欲しいもの**: 「吉日×自分の状況」に応じた次のアクションリスト

**GA4検証イベント**:
- `calendar_action_guide_view` — アクションガイド（新設想定）を開いたか
- `calendar_action_guide_exit` — ガイドから離脱した位置（どの段階まで読んだか）

---

## GA4カスタムイベント一覧（実装仕様）

### 共通イベントパラメータ

全イベントに以下を付与:
- `page_path` — 現在のパス（例: `/jp/calendar/2026/05`）
- `page_title` — ページタイトル
- `date_selected` — 該当月（YYYY-MM）
- `user_purpose` — 選択中の用途フィルタ値（未選択時は `none`）

### イベント定義

| イベント名 | 発火タイミング | パラメータ | 計測する痛み |
|---|---|---|---|
| `calendar_purpose_filter_used` | 用途フィルタ変更時 | `purpose` (選択値) | A |
| `calendar_luckyday_label_click` | 日付ラベル（大安など）クリック時 | `luckyday` (ラベル種別), `date` (YYYY-MM-DD) | B |
| `calendar_date_detail_open` | 日付セルをクリックして詳細表示 | `date`, `luckyday_count`, `overlap_score` | A, C |
| `calendar_overlap_detail_view` | 重複日（overlap_score>=2）の詳細表示 | `date`, `overlap_score`, `luckyday_types` (配列) | C |
| `calendar_year_nav` | 年切替操作 | `from_year`, `to_year` | A |
| `calendar_month_nav` | 月切替（前月/翌月） | `direction` (prev/next), `from_month`, `to_month` | A |
| `calendar_share` | 共有ボタンクリック | `share_type` (line/twitter), `date` (該当日があれば) | C |
| `calendar_today_jump` | 「今日」ボタン（新設） | — | A |
| `calendar_glossary_entry` | 解説ページ（/taian 等）に遷移 | `entry_from` (calendar/月別/他), `luckyday_type` | B |
| `calendar_glossary_scroll25` | 解説ページ 25%スクロール | `luckyday_type` | B |
| `calendar_glossary_scroll50` | 解説ページ 50%スクロール | `luckyday_type` | B |
| `calendar_glossary_scroll75` | 解説ページ 75%スクロール | `luckyday_type` | B |
| `calendar_glossary_scroll100` | 解説ページ 最下部到達 | `luckyday_type` | B |
| `calendar_glossary_cta_click` | 解説ページ→カレンダーへのCTA | `luckyday_type`, `target_path` | B |

### キーイベント（コンバージョン）候補

GA4管理画面でキーイベント登録する候補:
1. `calendar_share` — ユーザーが価値を感じて他者に広げた
2. `calendar_glossary_scroll100` — 解説を最後まで読んだ（知的好奇心充足）
3. `calendar_overlap_detail_view` かつ overlap_score >= 3 — レアな日を発見できた

---

## 実装コスト対効果の見立て

| 施策 | コスト感 | 効果検証手段 |
|---|---|---|
| 用途フィルタのイベント計測追加 | 小（gtag数行追加） | `calendar_purpose_filter_used` の発生数と内訳 |
| 日付クリック＋詳細表示の計測 | 小（コンポーネントに onClick 追加） | `calendar_date_detail_open` + overlap の関連 |
| 解説ページスクロール深度計測 | 中（IntersectionObserver） | 各スクロールイベントの到達率 |
| 用途フィルタのUI改善（痛みA） | 中 | フィルタ使用率と日付詳細表示数の相関 |
| 重複日スコアの視覚的強調（痛みC） | 中 | overlap_detail_view + share の増減 |
| アクションガイドMVP（痛みD） | 大（コンテンツ制作含む） | action_guide_view とフィルタ使用率の関連 |

---

## 観測したい指標（KPI候補）

1. **フィルタ利用率** = `calendar_purpose_filter_used` / sessions（現在0→計測開始により把握可能に）
2. **解説到達率** = `calendar_glossary_entry` / sessions（現在0）
3. **日付詳細参照率** = `calendar_date_detail_open` / sessions
4. **共有率** = `calendar_share` / sessions
5. **重複日詳細参照率** = `calendar_overlap_detail_view` / sessions（重複日の目立たせ方の効果測定に）

---

## 次のアクション（提案）

1. gtagにカスタムイベントを追加して計測開始（2-3日でデータが溜まり始める）
2. 2週間後、イベントデータを確認 → どの痛みが本当に存在するか仮説検証
3. 検証結果に基づき、痛みの強いものからUI/コンテンツ改善に着手
4. 改善前後でイベント発生率を比較し、効果判定
