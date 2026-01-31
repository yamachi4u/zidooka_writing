---
title: "OpenCode webをログイン時に自動起動する方法（Windows）"
date: 2026-01-26 13:00:00
categories:
  - Windows
tags:
  - PowerShell
  - CLI
  - Tooling
  - Automation
status: publish
slug: opencode-web-autostart-windows
---

OpenCodeの`opencode web`をログイン時に自動起動して、毎回同じポートで使えるようにする手順です。GUIが先に起動する場合も、CLIを固定ポートで立ち上げられます。

【結論】スタートアップにショートカットを置く方法が最短で確実です。

## 使うメリット（意外と知られていない）

【ポイント】ブラウザで完結するので、GUIアプリより操作が軽く感じます。

- 普段使っているブラウザで開けるので、通知やタブ管理が楽です。
- ローカルのファイル操作もそのまま行えます。
- アプリのGUIが苦手な人ほど使いやすいです。

ブラウザで使えるのは地味ですが大きなメリットです。既存のタブや拡張機能に統合できるので、作業の流れを崩さずに済みます。ローカルのファイル操作も通常のGUIと同じように進められるため、CLIに慣れていない人でも扱いやすいです。

![opencode webの画面例](../images/opencode-web-browser.png)

## 事前確認

CLI本体の場所を確認します。GUIの`OpenCode.exe`とCLIの`opencode-cli.exe`は別なので、今回はCLIを使います。

```powershell
Test-Path "$env:LOCALAPPDATA\OpenCode\opencode-cli.exe"
```

`True`なら準備OKです。`False`ならOpenCodeの再インストール、またはインストール先の確認が必要です。

## ログイン時に自動起動する（スタートアップ）

1. `Win + R` を押して `shell:startup` を実行します。
2. 開いたフォルダで右クリック → 新規作成 → ショートカット。
3. 「項目の場所」に以下を入力します。ここでポート番号とホストを固定します。

```text
"C:\Users\user\AppData\Local\OpenCode\opencode-cli.exe" web --port 4096 --hostname 127.0.0.1
```

4. 名前は `opencode-web` など任意でOKです。名前は表示用なので自由で大丈夫です。

![ショートカット作成ウィザード](../images/opencode-web-shortcut-wizard.png)

これでログイン時に自動で起動します。別ポートにしたい場合は `--port 4096` を差し替えてください。

【ポイント】`--hostname 127.0.0.1` を付けると、ローカルのみで待ち受けます。

`--hostname 127.0.0.1`は、同じPCからだけアクセスできる設定です。LAN内の別端末からアクセスしたい場合は別の設定が必要ですが、通常のローカル運用ならこのままで問題ありません。

## ブックマークしてすぐ開く

ポートを固定しておくと、ブラウザのお気に入りからすぐ開けます。毎回アプリを立ち上げるよりも手数が少なくなります。

```text
http://127.0.0.1:4096/
```

## うまく起動しないとき

【注意】同じポートを使う別プロセスがいると起動に失敗します。

ポート使用状況を確認します。

```powershell
Get-NetTCPConnection -LocalPort 4096 | Format-Table -AutoSize
```

一覧に出る場合は、別のポートに変更するか、該当プロセスを停止してください。ポート番号を変えたら、ショートカットの`--port`とブックマークのURLも同じ番号に合わせます。

## 自動起動を止めたいとき

`shell:startup` のフォルダから、作成したショートカットを削除するだけで解除できます。
