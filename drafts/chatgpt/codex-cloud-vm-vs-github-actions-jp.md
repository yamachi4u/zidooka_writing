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

ChatGPTとGitHubをつないで作業している最中、急に腑に落ちました。

Codexは、こちらの指示を受けてOpenAI側の隔離された環境でリポジトリを読み、ファイルを編集し、PythonやNode.jsを実行できます。スマホから頼んでも、自宅のPCを起動しておく必要はありません。

それなら、感覚としては==スマホの中にPCが生えた==ようなものではないでしょうか。

かなり近い理解です。ただし、ここで次の疑問が出てきました。

> GitHub Actionsを実行基盤にせず、CodexのVMですべて処理すればよいのでは？

結論から言えば、単発の仕事ならそれでよい場合があります。しかし、定期処理や公開処理まで全部をCodexの作業環境へ寄せるのは違います。CodexとGitHub Actionsは競合する基盤ではなく、役割が異なります。

:::conclusion
Codexは「考えて、その場で手を動かす作業員」、GitHub Actionsは「決められた手順を同じ条件で繰り返す実行装置」と考えると分かりやすいです。
:::

## Codexの「VM」は正確にはクラウドコンテナ

「VM」と呼びたくなりますが、OpenAIの公式ドキュメントでは、Codex cloudの作業環境は隔離されたコンテナとして説明されています。

タスクを渡すと、Codexはおおむね次のように動きます。

1. GitHubから対象リポジトリをチェックアウトする
2. セットアップスクリプトを実行する
3. ターミナルコマンドを繰り返し実行する
4. コードや文書を編集する
5. テストや検証を行う
6. 差分を提示し、Pull Requestへつなぐ

Python、Node.js、Gitなどの一般的な道具が入ったコンテナなので、単にコードを提案するだけではありません。実際にファイルを作り、プログラムを動かし、結果を確認できます。

その意味では「スマホの中にPCが生えた」という理解はかなり正しいです。より厳密には、スマホはPC本体ではなく、==クラウド上の作業員とコンテナへ目的を伝える司令端末==になっています。

## Codexに向いている仕事

Codexが強いのは、毎回少しずつ条件が違い、人間の言葉を解釈する必要がある仕事です。

- 話題を調べて記事を書く
- 既存コードを読んで修正箇所を判断する
- エラーの原因を調べて直す
- データを分析してレポートを作る
- 複数のファイルを横断して整合性を確認する
- Pythonを一度だけ実行して結果を得る
- Pull Requestを作り、結果を説明する

スマホから「この話を日英記事にして、既存の書式に合わせて公開して」と頼めるのは、この柔軟性があるからです。

## GitHub Actionsに向いている仕事

一方、GitHub Actionsが強いのは、手順がすでに決まっている仕事です。

- Pull Requestのマージ後に自動公開する
- 毎日・毎週決まった処理を実行する
- 同じバージョンの依存関係でテストする
- GitHub Secretsを使ってWordPress APIへ接続する
- 実行ログを残す
- 失敗時に止まり、再実行できるようにする
- 複数人が同じWorkflowを使う

GitHub Actionsでは、Repository SecretsやEnvironment SecretsをWorkflowの入力や環境変数として渡せます。WordPressのアプリケーションパスワードのような認証情報を、記事ファイルや公開リポジトリへ書かずに使えます。

また、`workflow_dispatch` を設定したWorkflowは、GitHubの画面、CLI、REST APIから手動実行できます。スマホのGitHub画面からボタンを押すだけの操作も作れます。

## なぜCodexだけに寄せないのか

最大の理由は、Codexのコンテナが永続サーバーではないからです。

OpenAIの公式説明では、コンテナのキャッシュは最大12時間です。環境変数はチャットのあいだ利用できますが、コンテナ内へ置いたファイルが永続的に残るとは期待できません。

さらに、Codex cloudのSecretsはセットアップスクリプトでのみ利用でき、エージェント段階に入る前に取り除かれます。これは安全性のためですが、Codexがその場で秘密鍵を読んで外部APIへ自由に接続する実行基盤には向かないことも意味します。

:::warning
APIキー入りの `.env` をGitHubへコミットして、この制約を回避してはいけません。秘密でない設定はリポジトリ、認証情報はSecretsへ分離します。
:::

つまりCodexのコンテナは、強力ではあるものの「その都度用意される作業場」です。常駐サーバーや永続ストレージの代わりではありません。

## いちばん強いのは役割分担

実際の構成は次のようになります。

1. スマホからChatGPT/Codexへ目的を伝える
2. Codexが調査、判断、執筆、コード修正を行う
3. 成果物をGitHubへ保存する
4. Pull Requestで差分を確定する
5. GitHub ActionsがSecretsを使って公開・デプロイ・定期処理を実行する
6. WordPressなどの永続サービスへ結果を残す

ここでGitHub Actionsは主役ではありません。人間の指示を解釈する役割はCodexへ移り、Actionsは確定済みの処理を安全に実行する「下請け」になります。

:::note
単発のPython処理や、秘密情報を必要としない検証ならCodexのコンテナだけで完結できます。すべての処理をActionsへ送る必要もありません。
:::

## スマホが「遠隔操作端末」ではなくなる

従来のスマホからのPC作業は、リモートデスクトップで小さな画面を操作するものでした。これはPCの画面をスマホへ縮小しているだけで、操作負担はあまり減りません。

Codex cloudでは違います。

人間は画面上の手順ではなく、目的を伝えます。

:::example
「この記事を、ZIDOOKAの既存ルールに合わせて日英で書く。公式資料で事実確認し、GitHubへPRを作り、マージ後のActionsでWordPressへ公開する」
:::

すると、ファイル探索、文体確認、執筆、リンク確認、Git操作といった中間手順をCodexへ渡せます。スマホは作業画面ではなく、指示・承認・結果確認のインターフェースになります。

:::conclusion
スマホの中にPCが生えた、という感覚は間違っていません。ただし本当に生えたのは一台の常駐PCではありません。必要なときに現れるCodexの作業場と、永続的で再現可能なGitHub Actionsの実行基盤を、会話から組み合わせられるようになったのです。
:::

## 参考資料

- [Codex cloud：クラウド環境で作業する](https://learn.chatgpt.com/docs/cloud)
- [Codexのクラウド環境とコンテナキャッシュ](https://learn.chatgpt.com/docs/environments/cloud-environment)
- [GitHub ActionsでSecretsを使う](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)
- [GitHub ActionsのWorkflowを手動実行する](https://docs.github.com/en/actions/how-tos/manage-workflow-runs/manually-run-a-workflow)
