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

## 学習ログ

### 2026-06-17
- ✅ `npm run posthog:check` で null rate が改善 (100%→0%) 確認済み
- ✅ `docs/decisions/` 導入 — benri-tools と同じADR形式
- ✅ `docs/SELF-IMPROVEMENT.md` 導入
