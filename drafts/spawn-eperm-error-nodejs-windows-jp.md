---
title: "spawn EPERMエラーとは？原因と対処法をわかりやすく解説｜Codex CLI・npm・Node.js"
date: 2026-02-13 18:00:00
categories:
  - AI
tags:
  - Codex CLI
  - Node.js
  - npm
  - トラブルシューティング
  - Windows
  - エラー解決
status: publish
slug: spawn-eperm-error-nodejs-windows
featured_image: ../images/2026/spawn-eperm-error-thumbnail.png
---

Codex CLIやnpmを使っていると、突然ターミナルに `spawn EPERM` というエラーが表示されて操作が止まってしまうことがあります。特にWindows環境では頻繁に遭遇するエラーです。

この記事では、`spawn EPERM` エラーの意味・原因・具体的な対処法を、初心者の方にもわかりやすく解説します。

:::conclusion
`spawn EPERM` は「子プロセスの起動が権限不足で拒否された」というエラーです。管理者権限での実行、ウイルス対策ソフトの除外設定、ファイルロックの解除で大半のケースが解決します。
:::

## spawn EPERMとは何か

`spawn EPERM` は Node.js の内部エラーコードです。分解すると次のようになります。

- **spawn**: Node.js が子プロセス（別のプログラム）を起動しようとする操作
- **EPERM**: "Error: Operation not PERMitted"（操作が許可されていない）

つまり、「プログラムを起動しようとしたが、OSの権限により拒否された」ことを示しています。

ターミナルに表示される典型的なエラーメッセージは以下のような形式です。

```
Error: spawn EPERM
    at ChildProcess.spawn (node:internal/child_process:413:11)
    ...
  errno: -4048,
  code: 'EPERM',
  syscall: 'spawn'
```

`errno: -4048` はWindows環境固有のエラー番号です。macOSやLinuxでは `-1` になることがあります。

## spawn EPERMが発生する主な原因

このエラーが起きる原因は大きく5つに分類できます。

### 1. ウイルス対策ソフトがファイルをロックしている

:::warning
Windows Defenderなどのウイルス対策ソフトが、Node.js が起動しようとするファイルをリアルタイムスキャン中にロックしてしまうケースが最も多い原因です。
:::

`npm install` や Codex CLI の実行時に、`node_modules` 内のファイルがスキャン対象となり、Node.js がアクセスしようとしたタイミングでブロックされます。

### 2. 管理者権限が不足している

通常のユーザー権限でターミナルを起動している場合、特定のディレクトリ（`C:\Program Files` 配下など）への書き込みや、グローバルパッケージのインストールに必要な権限が足りないことがあります。

### 3. 他のプロセスがファイルをロックしている

VS Codeや他のエディタ、開発サーバー（`npm run dev` など）がファイルを開いたまま別のnpmコマンドを実行すると、ファイルロックが原因で `EPERM` が発生します。

### 4. npmキャッシュの破損

npmのキャッシュファイルが破損していると、パッケージの展開や配置の際に権限エラーが起きることがあります。

### 5. Codex CLI のサンドボックス制限

Codex CLIには安全のためのサンドボックス機能があり、子プロセスの起動やファイル操作に制限がかかることがあります。GitHub上でも Issue #7810 や #8343 として報告されている既知の問題です。

## 対処法一覧

ここからは、具体的な対処法をステップごとに解説します。上から順番に試してみてください。

### 対処法 1: ターミナルを管理者権限で実行する

:::step
1. Windows のスタートメニューで「PowerShell」または「Windows Terminal」を検索します
2. 右クリックして「管理者として実行」を選択します
3. その状態でコマンドを再実行します
:::

```powershell
# 管理者権限のターミナルで実行
npm install
# または
codex --version
```

### 対処法 2: Windows Defender の除外設定を追加する

:::step
1. 「Windowsセキュリティ」を開きます
2. 「ウイルスと脅威の防止」>「設定の管理」を選択します
3. 「除外」セクションで「除外の追加」をクリックします
4. 以下のパスを除外に追加します
:::

除外すべきパスの例:

```
C:\Users\<ユーザー名>\AppData\Roaming\npm\
C:\Users\<ユーザー名>\AppData\Local\npm-cache\
C:\Users\<ユーザー名>\<プロジェクトフォルダ>\node_modules\
```

また、`node.exe` 自体をプロセスの除外に追加するのも効果的です。

### 対処法 3: ファイルをロックしているプロセスを終了する

:::step
1. VS Codeなどのエディタをいったん閉じます
2. 開発サーバーが動いている場合は `Ctrl + C` で停止します
3. その後にコマンドを再実行します
:::

