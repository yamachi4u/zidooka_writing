# Emphasis Snippets

Use these standardized patterns for strong, consistent emphasis in articles, notes, and answers. Keep each emphasis line concise (ideally one sentence).

## One-Line Emphasis
- 【結論】要点を一行で端的に述べる。
- 【ポイント】読者がまず押さえるべき要点。
- 【注意】誤りやすい点、落とし穴の明示。
- 【対処】実行可能な対処・コマンド・設定名を具体的に。

Rules:
- Use full-width brackets and bold labels as written above; do not add emojis.
- Keep the emphasized statement on a single line. Provide details in the following paragraph if needed.
- For commands/identifiers, wrap with backticks like: `npm run build` or `GAS Logger.log`.

## Short Block Emphasis (when one line is insufficient)
> 【結論】一文目で主旨を言い切る。
> 次行以降で補足（最大 2–3 行）。

Rules:
- Use a normal Markdown blockquote (`>`). Avoid special admonition syntaxes that may not render in all environments.
- Keep it short (max 3 lines). Longer guidance should go into normal sections.

## Examples
- 【結論】`PIPELINE_MANUAL.md` の「下書き→校正→公開」手順に必ず従う。
- 【注意】WordPress への貼り付け時は Markdown の生 URL を保持する。
- 【対処】`series_data.cjs` を更新後、`npm run generate:series` を実行する。

