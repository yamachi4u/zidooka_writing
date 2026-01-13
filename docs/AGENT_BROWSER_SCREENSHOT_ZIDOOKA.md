---
title: "Zidooka! 向け — agent-browserで画面スクショを簡単取得"
---

# Zidooka! 向け: agent-browser によるスクリーンショット取得（簡易ガイド）

【結論】`agent-browser` を使えばAIエージェントから手早くブラウザ操作・スクショ取得ができるので、サイトの確認や自動レポートにめっちゃ便利です。

## 概要

`agent-browser` は Rust バイナリ + Node.js フォールバックで動くブラウザ自動化 CLI です。エージェントから「ページを開く→スナップショット取得→要素操作」までをシンプルなコマンドで実行できます。

Zidooka の運用向けには、以下の2通りを用意しています:

- 公式 CLI（`agent-browser`）を使った方法
- Playwright を使うローカルスクリプト（フォールバック） — リポジトリに同梱済み

## 1) 公式 CLI を使う（推奨）

1. グローバルにインストール、または `npx` で利用します。

```powershell
npm install -g agent-browser
# または（ローカル利用）
npx agent-browser install
```

2. 初回はブラウザをダウンロードします。

```powershell
agent-browser install
```

3. Zidooka のトップページを開いてスクショを保存します（PC向けフルまたはビューポート指定）。

```powershell
agent-browser open https://www.zidooka.com
agent-browser screenshot images-agent-browser/zidooka.png --full
agent-browser close
```

## 2) フォールバック: Playwright スクリプト（リポジトリ付属）

リポジトリ内の `scripts/agent-browser-screenshot.mjs` は、Playwright を使って簡単にスクショを撮るためのスクリプトです。PC向けの初期表示（viewport）を模したデフォルト設定を含みます。

基本コマンド:

```powershell
# 必要なパッケージとブラウザをインストール
npm install playwright
npx playwright install chromium

# スクリプト実行（出力: images-agent-browser/zidooka-pc-viewport.png）
node scripts/agent-browser-screenshot.mjs https://www.zidooka.com/ images-agent-browser/zidooka-pc-viewport.png
```

デフォルトのビューポートは `1920x900`（PC初期表示を想定）です。幅や高さを環境変数 `SCREEN_WIDTH` / `SCREEN_HEIGHT` か、コマンド引数で上書きできます。

例: 幅1366、高さ768で撮る

```powershell
SCREEN_WIDTH=1366 SCREEN_HEIGHT=768 node scripts/agent-browser-screenshot.mjs https://www.zidooka.com/ images-agent-browser/zidooka-1366x768.png
```

（Windows PowerShell では `env:SCREEN_WIDTH=1366; env:SCREEN_HEIGHT=768; node ...` のように設定してください）

## 使いどころ / メリット

- サイト更新チェックやレイアウト確認を自動化できる
- 記事作成やQA時に、画面の変化を自動的に保存して履歴比較が可能
- AI エージェントと組み合わせて「操作→スクショ→解析」のワークフローが容易になる

## 備考

- 既に `PIPELINE_MANUAL.md` にスクショ手順を追記済みです。
- `images-agent-browser/` に生成される画像はリポジトリ内で管理できます（必要に応じて .gitignore を調整してください）。

---

もっとインフォーマルに紹介文を作るか、社内向けの短いREADMEにまとめることもできます。どうしましょう？
