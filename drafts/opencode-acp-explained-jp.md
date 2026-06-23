---
title: "OpenCodeのACP対応で何ができる？ エディタ選ばずAIエージェントを使う共通プロトコル"
categories:
  - AI
tags:
  - OpenCode
  - ACP
  - Agent Client Protocol
  - AI
  - Zed
  - エディタ
  - 開発環境
status: publish
slug: opencode-acp-explained-jp
featured_image: ../images/2026/05/opencode-acp-thumbnail-ja.png
---

OpenCodeには `opencote acp` というコマンドがあります。これは何のためにあるのか、どんなことができるのかを整理します。

:::conclusion
ACP（Agent Client Protocol）は、エディタとAIエージェントの間の共通通信プロトコルです。Zed Industriesが中心となって策定し、OpenCodeを含む30以上のエージェントが対応しています。Zed、JetBrains、VS Code、Neovim、Emacsなど幅広いエディタで「好きなエージェント」を選んで使えるようになります。
:::

## ACPとは何か

ACPは **Agent Client Protocol** の略で、コードエディタ（クライアント）とAIコーディングエージェント（サーバー）の間の通信を標準化するオープンプロトコルです。

仕様はGitHubで公開されており（[agentclientprotocol/agent-client-protocol](https://github.com/agentclientprotocol/agent-client-protocol)）、2026年5月時点で3,200以上のスターがついています。

発想の源はLSP（Language Server Protocol）です。LSPが「どんなエディタでも同じ言語サーバーを使える」ようにしたように、ACPは「どんなエディタでも同じAIエージェントを使える」ようにするものです。

:::note
ACPは Zed Industries が中心となって策定しましたが、現在はJetBrainsやGoogle、Anthropic、OpenAIなども参加するコミュニティ主導のプロトコルになっています。
:::

ACPの通信はJSON-RPC over stdioで行われます。エディタがエージェントをサブプロセスとして起動し、標準入出力でメッセージをやり取りします。将来的にはHTTPやWebSocketを使ったリモート接続にも対応予定です。

## OpenCodeとACP

OpenCodeをACPエージェントとして起動するには、次のコマンドを使います。

```powershell
opencode acp --cwd /path/to/project
```

このコマンドでOpenCodeはACP対応のサブプロセスとして起動し、エディタとJSON-RPCで通信します。

### 対応エディタでの設定例

**Zed**（`~/.config/zed/settings.json`）:

```json
{
  "agent_servers": {
    "OpenCode": {
      "command": "opencode",
      "args": ["acp"]
    }
  }
}
```

**JetBrains IDE**（`acp.json`）:

```json
{
  "agent_servers": {
    "OpenCode": {
      "command": "/absolute/path/bin/opencode",
      "args": ["acp"]
    }
  }
}
```

**Neovim + CodeCompanion.nvim**:

```lua
require("codecompanion").setup({
  interactions = {
    chat = {
      adapter = {
        name = "opencode",
        model = "claude-sonnet-4",
      },
    },
  },
})
```

### ACP経由で使えるOpenCodeの機能

ACP経由でも、OpenCodeのほぼ全機能が利用可能です。

- ファイルの読み書き・編集
- ターミナルコマンドの実行
- MCPサーバー（外部ツール連携）
- カスタムツール・スラッシュコマンド
- プロジェクトの `AGENTS.md` ルールの反映
- カスタムフォーマッター・リンター
- エージェント・パーミッションシステム

:::warning
現時点で `/undo` と `/redo` のスラッシュコマンドはACP経由では未対応です。
:::

## ACP対応エージェント一覧（抜粋）

OpenCode以外にも、多くのAIエージェントがACPに対応しています。2026年5月時点で30以上です。

| エージェント | 開発元 | リンク |
|-------------|--------|--------|
| Gemini CLI | Google | [github.com/google-gemini/gemini-cli](https://github.com/google-gemini/gemini-cli) |
| Claude Agent | Anthropic | [ACPアダプター経由](https://github.com/zed-industries/claude-agent-acp) |
| Codex CLI | OpenAI | [ACPアダプター経由](https://github.com/zed-industries/codex-acp) |
| GitHub Copilot | GitHub | [パブリックプレビュー中](https://github.blog/changelog/2026-01-28-acp-support-in-copilot-cli-is-now-in-public-preview/) |
| Cursor | Cursor | [cursor.com/docs/cli/acp](https://cursor.com/docs/cli/acp) |
| Goose | Square/Block | [ACPクライアント対応](https://block.github.io/goose/docs/guides/acp-clients) |
| Cline | Cline | [cline.bot](https://cline.bot/) |
| Qwen Code | Alibaba | [github.com/QwenLM/qwen-code](https://github.com/QwenLM/qwen-code) |
| Junie | JetBrains | [junie.jetbrains.com](https://junie.jetbrains.com/) |
| Augment Code | Augment | [docs.augmentcode.com/cli/acp](https://docs.augmentcode.com/cli/acp) |
| Mistral Vibe | Mistral AI | [github.com/mistralai/mistral-vibe](https://github.com/mistralai/mistral-vibe) |
| OpenHands | All Hands AI | [ACP対応](https://docs.openhands.dev/openhands/usage/run-openhands/acp) |

他にも `Aider`（実装進行中）、`Docker cagent`、`Kimi CLI`（Moonshot AI）、`Kiro CLI`、`Factory Droid` などが対応しています。

全リストは公式サイトで確認できます: <https://agentclientprotocol.com/overview/agents>

## ACP対応クライアント（エディタ・ツール）

エディタ側のACP対応も急速に広がっています。

### 主要エディタ

- **Zed** — ネイティブ対応（ACP発案元）[設定ドキュメント](https://zed.dev/docs/ai/external-agents)
- **JetBrains IDE** — IntelliJ、WebStorm、PyCharmなど全製品で対応 [ヘルプ](https://www.jetbrains.com/help/ai-assistant/acp.html)
- **VS Code** — [ACP Client拡張機能](https://github.com/formulahendry/vscode-acp) で対応
- **Neovim** — 3つのプラグイン:
  - [CodeCompanion.nvim](https://github.com/olimorris/codecompanion.nvim)
  - [avante.nvim](https://github.com/yetone/avante.nvim)
  - [agentic.nvim](https://github.com/carlos-algms/agentic.nvim)
- **Emacs** — [agent-shell.el](https://github.com/xenodium/agent-shell)
- **Obsidian** — [Agent Clientプラグイン](https://github.com/RAIT-09/obsidian-agent-client)
- **Unity** — 複数のACPプラグインが存在

### CLI・デスクトップツール

- **Jockey** — 複数のACPエージェントを束ねるマルチエージェントオーケストレーター [github.com/recailai/jockey](https://github.com/recailai/jockey)
- **acpx CLI** — ターミナル向けACPクライアント [github.com/openclaw/acpx](https://github.com/openclaw/acpx)
- **ACP UI** — クロスプラットフォームGUI [github.com/formulahendry/acp-ui](https://github.com/formulahendry/acp-ui)
- **Toad** — ターミナル向けエージェントインターフェース [batrachian.ai](https://www.batrachian.ai/)

### チャット連携

ACPエージェントをDiscord、Slack、Telegramから呼び出すブリッジも登場しています。

- [OpenACP](https://github.com/Open-ACP/OpenACP) — Telegram、Discord、Slack対応のセルフホストブリッジ
- [Telegram ACP Bot](https://github.com/mgaitan/telegram-acp-bot) — Telegram連携
- [WeChat ACP](https://github.com/formulahendry/wechat-acp) — WeChat連携

### フレームワーク連携

- **LangChain / LangGraph** — [Deep Agents ACP](https://docs.langchain.com/oss/python/deepagents/acp) で対応
- **LlamaIndex** — [workflows-acp](https://github.com/AstraBert/workflows-acp) アダプター
- **Koog**（JetBrains）— 組み込み対応

:::note
モバイルでも `Agmente`（iOS）、`Ferngeist`（Android）、`Happy`（iOS/Android/Web）などのACPクライアントが登場しています。
:::

## ACPのメリット

ACPを使うことで得られる利点は3つあります。

### 1. エディタとエージェントの自由な組み合わせ

従来は「このエージェントはこのエディタでしか使えない」という制約がありました。ACPでは、好きなエディタで好きなエージェントを選べます。

例えば、ZedでOpenCodeを使う、JetBrainsでGemini CLIを使う、NeovimでClaude Agentを使う、といった組み合わせがすべて可能です。

### 2. エージェントの切り替えが容易

プロジェクトやタスクに応じてエージェントを切り替えられます。設定ファイルを書き換えるだけで、同じエディタのまま別のエージェントを呼び出せます。

### 3. エコシステムの拡大

ACPが共通プロトコルになったことで、新しいエージェントやクライアントが続々と登場しています。エディタ開発者は1つのプロトコルに対応するだけで、30以上のエージェントにアクセスできるようになります。

## 実際のユースケース

### ケース1: Zed + OpenCode でターミナル不要のAI開発

ZedエディタにOpenCodeをACPで接続すると、エディタ内で直接AIに質問したり、コード生成を依頼したりできます。OpenCodeのTUIを別途起動する必要はありません。

### ケース2: JetBrains + 複数エージェントの使い分け

IntelliJ IDEAにOpenCodeとGemini CLIの両方をACPで登録しておき、通常のコーディングはOpenCode、大規模リファクタリングはGemini CLIという使い分けが可能です。

### ケース3: Jockeyでマルチエージェント並列実行

Jockeyを使うと、Claude Code、Gemini CLI、Codex CLIを同時に起動して、同じプロジェクトに対して並列にタスクを割り当てられます。コードレビューをClaude Codeに、テスト生成をGemini CLIに、といった分担が可能です。

### ケース4: Discord/SlackからACPエージェントを呼び出す

OpenACPなどのブリッジを使えば、チームのSlackチャンネルからOpenCodeに「このPRレビューして」と依頼できます。エディタを開いていなくてもAIアシスタントを利用できます。

:::conclusion
ACP（Agent Client Protocol）は、エディタとAIエージェントの垣根を取り払う共通プロトコルです。OpenCodeも `opencode acp` コマンドでACPエージェントとして動作し、Zed、JetBrains、Neovim、VS Codeなど幅広いエディタから利用できます。30以上のエージェントと多数のクライアントが対応しており、エコシステムは急速に拡大中です。
:::

### 参考リンク

- ACP公式サイト: <https://agentclientprotocol.com>
- ACP仕様リポジトリ: <https://github.com/agentclientprotocol/agent-client-protocol>
- ACP進捗レポート（Zed Blog）: <https://zed.dev/blog/acp-progress-report>
- OpenCode ACPドキュメント: <https://opencode.ai/docs/acp>
