---
title: OpenAI Codex CLIをデスクトップから一発起動する方法（ショートカット作成）
slug: codex-cli-shortcut-desktop-jp
date: 2026-01-22
category: AI
tags: [Codex, OpenAI, PowerShell, Windows, CLI]
---

**OpenAI Codex** をCLI（コマンドライン）で利用している際、毎回ターミナルを開いてコマンドを入力するのが面倒だと感じていませんか？この記事では、デスクトップのアイコンからCodexを一発で起動するためのショートカット作成方法を紹介します。

安定性と拡張性の高い **PowerShell** を使った方法を推奨します。

> **メモ:** この記事は OpenAI Codex ユーザー向けに特化した内容です。任意のCLIツール（npmやPythonスクリプトなど）全般のショートカット作成方法については、[CLIツールをデスクトップから一発起動するショートカットの作り方](https://www.zidooka.com/archives/3519) をご覧ください。

## 最適解: PowerShellで作るショートカット

この方法のメリットは、実体のスクリプトを `Documents` フォルダの奥など目立たない場所に隠し、デスクトップにはショートカットだけを整然と置ける点です。

### 一括作成スクリプト

以下のコマンドをPowerShellに貼り付けて実行するだけで、セットアップが完了します。このスクリプトは以下の3つを自動で行います。

1.  スクリプト置き場を作成 (`Documents\_tools\codex`)
2.  Codex起動用の `.ps1` ファイルを作成
3.  デスクトップにショートカット (`Codex.lnk`) を作成

以下のコードブロックをコピーして、PowerShellに貼り付けてください。

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
# Codexを起動
codex
'@ | Set-Content -Encoding UTF8 $scriptPath

# デスクトップにショートカット作成
$desktop = [Environment]::GetFolderPath("Desktop")
$shortcutPath = Join-Path $desktop "Codex.lnk"

$wsh = New-Object -ComObject WScript.Shell
$shortcut = $wsh.CreateShortcut($shortcutPath)
$shortcut.TargetPath = "powershell.exe"
# -NoExit: 実行後もウィンドウを閉じない（対話用）
# -ExecutionPolicy Bypass: スクリプト実行制限を回避
$shortcut.Arguments = "-NoExit -ExecutionPolicy Bypass -File `"$scriptPath`""
$shortcut.WorkingDirectory = $HOME
$shortcut.IconLocation = "$env:SystemRoot\System32\WindowsPowerShell\v1.0\powershell.exe"
$shortcut.Save()

Write-Host "完了：デスクトップに Codex ショートカットを作成しました。"
```

### 実行の流れ

1.  **貼り付け:** コードを貼り付けると、一瞬で処理が完了します。警告が出た場合は「強制的に貼り付け」を選択してください。
    ![PowerShell 実行画面](../images/202601/image%20copy%2019.png)
2.  **確認:** デスクトップに **Codex** というショートカットが作成されます。
    ![デスクトップアイコン](../images/202601/image%20copy%2021.png)
3.  **起動:** ダブルクリックするとPowerShellが開き、Codexが即座に起動します。`-NoExit` オプションがついているため、対話モードもそのまま利用できます。

## 代替案: CMD（バッチファイル）

もしシンプルなバッチファイルの方を好む場合は、デスクトップに `RunCodex.bat` というファイルを作成し、以下を記述してください。

```batch
@echo off
cd /d "%USERPROFILE%"
call codex
cmd /k
```

基本的には、現代的なWindows環境に適したPowerShell版の使用をおすすめします。
