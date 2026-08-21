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

GitHubは「リモートコネクタ」に分類され、本来はWeb・モバイル・デスクトップ・Cowork・Claude Codeから共通して利用できる。一方、Anthropic公式ヘルプは「モバイルへのコネクタのインストールは現在ベータ版」で、Claude DesktopとWebを主要な追加ルートとして案内している。したがって、スマホのConnectors一覧にGitHubが見えない場合は、PCまたはWeb版で先に接続するのが最も確実だ。
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

GitHubのようなクラウドサービス向けのコネクタは「リモートコネクタ」で、Anthropic公式ヘルプではWeb、モバイル、Cowork、Desktop、Claude Codeの各環境で使えるとされている。一度接続すれば、追加のセットアップなしで各環境から利用できる。

ただし同じ公式ヘルプには、モバイルアプリからのコネクタのインストールは現在ベータ版であり、Claude DesktopとWebが主要な追加ルートであるとも明記されている。

つまり「モバイルで利用できる」と「モバイルから安定して新規追加できる」は別の話だ。スマホでGitHubが一覧に出ない場合、仕様上の非対応というより、モバイル側の追加UIがまだ不安定・発展途上である可能性が高い。

:::warning
モバイルアプリのConnectors一覧にGitHubがないからといって、「Claude MobileはGitHub非対応」とは言えない。公式GitHub MCPはClaude Mobile対応で、リモートコネクタ自体も全Claude環境で共通利用できる。一方、モバイルからの新規インストールはベータ版なので、最初の接続はWebまたはDesktopで行うのが安全だ。
:::

## 現時点で一番確実な接続手順

### GitHub MCPを使いたい場合

1. PCのブラウザで `claude.ai` を開く
2. 左下の「+」または「/」から Connectors → Manage connectors を開く、または Settings → Customize → Connectors を開く
3. GitHub / GitHub MCPを探して Connect を押す
4. GitHub側の認証画面でアクセスを許可する
5. 接続後、Claudeモバイルアプリを開く
6. 「このリポジトリのIssueを一覧して」「PRをレビューして」などと指示する

リモートコネクタは一度接続すれば各Claude環境から使えるので、初回接続だけPC/Webで行えば、その後はスマホ側でも同じGitHub連携を利用できる。

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

ポイントは、GitHubが「リモートコネクタ」なので一度接続すればWeb・Mobile・Desktop・Cowork・Claude Codeから共通利用できる一方、モバイルからの新規コネクタ追加は現在ベータ版であること。スマホでGitHubが見つからない場合は、PCまたはWeb版Claudeで最初の接続を済ませるのが確実だ。

単にリポジトリをClaudeへ読み込む「Add from GitHub」と、GitHub APIを操作する「GitHub MCP」、実際の開発作業を行う「Claude Code」は別物なので、そこを分けて理解する必要がある。

## 参考

- Anthropic Connectors Directory, GitHub MCP: https://claude.com/connectors/github
- Claude Help Center, Use the GitHub integration: https://support.claude.com/en/articles/10167454-use-the-github-integration
- Claude Help Center, Use connectors to extend Claude's capabilities: https://support.claude.com/en/articles/11176164-use-connectors-to-extend-claude-s-capabilities
- Claude Help Center, When to use desktop and web connectors: https://support.claude.com/en/articles/11725091-when-to-use-desktop-and-web-connectors
- Claude Help Center, Open the Claude mobile app with a link: https://support.claude.com/en/articles/14898120-open-the-claude-mobile-app-with-a-link
