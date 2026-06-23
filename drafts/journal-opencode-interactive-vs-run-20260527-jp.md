---
title: "Codexがopencode runを呼んだとき、OpenCodeは聞き返せるか？待機して対話できるのか？"
slug: journal-opencode-interactive-vs-run-20260527-jp
categories:
  - journal
  - AI
tags:
  - OpenCode Go
  - Codex
  - CLI
  - 使い方
  - AI
status: publish
---

Codex（AnthropicのCLIエージェント）が `opencode run "タスク"` をbash経由で呼び出したとき、OpenCodeは「これ曖昧だよ」と聞き返せるのか？複数ターンの対話が成立するのか？

結論：**できない。`opencode run` はワンショット。聞き返さないし、待たない。**

## なぜできないか

`opencode run` は **非対話型モード** です。与えられたプロンプトを処理し、結果を標準出力に出して終了。標準入力からの対話は想定されていません。

公式ドキュメントの原文：
> "Run opencode in non-interactive mode by passing a prompt directly. This is useful for scripting, automation, or when you want a quick answer without launching the full TUI."

OpenCodeが情報不足でも、推測して実行するかエラーを返すだけで、聞き返すことはありません。

## じゃあ何のために使うの？

**対話ではなく役割分担**に価値があります。

- Codex（高性能・高コスト）→ 全体設計、判断、レビュー
- OpenCode Go Flash（低コスト・高速）→ 局所実行

Codexが一方的に「これをやれ」と投げ、結果を受け取る。対話は不要です。

## 対話したければ → opencode serve + HTTP API

ここが今回のポイントです。OpenCodeには **`opencode serve`** というモードがあり、HTTPサーバーとして起動できます。

```text
opencode serve
# → localhost:4096 でREST API起動
```

このサーバーに対して `POST /session/:id/message` を送ると、OpenCodeがレスポンスを返します。**セッションを維持したまま複数ターンの対話が可能**です。

さらに、公式のJS SDK（`@opencode-ai/sdk`）を使えばTypeScriptで型安全にクライアントを書けます。

```typescript
import { createOpencodeClient } from "@opencode-ai/sdk"
const client = createOpencodeClient({ baseUrl: "http://localhost:4096" })

// セッション作成
const session = await client.session.create({ body: { title: "対話" } })

// メッセージ送信（レスポンスを待つ）
const result = await client.session.prompt({
  path: { id: session.id },
  body: {
    parts: [{ type: "text", text: "このコードをレビューして" }],
  },
})
```

つまり、Codexが `webfetch` ツールでこのAPIを叩くようにすれば、bashの `opencode run` 経由ではない **HTTPベースの対話** が構築できます。

:::note
ただし、Codexが自発的にこのパターンを実行するわけではありません。Codexに明示的に指示するか、ラッパースクリプトを用意する必要があります。
:::

## 全体比較

| 方法 | 対話 | 呼び出し元 | 備考 |
|---|---|---|---|
| `opencode`（TUI） | フル対話 | 人間 | 今やってるこれ |
| `opencode run "..."` | ワンショット | Codex（bash経由） | 聞き返せない |
| `opencode serve` + HTTP API | 複数ターン可 | 任意のHTTPクライアント | 自発的にはやらない |
| `opencode serve` + JS SDK | 複数ターン可 | TypeScript/JSプログラム | ラッパー必須 |

## サブエージェント（補足）

OpenCode（TUI）には内部のサブエージェント機能（`@general` や `@explore`）があります。プライマリエージェントがtaskツール経由で子セッションに委譲できますが、これはOpenCode内部の話でCodexからの呼び出しとは無関係です。

## 結論

`opencode run` はワンショット非対話型。OpenCodeは聞き返さず待たない。しかし `opencode serve` + HTTP API を使えば、セッションを維持した擬似対話が構築可能。現在の実用パターンは「安いモデルに細かいタスクを投げる」という割り切りが正解。

:::conclusion
`opencode run` からはOpenCodeに聞き返せない。対話が必要なら `opencode serve` + HTTP APIで橋渡しを構築する。今のCodex連携パターンは対話不要の役割分担として成立している。
:::
