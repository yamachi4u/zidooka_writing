---
title: "【Lolipop】WAFを無効にしてもWordPress REST APIで403エラーが出る件"
slug: "lolipop-waf-403-rest-api"
status: "future"
date: "2025-12-16T09:00:00"
categories: 
  - "WordPress"
  - "Server"
tags: 
  - "Lolipop"
  - "WAF"
  - "REST API"
  - "403 Forbidden"
featured_image: "http://www.zidooka.com/wp-content/uploads/2025/12/403-error-1.png"
---

# WAFを切ったのに403エラーが消えない？

こんにちは、ZIDOOKAです。
自作のCLIツールからWordPressに画像をアップロードしようとしたところ、**403 Forbidden** エラーに悩まされました。

「あー、はいはいWAFね」と思い、レンタルサーバー（ロリポップ！）の管理画面からWAF設定を無効にしました。

![Lolipop WAF Settings](http://www.zidooka.com/wp-content/uploads/2025/12/403-error-1.png)

しかし、**WAFを無効にしても、.htaccessで許可しても、なぜか403エラーが消えません。**

## ログを見てもエラーがない

さらに不可解なのが、WAFの検知ログを見ても**エラーが記録されていない**ことです。

![No Error Log](http://www.zidooka.com/wp-content/uploads/2025/12/403-error-2-5.png)

通常、WAFでブロックされた場合はここに検知ログが残るはずですが、今回は「検知されていないのにブロックされている」という奇妙な状態です。

## 原因の推測

現在調査中ですが、以下の可能性を疑っています。

1.  **反映のタイムラグ**: ロリポップのWAF設定変更は反映に時間がかかることがあります（数分〜1時間）。
2.  **別のセキュリティ機能**: WAF以外にも、海外IP制限や、WordPress固有のセキュリティプラグイン（SiteGuardなど）が干渉している可能性があります。
3.  **REST API制限**: `wp-json/wp/v2/media` エンドポイントに対して、サーバー側で特別な制限がかかっているかもしれません。

## 現時点での回避策

今のところ、画像アップロードだけは管理画面から手動で行うしかありません。
記事のテキスト更新（`wp/v2/posts`）は問題なく通るため、画像データ（バイナリ）の送信だけが引っかかっているようです。

もし同じ現象に遭遇した方がいれば、WAF以外の設定も疑ってみてください。解決策が見つかり次第、追記します。
