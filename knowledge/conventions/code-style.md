---
type: concept
description: TypeScript/React コード規約
tags: [conventions, typescript, react, nextjs, coding]
updated: 2026-06-30
links:
  - ../projects/benri-tools.md
---

# Code Style (benri-tools)

## 基本

- TypeScript strict
- React 関数コンポーネント + フック
- インデント2スペース、シングルクォート、セミコロンあり
- コンポーネント PascalCase、ルートフォルダ kebab-case、ファイル名 `page.tsx` / `layout.tsx`
- Tailwind ユーティリティ優先（MUI 不使用）
- Conventional Commits（`feat:` / `fix:` / `chore:` / `refactor:` / `docs:` / `test:` のいずれか、件名72文字以内）

## Anti-Patterns

| 禁止 | 代わりに |
|------|---------|
| `setInterval` でのカウントダウン | `useDriftFreeTimer` または `Date.now()` + refs |
| 空の `catch {}` | `posthogCapture('error', { context, message })` を必ず入れる（ただし expected error は免除） |
| `dangerouslySetInnerHTML` 非エスケープ | `escapeHtml()` 必須 |
| `(window as any).webkitAudioContext` | `(window as unknown as { webkitAudioContext: typeof AudioContext }).webkitAudioContext` |
| プロダクションURLハードコード | 相対URLまたは `getSiteBaseUrl()` |

## セキュリティ

- `next.config.ts` の COOP/COEP ヘッダーを削除しない（ffmpeg.wasm 必須）
- ファイル処理はクライアントサイド優先
- シークレットをコミットしない。`.env.local` を使用
