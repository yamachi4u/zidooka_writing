---
type: concept
description: benri-tools プロジェクト詳細
tags: [project, tools, nextjs, zidooka]
updated: 2026-06-30
links:
  - ../operations/improvement-cycle.md
  - ../conventions/code-style.md
  - ../reference/troubleshooting.md
---

# benri-tools

## 概要

便利ツールサイト `tools.zidooka.com` の Next.js (App Router) プロジェクト。

- **ディレクトリ**: `C:\Users\user\Documents\benri-tools`
- **本番URL**: `https://tools.zidooka.com`
- **デプロイ**: Netlify（`git push origin main` で自動）
- **フレームワーク**: Next.js 16 + Tailwind CSS v4

## 絶対禁止

1. **新規ツールの作成禁止** — `app/` 以下に新規ページ・ルートを作らない
2. **カレンダーの日付データ・ラベル定義・スコアリングは変更禁止**
3. **カレンダーUIの変更は必ず PostHog A/B テスト経由**
4. **カレンダーA/Bテスト以外のコード改変は人間の明示許可が必要**

## 開発コマンド

| 目的 | コマンド |
|------|---------|
| 型チェック | `npm run typecheck` |
| Lint | `npm run lint` |
| 両方 | `npm run verify` |
| ユニットテスト | `npm run test:unit` |
| E2E | `npm run test:e2e` |
| 自己改善 | `npm run improve` |

## ツール構造

- App Router。各ツールは `app/<slug>/page.tsx` + `layout.tsx`
- 全ツールは `<ToolShell>` でラップ
- ツールメタデータ: `lib/tool-metadata.ts`
- ツール一覧: `lib/tool-registry.ts`
- 全ツール Page は `'use client'`

## 共有ライブラリ

- `lib/ffmpeg.ts` → `useFfmpeg()`: 全メディアツール共用
- `lib/timer-utils.ts` → `useDriftFreeTimer()`: カウントダウン処理
- `lib/posthog.tsx` → `useFeatureFlag()`, `posthogCapture()`: PostHog ラッパー
- `lib/csv.ts`: CSV解析
- `lib/db.ts`: Turso/libSQL コメントDB
