---
title: "ベッドからChatGPTでWordPress記事を投稿する：GitHub Actions×REST API入門"
slug: chatgpt-github-actions-wordpress-rest-api-jp
status: publish
categories:
  - ChatGPT
  - Wordpress
tags:
  - ChatGPT
  - Codex
  - GitHub Actions
  - WordPress REST API
  - 自動化
featured_image: ../../images/2026/08/chatgpt-github-actions-wordpress-rest-api-thumbnail.png
---

この記事の発端そのものが、この記事で紹介する仕組みの実例です。

私はいま、ベッドにいます。スマホでChatGPTに話しかけて、ZIDOOKAの記事投稿用GitHub Actionsへ予約投稿機能を追加してもらいました。コードの確認、修正、テスト、Pull Requestの作成、マージまで終わったところで、続けてこう頼みました。

> 「ChatGPTからREST APIとGitHub Actionsで記事を投稿しよう、みたいな入門記事を書いてアップしておいて」

パソコンの前に座っていません。それでも、会話を入口にして記事制作から公開まで進められます。

:::conclusion
スマホは作業場所ではなく「指示と確認の端末」になります。重い処理、認証、履歴管理、公開処理はGitHub ActionsとWordPressに任せます。
:::

## どんな仕組みなのか

今回の構成は、次の6段階です。

1. ChatGPTに記事の目的と内容を伝える
2. ChatGPTまたはCodexが日本語版と英語版のMarkdownを作る
3. Markdownと画像をGitHubリポジトリへ追加する
4. Pull Requestで差分を確認してマージする
5. GitHub ActionsがNode.jsの投稿スクリプトを実行する
6. 投稿スクリプトがWordPress REST APIへ記事と画像を送る

ChatGPTがWordPressの管理画面を直接操作し続けるわけではありません。==会話、原稿、実行、認証、公開を別々の層に分ける==のがポイントです。

OpenAIの公式ドキュメントでも、ChatGPTやCodexのコネクタはGitHubなどの外部ツールを読み取り、権限の範囲内で操作する仕組みとして説明されています。またCodex cloudは、GitHubと接続したクラウド環境で作業し、結果をPull Requestとして確認する流れを案内しています。

:::note
利用できる連携機能や画面は、契約プラン、端末、ワークスペース設定によって異なります。この記事では、GitHubへアクセスできるChatGPT/Codex環境が用意されている前提で説明します。
:::

## なぜGitHub Actionsを間に挟むのか

ChatGPTからWordPress REST APIを直接呼ぶだけでも、技術的には投稿できます。しかし、日常運用ではGitHub Actionsを間に挟むほうが扱いやすくなります。

- Markdown原稿がGitの履歴に残る
- Pull Requestで公開前に差分を確認できる
- WordPressの認証情報をGitHub Secretsに隔離できる
- 同じ投稿処理を毎回同じ環境で実行できる
- 失敗時のログをActionsで確認できる
- 予約投稿や日英同時投稿を共通処理にできる

つまりGitHub Actionsは、ChatGPTとWordPressの間に置く==再現可能な作業台==です。

## 必要なもの

最低限、次のものを用意します。

- WordPressサイト
- WordPress REST APIへ投稿できるユーザーとアプリケーションパスワード
- GitHubリポジトリ
- GitHub ActionsのWorkflow
- MarkdownをWordPress用データへ変換して送るスクリプト
- GitHubへ接続できるChatGPT/Codex環境、または自分で原稿を追加する手段

WordPressの投稿作成エンドポイントは `POST /wp/v2/posts` です。`title`、`content`、`slug`、`status`、`categories`、`tags`、`featured_media` などをJSONで送れます。

## まずはWordPress REST APIで1本投稿する

Node.jsとAxiosを使う最小イメージは次のようになります。

```js
import axios from 'axios';

const apiUrl = process.env.WP_API_URL;
const user = process.env.WP_USER;
const password = process.env.WP_APP_PASSWORD.replace(/\s/g, '');

const authorization = Buffer
  .from(`${user}:${password}`)
  .toString('base64');

const response = await axios.post(
  `${apiUrl}/wp/v2/posts`,
  {
    title: 'ChatGPTから投稿した記事',
    content: '<p>Hello from GitHub Actions.</p>',
    slug: 'hello-from-github-actions',
    status: 'publish'
  },
  {
    headers: {
      Authorization: `Basic ${authorization}`
    }
  }
);

console.log(response.data.link);
```

実運用では、Markdown変換、画像アップロード、カテゴリIDの解決、既存記事の更新判定、リトライなども追加します。

:::warning
WordPressのユーザー名やアプリケーションパスワードを、記事、リポジトリ、ChatGPTのプロンプトへ直接書かないでください。
:::

## 認証情報はGitHub Secretsへ入れる

この構成では、次の値をGitHub ActionsのRepository Secretsへ保存します。

```text
WP_API_URL
WP_MEDIA_API_URL
WP_USER
WP_APP_PASSWORD
WP_TIMEZONE
```

GitHubの公式ドキュメントでは、Repository Secretsはリポジトリの `Settings` → `Secrets and variables` → `Actions` から登録できます。

Workflowからは次のように参照します。

```yaml
env:
  WP_API_URL: ${{ secrets.WP_API_URL }}
  WP_MEDIA_API_URL: ${{ secrets.WP_MEDIA_API_URL }}
  WP_USER: ${{ secrets.WP_USER }}
  WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
  WP_TIMEZONE: ${{ secrets.WP_TIMEZONE }}
```

