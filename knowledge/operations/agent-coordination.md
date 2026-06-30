---
type: concept
description: マルチエージェント協調ルール
tags: [operations, agents, coordination]
updated: 2026-06-30
links:
  - improvement-cycle.md
  - ../projects/index.md
---

# エージェント間協調ルール

## 基本原則

1. **同じファイルを触るタスクは同時に進行しない**。`lib/` 以下、`docs/decisions/` は特に注意
2. **1タスク = 1コミット**。Conventional Commits
3. **判断に迷ったら `npm run weekly` で直近データを見る**
4. **daily-agent ログは append-only**。他エージェントのエントリを書き換えない

## セッション開始ルーチン

```powershell
# 1. 検証日超過の判断がないか確認
npm run decisions

# 2. daily-agent/ の最新ログを読む
ls daily-agent/ | Sort-Object LastWriteTime | Select-Object -Last 1
cat daily-agent/YYYYMMDD.md

# 3. プロジェクトの AGENTS.md を確認
# zidooka_writing は AGENTS.md、benri-tools は AGENTS.md が該当
```

## daily-agent ログフォーマット

```markdown
# YYYY-MM-DD <AgentName>

## Start
- Task: <task description>

## Completed
- <done items>
```

## ステータスワード

`start`, `claim`, `doing`, `blocked`, `handoff`, `done`, `memo`
