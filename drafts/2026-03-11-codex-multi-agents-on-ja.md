---
title: "CodexのMulti-agentsモードをONにする方法"
date: 2026-03-11 00:01:00
categories:
  - AI
tags:
  - Codex
  - OpenAI
  - CLI
  - Multi-agents
  - マルチエージェント
status: publish
slug: codex-multi-agents-on-ja
---

OpenAIのCodexで、複数のサブエージェントを並列で動かす `Multi-agents` 機能を使いたい人向けのメモです。

2026年3月11日時点のOpenAI公式ドキュメントでは、`Multi-agents` は **experimental** 扱いで、最初は明示的に有効化する必要があります。

:::conclusion
Codexの `Multi-agents` をONにする一番確実な方法は、`~/.codex/config.toml` の `[features]` に `multi_agent = true` を追加して、Codexを再起動することです。
:::

## まず結論

やることはシンプルです。

```toml
[features]
multi_agent = true
```

Windowsなら、設定ファイルの場所は通常 `C:\Users\ユーザー名\.codex\config.toml` です。

たとえばこの環境なら、以下のパスです。

```text
C:\Users\user\.codex\config.toml
```

## ONにする方法は2通りあります

OpenAI公式ドキュメントでは、`Multi-agents` の有効化方法として次の2つが案内されています。

### 1. CLIから `/experimental` を使う

Codex CLI上で `/experimental` を開き、`Enable Multi-agents` を有効化します。

:::step
Codex CLIで `/experimental` を開き、`Enable Multi-agents` をONにしたあと、Codexを再起動します。
:::

CLI上で設定を探すのが面倒なら、次の `config.toml` 直接編集のほうが速いです。

### 2. `config.toml` に直接書く

手元で確実に管理したいなら、こちらがおすすめです。

`~/.codex/config.toml` を開いて、`[features]` セクションに `multi_agent = true` を追加します。

```toml
[features]
multi_agent = true
```

すでに `[features]` セクションがあるなら、その中に1行足せばOKです。  
まだ無いなら、新しく作って問題ありません。

:::step
`~/.codex/config.toml` を編集し、`[features]` の中へ `multi_agent = true` を追加して保存します。
:::

## 再起動が必要です

ここは見落としやすいです。

OpenAI公式ドキュメントでは、`Multi-agents` を有効化したあと **Codexの再起動** が必要と案内されています。設定を書いたのに動かないときは、まず再起動を疑ってください。

:::warning
`multi_agent = true` を追加しただけでは反映されないことがあります。設定変更後はCodexを再起動してください。
:::

## 何が有効になるのか

OpenAIの設定リファレンスでは、`features.multi_agent` を有効にすると、次の multi-agent collaboration tools が使えるようになると説明されています。

- `spawn_agent`
- `send_input`
- `resume_agent`
- `wait`
- `close_agent`
- `spawn_agents_on_csv`

つまり、親エージェントが複数の子エージェントを立ち上げて、並列で調査や実装を進め、最後に結果をまとめるワークフローが使えるようになります。

## いまはCLI中心です

この点も公式ドキュメントに書かれています。

2026年3月11日時点では、`Multi-agent` の動作状況は主に **Codex CLI** で確認できます。Codex app や IDE Extension での見え方は「coming soon」とされています。

:::note
今すぐ一番使いやすいのはCodex CLIです。アプリやIDE拡張でも今後見やすくなる可能性はありますが、現時点ではCLI前提で考えるのが安全です。
:::

## ONにしたあと、どう試すか

有効化できたら、まずは「並列レビュー」を1回やってみるのがわかりやすいです。

公式ドキュメントには、次のような試し方が載っています。

```text
I would like to review the following points on the current PR (this branch vs main). Spawn one agent per point, wait for all of them, and summarize the result for each point.
1. Security issue
2. Code quality
3. Bugs
4. Race
5. Test flakiness
6. Maintainability of the code
```

これをそのまま使わなくても、たとえば以下のように日本語で依頼すれば十分です。

:::example
このブランチをレビューしてください。セキュリティ、バグ、テスト不足の3観点で1エージェントずつ並列に走らせて、全員の結果をまとめてください。
:::

## さらに使い込むなら agent roles も設定できる

`Multi-agents` はただONにするだけでも使えますが、OpenAI公式ドキュメントでは `[agents]` セクションで役割ごとの設定を分ける方法も案内されています。

たとえば、

- `explorer`: 調査専用
- `reviewer`: レビュー専用
- `monitor`: 長時間監視用

のように役割を分けると、長い作業をかなり整理しやすくなります。

ただし、最初はそこまでやらなくても大丈夫です。まずは `features.multi_agent = true` を入れて、1回実際に並列実行してみるのが先です。

## 参考リンク

- [OpenAI公式: Multi-agents](https://developers.openai.com/codex/multi-agent/)
- [OpenAI公式: Configuration Reference](https://developers.openai.com/codex/config-reference/)