## Markdown原稿を決まった形式にする

記事本文だけでなく、タイトルやスラッグもFrontmatterへ入れます。

```markdown
---
title: "記事タイトル"
slug: article-slug-jp
status: publish
categories:
  - ChatGPT
tags:
  - GitHub Actions
  - WordPress REST API
featured_image: ../../images/thumbnail.png
---

ここから本文です。
```

ファイル名にも規則を設けると、自動処理が簡単になります。

```text
article-name-jp.md
article-name-en.md
```

ZIDOOKAでは片方のファイルを指定すると、投稿スクリプトが同じディレクトリにある相方を探し、日英2本を続けて投稿します。

## GitHub Actionsで公開する

最小構成なら、手動実行用の `workflow_dispatch` を用意します。

```yaml
name: Publish WordPress Article

on:
  workflow_dispatch:
    inputs:
      draft:
        description: Markdown file path
        required: true
        type: string

jobs:
  publish:
    runs-on: ubuntu-latest
    env:
      WP_API_URL: ${{ secrets.WP_API_URL }}
      WP_USER: ${{ secrets.WP_USER }}
      WP_APP_PASSWORD: ${{ secrets.WP_APP_PASSWORD }}
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 22
      - run: npm ci --ignore-scripts
      - run: node src/index.js post-pair "${{ inputs.draft }}"
```

`workflow_dispatch` を設定したWorkflowは、GitHubのActions画面、GitHub CLI、REST APIから手動実行できます。

ZIDOOKAではさらに、記事ファイルを含むPull Requestがマージされたとき、自動的に変更対象を探して投稿する処理を追加しています。

## ベッドから実際に何をするのか

仕組みが完成した後の日常操作は、とても短くなります。

:::example
「ChatGPTとGitHub ActionsとWordPress REST APIをつないだ入門記事を書いて。さっきベッドから予約投稿機能を追加した実話を冒頭に入れて。日英版とサムネイルを作り、ZIDOOKAへ投稿して」
:::

すると、AI側は次の作業を順に処理できます。

1. リポジトリの執筆ルールを読む
2. 公式情報を確認する
3. 日英Markdownを作る
4. サムネイルを生成する
5. Frontmatterとリンクを検証する
6. GitHubへ変更を反映する
7. Actions経由でWordPressへ公開する

人間がすることは、目的を伝え、重要な差分や公開操作を承認し、結果を確認することです。

## 予約投稿もWordPressに任せる

GitHub Actionsを公開時刻まで待機させる必要はありません。投稿時に未来の日時と `future` ステータスをWordPress REST APIへ送り、実際の公開時刻はWordPressに管理させます。

ZIDOOKAではFrontmatterへ次のように書けます。

```yaml
publish_at: "2026-08-15 09:00"
```

また、Actionsの手動実行では次の3モードを選べます。

- `publish_now`: 即時公開
- `schedule_at`: 指定日時で予約
- `next_available`: 予約が入っていない次の日の9時に予約

WordPressの公式REST API仕様でも、投稿ステータスとして `publish`、`future`、`draft` などが定義されています。

## 安全に運用するための注意

:::warning
AIに強い権限を渡すほど、公開前確認と権限分離が重要になります。「会話できる」ことと「無制限に実行できる」ことを同じにしないでください。
:::

- WordPressでは専用ユーザーと取り消し可能なアプリケーションパスワードを使う
- Secretsをログへ表示しない
- GitHub Actionsの `permissions` を必要最小限にする
- 外部から渡されたファイルパスや日時を検証する
- Pull Requestで記事とコードの差分を確認する
- スラッグをキーにして同じ記事の重複作成を防ぐ
- API障害時は勝手に続行せず、失敗として停止する
- 個人情報を原稿や公開リポジトリへ持ち出さない

## この仕組みの本当の価値

単に「AIがブログを書いた」という話ではありません。

会話で曖昧な目的を伝えられる一方、実際の公開処理はGit、Workflow、スクリプト、REST APIという決定的な仕組みに落とせます。==人間の柔らかい指示と、機械の再現可能な処理を接続できる==ことが重要です。

そして、作業場所も変わります。

ベッド、移動中、喫茶店。手元にスマホしかなくても、重い作業をクラウドへ渡し、差分と結果だけを確認できます。OpenAIのCodex cloud公式ガイドでも、開発端末から離れているときにWebから作業を開始・確認する利用方法が案内されています。

:::conclusion
「パソコンをスマホで遠隔操作する」のではなく、「再現可能な仕事の流れをクラウドへ置き、スマホから目的を伝える」。これがChatGPT、GitHub Actions、REST APIを組み合わせる面白さです。
:::

## 参考資料

- [Plugins：ChatGPTとCodexからGitHubなどの外部ツールへ接続する仕組み](https://learn.chatgpt.com/docs/plugins)
- [Codex cloud：GitHubと接続したクラウド環境で作業する](https://learn.chatgpt.com/docs/cloud)
- [GitHub Actions Workflow syntax](https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax)
- [GitHub ActionsでSecretsを使う](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)
- [Workflowを手動実行する](https://docs.github.com/en/actions/how-tos/manage-workflow-runs/manually-run-a-workflow)
- [WordPress REST API：Posts](https://developer.wordpress.org/rest-api/reference/posts/)
