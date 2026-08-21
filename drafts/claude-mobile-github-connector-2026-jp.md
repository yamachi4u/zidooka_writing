---
title: "ClaudeアプリからGitHubを操作する方法：モバイルでGitHubコネクタが見えない場合の対処"
categories:
  - AI
tags:
  - Claude
  - GitHub
  - MCP
  - Claude Code
  - Android
status: publish
slug: claude-mobile-github-connector-2026
---

ClaudeのスマホアプリからGitHubを触りたい。しかし、Android/iOS版のClaudeでConnectorsを開いてもGitHubが見当たらない――2026年8月時点では、この状況が少しややこしい。

調べると、Claudeには実質3種類のGitHub連携が並存している。

:::conclusion
GitHubのIssue・PR・コード検索などをClaudeから操作したいなら、現在の本命は公式の「GitHub MCP」コネクタ。AnthropicのConnectors DirectoryではClaude Mobile対応、Read & Write対応と明記されている。

ただし、モバイルアプリ側のConnectors一覧にGitHubが表示されないケースがある。その場合はWeb版のClaudeでGitHub MCPを先に接続し、その後モバイルアプリから使うのが現実的な回避策になる。
:::

## ClaudeにはGitHub連携が3種類ある

### 1. 従来の「Add from GitHub」

ClaudeのチャットやProjectsで「+」→「Add from GitHub」を選び、リポジトリ内のファイルをClaudeのコンテキストとして読み込む機能。

これはコードベースの説明やレビューには便利だが、Anthropicのヘルプでは取得対象は「特定ブランチのファイル名と内容」に限られ、commit履歴、PR、そのほかのメタデータは取得しないと明記されている。

つまり、これは基本的に「GitHubを操作する機能」ではなく「GitHub上のコードをClaudeに読ませる機能」だ。

### 2. GitHub MCP Connector

現在はこちらが本命。

AnthropicのConnectors Directoryには「GitHub MCP / The Official GitHub MCP Server」が掲載されており、利用可能な環境としてClaude、Claude Desktop、Claude Mobile、Claude Code、Claude APIが挙げられている。

Capabilitiesは「Read & write」。紹介されているユースケースも、READMEやrecent commitsの確認、Issue管理、PRレビュー、リポジトリ内検索など、従来のAdd from GitHubよりかなり広い。

つまり構造的には、

`Claudeアプリ → GitHub MCP → GitHub API`

という形で、ClaudeにGitHubの操作ツールを与える方式と理解するとよい。

### 3. Claude CodeのGitHub連携

コードを実際に編集し、ブランチを切り、pushやPR作成までやらせたいならClaude Code側がさらに強い。

Claude Code on the webはGitHubとのgit操作をサンドボックス内の安全なproxy経由で処理する設計になっている。2026年のClaude MobileではCodeタブも用意され、接続済みGitHubリポジトリを指定して新しいCode sessionを始めることができる。

公式ドキュメントには、たとえば次のようなdeep linkも案内されている。

`claude://code/new?repo=owner%2Frepo&branch=main`

GitHub MCPが「GitHub API上の操作」に強いのに対し、Claude Codeは「リポジトリをcheckoutしてコードを書き換え、実行し、gitで戻す」ところまで含む開発エージェントだと考えると分かりやすい。

## では、なぜスマホのConnectors一覧にGitHubがないのか

ここが今回の引っかかりどころ。

Anthropic公式ドキュメント上では、remote connectorはClaude Mobileでも利用可能で、GitHubもremote connectorの例として挙げられている。さらにGitHub MCPの個別ページにもClaude Mobile対応と明記されている。

一方、2026年8月のコミュニティ報告では、iOS/AndroidのConnectors一覧にGitHubが表示されず、Web版から設定すると使える、という報告が出ている。

公式仕様とモバイルUIの表示が完全には一致していない状態に見える。

:::warning
モバイルアプリのConnectors一覧にGitHubがないからといって、「Claude MobileはGitHub非対応」とは言えない。公式には対応対象に入っている。UIの露出、アカウント状態、段階的ロールアウトなどの影響を疑うべき状況だ。
:::

## 現時点で一番確実な接続手順

### GitHub MCPを使いたい場合

1. スマホのブラウザまたはPCで `claude.ai` を開く
2. Settings → Connectors を開く
3. GitHub / GitHub MCPを探す
4. GitHub OAuthで認証する
5. 接続後、Claudeモバイルアプリを開く
6. 「このリポジトリのIssueを一覧して」「PRをレビューして」などと指示する

Remote connectorはアカウント単位で使う仕組みなので、Web側で接続したものをモバイルでも利用する、というのがポイントになる。

### コードそのものを編集したい場合

Claudeアプリの「Code」タブを使う。

GitHubアカウントが接続済みなら、repoとbranchを指定してClaude Code sessionを開始できる。Codeタブが利用できる契約・アカウントであれば、PCを起動していなくてもクラウド上のClaude Codeからリポジトリを操作できる。

## 使い分け

ざっくり次のように分けると迷いにくい。

- コードをClaudeに読ませて相談したい → Add from GitHub
- Issue、PR、commit、リポジトリ検索などGitHub自体を操作したい → GitHub MCP
- 実際にコードを書き換えて、テスト、commit、push、PRまで進めたい → Claude Code

「ClaudeアプリでGitHub操作したい」という要求に最も近いのは、2026年時点ではGitHub MCPまたはClaude Codeである。

## まとめ

Claude MobileからGitHubを使う手段は存在する。しかも公式GitHub MCPはRead & Write対応になっている。

ただしモバイルアプリ上でGitHubコネクタが一覧に出ないケースがあり、その場合はWeb版Claudeで先にGitHubを接続する方法が有力。単にリポジトリをClaudeへ読み込む「Add from GitHub」と、GitHub APIを操作する「GitHub MCP」、実際の開発作業を行う「Claude Code」は別物なので、そこを分けて理解する必要がある。

## 参考

- Anthropic Connectors Directory, GitHub MCP: https://claude.com/connectors/github
- Claude Help Center, Use the GitHub integration: https://support.claude.com/en/articles/10167454-use-the-github-integration
- Claude Help Center, Use connectors to extend Claude's capabilities: https://support.claude.com/en/articles/11176164-use-connectors-to-extend-claude-s-capabilities
- Claude Help Center, When to use desktop and web connectors: https://support.claude.com/en/articles/11725091-when-to-use-desktop-and-web-connectors
- Claude Help Center, Open the Claude mobile app with a link: https://support.claude.com/en/articles/14898120-open-the-claude-mobile-app-with-a-link
