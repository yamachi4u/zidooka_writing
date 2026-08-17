---
title: "CI環境って何？ 自前CIは持てる？ GitHub Actionsのself-hosted runnerまで整理する"
categories:
  - general
tags:
  - CI
  - GitHub Actions
  - self-hosted runner
  - DevOps
  - GitHub
status: publish
slug: self-hosted-ci-guide-ja
---

CI（Continuous Integration、継続的インテグレーション）という言葉を見かけると、最初は少し大げさな仕組みに見えます。でも実際には、かなり単純です。

:::conclusion
CI環境とは、コードを変更したときに、テスト・ビルド・静的解析などを自動実行するための実行環境です。そして、その実行環境は自分でも持てます。
:::

## CI環境とは何か

たとえばGitHubにコードを置いていて、`git push` したら自動で次の処理を走らせたいとします。

```text
自分のPC
  ↓ git push
GitHub
  ↓
CI環境
  ├─ npm install
  ├─ npm run build
  ├─ npm test
  └─ lint / type check
```

この「CI環境」に相当するのが、GitHub Actionsであれば通常はGitHubが用意するrunnerです。

ワークフローに次のように書けば、GitHub側がUbuntu環境を用意してジョブを実行します。

```yaml
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm test
```

自分のPCでテストするのではなく、別の標準化された環境で毎回自動確認するため、「自分のPCでは動くけど他では動かない」という問題も見つけやすくなります。

## CI環境は自前で持てる

持てます。

大きく分けると、2通りあります。

1つ目は、GitHub Actionsの管理部分はGitHubに任せつつ、実際に処理を実行するマシンだけ自前にする方法です。GitHubではこれを **self-hosted runner** と呼びます。

2つ目は、JenkinsやGitLab CI/CDなどを使い、GitサーバやCIサーバを含めて基盤そのものを自分で管理する方法です。

個人用途で「まず自前CIを触ってみたい」なら、GitHub Actions + self-hosted runnerが分かりやすい構成です。

## GitHub Actionsのself-hosted runner

self-hosted runnerでは、GitHubがジョブを管理し、実際の処理だけ自宅PC、ミニPC、研究室サーバ、VPSなどで実行できます。

```text
GitHub repository
      ↓ workflow開始
GitHub Actions
      ↓ ジョブを割り当て
自宅・研究室のPC
      ↓
build / test / deploy
```

GitHub公式ドキュメントでも、self-hosted runnerは物理マシン、仮想マシン、コンテナ、オンプレミス、クラウドなどで利用できると説明されています。

[GitHub Docs: Self-hosted runners](https://docs.github.com/en/actions/concepts/runners/self-hosted-runners)

GitHub-hosted runnerよりも、OS、CPU、メモリ、GPU、インストール済みソフトウェア、ローカルネットワークへのアクセスなどを細かく制御できます。

たとえばGPUを積んだPCをrunnerにすれば、GitHubのワークフローから自分のGPUマシンにAI処理を実行させる、といった構成も可能です。

## workflow側ではどう書くのか

self-hosted runnerをGitHubに登録したら、workflowの `runs-on` を変更します。

```yaml
jobs:
  build:
    runs-on: self-hosted
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm run build
```

つまり、

```yaml
runs-on: ubuntu-latest
```

ならGitHub側のマシン、

```yaml
runs-on: self-hosted
```

なら登録した自前マシンで実行されます。

OSや用途ごとのlabelを付けて、特定のrunnerにジョブを振り分けることもできます。

[GitHub Docs: Using self-hosted runners in a workflow](https://docs.github.com/en/actions/how-tos/manage-runners/self-hosted-runners/use-in-a-workflow)

## 何がうれしいのか

自前CIのメリットは、単に「GitHubの計算時間を節約できる」ということだけではありません。

まず、自分でハードウェアを選べます。大量のRAMが必要、GPUが必要、大容量SSDを使いたい、といった用途にも対応できます。

次に、特殊なソフトウェアや依存関係をあらかじめインストールしておけます。毎回巨大な環境を構築する必要がある処理ではかなり便利です。

さらに、自宅や研究室のLAN内にある機器・ストレージ・サービスへアクセスするCIも構築できます。

:::example
研究データをNASに置き、GitHubへのpushをきっかけに研究室のrunnerで解析スクリプトを実行し、結果だけを成果物として残す、といった運用もできます。
:::

## GitサーバもCIも全部自前にすることもできる

さらに進めると、GitHub自体を使わない構成も可能です。

```text
自前Gitサーバ
    ↓ push
自前CIサーバ
    ↓
test
    ↓
build
    ↓
deploy
```

GitLabをself-managedで運用したり、GitサーバとJenkinsを組み合わせたりすれば、ソースコード管理からCI/CDまで自分のネットワーク内で完結できます。

ただし、この構成ではサーバ更新、バックアップ、認証、セキュリティ、障害対応まで自分で担当することになります。

## self-hosted runnerで一番注意したいこと

便利ですが、セキュリティは重要です。

GitHubの公式ドキュメントは、self-hosted runnerについて、GitHub-hosted runnerのようにジョブごとに必ずクリーンな一時環境になるわけではないと説明しています。また、信頼できないコードをrunnerで実行すると、そのマシンや保存されている認証情報へ影響が及ぶ可能性があります。

:::warning
特に公開リポジトリでself-hosted runnerを使う場合は要注意です。GitHubも、公開リポジトリでは悪意あるPull Request経由でrunnerが攻撃される可能性があるため、self-hosted runnerの利用を強く警戒するよう案内しています。
:::

[GitHub Docs: Secure use reference](https://docs.github.com/en/actions/reference/security/secure-use)

普段使っているメインPCをそのままrunnerにするより、専用のミニPC、VM、コンテナ、用途を限定したサーバなどに分離する方が安全です。

## 余っているPCがCIサーバになる

ここが自前CIのおもしろいところです。

古いデスクトップPCやミニPCでも、Linuxを入れてself-hosted runnerとして登録すれば、GitHubからジョブを受け取るCIマシンとして再利用できます。

普段はほぼ何もしていなくても、pushされたときだけ、

```text
コード取得
↓
依存関係確認
↓
テスト
↓
ビルド
↓
必要ならデプロイ
```

という処理を自動でこなしてくれます。

:::conclusion
個人開発なら、最初から巨大なCI基盤を構築する必要はありません。まずGitHub Actionsを使い、必要になったら余っているPCやミニPCをself-hosted runnerとして追加する。この構成だけでも「自分のCI環境を持つ」感覚はかなり分かりやすく体験できます。
:::
