---
title: "スマホ版ChatGPTだけでWordPressに画像付き記事を投稿できた――GitHub Actions経由の画像パイプライン"
slug: mobile-chatgpt-wordpress-image-pipeline-jp
status: publish
---

# スマホ版ChatGPTだけでWordPressに画像付き記事を投稿できた

スマホで撮った写真をChatGPTに添付して、そのままWordPressの記事のアイキャッチ画像にできないか試した。

結論から言えば、できた。

今回通った経路は次のようなものだ。

`Android版ChatGPT → GitHub → GitHub Actions → WordPress REST API → Media Library → featured_media`

PCを開かず、スマホのChatGPTから写真を渡し、GitHub側のパイプラインを経由してWordPressのメディアライブラリへ画像をアップロードし、既存記事のアイキャッチとして設定できた。

## 何をしたのか

もともとZIDOOKAでは、Markdownの記事をGitHubへ置き、GitHub ActionsからWordPress REST APIへ投稿する仕組みを使っていた。

そこで記事本文だけではなく画像も同じ経路に乗せた。

画像ファイルをGitHubの所定ディレクトリへ追加するとActionsが起動し、WordPressのMedia APIへ画像をPOSTする。返ってきたmedia IDを記事の`featured_media`に設定する。

今回はKeychron R3の記事で実験した。画像のアップロード後、さらに確認用のGitHub Actionsも追加した。

確認処理ではWordPress REST APIから記事をslugで取得し、`featured_media`が0ではないことを確認する。さらに、そのmedia IDの実体を取得し、MIME typeと画像URLまで確認する。

実際の検証結果は、日本語記事がpost ID 4657 / featured_media 4661、英語記事がpost ID 4659 / featured_media 4662。どちらも`image/jpeg`としてWordPress側に存在し、検証Workflowはsuccessになった。

## base64を記事に埋め込む必要はない

最初に気になったのは画像の扱いだった。記事本文へbase64で巨大な画像データを埋め込むような方式は避けたい。

今回の方式ではそうしていない。画像は通常の画像ファイルとして扱い、GitHubからActionsへ渡し、WordPressのMedia APIへバイナリとしてアップロードする。GitHub API内部で転送用にbase64表現が使われる場面があっても、公開記事にbase64画像を埋め込む設計ではない。

WordPress側では通常のメディアファイルとして保存される。

## これで何ができるようになったか

かなり単純化すると、今後はこういう運用ができる。

1. スマホで写真を撮る
2. ChatGPTに写真を添付する
3. 「この写真を使って記事を書いて、アップして」と頼む
4. ChatGPT側からGitHubへ記事と画像を送る
5. GitHub ActionsがWordPressへ投稿する
6. 別の検証Actionがアイキャッチ画像まで確認する

つまり、スマホのChatGPTをWordPressの投稿クライアントのように使える。

WordPress管理画面をスマホで開き、画像を選び、メディアライブラリへアップロードし、アイキャッチを設定し、本文を貼り付ける、といった作業をかなり省略できる。

## 今回わかった問題：画像がガビガビになった

パイプライン自体は成功したが、最初のテスト画像はかなり画質が落ちた。

これはWordPress REST APIやGitHub Actionsの限界というより、アップロード前の画像生成・圧縮条件の問題と考えられる。

次の改善では、元画像の縦横比をなるべく維持しつつ、アイキャッチ向けに長辺1600〜2000px程度を確保し、JPEG品質85前後を基準にする。また、すでに十分小さい画像は再圧縮しない、という条件も入れたい。

さらにWordPress側で生成されるサムネイルサイズとテーマ側が実際に要求する表示サイズも確認し、二重の縮小や低解像度画像の引き伸ばしを避ける必要がある。

## 面白いのは「スマホだけで完結した」こと

今回のポイントは単にWordPress REST APIで画像をアップロードできたことではない。それ自体は以前から可能だ。

面白いのは、ユーザー側の操作がスマホ版ChatGPTへの画像添付と自然言語の指示だけで済んだことだと思う。

ChatGPTがGitHubを操作し、GitHub Actionsが認証情報を持った実行環境としてWordPressと通信する。この分業にすると、WordPressの認証情報を毎回ChatGPTへ渡す必要もない。

`ChatGPT = 操作インターフェース`

`GitHub = 記事・画像・変更履歴の保管場所`

`GitHub Actions = 実行環境`

`WordPress = 公開先`

という構成になる。

まだ画像品質やファイル命名、既存メディアの重複防止など改善点はあるが、「スマホのChatGPTだけからWordPressへ画像付き記事を公開できる」というところまでは実証できた。

これは思っていたより使える。
