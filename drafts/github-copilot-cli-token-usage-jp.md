---
title: GitHub Copilot CLIで「sorry, you have exceeded your copilot token usage」と出たときの原因と対処法
date: 2025-12-18T09:00:00
categories: 
  - AI
  - Copilot &amp; VS Code Errors
  - エラーについて
tags: 
  - GitHub Copilot
  - CLI
  - VS Code
slug: github-copilot-cli-token-usage-jp
---

GitHub Copilot CLI や VS Code 上で作業していると、突然次のようなエラーに遭遇することがあります。

```
sorry, you have exceeded your copilot token usage.
please review our terms of service.
```

有料プランを使っているにもかかわらずこの表示が出ると、「え、もう使えないの？」「課金足りてない？」と戸惑いますよね。この記事では、このエラーの正体と実務的な対処法を整理します。

## 結論：これは「契約切れ」ではなく、一時的な利用制限

まず結論から言うと、このメッセージは**アカウント停止や契約失効ではありません**。

多くの場合、**GitHub Copilot 側の一時的なトークン使用量（レート）制限に引っかかっているだけ**です。

実際、GitHub Copilot CLI の Issue や Community Discussion でも、有料ユーザーが普通に作業していて突然出るケースが多数報告されています。

## なぜトークン制限に引っかかるのか

Copilot は「月額で使い放題」というイメージを持たれがちですが、実際には以下のような制限が存在します。

### 1. 短時間で大量のトークンを消費した

Copilot CLI や Agent モードでは、以下のような使い方をすると短時間で大量のトークンを消費します。

*   大きなファイルを丸ごと投げる
*   長いコンテキストを何度も送る
*   連続でコマンド補完・生成を走らせる

この「瞬間的な使用量」が一定値を超えると、一時ブロックされます。

### 2. 月次上限とは別の「リアルタイム制限」がある

UI 上で見える使用量（Usage bar）が余っていても、バックエンドの内部カウンタやモデル別の上限、CLI / Agent 用の独立枠によって、見た目と実際の制限がズレることがあります。そのため「まだ余ってるはずなのに弾かれた」という現象が起きます。

### 3. Copilot CLIは特に制限に当たりやすい

VS Code の通常の補完よりも、Copilot CLI は「1リクエストあたりの情報量が多い」「自動で何度も問い合わせる」という特性があり、制限に到達しやすい傾向があります。

GitHub Copilot CLI Issue #793 でも、同様の報告が継続的に出ています。

```json
Model call failed: {"message":"Sorry, you have exceeded your Copilot token usage. Please review our Terms of Service.","code":"rate_limited"}
```

## すぐできる対処法

### ① 少し時間を置く（最重要）

この制限はほぼ確実に一時的です。

*   10分〜数十分
*   長くても数時間

待つだけで復活するケースが大半です。焦らず休憩しましょう。

### ② エディタやCLIを再起動する

内部セッションがリセットされることで、再びトークンが通るようになったり、エラーが消えたりすることがあります。「ダメ元で再起動」はわりと有効です。

### ③ 一度に投げるコンテキストを減らす

以下を意識すると、再発を防ぎやすくなります。

*   巨大ファイルをそのまま渡さない
*   差分や必要箇所だけ貼る
*   Agent に丸投げしすぎない

特に CLI 利用時は、プロンプトを小さく刻むのがコツです。

### ④ モードを切り替える

Copilot のモードを切り替えることで通ることもあります。

*   Agent → Ask
*   CLI → エディタ補完

## まとめ

*   このエラーは契約エラーではない
*   原因は一時的なトークン・レート制限
*   Copilot CLI / Agent 利用時に特に起きやすい
*   待つ・再起動・プロンプト軽量化が有効

Copilot をガンガン使うほど遭遇しやすいエラーなので、「来たら落ち着いて休憩」くらいの感覚でOKです。

:::note
Gemini 3 Proで出やすいエラーかもしれません。
:::

---

:::note
**English Version:**
[GitHub Copilot CLI Error: 'Sorry, you have exceeded your copilot token usage' — Causes and Fixes](https://www.zidooka.com/?p=2546)
:::
