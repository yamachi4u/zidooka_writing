# Emphasis Snippets

Use Zidooka blocks for strong, consistent emphasis in articles, notes, and answers.

## Supported Blocks
- `:::conclusion` — 結論・まとめ
- `:::note` — 補足・メモ・ポイント
- `:::warning` — 注意・警告
- `:::step` — 手順・ステップ・対処
- `:::example` — 具体例

Rules:
- Do not use bracket labels such as `【結論】`, `【ポイント】`, `【注意】`, or `【対処】`.
- Keep each block concise whenever possible.
- For commands and identifiers, wrap them with backticks such as `npm run build` or `GAS Logger.log`.
- Do not repeat the block role inside the block body; the block type already communicates it.

## Examples

```markdown
:::conclusion
`PIPELINE_MANUAL.md` の「下書き→校正→公開」手順に従います。
:::

:::warning
WordPress への貼り付け時は、生 URL を日本語文の直前に置かないようにします。
:::

:::step
`series_data.cjs` を更新後、`npm run generate:series` を実行します。
:::
```

