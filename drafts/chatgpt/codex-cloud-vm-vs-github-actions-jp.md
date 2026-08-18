---
title: "スマホの中にPCが生えた：CodexクラウドVMとGitHub Actionsの正しい使い分け"
slug: codex-cloud-vm-vs-github-actions-jp
status: publish
categories:
  - ChatGPT
tags:
  - ChatGPT
  - Codex
  - GitHub Actions
  - クラウド開発
  - 自動化
---

ChatGPTとGitHubをつないで作業している最中、急に腑に落ちた。

Codexは、こちらの指示を受けてOpenAI側の隔離された環境でリポジトリを読み、ファイルを編集し、PythonやNode.jsを実行できる。スマホから頼んでも、自宅のPCを起動しておく必要はない。

それなら、感覚としては==スマホの中にPCが生えた==ようなものではないか。

かなり近い。ただし、ここで次の疑問が出てきた。

> GitHub Actionsを実行基盤にせず、CodexのVMですべて処理すればよいのでは？

結論から言えば、単発の仕事ならそれでよい。しかし、定期処理や公開処理まで全部をCodexの作業環境へ寄せるのは違う。CodexとGitHub Actionsは競合する基盤ではなく、役割が異なる。

:::conclusion
Codexは「考えて、その場で手を動かす作業員」、GitHub Actionsは「決められた手順を同じ条件で繰り返す実行装置」と考えると分かりやすい。
:::

## Codexの「VM」は正確にはクラウドコンテナ

「VM」と呼びたくなるが、OpenAIの公式ドキュメントでは、Codex cloudの作業環境は隔離されたコンテナとして説明されている。

タスクを渡すと、Codexはおおむね次のように動く。

1. GitHubから対象リポジトリをチェックアウトする
2. セットアップスクリプトを実行する
3. ターミナルコマンドを繰り返し実行する
4. コードや文書を編集する
5. テストや検証を行う
6. 差分を提示し、Pull Requestへつなぐ

Python、Node.js、Gitなどの一般的な道具が入ったコンテナなので、単にコードを提案するだけではない。実際にファイルを作り、プログラムを動かし、結果を確認できる。

その意味では「スマホの中にPCが生えた」という理解はかなり正しい。より厳密には、スマホはPC本体ではなく、==クラウド上の作業員とコンテナへ目的を伝える司令端末==になっている。

## Codexに向いている仕事

Codexが強いのは、毎回少しずつ条件が違い、人間の言葉を解釈する必要がある仕事だ。

- 話題を調べて記事を書く
- 既存コードを読んで修正箇所を判断する
- エラーの原因を調べて直す
- データを分析してレポートを作る
- 複数のファイルを横断して整合性を確認する
- Pythonを一度だけ実行して結果を得る
- Pull Requestを作り、結果を説明する

スマホから「この話を日英記事にして、既存の書式に合わせて公開して」と頼めるのは、この柔軟性があるからだ。

## GitHub Actionsに向いている仕事

一方、GitHub Actionsが強いのは、手順がすでに決まっている仕事だ。

- Pull Requestのマージ後に自動公開する
- 毎日・毎週決まった処理を実行する
- 同じバージョンの依存関係でテストする
- GitHub Secretsを使ってWordPress APIへ接続する
- 実行ログを残す
- 失敗時に止まり、再実行できるようにする
- 複数人が同じWorkflowを使う

GitHub Actionsでは、Repository SecretsやEnvironment SecretsをWorkflowの入力や環境変数として渡せる。WordPressのアプリケーションパスワードのような認証情報を、記事ファイルや公開リポジトリへ書かずに使える。

また、`workflow_dispatch` を設定したWorkflowは、GitHubの画面、CLI、REST APIから手動実行できる。スマホのGitHub画面からボタンを押すだけの操作も作れる。

## なぜCodexだけに寄せないのか

最大の理由は、Codexのコンテナが永続サーバーではないからだ。

OpenAIの公式説明では、コンテナのキャッシュは最大12時間である。環境変数はチャットのあいだ利用できるが、コンテナ内へ置いたファイルが永続的に残ると期待すべきではない。

さらに、Codex cloudのSecretsはセットアップスクリプトでのみ利用でき、エージェント段階に入る前に取り除かれる。これは安全性のためだが、Codexがその場で秘密鍵を読んで外部APIへ自由に接続する実行基盤には向かないことも意味する。

:::warning
APIキー入りの `.env` をGitHubへコミットして、この制約を回避してはいけない。秘密でない設定はリポジトリ、認証情報はSecretsへ分離する。
:::

つまりCodexのコンテナは、強力ではあるが「その都度用意される作業場」だ。常駐サーバーや永続ストレージの代わりではない。

## いちばん強いのは役割分担

実際の構成は次のようになる。

1. スマホからChatGPT/Codexへ目的を伝える
2. Codexが調査、判断、執筆、コード修正を行う
3. 成果物をGitHubへ保存する
4. Pull Requestで差分を確定する
5. GitHub ActionsがSecretsを使って公開・デプロイ・定期処理を実行する
6. WordPressなどの永続サービスへ結果を残す

ここでGitHub Actionsは主役ではない。人間の指示を解釈する役割はCodexへ移り、Actionsは確定済みの処理を安全に実行する「下請け」になる。

:::note
単発のPython処理や、秘密情報を必要としない検証ならCodexのコンテナだけで完結できる。すべての処理をActionsへ送る必要もない。
:::

## スマホが「遠隔操作端末」ではなくなる

従来のスマホからのPC作業は、リモートデスクトップで小さな画面を操作するものだった。これはPCの画面をスマホへ縮小しているだけで、操作負担はあまり減らない。

Codex cloudでは違う。

人間は画面上の手順ではなく、目的を伝える。

:::example
「この記事を、ZIDOOKAの既存ルールに合わせて日英で書く。公式資料で事実確認し、GitHubへPRを作り、マージ後のActionsでWordPressへ公開する」
:::

すると、ファイル探索、文体確認、執筆、リンク確認、Git操作といった中間手順をCodexへ渡せる。スマホは作業画面ではなく、指示・承認・結果確認のインターフェースになる。

:::conclusion
スマホの中にPCが生えた、という感覚は間違っていない。ただし本当に生えたのは一台の常駐PCではない。必要なときに現れるCodexの作業場と、永続的で再現可能なGitHub Actionsの実行基盤を、会話から組み合わせられるようになったのである。
:::

## 参考資料

- [Codex cloud：クラウド環境で作業する](https://learn.chatgpt.com/docs/cloud)
- [Codexのクラウド環境とコンテナキャッシュ](https://learn.chatgpt.com/docs/environments/cloud-environment)
- [GitHub ActionsでSecretsを使う](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)
- [GitHub ActionsのWorkflowを手動実行する](https://docs.github.com/en/actions/how-tos/manage-workflow-runs/manually-run-a-workflow)
