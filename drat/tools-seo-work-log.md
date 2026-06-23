# Tools.zidooka.com SEO改善 作業ログ

> 2026-05-13

## 現状
- カレンダー以外の50+ツールはほぼ0トラフィック
- GSCで確認できた表示ありツール:
  - `weeks-from-today` — 200imp, 0 clicks, 順位40
  - `jp/text-cleaner` — 46imp, 0 clicks, 順位24
  - `csv-column-count` — 20imp, 0 clicks, 順位8
  - `hourglass` — 3imp, 0 clicks

## やったこと
- `lib/tool-metadata.ts` 作成: 全ツールにSEOタイトル・メタデスクリプションを定義
- `buildToolMetadata(slug)` 関数: Next.js Metadata 形式に変換
- `layout.tsx` を各ツールディレクトリに追加（計37ツールにmetadata設定完了）

## 期待効果
- Googleが各ツールの内容を正しく認識できるようになる
- weeks-from-today の検索順位が改善する可能性（現状40位）
- 最終的に0%のCTRが改善されれば数クリック/month獲得

## TODO（次回）
- [ ] 1週間後にGSCで tools.zidooka.com の変化を確認
- [ ] 新ツール追加時は `lib/tool-metadata.ts` にエントリを追加することを忘れない
