---
title: "AI Agentが `clasp push -f` を使いたがる本当の理由：インタラクティブ回避"
categories:
  - GAS
tags:
  - google-apps-script
  - automation
  - ai-coding
  - devops
status: publish
date: 2026-01-13 16:26:00
slug: clasp-push-force-reason-jp
thumbnail: ../images/clasp-developers-guide.png
---

GitHub CopilotなどのAIエージェントにGoogle Apps Script (GAS) のデプロイを指示すると、彼らは頑なに `clasp push --force` や `clasp push -f` を実行したがります。

最初は「なんでそんなに強引に上書きしたがるんだ？確認くらいすればいいのに」と思っていましたが、その合理的な理由に気がつきました。

## 理由は「キーボード入力を避けるため」

結論から言うと、**マニフェストファイル（appsscript.json）の競合確認プロンプトを出させないため** です。

通常の `clasp push` では、ローカルのマニフェストとリモートのマニフェストに不整合がある場合、Claspは安全のために処理を一時停止し、ユーザーに入力を求めます。

```bash
Manifest file has been updated. Do you want to push and overwrite? (y/N)
```

## 非対話モードの維持

人間が手動でターミナルを操作しているなら「y」と押すだけなので問題ありません。しかし、AIエージェントがシェルコマンドを実行している最中や、CI/CDパイプラインの中では、この「入力待ち」が致命的です。ここで処理が止まってしまうと、永遠にデプロイが完了しません。

`-f` オプションをつけることで、この確認プロセスをスキップし、「問答無用でローカルの内容で上書き」させることができます。

つまり、Agentたちが `-f` を好むのは、破壊的な性格だからではなく、**処理が途中で止まってしまうのを防ぐための処世術** だったのです。

## 参考

- [GAS のGoogle製CLIツール clasp - Qiita](https://qiita.com/HeRo/items/4e65dcc82783b2766c03)

