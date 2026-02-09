---
title: "バイブコーディングの強力な相棒：CLIツールによるコンテキスト共有"
slug: vibe-coding-cli-context-20260202
date: 2026-02-02 09:00:00
categories:
  - journal
tags:
  - バイブコーディング
  - gog
  - CLI
  - AI
  - 開発効率
status: publish
---

バイブコーディング（AIと対話しながらコードを書くスタイル）をしているとき、よく「このエラーメール見てほしい」「このログファイル解析して」という場面がある。

:::conclusion
CLIツール（gogなど）を使うと、スクリーンショットやファイルアップロードなしに、正確なテキスト情報をAIに"ガツンと"渡せる。
:::

![gogcliでGmailを操作](../images-agent-browser/gogcli-home.png)

## 従来の方法の問題点

バイブコーディング中にエラーが発生したとき：

1. **スクリーンショットを撮る** → 画像をアップロード → AIに見せる
2. **ブラウザでGmail開く** → エラーメールを探す → 内容をコピー → AIに貼り付け

これらは手間がかかり、コンテキストが失われがち。

## CLIツールを使った効率的な方法

```bash
# 未読メールを一覧
gog gmail messages list --label UNREAD --max 5

# 特定のメール本文を取得
gog gmail messages get <message-id>

# Driveのログファイルをダウンロード
gog drive download <file-id> --out ./error.log
```

**この方法のメリット：**
- ブラウザを開かずに即座に情報取得
- 正確なテキストデータをコピペで渡せる
- 画像解析より正確にエラー内容を把握してもらえる

## 実際のワークフロー例

```
【エラー発生】
    ↓
gog gmail messages list --query "from:alerts@example.com"
    ↓
（エラーメールのIDを確認）
    ↓
gog gmail messages get <message-id>
    ↓
（本文をコピー）
    ↓
AIに貼り付け：「このエラーメール見て、原因特定して」
    ↓
AIが解析 → コード修正案を提示
```

## 他にも活用できるCLIツール

- **gh**（GitHub CLI）→ IssueやPRの内容を取得
- **aws-cli** → AWSのログや設定を確認
- **kubectl** → KubernetesのPodログを取得

:::example
【ポイント】バイブコーディングの本領発揮は、"正確なコンテキストを素早くAIに渡せる"こと。CLIツールはその架け橋になる。
:::

## まとめ

バイブコーディングを最大限活用するには、**情報取得の手段をCLI化**しておくと圧倒的に効率が上がる。

- ブラウザ操作の手間を省ける
- 正確なテキスト情報をそのままAIに渡せる
- コンテキストロスを最小限に抑えられる

【対処】gog、clasp、ghなどのCLIツールを予めインストールしておくと、バイブコーディングが格段にスムーズになる。
