---
type: concept
description: zidooka.com 記事の文体ルール
tags: [conventions, writing, style, japanese, english]
updated: 2026-07-07
links:
  - ../projects/zidooka-writing.md
  - ../reference/links.md
---

# Writing Style (zidooka)

## 日本語

- ですます調
- Zidooka Blocks を使用（【】は使わない）
- `docs/ZIDOOKA-STYLE.md` のセルフチェック通過必須
- 記事制作ルール（PREP構成・EEAT・参照URLリスト形式）も同ファイル末尾に統合済み（旧 `docs/ZIDOOKA_STYLE.md` は2026-07-07に削除）
- エラー系記事はエラーハブ記事へ内部リンクを張る（URL一覧: [../reference/links.md](../reference/links.md)）

### Zidooka Blocks

```
:::conclusion  結論・まとめ
:::note       補足・メモ・ポイント
:::warning    注意・警告
:::step       手順・ステップ・対処
:::example    具体例
```

## 英語

- Standard technical writing
- Contractions OK
- URLs: `<...>` でラップするか Markdown リンクを使用（WordPress 自動リンクバグ回避）

## 共通

- 日英ペア公開がデフォルト（単体希望時のみ明示）
- `affiliate` タグ追加で該当ページの AdSense を無効化
