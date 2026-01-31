---
title: "OpenCodeでClaude Opus 4.5を動かしてみた — GitHub Copilot連携で「AI課金の恐怖」を回避する"
slug: "opencode-opus-45-review-jp"
status: "publish"
categories: 
  - "AI"
  - "便利ツール"
tags: 
  - "OpenCode"
  - "Claude"
  - "Opus 4.5"
  - "GitHub Copilot"
  - "AIエージェント"
featured_image: "../images/image copy 8.png"
---

# OpenCodeでClaude Opus 4.5を動かしてみた — GitHub Copilot連携で「AI課金の恐怖」を回避する

![Claude Opus 4.5で疎通確認できた画面](../images/image%20copy%208.png)

【結論】Claude Codeのサブスクリプション契約に躊躇しているなら、**OpenCodeとGitHub Copilotの連携**が合理的で「コスパの良い」選択肢になるかもしれない。

## なぜこの構成なのか？（AI課金へのスタンス）

最近、「Claude Code」などが話題だが、正直なところ**固定のサブスクリプション契約（特に高額なもの）はちょっと怖い**と感じていた。

仕事を受注してから作業に入るフローの場合、仕事がない月も課金され続けるのは精神衛生上よくない。
理想は、「**受注した仕事の〇％をAI経費として割り当てる**」という考え方だ。

そう考えると、従量課金や既存のGitHub Copilotライセンスを活用できる**OpenCode**でClaude Opus 4.5を動かすのが、現時点ではかなり合理的だと思った。

## インストールはnpm一発

インストールは公式サイトからインストーラーを落とすのもありだが、普段コードを書いているWindowsユーザーならnpm経由が早い。

[OpenCode Download](https://opencode.ai/download)

```powershell
npm i -g opencode-ai
```

インストールには2〜3分ほどかかったが、最近はAIの応答待ちに慣らされているので、待つこと自体は苦ではない（笑）。

## 意外なGUIと「無料モデル」の衝撃

コマンドラインツール（CUI）だと思って起動したら、しっかりしたGUIの初期化が始まった。

![OpenCodeの初期化画面](../images/image%20copy%204.png)

そして驚いたのが、**GLM-4.7などのモデルがフリーで使える**こと。
GUI版だとこれがデフォルトで選べるようだ。これは地味にすごい。

![GLM-4.7などがFreeで表示されている](../images/image%20copy%205.png)

## GitHub Copilotと連携してOpusを召喚

今回の目的はClaude Opus 4.5だ。
「Connect Provider」から**GitHub Copilot**を選択する。

![Connect Provider画面](../images/image%20copy%206.png)

そのままブラウザ経由でAuthorize（認証）を行う。非常にスムーズだ。

![GitHubの認証画面](../images/image%20copy%207.png)

## Claude Opus 4.5 疎通確認！

設定後、モデル選択からClaude Opus 4.5を選んでチャットしてみる。
無事に疎通確認が完了した。

![Opus 4.5からの応答を確認](../images/image%20copy%208.png)

## まとめ

**「AI課金は怖いけど、最新モデルは使いたい」**

そんなワガママな要望に対して、OpenCodeは一つの解になりそうだ。
特にGitHub Copilotを既に契約している開発者にとっては、追加コストなし（またはCopilotの枠内）でOpusクラスのモデルを試せる環境として、非常に優秀なツールだと感じた。

---
**検証環境**: Windows 11 / OpenCode (2026-01-15時点)
