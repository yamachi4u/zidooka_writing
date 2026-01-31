---
title: CLIツールをデスクトップから一発起動するショートカットの作り方（PowerShell / CMD）
date: 2026-01-22
category: AI
tags: [PowerShell, Windows, CLI, 効率化, Command Prompt]
---

`codex` や `npm` などのCLIツールを頻繁に使う場合、毎回ターミナルを開いてコマンドを打つのは少し手間がかかります。

> **Codexユーザーの方へ:** もし OpenAI Codex の起動用ショートカットを専門にお探しの場合は、専用記事 [OpenAI Codex CLIをデスクトップから一発起動する方法](https://www.zidooka.com/archives/3529) をご覧ください。

この記事では、ご愛用のCLIツールをデスクトップのアイコンから一発で起動するためのショートカット作成方法を紹介します。管理しやすい **PowerShell（推奨）** の方法と、手軽な **CMD（バッチファイル）** の方法の2種類です。

## 方法1: PowerShellで作る（推奨）

この方法のメリットは、実体のスクリプトを `Documents` フォルダの奥など目立たない場所に隠し、デスクトップにはショートカットだけを置ける点です。作業ディレクトリの指定や権限設定も柔軟です。

### 一括作成スクリプト

以下のコマンドをPowerShellに貼り付けて実行するだけで、セットアップが完了します。このスクリプトは以下の3つを自動で行います。

1.  スクリプト置き場を作成 (`Documents\_tools\codex`)
2.  起動用の `.ps1` ファイルを作成
3.  デスクトップにショートカット (`Codex.lnk`) を作成

```powershell
# 保存先（邪魔にならない場所）
$baseDir = Join-Path $HOME "Documents\_tools\codex"
$scriptPath = Join-Path $baseDir "run-codex.ps1"

# ディレクトリ作成
New-Item -ItemType Directory -Force -Path $baseDir | Out-Null

# Codex 起動用 ps1 を作成
@'
# 作業ディレクトリをホームに固定（必要に応じて変更可）
Set-Location "$HOME"
# CLIツールを起動
codex
'@ | Set-Content -Encoding UTF8 $scriptPath

# デスクトップにショートカット作成
$desktop = [Environment]::GetFolderPath("Desktop")
$shortcutPath = Join-Path $desktop "Codex.lnk"

$wsh = New-Object -ComObject WScript.Shell
$shortcut = $wsh.CreateShortcut($shortcutPath)
$shortcut.TargetPath = "powershell.exe"
# -NoExit: 実行後もウィンドウを閉じない（ログ確認用）
# -ExecutionPolicy Bypass: スクリプト実行制限を回避
$shortcut.Arguments = "-NoExit -ExecutionPolicy Bypass -File `"$scriptPath`""
$shortcut.WorkingDirectory = $HOME
$shortcut.IconLocation = "$env:SystemRoot\System32\WindowsPowerShell\v1.0\powershell.exe"
$shortcut.Save()

Write-Host "完了：デスクトップに Codex ショートカットを作成しました。"
```

### 実行イメージ

コマンドを貼り付けると、一瞬で処理が完了します。

![PowerShell 実行画面](../images/202601/image%20copy%2019.png)

**注意:** 複数行のコードを貼り付けた際、以下のような警告ダイアログが出ることがありますが、「強制的に貼り付け」を選択して問題ありません。

![貼り付け時の警告](../images/202601/image%20copy%2020.png)

### 完成

デスクトップに新しいショートカットが作成されます。

![デスクトップアイコン](../images/202601/image%20copy%2021.png)

このアイコンをダブルクリックすると PowerShell が開き、指定したディレクトリでツールが起動します。`-NoExit` オプションがついているため、ツールの実行が終わった後もウィンドウは閉じず、出力結果を確認したり続けて作業したりできます。

---

## 方法2: CMD（バッチファイル）で作る

「もっと枯れた技術でいい」「シンプルに作りたい」という場合は、`.bat` ファイルを使う方法もあります。

1.  デスクトップなどで右クリックし、新規テキストファイルを作成して名前を `RunCodex.bat` に変更します。
2.  ファイルを右クリックして「編集」を選び、以下を貼り付けます。

```batch
@echo off
:: 作業ディレクトリへ移動
cd /d "%USERPROFILE%"

:: コマンドを実行
call codex

:: ウィンドウを開いたままにする
cmd /k
```

3.  保存して閉じます。

これをダブルクリックすれば、コマンドプロンプト上でツールが起動します。手軽ですが、日本語文字化けや環境変数の扱いなどが少し面倒になることがあるため、本格的に使うならPowerShell版がおすすめです。
