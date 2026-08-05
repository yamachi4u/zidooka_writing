---
title: "WindowsのスクリーンショットをAIエージェントに渡すなら、ファイルパスで貼り付ける方法"
categories:
  - AI
tags:
  - Windows
  - ShareX
  - AIツール
  - 便利ツール
  - バイブコーディング
  - ワークフロー
status: publish
slug: windows-screenshot-path-ai-agent
---

AIコーディングツールでスクリーンショットを見せたい場面はよくあります。ところがWindowsでは、スクリーンショットを撮って貼り付けると**画像データ**として埋め込まれてしまい、CodexやOpenCodeのようなCLIベースのAIエージェントからファイルにアクセスできないという問題があります。

## 何が問題か

Windowsのスクリーンショット（Win+Shift+SやPrintScreen）は、クリップボードに画像データ（ビットマップ）としてコピーされます。このままAIツールに貼り付けると、チャットUI上では画像として表示されるので人間からは見えます。しかし、CLIエージェントは画像データをファイルとして読み込めません。

エージェントにスクリーンショットを読ませるには、画像が保存された**ファイルのパス**（例: `C:\Users\...\screenshot.png`）をテキストとして渡す必要があります。パスが渡れば、エージェントはそのファイルを開いて内容を解釈できます。

理想的な流れ：

1. スクリーンショットを撮る
2. 自動的にファイルとして保存される
3. クリップボードにはファイルのパスがコピーされる（画像データはコピーしない）
4. AIツールに貼り付けると、パスがテキストとして入力される

## 解決策：ShareX を使う

最も簡単な方法は、**ShareX** というオープンソースのスクリーンショットツールを使うことです。ShareXには「ファイルパスをクリップボードにコピー」が標準で備わっています。

### セットアップ手順

1. [getsharex.com](https://getsharex.com) からダウンロードしてインストール
2. タスクバーのShareXアイコンを右クリック → **「タスク設定」**（歯車アイコン）
3. **「キャプチャ後」** タブで以下のチェックを入れる：
   - ☑ **「画像をファイルに保存する」**
   - ☑ **「ファイルパスをクリップボードにコピー」** ← これがキモ
   - ☐ **「画像をクリップボードにコピー」** （外す）
4. **「キーボードショートカット」** からホットキーを割り当てる（例: PrintScreen → 「矩形領域キャプチャ」）

:::note
「画像をファイルに保存する」と「ファイルパスをクリップボードにコピー」の2つがセットになっていることが重要です。「画像をクリップボードにコピー」は外しておかないと、画像データとパスの両方がコピーされてツールによっては画像データが優先されることがあります。
:::

### 使い方

ホットキーを押して範囲選択するだけです。以下の処理が自動で行われます：

1. 指定したフォルダにPNGファイルが保存される
2. クリップボードにファイルパス（例: `C:\Users\You\Documents\ShareX\Screenshots\2026-07-11_14-30-00.png`）がコピーされる

あとはAIツールにCtrl+Vで貼り付けるだけ。CodexやOpenCodeのCLIであれば、エージェントがパスを認識してファイルを読みに行きます。

### ファイル名のカスタマイズ

「タスク設定」→「保存」タブでファイル名のパターンを変更できます。

- `%yyyy-%mm-%dd_%hh-%mm-%ss.png` → `2026-07-11_14-30-00.png`
- `screenshot_%yy%mm%dd_%hh%mm%ss.png` → `screenshot_260711_143000.png`

## 別解：PowerShell + AutoHotkey

ShareXをインストールしたくない場合や、完全に制御したい場合は、PowerShell単体のスクリプトを使う方法もあります。

まず、以下のPowerShellスクリプトを `save-clipboard-image.ps1` として保存します：

```powershell
Add-Type -AssemblyName System.Windows.Forms, System.Drawing
$img = [System.Windows.Forms.Clipboard]::GetImage()
if ($img) {
    $dir = "$env:USERPROFILE\Desktop\Screenshots"
    if (!(Test-Path $dir)) { New-Item -ItemType Directory -Path $dir -Force | Out-Null }
    $path = Join-Path $dir "screenshot_$(Get-Date -Format 'yyyy-MM-dd_HH-mm-ss').png"
    $img.Save($path, [System.Drawing.Imaging.ImageFormat]::Png)
    $img.Dispose()
    Set-Clipboard $path
}
```

このスクリプトをホットキーから呼び出すには、AutoHotkey v2を使うと便利です：

```autohotkey
^+s::RunWait("powershell -NoProfile -STA -File C:\path\to\save-clipboard-image.ps1")
```

Ctrl+Shift+Sを押すと、クリップボード内の画像がデスクトップの `Screenshots` フォルダに保存され、そのパスがクリップボードにセットされます。

:::note
このスクリプトで使っている `[System.Windows.Forms.Clipboard]::GetImage()` は、PowerShellを **STAモード**（`-STA` フラグ）で実行する必要があります。PowerShell 7以降はデフォルトがMTAのため、必ず `-STA` を付けて実行してください。
:::

ただし、ShareXのほうがセットアップは格段に楽で、動作も安定しています。

## 補足：PowerToys Advanced Paste ではできないの？

Microsoft PowerToysの「Advanced Paste」（Win+Shift+V）には「ファイルとして貼り付け」がありますが、これはファイルオブジェクトとして貼り付ける機能であり、AIエージェントが必要とするテキスト形式のパスにはなりません。目的には合いません。

## まとめ

| 方法 | 設定の手軽さ | 安定性 |
|------|-------------|--------|
| **ShareX**（推奨） | ★★★ 簡単 | ★★★ 安定 |
| PowerShell + AutoHotkey | ★★ 中級 | ★★ 安定 |
| PowerToys Advanced Paste | — 非対応 | — |
| Windows標準 Snipping Tool | — 非対応 | — |

ShareXを入れて「ファイルパスをクリップボードにコピー」にチェックを入れるだけ。シンプルで確実な方法なので、ぜひ試してみてください。
