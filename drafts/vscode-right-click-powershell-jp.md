---
title: "【コマンド一発】PowerShellでVS Codeの右クリックメニューを追加する方法"
date: 2026-01-20 12:00:00
categories: 
  - PC
tags: 
  - VSCode
  - PowerShell
  - Windows
  - レジストリ
status: draft
slug: vscode-right-click-powershell
featured_image: ../images/vscode-right-click-menu.png
---

【結論】PowerShellでコマンドをコピペするだけで、VS Codeの右クリックメニューを追加できます。レジストリエディタの手動操作は不要です。

## この記事でできること

エクスプローラーの右クリックメニューに「Open with VS Code」を追加します。

![右クリックメニューにVS Codeが表示された状態](https://www.zidooka.com/wp-content/uploads/2025/02/image-3.png)

:::note
手動でレジストリを編集する方法は、以下の記事で解説しています。
[Windowsで右クリックメニューからVS Codeを開く方法](https://www.zidooka.com/archives/209)
:::

## 設定コマンド（コピペでOK）

PowerShellを開いて、以下のコマンドをそのまま貼り付けて実行してください。

```powershell
# VS Codeのパスを動的に取得（PATHから検索 → 見つからなければ標準パス）
$codePath = (Get-Command code -ErrorAction SilentlyContinue).Source
if ($codePath) {
    # code.cmdからCode.exeのパスを解決
    $code = (Get-Item $codePath).Directory.Parent.FullName + "\Code.exe"
} else {
    # フォールバック: 標準インストールパス
    $code = "$env:LOCALAPPDATA\Programs\Microsoft VS Code\Code.exe"
}

# 存在確認
if (-not (Test-Path $code)) {
    Write-Host "エラー: VS Codeが見つかりません: $code" -ForegroundColor Red
    return
}
Write-Host "VS Codeのパス: $code" -ForegroundColor Cyan

# ファイルの右クリックメニューに追加
New-Item -Path "HKCU:\Software\Classes\*\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\*\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\*\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%1`""

# フォルダの右クリックメニューに追加
New-Item -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\Directory\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%V`""

# フォルダ背景（何もないところ）の右クリックメニューに追加
New-Item -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Name "(Default)" -Value "Open with VS Code"
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Name "Icon" -Value "`"$code`""
New-Item -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode\command" -Force | Out-Null
Set-ItemProperty -Path "HKCU:\Software\Classes\Directory\Background\shell\VSCode\command" -Name "(Default)" -Value "`"$code`" `"%V`""

Write-Host "設定完了！エクスプローラーを再起動してください。" -ForegroundColor Green
```

【ポイント】実行後、エクスプローラーを再起動（または一度閉じて開き直す）すると反映されます。

## このコマンドのメリット

- **管理者権限が不要** - ユーザー領域（HKCU）に書き込むため
- **インストール先を自動検出** - PATHに登録されていれば自動で見つける
- **ユーザー名の手動入力が不要** - 環境変数で自動取得
- **アイコンも自動設定** - メニューにVS Codeのアイコンが表示される

## 削除したい場合

追加したメニューを削除するには、以下のコマンドを実行します。

```powershell
Remove-Item "HKCU:\Software\Classes\*\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item "HKCU:\Software\Classes\Directory\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item "HKCU:\Software\Classes\Directory\Background\shell\VSCode" -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "削除完了！" -ForegroundColor Green
```

## 仕組みの解説

このコマンドは、Windowsのレジストリにエントリを追加しています。

| レジストリパス | 対象 |
| --- | --- |
| `HKCU:\Software\Classes\*\shell\VSCode` | ファイルを右クリック |
| `HKCU:\Software\Classes\Directory\shell\VSCode` | フォルダを右クリック |
| `HKCU:\Software\Classes\Directory\Background\shell\VSCode` | フォルダ内の空白部分を右クリック |

:::note
`HKCU`（HKEY_CURRENT_USER）はユーザーごとの設定領域です。管理者権限なしで書き込めますが、設定は現在のユーザーにのみ適用されます。
:::

## まとめ

:::conclusion
PowerShellでコマンドを実行するだけで、VS Codeの右クリックメニューを追加できます。レジストリエディタを手動で操作する必要がないため、ミスのリスクも減らせます。
:::

## 関連記事

- [Windowsで右クリックメニューからVS Codeを開く方法](https://www.zidooka.com/archives/209) - 手動設定の詳細解説
