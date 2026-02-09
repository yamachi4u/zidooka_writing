---
title: "OpenAI Codex AppがWindows/Macで登場へ"
date: 2026-02-03 10:00:00
categories: 
  - AI
tags: 
  - OpenAI
  - Codex
  - AIエージェント
  - 開発ツール
  - Windows
status: publish
slug: openai-codex-app-windows-mac
featured_image: ../images-agent-browser/codex-app-waitlist.png
---

Codex Appはローカル環境で動作するAIコーディングエージェントのデスクトップ版です。

## ウェイトリストが到着

本日、OpenAIからCodex Appのウェイトリスト登録案内が届きました。これは従来のWeb版Codexとは異なる、==デスクトップアプリケーション==として提供される新しい形態です。

![Codex Appウェイトリストの案内](../images-agent-browser/codex-app-waitlist.png)

## Codex Appとは何か

Codex Appは、OpenAIのAIコーディングエージェント「Codex」を==ローカル環境で動作させるデスクトップアプリケーション==です。

### 従来のCodexとの違い

| 機能 | Codex Web（クラウド版） | Codex App（デスクトップ版） |
|------|------------------------|---------------------------|
| ==動作環境== | OpenAIのクラウド | 自分のPC（ローカル） |
| ==コード実行== | クラウド環境で実行 | ローカル環境で直接実行 |
| ==Git連携== | GitHub連携必須 | ローカルGit直接操作 |
| ==Skills== | 一部制限あり | フルサポート |
| ==Worktree== | なし | あり（並列タスク用） |

### 主な特徴

- ==ローカルファースト==: 自分のPC上で完全に動作し、ローカルファイルを直接編集します
- ==内蔵ターミナル==: スレッドごとにターミナルを開き、コマンド実行や開発サーバーの起動が可能
- ==Git統合==: diff確認、インラインコメント、ステージ/リバート、コミットがアプリ内で完結
- ==Skillsシステム==: カスタムワークフローを定義して自動化可能
- ==MCPサポート==: Model Context Protocolによる外部サービス連携

## Skills機能の詳細

Skillsは、Codexに==タスク固有の能力を拡張するシステム==です。

### Skillsの構成

```
my-skill/
├── SKILL.md          # 必須: 指示とメタデータ
├── scripts/          # オプション: 実行可能なコード
├── references/       # オプション: ドキュメント
├── assets/           # オプション: テンプレート、リソース
└── agents/
    └── openai.yaml   # オプション: 外観と依存関係
```

### 利用可能なSkills例

- `$skill-creator`: 新しいSkillを作成
- `$create-plan`: 機能実装の計画を作成（実験的）
- `$skill-installer`: GitHubからSkillをインストール
- Linear連携、Notion連携など

## 対応プラットフォーム

:::note
現状: macOS（Apple Silicon）のみ正式提供
Windows版: ウェイトリスト登録でベータアクセス可能
:::

Windows版はまだ正式リリースされていませんが、ウェイトリストに登録することで==先行してベータ版を試用できる==ようです。

## 利用条件

ChatGPT Plus、Pro、Business、Edu、Enterpriseプランに含まれています。APIキーでのサインインも可能ですが、一部機能（クラウドスレッドなど）が制限される場合があります。

## まとめ

Codex Appは、クラウド版とは異なる==ローカル環境で動作するAIコーディングエージェント==です。Skillsによるカスタマイズや、ローカルGitとの統合など、開発者にとって強力な機能を提供します。Windows版のベータが気になる方は、ぜひウェイトリストに登録してみてください。

---

**参考リンク**: [OpenAI Codex Documentation](https://developers.openai.com/codex)
