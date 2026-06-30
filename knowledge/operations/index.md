---
type: section
description: 改善サイクル・判断記録・運用手順
tags: [operations, improvement, cicd]
updated: 2026-06-30
---

# Operations

運用ルール・改善サイクル・意思決定の記録。

## コンテンツ

| ファイル | 説明 |
|---------|------|
| [improvement-cycle.md](./improvement-cycle.md) | 自己改善サイクルの詳細 |
| [decision-records.md](./decision-records.md) | 判断記録の管理方法 |
| [agent-coordination.md](./agent-coordination.md) | マルチエージェント協調ルール |

## 全エージェント共通ルール

1. セッション開始時: `npm run decisions` → `daily-agent/` 最新ログを読む
2. 実装前後: `npm run verify` / `npm run test:unit` が通ることを確認
3. コミット: Conventional Commits（`feat:` / `fix:` / `chore:` / `refactor:` / `docs:` / `test:`）
4. 1タスク = 1コミット
