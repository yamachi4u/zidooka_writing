---
title: "「ハーネス自体を拡張していく」AIエージェントを探したら、こうなった（Hermes・OpenCode・Claude Code・Goose）"
categories:
  - AI
tags:
  - OpenCode
  - AI Agent
  - Agent
  - Claude
  - open-source
  - Comparison
  - Automation
status: publish
slug: extensible-ai-agent-harness-comparison-ja
---

:::conclusion
Hermes Agent みたいに「ハーネス自体を育てていける」AI エージェントを探すと、大きく分けて 3 系統に落ち着く。コアを書き換えずに **Skills / Plugins / MCP で機能を足せる** OpenCode・Claude Code・Goose と、**エージェント自身が使った経験から自動でスキルを生やせる** Hermes。拡張の主役が「自分で足す」か「エージェントが勝手に学ぶ」かの違いが、実は一番の選びどころだ。
:::

## きっかけは Hermes の「学習ループ」

前回 Hermes Agent を OpenCode ユーザー目線で見直したとき、一番刺さったのが「エージェントが自分の経験からスキルを自動生成する」機能でした。

```text
複雑なタスクを成功させた → その手順をスキルとして保存 → 次から同じ流れを再利用 → 使うたびにスキルを自己改善
```

この「ハーネスが勝手に伸びていく」体験は、他のツールだとあまり見かけません。そこで今回は「ハーネス自体を拡張していける AI エージェント」をテーマに、各ツールの拡張の仕組みを比べてみました。

## 結論: 拡張の方法は「手動で足す」と「自動で学ぶ」の 2 系統

2026 年 8 月時点で調べた範囲では、拡張できるハーネスは次の 4 つが中心です。

| ツール | ライセンス | 主な拡張方法 | 拡張の主体 |
|---|---|---|---|
| Hermes Agent | MIT | Skills / Plugins / MCP / 学習ループ | **エージェントが自動で学ぶ** |
| OpenCode | MIT | Plugins / Custom Tools / MCP / LSP | ユーザーが手動で足す |
| Claude Code | 商用 | Plugins / Skills / MCP / Subagents | ユーザーが手動で足す |
| Goose | Apache-2.0 | MCP / Extensions | ユーザーが手動で足す |

「ハーネスを拡張する」とひと口に言っても、実はこの 2 系統の組み合わせなんですね。

## 1. Hermes Agent — エージェント自身がスキルを生やせる

Hermes（NousResearch, MIT）は拡張の方法を 4 種類持っています。

- **Skills**: `~/.hermes/skills/` に `SKILL.md` を置くだけ。agentskills.io のオープン標準に準拠。スキルはプログレッシブ・ディスクロージャー（必要になったときだけ全文を読み込む）でトークン消費を抑える
- **Plugins**: `plugin.yaml` + Python でカスタムツールやフックを追加。`ctx.register_tool()` / `ctx.register_hook()` でツール・ライフサイクルフック・スラッシュコマンドを足せる。4 種類（general / memory / context engine / model provider）に分かれている
- **MCP**: `config.yaml` の `mcp_servers` に書くだけで外部ツールに接続
- **学習ループ**: ここが他と違う点。`skill_manage` ツールで、エージェントが複雑なタスクを終えた後やユーザーに直されたときに**自分でスキルを作成・更新**する。`/learn` コマンドなら、既存のドキュメントや手順を読ませてスキル化まで自動でやってくれます

つまり「ユーザーが機能を足す」だけでなく「エージェントが勝手に自分の手順を覚える」段階まで進んでいるのが Hermes の特徴です。

```yaml
# ~/.hermes/plugins/hello-world/plugin.yaml
name: hello-world
version: "1.0"
description: A minimal example plugin
```

```python
# ~/.hermes/plugins/hello-world/__init__.py
def register(ctx):
    def handle_hello(params, **kwargs):
        return json.dumps({"success": True, "greeting": "Hello!"})
    ctx.register_tool(
        name="hello_world", toolset="hello_world",
        schema=schema, handler=handle_hello,
    )
```

プラグインは初期状態でオプトイン（`hermes plugins enable <name>` で有効化）。安全側に倒している設計です。

## 2. OpenCode — 型安全なプラグインでイベントに介入

OpenCode（MIT）は、ターミナル中心のコーディングハーネスとして、**プラグインでイベントをフック**できるのが特徴です。

- プラグインは `.opencode/plugins/`（プロジェクト）か `~/.config/opencode/plugins/`（グローバル）に JS/TS ファイルを置くだけ
- npm パッケージを `opencode.json` の `plugin` に書けば自動インストール
- `tool.execute.before` / `tool.execute.after`、`session.*`、`file.edited` など豊富なイベントを購読可能
- **Custom Tools**: `@opencode-ai/plugin` の `tool()` ヘルパーで Zod スキーマ付きカスタムツールを追加。LLM が呼べるツールとして組み込まれる
- MCP / LSP / Agent Skills にも対応

