---
title: "ChatGPT が GitHub リポジトリを読めるようになった — AGENTS.md を読むのが効く"
categories:
  - AI
  - ChatGPT
tags:
  - ChatGPT
  - github
  - Codex
  - OpenCode
  - AI Agent
  - AGENTS.md
status: draft
slug: chatgpt-read-github-repo-ja
---

:::conclusion
ChatGPT に GitHub コネクタが追加された。接続したリポジトリのコードや README、ドキュメントを読んで、答えに引用付きで答えてくれる。ただし読み取り専用で、プッシュもローカル実行もできない。肝は AGENTS.md。AIコーディングエージェントと同じ「プロジェクトの案内書」を ChatGPT も読めるようになった点だ。
:::

## 何が変わったか

設定画面（Settings → Apps）から GitHub を接続し、公開するリポジトリを選ぶだけだ。数分で終わる。接続後は ChatGPT がコードや README、その他のドキュメントをライブで読んで、該当箇所を引用して回答してくれる。

使える場所は Deep Research や Agent Mode（Codex）などの体験だ。プランによっては通常チャットで GitHub アプリが出ないこともある。

## AGENTS.md を読ませてみる

AGENTS.md はリポジトリ直下に置くテキストファイル。ビルドコマンド、テストの流れ、命名規約など、AIエージェント向けの「プロジェクトの案内書」だ。Codex CLI や OpenCode は作業を始める前に必ず目を通す。

私も実際に試してみた。自分のリポジトリを接続して「このプロジェクトの開発環境を教えて」と聞くと、AGENTS.md に書いたセットアップ手順をそのまま引用して説明してくれた。知らないコードベースに触れるとき、ゼロから探す手間がかなり減る。

:::example
「このリポジトリのテストはどうやって実行するの？」
「README には書いていないけど、CI の設定から推測できる？」
こうした聞き方が自然にできるようになる。
:::

どれくらい嬉しいか？ エージェントも ChatGPT も同じ案内書を読む、という共通点がここにある。

## 現状の制限

気になるのは制限だ。

:::warning
現時点では読み取り専用。コードのプッシュや PR 作成はできない。書き込みは Codex の役割だ。
:::

- **ローカルの .env は読めない**: ChatGPT が見るのは GitHub 上のインデックス。手元の PC にしかない未コミットの .env やシークレットは対象外
- **ローカル実行はできない**: コードを動かすのは自分の環境。ChatGPT は読んで答えるだけ
- **ファイル名検索は不可**: リポジトリ名では探せるが、個別ファイル名での検索は非対応
- **反映にタイムラグ**: 接続直後は最大5分ほど待つ。プライベートや新規リポジトリはアクセス設定の確認が必要
- **管理者承認**: GitHub 側の管理者が接続をブロックしていれば使えない

プライバシーも気にしたい。個人プランでは「モデル改善」設定がオンだと学習に使われることがある。ビジネス向けはデフォルトで除外される。

## まとめ

ChatGPT が GitHub を読めるようになったのは、単に「ファイルが増えた」話ではない。AGENTS.md というエージェント共通の案内書をそのまま読めるのが効いている。

書き込みもローカル実行もまだできない。それでも、知らないリポジトリに質問できるだけでも十分実用的だ。まずは自分のリポジトリを1つ接続して、AGENTS.md について聞いてみるのがおすすめだ。

## 参考

1. OpenAI Help Center「Connecting GitHub to ChatGPT」: <https://help.openai.com/en/articles/11145903-connecting-github-to-chatgpt-deep-research>
2. OpenAI Help Center「Using Codex with your ChatGPT plan」: <https://help.openai.com/en/articles/11369540-using-codex-with-your-chatgpt-plan>
3. GitHub Apps「ChatGPT Codex Connector」: <https://github.com/apps/chatgpt-codex-connector>
4. OpenAI Developer Platform「Codex」: <https://developers.openai.com/codex/>
