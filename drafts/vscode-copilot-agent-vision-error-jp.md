---
title: "【VS Code】Copilot Agentで「vision_attachment_not_accessible」エラーが出ても諦めないで！"
slug: vscode-copilot-agent-vision-error-jp
date: 2026-01-14 11:30:00
categories: 
  - Copilot &amp; VS Code Errors
  - AI
  - エラーについて
tags: 
  - VS Code
  - GitHub Copilot
  - AI
  - Claude 4.5 Opus
  - Error
status: publish
featured_image: ../images/copilot-vision-error.png
---

VS Code で GitHub Copilot Agent（特に Claude 3.5 Sonnet や 4.5 Opus モデル）を使用していて、画像を添付してプロンプトを送った際、突然こんな赤文字のエラーが出ることがあります。

```json
Sorry, your request failed. Please try again.

Copilot Request id: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
GH Request Id: xxxx:xxxx:xxxx:xxxx:xxxx:xxxx:xxxx:xxxx

Reason: Request Failed: 400 {"error":{"message":"one or more attachments was not accessible","code":"vision_attachment_not_accessible"}}
```

「**vision_attachment_not_accessible**（画像添付ファイルにアクセスできません）」

これを見ると「画像サイズが大きすぎた？」「フォーマットが非対応？」と不安になりますが、**実はこれ、ただの一時的な通信エラーであることが多いです。**

## 【結論】「Try again」を押せば直る（ことが多い）

このエラーに遭遇した時の対処法はシンプルです。

**「Retry（再試行）」ボタンを押すだけ。**

私自身、Claude 4.5 Opus 利用時に画像を添付してこのエラーが出ましたが、**そのままリトライしたら何事もなく成功しました。**

### なぜ起きるのか？

エラーコード `vision_attachment_not_accessible` は、Copilot のサーバー（またはモデルのプロバイダー）が画像を読み込もうとした瞬間に失敗したことを示しています。

- 画像のアップロード処理（Blob化）がタイムアウトした
- バックエンドでの画像展開が一瞬コケた
- 通信が一瞬不安定だった

こういった「一過性の失敗（Glitch）」である可能性が非常に高いです。
画像ファイル自体が壊れているわけではないので、**設定を変えたり画像を圧縮し直したりする前に、まずはリトライを試してください。**

## まとめ

- **症状**: Copilot Agent で画像添付時に `400 vision_attachment_not_accessible` が出る。
- **対処**: 焦らず「Try again」をクリック。
- **原因**: たいていは一時的なアップロード/展開エラー。

AI系の機能はまだ発展途上で、APIのエラーも頻発します。「エラー＝自分のミス」と思わずに、とりあえず何度か叩いてみるのが現代のAIハック術ですね。
