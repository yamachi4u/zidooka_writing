---
title: "何をしてもサムネイルが出ないときの確認手順【WordPress】"
date: 2025-02-10 20:00:00
categories: 
  - WordPress
tags: 
  - WordPress
  - トラブルシューティング
  - アイキャッチ画像
  - 子テーマ
status: publish
slug: wordpress-featured-image-troubleshoot-jp
featured_image: ../images/2025/thumbnails.png
---

WordPressでサムネイル（アイキャッチ画像）が表示されない問題は、とにかく混乱しやすいトラブルです。functions.php を直しても、single.php を見直しても、何も変わらない。結果として「もう意味がわからない」状態になります。

この経験は、多くのWordPress開発者が一度は通る道です。私自身も、まさにこの状況にハマりました。その時の思考プロセスを整理し、今回の記事を通じて、闇雲にコードをいじらずに済む**確認順序**を共有します。

## 【最初の確認】投稿編集画面に「アイキャッチ画像」が存在するか

最初に疑うべきなのは、投稿編集画面の右サイドバーに「アイキャッチ画像」または「Featured Image」の設定欄が存在するかどうかです。

**ここが表示されていない場合、その記事単体の問題ではありません。** この時点で single.php や CSS を疑うのは早すぎます。原因は、テーマ側の設定か、テーマの読み込みそのものにあります。

特に子テーマを使っている場合、親テーマが正しく読み込まれていないと、アイキャッチ画像のUI自体が消えることがあります。**WordPressは親テーマが欠けていてもサイトを表示してしまう**ため、致命的エラーとして気づきにくいのが厄介な点です。

## 【次の確認】テーマ側で post-thumbnails が有効になっているか

次に確認するのは、テーマの functions.php に`add_theme_support('post-thumbnails')`が定義されているかどうかです。

```php
add_theme_support('post-thumbnails');
```

ただし、ここで記述が見つかっても**安心してはいけません。** 親テーマが正常にロードされていない場合、この設定が効いていないように振る舞うことがあります。

また、カスタム投稿タイプで`register_post_type()`を使っている場合は、supports配列に`thumbnail`が含まれているかも確認してください。この設定が抜けていると、その投稿タイプではサムネイルが使えません。

```php
register_post_type('custom_post', array(
    'supports' => array('title', 'editor', 'thumbnail'), // ← 'thumbnail' を忘れずに
    // ... その他の設定
));
```

## 【テンプレート確認】単.php や archive.php で実際に呼び出されているか

ここまで問題がなければ、初めてテンプレート側を確認します。

single.php や archive.php などのテンプレートファイルで、実際に`the_post_thumbnail()`が呼ばれているか確認してください。

```php
<?php
if (has_post_thumbnail()) {
    the_post_thumbnail('medium');
}
?>
```

`has_post_thumbnail()`の条件分岐で引っかかっていないか、そもそも呼び出しそのものが存在するかを確認します。

重要なのは、**この確認順を飛ばさないこと**です。表示の問題に見えて、実は設定や構成が原因というケースが非常に多いからです。

## 【CSSの確認】非表示になっていないか

`the_post_thumbnail()`が呼び出されているのに画像が表示されない場合、CSSで意図的に非表示にされていないか確認します。

特に、重ねられたスタイルシートが多い場合、予期しないCSSルールが画像を隠していることもあります。ブラウザの開発者ツール（F12キー）で実際のDOMを確認し、`display: none` や `visibility: hidden` が適用されていないかチェックしてください。

## 【最後の砦】子テーマの style.css は正しいか

それでも解決しない場合、最後に確認すべきなのが**「子テーマが本当に正常な状態かどうか」**です。特に`style.css`の`Template`指定は要注意です。

```css
/*
Theme Name: My Child Theme
Template: parent-theme-slug
*/
```

**`Template`には親テーマの表示名ではなく、親テーマのフォルダ名を指定する必要があります。** この値が一致していないと、親テーマが存在していてもWordPressはそれを認識できません。

### 実例：私自身がハマった事例

親テーマが`picostrap5`というフォルダ名であるにもかかわらず、子テーマの`style.css`の`Template`指定が異なっていました。

その結果：
- functions.php や single.php をいくら確認しても直らず
- 最終的に「子テーマ自体が壊れている状態」だったと気づいた

子テーマの親テーマ認識が失敗していたため、functions.php の設定が一切反映されていなかったのです。

詳しくは、以下の関連記事も参考にしてください：

- [このテーマは壊れています。親テーマが見つかりません【WordPress／子テーマの落とし穴】](https://www.zidooka.com/archives/2279)
- [WordPress Error: "The Parent Theme Is Missing" — Even Though It Exists](https://www.zidooka.com/archives/2283)

## 【チェックリスト】デバッグを迷子にしないための順序

サムネイルが出てこない問題は、原因を一つずつ潰していかないと、簡単に迷子になります。以下の順序で確認してください：

1. **投稿編集画面にアイキャッチ画像パネルが表示されているか**
2. **functions.php に add_theme_support('post-thumbnails') があるか**
3. **カスタム投稿タイプの場合、supports に thumbnail が含まれているか**
4. **テンプレートファイル（single.php など）で the_post_thumbnail() が呼ばれているか**
5. **CSSで非表示にされていないか**
6. **子テーマの style.css の Template 指定が正しいか**

この順番を守ることで、無駄な遠回りを避けることができます。私と同じようにハマっている方の、デバッグの助けになれば幸いです。
