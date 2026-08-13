---
title: "スマホのChatGPTだけでWordPressに画像をアップできた：GitHub Actionsでアイキャッチまで自動化する"
slug: mobile-chatgpt-wordpress-image-pipeline-jp
status: publish
categories:
  - ChatGPT
  - Wordpress
tags:
  - ChatGPT
  - GitHub Actions
  - WordPress REST API
  - 画像アップロード
  - 自動化
---

スマホのChatGPTだけで、WordPressの記事本文だけでなく、写真までアップロードしてアイキャッチ画像に設定できた。

今回やったことは単純で、スマホからChatGPTへKeychron R3の写真を添付し、「最新記事のサムネイルにできないか」と頼んだ。それを起点にGitHubへ画像を渡し、GitHub ActionsからWordPress REST APIへアップロードし、既存記事の `featured_media` に設定するところまで自動化した。

:::conclusion
AndroidのChatGPTに写真を添付するだけで、GitHub → GitHub Actions → WordPress Media API → アイキャッチ設定、という経路を通せることが実際に確認できた。PCは使っていない。
:::

## 何ができたのか

今回成立したパイプラインは次の通り。

1. スマホで写真を撮る
2. ChatGPTへ写真を添付する
3. ChatGPTからGitHubリポジトリへ画像と必要な変更を反映する
4. Pull Requestをマージする
5. GitHub Actionsが画像を検出する
6. WordPress REST APIのMediaエンドポイントへ画像を送る
7. 返ってきたmedia IDを記事の `featured_media` に設定する
8. 別の検証WorkflowがWordPress APIを読み、画像が本当に設定されたか確認する

ポイントは、画像を記事本文へbase64で埋め込んでいるわけではないことだ。画像は通常の画像ファイルとしてWordPressのメディアライブラリへ入り、記事側にはWordPressのmedia IDが設定される。

## 実際の確認結果

Keychron R3の記事で試したところ、日本語記事はpost ID 4657に対して `featured_media=4661`、英語記事はpost ID 4659に対して `featured_media=4662` が設定された。

検証WorkflowではWordPress REST APIから記事をslugで取得し、`featured_media` が0ではないことを確認したうえで、そのmedia IDの実体も取得した。

```text
OK keychron-r3-impressions-jp: post=4657 featured_media=4661 mime=image/jpeg
OK keychron-r3-impressions-en: post=4659 featured_media=4662 mime=image/jpeg
```

つまり「Actionsが成功と表示した」だけではなく、WordPress側に画像が存在し、記事のアイキャッチとして参照されているところまで機械的に確認できた。

## WordPress側では何をしているのか

WordPress REST APIでは、メディアを `/wp/v2/media` へアップロードできる。アップロードに成功すると画像のIDが返るので、そのIDを投稿の `featured_media` に渡す。

概念的には次の2段階になる。

```text
POST /wp/v2/media
  ↓
media ID = 4661
  ↓
POST /wp/v2/posts/4657
{
  "featured_media": 4661
}
```

認証情報はGitHub Secretsへ置き、ChatGPTとの会話や公開リポジトリへ直接書かない。

## なぜ確認用Workflowまで作ったのか

自動投稿では「送信処理がエラーにならなかった」と「公開サイトで期待した状態になっている」は別問題になる。

そこで今回は、投稿後に別のWorkflowからWordPress REST APIを読み直す検証処理も追加した。検証するのは次の3点だ。

- 対象slugの記事が存在するか
- `featured_media` が0以外か
- そのmedia IDが実在する画像として取得できるか

この検証が通れば、少なくともWordPressのデータ上はアイキャッチ画像が正常に設定されていると判断できる。

## ただし最初の画像はガビガビだった

パイプラインそのものは成功したが、最初に流した画像は圧縮しすぎて画質がかなり悪かった。

これはWordPressやREST APIの制約ではなく、アップロード前の画像処理の問題だ。次の改善では、スマホ写真を無闇に小さくせず、長辺1600〜2000px程度を上限にし、JPEG品質85前後を基準として、元画像より大きくしない処理にする予定だ。

さらに、同じ画像を日本語記事と英語記事へ設定するときにWordPressへ2回アップロードしているため、media IDが日英で別々になっている。この重複もなくせる。

:::note
今回の重要な成果は画質ではなく、「スマホのChatGPTに渡したローカル写真を、会話を起点にWordPressのメディアライブラリまで運べる」と確認できたことにある。
:::

## スマホがCMSの操作端末になる

以前、ChatGPTからMarkdown記事をGitHub Actions経由でWordPressへ投稿する仕組みを作った。画像まで同じ経路に乗ると、できることが一段増える。

たとえば外出中にスマホで写真を撮り、その場でChatGPTへ、

> この写真を使って短い記事を書いて、サムネイルに設定して公開して。

と頼む。すると原稿作成、Git管理、画像アップロード、WordPress投稿、アイキャッチ設定、公開後確認までクラウド側へ寄せられる。

これはスマホからWordPress管理画面を頑張って操作する話ではない。スマホは「素材を渡して目的を指示する端末」になり、決定的な処理はGitHub ActionsとWordPress APIへ任せる。

## 次に改善すること

今回の実験で経路そのものは成立した。次は運用品質を上げる。

- 画像のリサイズとJPEG圧縮を高品質化する
- EXIFの扱いと画像回転を安定させる
- 日英記事で同じメディアを再利用して重複アップロードを防ぐ
- ファイルサイズと解像度をログへ出す
- アップロード後に画像URLだけでなく寸法も検証する
- WebPへの変換を必要に応じて選べるようにする

このあたりまで整えば、「スマホで撮る → ChatGPTに投げる → WordPressへ公開」という流れを、特殊なデモではなく普段使いの投稿経路にできる。

:::conclusion
今回わかったのは、ChatGPTを文章生成UIとして使うだけでなく、スマホから写真というバイナリ素材を渡し、その後のGitHub ActionsとREST APIを組み合わせれば、WordPressのメディア管理まで会話から操作できるということだ。ブログ投稿の入口をWordPress管理画面からChatGPTへ移せる。
:::

<!-- paired EN draft added; republish -->
