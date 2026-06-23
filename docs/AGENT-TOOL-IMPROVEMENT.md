# Agent Tool Improvement Log (zidooka_writing)

## 現行ベストプラクティス

### PostHog Operations
| 状況 | 改善ルール |
|------|-----------|
| 実験確認 | `npm run posthog:check` → `drat/posthog-status.md` を読む |
| 判定前 | daily-agent/ で他エージェントのアクティブクレームを確認 |
| フラグ変更前 | クライアントJSのデプロイが必要 → `downloads/zidooka-tw/assets/posthog-experiments.js` |

### Publishing
| 状況 | 改善ルール |
|------|-----------|
| 日英ペア公開 | `node src/index.js post-pair drafts/file-ja.md` |
| 即時公開 | frontmatter に `date` を入れない |
| 公開後 | IndexNow + サムネイル生成 |

### ファイル編集
| 状況 | 改善ルール |
|------|-----------|
| テーマファイル | `downloads/zidooka-tw/` で編集 → `node scripts/remote-agent/index.js push` |
| 確認 | pull → local比較 → push の順 |

## ナレッジ（daily-agent から格上げ）

### PostHog flag resolution 問題
- `posthog.getFeatureFlag()` の非同期解決が遅いユーザーが一定数存在する
- forceInit fallback でカバーしているが、experiment impression が正しく割り当てられない
- **解決策**: server-side body class による割当 (2026-06-23 導入)
  - `add_filter('body_class', ...)` で variant を body class に埋め込み
  - JS の `getServerVariant()` が body class を優先参照
  - cookie でユーザーごとに固定 (90日)
  - 参考: `tmp_remote_agent/functions.php` の `// Server-side A/B assignment`

### 実験パイプラインの優先順位
- code_fold (current) → header_image → author_pos
- TOC sticky は本採用済み (2026-06-10: +203% clicks, +17.3% engagement)
- 同時稼働は1本まで（最大2本、同じ目的の場合のみ）

### 判断記録の検証
- `npm run decisions` で検証日超過の判断を自動チェック
- 導入: 2026-06-23 (benri-tools から移植)

## 学習ログ

### 2026-06-23
- ✅ server-side A/B assignment 導入 (zdk_code_fold)
- ✅ forceInit 内でも getServerVariant を試行するよう改善
- ✅ `npm run decisions` 導入
- ✅ daily-agent ログ構造化テンプレート導入
- ✅ PostHog 古い inactive flag は削除不可 (API 405) のため現状維持

### 2026-06-17
- ✅ `npm run posthog:check` で null rate が改善 (100%→0%) 確認済み
- ✅ `docs/decisions/` 導入 — benri-tools と同じADR形式
- ✅ `docs/SELF-IMPROVEMENT.md` 導入

### 2026-06-10
- ✅ forceInit fallback (timeout 10s→15s, MAX_FALLBACK 20) で null rate 緩和
- ✅ TOC sticky 本採用 (+203% clicks, +17.3% Engaged 60s)
