---
title: "Retrieving Notebook summary… で止まる！待っても直らない不具合の対処法【VS Code Copilot】"
date: 2026-01-07 12:00:00
categories: 
  - Copilot &amp; VS Code Errors
tags: 
  - GitHub Copilot
  - VS Code
  - Jupyter Notebook
  - Error
  - Troubleshooting
status: publish
slug: copilot-notebook-retrieving-summary-jp
featured_image: ../images/202601/image copy 10.png
---

## VS Code + GitHub Copilot で Notebook を使うと「Retrieving Notebook summary…」が終わらない場合の対処法

VS Code 上で GitHub Copilot Chat を使い、Jupyter Notebook（.ipynb）を開いた状態で質問すると、「Retrieving Notebook summary…」と表示されたまま処理が完了しない現象が発生することがあります。この記事では、この不具合の実態と、実務上もっとも安定する回避策を整理します。

---

### 結論から

現時点では、Notebook（.ipynb）と Copilot Chat の組み合わせは安定していません。作業を継続する場合は、Notebook を Python スクリプト（.py）に分割し、Copilot は .py ファイル上で使う運用に切り替えるのが、もっとも確実です。

---

### どのような不具合か

問題の挙動は以下の通りです。

* Copilot Chat を開いた瞬間、または質問送信時に
* 画面左下やチャット欄に

  「Retrieving Notebook summary…」

  と表示される
* 数分〜数十分待っても処理が進まず、事実上フリーズ状態になる

VS Code 自体は操作できるものの、Copilot Chat が一切応答しなくなります。

---

### 発生しやすい条件

調査と実体験から、以下の条件が重なると再現しやすいことがわかっています。

* Notebook のセル数が多い
* matplotlib などで画像出力が多い
* 数式や長い配列の出力が残っている
* 日本語環境で Copilot Chat を使用している
* Copilot の Notebook Context 機能が有効

Copilot は内部で Notebook 全体を要約し、コンテキストとして送信しようとしますが、この要約処理が途中で停止するケースがあります。

---

### 待っても直らない理由

この状態は一時的な遅延ではなく、Copilot 側の要約タスクが内部で詰まっている可能性が高いため、基本的に待っても解消しません。VS Code の再起動や Copilot Chat の再読み込みが必要になります。

---

### 回避策① Notebook Context を無効化する

設定から Notebook Context をオフにすると、症状が改善する場合があります。

VS Code 設定：

* GitHub Copilot
* Enable Notebook Context をオフ

settings.json で設定する場合：

```json
"github.copilot.enableNotebookContext": false
```

ただし、完全には防げないケースもあります。

---

### 回避策② 出力セルをすべて削除する

Notebook 上で以下を実行します。

* Clear All Outputs

画像や大量の数値出力があると、要約処理が失敗しやすくなります。

---

### 回避策③ Notebook を .py に変換して運用する（推奨）

もっとも安定する方法は、Notebook を使わない運用に切り替えることです。

例：

* VS Code の Export as Python Script
* または

```bash
jupyter nbconvert --to script sample.ipynb
```

処理単位で .py ファイルを分割し、Copilot は Python ファイル上で使います。

この方法では、Copilot の応答速度と安定性が大きく改善します。

---

### 実務上の判断

Notebook は可視化や検証には便利ですが、Copilot と組み合わせた長時間の開発には向いていません。現状では、

* 実験・可視化：Notebook
* 実装・整理・Copilot 利用：.py

と役割を分けるのが現実的です。
