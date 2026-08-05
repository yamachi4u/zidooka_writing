# PostHog Experiments — zidooka.com

> 運用開始: 2026-06-03
> プロジェクトID: 451906 (zidooka.com)
> 管理: Codex / Claude / Opencode エージェントが自律的に運用
> 週次チェック: `npm run posthog:check` → `daily/posthog/YYYY-MM-DD.md`

## 基本ルール

- CTA（相談バナー）に関する実験は禁止（受注パンク防止）
- カレンダーの日付データ・ラベル定義・スコアリングは変更禁止
- 実験開始前にこのドキュメントにエントリを追加する
- 1〜2週間データが溜まったら結果を判定し、勝ちバリアントを採用する
- 負けバリアントは Feature Flag を削除 or 無効化する
- **同時稼働は原則1本、多くても同一目的2本まで**。交絡を防ぐため。
- **広告/CTA系は常に単独実験**。収益や問い合わせ数に影響するため。
- **勝敗判定は impression ではなく outcome event で行う**（`zdk_read_depth`, `zdk_engaged_60s`, `zdk_toc_click`, `zdk_related_click` 等）。

## 判定基準（Hard Thresholds）

`scripts/posthog-check.mjs` に実装。変更時はスクリプトとこのドキュメントを同時更新。

| 閾値 | 値 | 意味 |
|---|---|---|
| 最低データ日数 | 5日 | これ未満は判定不可 |
| 最低インプレッション/バリアント | 200 | これ未満は延長 |
| 最低アウトカムイベント/バリアント | 100 | これ未満は延長 |
| 意味のあるリフト | 15%+ | これ未満の差は「差なし」扱い |
| 最大 null rate | 30% | 超過時は計測不良。修正優先 |

## 実験ライフサイクル

```
[提案] → [優先順位付け] → [実装] → [監視(5-14日)] → [判定] → [勝ち適用/クローズ]
```

| Phase | 期間 | アクション |
|---|---|---|
| 提案 | 随時 | このドキュメントのパイプラインに追加 |
| 優先順位付け | 次回枠まで | パイプラインの上から順に |
| 実装 | 1セッション | JS/CSS変更、flag作成/有効化、デプロイ、レジストリ更新 |
| 監視 | 5〜14日 | `npm run posthog:check` 週2回 |
| 判定 | 1セッション | レポート読んで勝敗決定 |
| 適用 | 1セッション | 勝ちバリアントを実コードにマージ、flagをクローズ |

## 実験一覧

### 進行中 (Active)

| # | 実験名 | Flag Key | 開始日 | 判定予定日 | バリアント | KPI | ステータス |
|---|--------|----------|--------|-----------|-----------|-----|-----------|
| 10 | サイドバー広告A/B (TechGo vs FPカフェ) | — (server-side PHP random) | 2026-07-09 | **2026-07-23** | fp_cafe / techgo_banner | ad_click率 (PostHog) | server-side active |

### 完了 (Completed)

| # | 実験名 | 期間 | 勝ちバリアント | 効果 | 備考 |
|---|--------|------|---------------|------|------|
| 1 | 記事フォントサイズ | 2026-06-03〜2026-06-09 | large(20px) | Engaged 60s +27%, TOC Click +228% | single.css に本採用、flag無効化 |
| 2 | TOC sticky | 2026-06-09〜2026-06-10 | sticky | TOC Click +203%, Engaged 60s +17.3% | single.css に本採用、flag無効化 |

### 却下 (Rejected)

| # | 実験名 | 期間 | 却下理由 |
|---|--------|------|---------|
| 2 | 行間 | 2026-06-10〜2026-06-16 | inconclusive — 全指標で有意差なし |
| 3 | 関連記事レイアウト | 2026-06-16〜2026-06-22 | inconclusive — KPI（関連記事クリック）が0件のため判定不能。トラッキングセレクタ修正済み |
| 4 | コードブロック折りたたみ | 2026-06-22〜2026-07-09 | inconclusive — 全指標で有意差なし。Read Depth +2.0%, Engaged 60s -0.3%。クローズ |
| 5 | 記事ヘッダー画像サイズ | 2026-06-23〜2026-07-09 | inconclusive — 全指標で有意差なし。Read Depth -6.9%, Engaged 60s -9.1%。クローズ |

## アクティブなFeature Flags

| Flag Key | 説明 | バリアント | 配分 | ステータス |
|----------|------|-----------|------|-----------|
| `zdk_font_size` | フォントサイズ | control(50%) / large(50%) | 100% | inactive (2026-06-09 勝ち採用) |
| `zdk_line_height` | 行間 | control(50%) / loose(50%) | 100% | inactive (2026-06-16 クローズ: inconclusive) |
| `zdk_toc_sticky` | TOC追従 | control(50%) / sticky(50%) | 100% | inactive (2026-06-10 勝ち採用) |
| `zdk_line_height` | 行間 | control(50%) / loose(50%) | 100% | inactive (2026-06-16 クローズ: inconclusive) |
| `zdk_related_posts` | 関連記事 | control(50%) / grid4(50%) | 100% | inactive (2026-06-22 クローズ: inconclusive) |
| `zdk_ad_position` | 広告位置 | control(50%) / early(50%) | 100% | inactive (2026-06-04 停止) |
| `zdk_code_fold` | コードブロック折りたたみ | control(50%) / folded(50%) | 100% | inactive (2026-07-09 クローズ: inconclusive) |
| `zdk_header_image` | 記事ヘッダー画像サイズ | control(50%) / small(50%) | 100% | inactive (2026-07-09 クローズ: inconclusive) |
| `zdk_author_pos` | 著者プロフィール位置 | control(50%) / compact(50%) | 100% | **active** (2026-06-23, server-side body class) |

