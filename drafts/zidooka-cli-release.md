---
title: "【完全自動化】VS CodeからWordPressへ爆速投稿する「ZIDOOKA CLI」を作ってみた"
slug: "zidooka-cli-release"
status: "draft"
categories: 
  - "Tech"
  - "WordPress"
tags: 
  - "VS Code"
  - "Node.js"
  - "Automation"
featured_image: "../images/image.png"
---

# VS Codeから出たくない！

こんにちは、ZIDOOKA! です。
普段記事を書くとき、**「VS CodeでMarkdownを書いて、ブラウザを開いてWordPressにコピペして、画像をアップロードして…」** という作業が面倒で仕方ありませんでした。

そこで、**「VS Codeからコマンド一発でWordPressに投稿できるツール」** を自作しました！
その名も **ZIDOOKA CLI** です。

## 何ができるの？

このツールを使うと、以下のフローで記事投稿が完了します。

1. VS CodeでMarkdownを書く
2. 画像はローカルのパスを指定するだけ（`![img](../images/pic.png)`）
3. ターミナルで `node src/index.js post drafts/article.md` を叩く
4. **完了！**

これだけで、**画像のアップロード**、**リンクの置換**、**HTML変換**、**カテゴリ設定**、さらには**アイキャッチ画像の設定**まで全自動でやってくれます。

## 技術的な裏側

- **言語**: Node.js
- **API**: WordPress REST API
- **認証**: Application Passwords

特に苦労したのが認証周りです。Xserverなどの環境では、セキュリティ対策で `Authorization` ヘッダーが削除されてしまうため、`.htaccess` の修正が必要でした。

```apache
# REST API Authorization Header Fix
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]
</IfModule>
```

これを乗り越えて、無事に投稿できたときの感動といったら！

## まとめ

これで執筆効率が爆上がり間違いなしです。
この記事自体も、もちろん **ZIDOOKA CLI** を使って投稿しています。

もし興味がある方がいれば、コードを公開するかもしれません。
それでは、快適な執筆ライフを！
