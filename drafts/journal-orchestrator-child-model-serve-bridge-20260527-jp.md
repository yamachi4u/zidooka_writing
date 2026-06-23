---
title: "司令塔＋子モデル構成をOpenCode Serveで作る ― ワンショットから対話への橋渡し"
slug: journal-orchestrator-child-model-serve-bridge-20260527-jp
categories:
  - journal
  - AI
tags:
  - OpenCode Go
  - Codex
  - ワークフロー
  - AI
status: publish
---

## 欲しかったもの：司令塔と子モデル

AIを使ったコーディングでは、高性能なモデルに判断を任せたい場面が多いものです。しかしすべての処理に高性能なモデルを使うと、コストが大きくなりすぎます。

そこで考えられるのが、役割の分担です。

```text
司令塔（GPT-5.5 / Claude Sonnet 等）
  ├─ 全体設計
  ├─ 判断・方針決定
  └─ 品質レビュー

実行役（DeepSeek V4 Flash / Kimi K2.6 等）
  ├─ ファイル修正
  ├─ ログ調査
  └─ 単発実装
```

高性能なモデルが方針を決め、低コストなモデルに細かい作業を任せる。この分担は実際に役立ちます。

## なぜOpenCode Goなのか

OpenCode Goは定額制です。DeepSeek V4 FlashやKimi K2.6といったモデルを、追加の従量課金なしで使えます。特にFlashは非常に低コストで、細かいタスクを大量に処理するのに適しています。

| モデル | 月間リクエストの目安 |
|---|---|
| DeepSeek V4 Flash | 約158,000件 |
| Kimi K2.6 | 約19,000件 |
| GLM-5.1 | 約4,300件 |

## 問題：ワンショットしかできない

OpenCode Goを `opencode run` で呼び出すと、**1回の実行で完了**します。

```text
司令塔 → bash → opencode run "タスク" → 実行 → 終了
```

この方式では、OpenCodeが「質問をしたい」「確認したい」と思ってもそれができません。司令塔が一方的に指示を出し、結果を受け取るだけです。

もちろん、このパターンでも十分に便利で、実際に何度も使ってきました。しかし**対話しながら調整する**という使い方はできません。

## 解決策：opencode serve + HTTP API

OpenCodeには `opencode serve` というモードがあります。これを起動しておくと、HTTPのAPI経由でOpenCodeと通信できるようになります。

```text
司令塔（Codex / Claude Code 等）
  │  メッセージを送信
  ▼
opencode serve（ローカルで待機）
  │  同じセッションに連続送信
  ▼
実行役モデル
  ├─ 1回目「このファイルを読んでください」
  ├─ 2回目「では修正してください」
  └─ 3回目「確認してください」
```

これで対話が成立します。ポイントは**同じセッションに対してメッセージを積み重ねる**ことです。そうすれば、過去の会話を踏まえた応答が返ってきます。

## 実験結果

実際に試しました。`opencode serve` を起動し、API経由で3回のやりとりを行いました。

```
1回目「マークダウンファイルを一覧してください」
2回目「最近7日分に絞り込んでください」
3回目「ファイル名に journal を含むものはいくつありますか？」
```

結果：**3回とも前の会話を正しく参照できました**。文脈が維持されていることを確認しました。

## Handover：実装手順

この構成を再現するための手順を以下にまとめます。司令塔として動作するエージェント（CodexやClaude Code）にこの手順を渡せば、すぐに利用できます。

### 1. opencode serve を起動

```powershell
opencode serve
```

### 2. セッションを作成

```powershell
$session = curl -X POST http://localhost:4096/session `
  -H "Content-Type: application/json" `
  -d '{"title": "作業セッション"}'

$sessionId = $session.id
```

### 3. メッセージを送信

```powershell
$body = '{"parts": [{"type": "text", "text": "このファイルを解析してください"}]}'
$response = curl -X POST "http://localhost:4096/session/$sessionId/message" `
  -H "Content-Type: application/json" `
  -d $body
```

### 4. 続けて送信（文脈は自動維持）

```powershell
$body2 = '{"parts": [{"type": "text", "text": "解析結果を踏まえて修正してください"}]}'
curl -X POST "http://localhost:4096/session/$sessionId/message" `
  -H "Content-Type: application/json" `
  -d $body2
```

### 5. 子セッションを作成する場合

```powershell
# フォーク（メッセージを継承、親子リンクなし）
curl -X POST "http://localhost:4096/session/$parentId/fork"

# 明示的な子セッション（親子リンクあり、メッセージは空）
curl -X POST http://localhost:4096/session `
  -H "Content-Type: application/json" `
  -d "{`"title`":`"サブタスク`",`"parentID`":`"$parentId`"}"
```

### 6. JS SDKを使う場合

```powershell
npm install @opencode-ai/sdk
```

```typescript
import { createOpencodeClient } from "@opencode-ai/sdk"
const client = createOpencodeClient({ baseUrl: "http://localhost:4096" })

const session = await client.session.create({ body: { title: "作業" } })
const id = session.id

await client.session.prompt({ path: { id }, body: { parts: [{ type: "text", text: "解析してください" }] } })
await client.session.prompt({ path: { id }, body: { parts: [{ type: "text", text: "修正してください" }] } })
```

### 司令塔への指示文

Codexにこのパターンを使わせたい場合は、以下の内容をシステムプロンプトまたはAGENTS.mdに追加します。

> opencode serve が localhost:4096 で稼働しています。子モデルと対話したい場合は、まず POST /session でセッションを作成し、その後 POST /session/:id/message でメッセージを送信してください。同じセッションIDに対して送信することで文脈が維持されます。

## 残っている課題

技術的には成立を確認しましたが、実運用には以下の対応が必要です。

- ラッパースクリプトの整備（より簡単に呼び出せるようにする）
- serveが停止したときの復旧処理
- 古いセッションの管理と削除

## まとめ

```text
目的: 高性能な司令塔と低コストな子モデルの対話型連携
問題: opencode run はワンショット実行のみ
解決策: opencode serve + HTTP API で橋渡し
確認: 3ターンの対話、文脈維持を確認済み
```

OpenCodeのserveモードとHTTP APIを組み合わせることで、ワンショット実行から対話型の連携に移行できます。「高いモデルに考えさせる」「安いモデルに作業させる」という構成を目指す場合の、現実的な選択肢の一つになるでしょう。

:::conclusion
`opencode run` のワンショット実行では実現できなかった司令塔と子モデルの対話型連携が、`opencode serve` + HTTP API によって可能になりました。実験では3ターンの文脈維持を確認しています。実運用に向けた準備はまだ必要ですが、技術的な目処は立ちました。
:::