## パイプライン（優先順位付き）

`zdk_font_size` の決着後、以下の順で実施。優先順位の根拠は「測定基盤が整っているか × 実装が軽いか × 単独で判定可能か」。

| 優先度 | 実験 | Flag Key | 仮説 | 難易度 | 備考 |
|--------|------|----------|------|--------|------|
| **次** | コードブロック折りたたみ | `zdk_code_fold` | 長いコードブロックでスクロール軽減 | 中 | 新規flag。scrolldepthで間接測定可 |
| 4 | コードブロック折りたたみ | `zdk_code_fold` | 長いコードブロックでスクロール軽減 | 中 | 新規flag。scrolldepthで間接測定可 |
| 5 | 記事ヘッダー | `zdk_header_image` | feat image大 vs 小 | 低 | **active** (2026-06-23, server-side body class) |
| 6 | 著者プロフィール位置 | `zdk_author_pos` | コンパクト vs 現在 | 低 | **active** (2026-06-23, server-side body class) |
| 7 | 広告位置 | `zdk_ad_position` | RPM改善 | 低 | CSS実装修正が必要（現在は「最初の広告を隠す」だけ）。単独実験必須 |
| 8 | 広告密度 | `zdk_ad_density` | 1個 vs 2個 | 低 | 新規flag。単独実験必須。収益影響あり |
| 9 | モバイル広告配置 | `zdk_mobile_ad` | 記事中 vs 記事下 | 低 | 新規flag。単独実験必須 |

## アクションログ

| 日付 | 内容 | 担当 |
|------|------|------|
| 2026-06-03 | 運用ドキュメント作成、実験JS/CSS実装、デプロイ | Opencode |
| 2026-06-03 | functions.phpにPostHog実験コード追加 | Opencode |
| 2026-06-03 | assets/posthog-experiments.js 作成・デプロイ | Opencode |
| 2026-06-03 | PostHog APIで5個のFeature Flag作成・有効化 | Opencode |
| 2026-06-04 | 交絡低減: 4本のflag停止、zdk_font_sizeのみ維持 | Opencode |
| 2026-06-04 | アウトカムイベント追加: zdk_read_depth/zdk_engaged_60s/zdk_toc_click/zdk_related_click | Opencode |
| 2026-06-04 | 同時稼働ルール追加: 原則1本、多くても同一目的2本、広告/CTA系は単独実験 | Opencode |
| 2026-06-05 | **メタ運用改善**: npm run posthog:check スクリプト新設、判定閾値の具体化、パイプライン優先順位付け、週次チェックカレンダー設定 | Opencode |
| 2026-06-05 | **バグ修正**: single.phpに関連記事セクション用 `zidooka-related-posts` クラス追加（zdk_related_click が0件だった問題を修正） | Opencode |
| 2026-06-05 | **診断追加**: posthog-experiments.js に `zdk_flag_resolution_error` イベント追加（null variant 42%の原因特定用） | Opencode |
| 2026-06-05 | **.env修正**: REMOTE_BASES の余計な外側クォート除去（remote-agent push/pull 失敗の原因） | Opencode |
| 2026-06-16 | zdk_line_height クローズ（inconclusive）、zdk_related_posts 開始、CSS実装+デプロイ | Opencode |
| 2026-06-22 | zdk_related_posts クローズ（inconclusive: KPI未計測）、TOCクリックセレクタ修正、posthog-check.mjs バグ修正、zdk_code_fold 開始 | Opencode |
| 2026-06-23 | server-side A/B assignment導入（code_fold + header_image + author_pos）計画、死にコード掃除、TOC/Related Click closest() 修正、npm run decisions導入 | Opencode |
| 2026-06-24 | action A-9: zdk_header_image 実装完了 — PostHog flag作成/有効化、functions.php body_class filter+B/A実験CSS追加、posthog-experiments.js 確認済 (既存)、status docs更新 | Opencode |

## 実験作成手順（エージェント用）

```powershell
# 1. パイプラインの優先度1の実験を選ぶ
# 2. PostHogにFeature Flagを作成（または既存flagを再有効化）
# 3. functions.php または対象テンプレートにフラグ判定を追加
# 4. posthog-experiments.js にCSSクラス適用ロジック追加（必要に応じて）
# 5. remote-agentでデプロイ
# 6. このドキュメントにエントリを追加し、判定予定日（+7日）を設定
# 7. 1〜2週間監視（npm run posthog:check を週2回）
# 8. 判定閾値を満たしたら結果判定 → 勝ち採用 or クローズ
```

## 留意点

- PostHog無料枠のFeature Flagリクエストは月100万件まで
- エージェントは週2回（月・木推奨）`npm run posthog:check` を実行し、判定可能になった実験をクローズする
- 新しい実験案は随時このドキュメントの「パイプライン」セクションに追記し、優先度を検討する
- 判定が「差なし」になった場合もクローズし、次の実験に進む（「何が効かないか」も知識）

## Design Refactoring Queue — 2026-07-11

- Production PostHog flags: no active `zdk_*` flag as of `npm run posthog:check` on 2026-07-11.
- Server-side sidebar ad offer test remains active through 2026-07-23 and blocks readability/navigation experiments.
- `zdk_header_density` was created in PostHog as inactive, 50% `control` / 50% `compact`.
- Theme support and outcome attribution are deployed while inactive.
- Activation gate: close the ad test, confirm no active experiment, then activate only `zdk_header_density` and set its dates in `scripts/posthog-check.mjs`.
- Visual QA source of truth: `docs/THEME_VISUAL_QA.md`.