```powershell
# 開発サーバーの停止
# ターミナルで Ctrl + C を押す

# node プロセスが残っている場合は強制終了
taskkill /f /im node.exe
```

:::warning
`taskkill /f /im node.exe` はすべてのNode.jsプロセスを終了します。他にNode.jsを使った作業をしている場合はご注意ください。
:::

### 対処法 4: npm キャッシュをクリアして再インストールする

```powershell
# npmキャッシュをクリア
npm cache clean --force

# node_modules と package-lock.json を削除して再インストール
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json
npm install
```

### 対処法 5: Codex CLI のサンドボックスを無効化する

Codex CLI で `spawn EPERM` が出る場合は、サンドボックスモードが原因のことがあります。

```powershell
# サンドボックスを無効にして起動
codex --sandbox none

# または設定ファイルで永続的に変更
# ファイルパス: %UserProfile%\.codex\config.toml
```

```toml
# ~/.codex/config.toml
sandbox_mode = "danger-full-access"
```

:::warning
サンドボックスを無効にすると、Codex がファイルシステムやネットワークに制限なくアクセスできるようになります。信頼できるプロジェクトでのみ使用してください。
:::

### 対処法 6: WSL2 を使う（Windows上級者向け）

Windows固有の権限問題を根本的に回避する方法として、WSL2（Windows Subsystem for Linux）上でCodex CLIを実行する方法があります。GitHub Issue #7810 でも、WSL2上では問題なく動作するとの報告があがっています。

```powershell
# WSL2 のインストール（まだの場合）
wsl --install

# WSL2 上で Codex CLI を使用
wsl
npm install -g @openai/codex
codex
```

## Codex CLI で特によく起きるパターン

Codex CLIにおける `spawn EPERM` エラーは、一般的なnpmのケースとは少し異なるパターンがあります。

### パターン1: 起動直後にエラーになる

```
$ codex --version
Error: spawn EPERM
```

Node.js 24 + NVM の組み合わせで報告されています。対処としては Node.js のバージョンを LTS（20.x または 22.x）に変更することが有効です。

```powershell
# NVM を使ったバージョン切り替え
nvm install 22
nvm use 22
```

### パターン2: セッション中にエラーが出る

Codex が内部でコマンドを実行しようとした際に `spawn EPERM` が出るケースです。サンドボックスの制限に引っかかっていることが多いため、`--sandbox none` で回避できます。

### パターン3: IPC パイプの作成で失敗する

サンドボックス内で `tsx` などのツールが IPC パイプを作成しようとして `EPERM` になるパターンです（Issue #8343）。こちらもサンドボックスの緩和が対処法となります。

## エラーを予防するためのベストプラクティス

:::note
以下の習慣を身につけておくと、`spawn EPERM` に遭遇する頻度を大幅に減らすことができます。
:::

1. **プロジェクトは `C:\Users\<ユーザー名>` 配下に配置する** - `Program Files` や `Windows` 直下はOS保護の対象になりやすいため避けましょう
2. **Node.js は LTS バージョンを使用する** - 最新版（Current）ではWindowsとの互換性問題が発生することがあります
3. **開発中はウイルス対策ソフトの除外設定を入れておく** - `node_modules` は大量のファイルを含むため、スキャン対象から外すと安定します
4. **npm コマンドの実行前に開発サーバーを止める** - ファイルロックの競合を防止します
5. **プロジェクトは NTFS フォーマットのドライブに配置する** - exFATやFAT32ではシンボリックリンクが機能しないため、npmで問題が起きることがあります

## まとめ

:::conclusion
`spawn EPERM` エラーは「権限がないため子プロセスを起動できなかった」という意味です。Windows環境では特に頻発しますが、管理者権限での実行、ウイルス対策ソフトの除外設定、npmキャッシュのクリアという3つの対処法でほとんどのケースを解決できます。Codex CLIの場合はサンドボックス設定の緩和も有効です。
:::

## 参考URL / References
1. Node.js child_process Documentation
https://nodejs.org/api/child_process.html
2. GitHub Issue #7810: Codex CLI spawn EPERM on Windows
https://github.com/openai/codex/issues/7810
3. GitHub Issue #8343: spawn EPERM in sandbox (IPC pipe)
https://github.com/openai/codex/issues/8343
4. npm Documentation: Troubleshooting
https://docs.npmjs.com/common-errors
5. Microsoft: Windows Defender Exclusions
https://support.microsoft.com/en-us/windows/add-an-exclusion-to-windows-security