```ts
// .opencode/plugins/custom-tools.ts
import { type Plugin, tool } from "@opencode-ai/plugin"
export const CustomToolsPlugin: Plugin = async (ctx) => {
  return {
    tool: {
      mytool: tool({
        description: "This is a custom tool",
        args: { foo: tool.schema.string() },
        async execute(args, context) { return `Hello ${args.foo}` },
      }),
    },
  }
}
```

実体験ですが、プラグイン名をビルトインツールと同じにすると優先される、という挙動も仕様としてあるので「既存ツールの挙動を上書きしたい」ときに素直に使えます。プラグインは Bun で自動インストールされ、依存は `~/.cache/opencode/` にキャッシュされます。

## 3. Claude Code — MCP が圧倒的に使いやすい

Claude Code（Anthropic, 商用）の拡張は、**MCP（Model Context Protocol）**が中心です。

- `claude mcp add --transport http <name> <url>` の 1 行で外部ツールに接続
- スコープは local / project / user の 3 段階。`.mcp.json` をリポジトリにコミットすればチーム共有もできる
- プラグインシステム（`/plugin install`）もあり、`mcp-server-dev` のような公式プラグインで MCP サーバーを自動生成できる
- GitHub・Sentry・DB など Anthropic Directory のコネクタが充実

```bash
# 例: GitHub の MCP サーバーに接続
claude mcp add --transport http github https://api.githubcopilot.com/mcp/ \
  --header "Authorization: Bearer YOUR_GITHUB_PAT"
```

MCP サーバーは外部プロセスとして動くので、ハーネスのコードには一切触れません。「ハーネス本体は触らず、外側にツールを足していく」拡張の代表格です。商用なので本体の改造はできませんが、その分 MCP エコシステムの充実度は一番です。

## 4. Goose — MCP エクステンションで全部足す

Goose（Linux Foundation / AAIF, Apache-2.0）は、Block が作っていたローカルエージェント。拡張は MCP エクステンションに一本化されています。

- Desktop / CLI / API の 3 形態
- コーディングだけでなく、調査・文章・自動化・データ分析まで対象
- 多数の MCP サーバーを「エクステンション」として追加して機能を広げていく

拡張の哲学としては「自分ではツールを作らせず、外部の MCP に任せる」シンプルさが売りです。

## 5. フレームワーク系は「ハーネス」ではない

Pydantic AI や CrewAI、LangGraph のような **エージェントフレームワーク**も拡張性は高いのですが、これは「動くもの」ではなく「組み立てる材料」です。エンドユーザーが毎日使うハーネスというより、開発者が自作エージェントを作る土台なので、今回の「ハーネスを育てる」文脈とは分けて考えるのが正確だと思います。

## 結局どう選ぶか

- **ハーネスに自動で学ばせたい** → Hermes。経験からスキルを自動生成・自己改善する
- **開発ハーネスに手軽にツールを足したい** → OpenCode。型安全なプラグインとイベントフック
- **MCP の豊富なコネクタを使いたい** → Claude Code。エコシステムが最大
- **シンプルに外部ツールを足したい** → Goose。MCP エクステンション中心

:::note
「拡張できる」にも段階がある、というのが今回の気づきです。コアを書き換えずに機能を足せる（OpenCode / Claude Code / Goose）段階と、エージェント自身が経験から手順を学んで保存できる（Hermes）段階では、時間が経つほど使い勝手の差が広がります。
:::

私の場合は、日々のコーディングは OpenCode、常駐・自動化は Hermes と、使い分けがしっくりきています。ハーネスを 1 本に絞るより、拡張性の方向性が違うものを併用するのが 2026 年の現実解です。

## 参考

1. [Hermes Agent — Features Overview](<https://hermes-agent.nousresearch.com/docs/user-guide/features/overview/>)
2. [Hermes Agent — Plugins](<https://hermes-agent.nousresearch.com/docs/user-guide/features/plugins/>)
3. [Hermes Agent — Skills System](<https://hermes-agent.nousresearch.com/docs/user-guide/features/skills/>)
4. [OpenCode — Plugins](<https://opencode.ai/docs/plugins/>)
5. [OpenCode — Custom Tools](<https://opencode.ai/docs/custom-tools/>)
6. [Claude Code — Connect via MCP](<https://code.claude.com/docs/en/mcp>)
7. [Goose — GitHub](<https://github.com/block/goose>)
