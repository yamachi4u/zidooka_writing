---
title: "gog(gogcli)インストール完全ガイド：Homebrew・Windowsビルド・初期設定まで"
slug: gogcli-install-guide-2026
date: 2026-01-31 12:00:00
categories:
  - PC
tags:
  - gog
  - gogcli
  - Google Workspace
  - CLI
  - Gmail
  - Calendar
  - Drive
  - Windows
  - macOS
  - Linux
status: draft
---

Google Workspace の主要サービスを1つのCLIで操作できるのが `gog`（gogcli）です。Gmail/Calendar/Drive/Contacts/Tasks/Sheets/Docs/Slides/People に対応しており、`--json` 出力で自動化にも向きます。

【結論】macOS/LinuxはHomebrewで導入し、WindowsはGoでソースビルドしてPATHに追加すれば使えます。

この記事では、OS別のインストールから、OAuthクライアントの準備、初期認証、最初のコマンドまでを一気通貫でまとめます。

## 1. 先に把握しておくポイント

- gogは「OAuthクライアントJSON（Desktop app）」が必要です
- 認証情報はOSのキーチェーン（WindowsはCredential Manager）に保存されます
- `gog auth manage` と `GOG_ACCOUNT` を使うと複数アカウント運用が楽になります

【注意】OAuthクライアントJSONは機密情報なので、共有フォルダやGit管理に置かないでください。

## 2. インストール（macOS / Linux）

### 2-1. Homebrew（推奨）

Homebrewを使える環境なら、公式のタップから導入できます。

```bash
brew install steipete/tap/gogcli
```

### 2-2. ソースからビルド

Homebrewを使わない場合はソースビルドが確実です。

```bash
git clone https://github.com/steipete/gogcli.git
cd gogcli
make
./bin/gog --help
```

【ポイント】`make` が使えない環境では、Windowsの手順（Go build）でビルドできます。

## 3. インストール（Windows）

Windowsは公式のHomebrewが無いので、Goでビルドするのが最短です。

### 3-1. 依存ツールを入れる

```powershell
winget install -e --id GoLang.Go
winget install -e --id Git.Git
```

### 3-2. ソース取得とビルド

```powershell
git clone https://github.com/steipete/gogcli.git
cd gogcli

go mod download

go build -o .\bin\gog.exe .\cmd\gog
.\bin\gog.exe --help
```

### 3-3. PATHに追加（ユーザー環境）

```powershell
setx PATH "%USERPROFILE%\gogcli\bin;%PATH%"
```

【対処】`gog` が見つからない場合は、ターミナルを再起動してPATH更新を反映します。

## 4. 初期設定（OAuthクライアントの作成）

Google Cloud Consoleで以下を作成します。

1. 新規プロジェクトを作成
2. OAuth同意画面を設定
3. 認証情報 → OAuthクライアントID を作成
4. アプリの種類は **Desktop app** を選択
5. JSONをダウンロード

作成したJSONを指定して登録します。

```bash
gog auth credentials ~/Downloads/client_secret_XXXX.json
```

【注意】JSONファイルは削除せず、保管場所だけ安全なローカルに固定してください。

## 5. アカウント認証

```bash
gog auth add you@gmail.com
```

ブラウザが開けない環境では `--manual` を使います。

```bash
gog auth add you@gmail.com --manual
```

【ポイント】スコープ追加後に更新トークンが返らない場合は `--force-consent` を使います。

```bash
gog auth add you@gmail.com --services sheets --force-consent
```

## 6. 最初のコマンド

```bash
# よく使うアカウントを固定
export GOG_ACCOUNT=you@gmail.com

# Gmail: ラベル一覧
gog gmail labels list

# Calendar: カレンダー一覧
gog calendar calendars --max 5

# Drive: PDFだけ検索
gog drive ls --query "mimeType='application/pdf'" --max 3
```

JSONで扱いたい場合は `--json` を追加します。

```bash
gog gmail search 'newer_than:7d' --max 10 --json
```

## 7. よくあるつまずき

### 7-1. `gog` が見つからない

- Homebrewなら `brew --prefix` のbinがPATHに入っているか確認
- Windowsなら `C:\Users\<user>\gogcli\bin` がPATHに入っているか確認
- 変更後はターミナル再起動

### 7-2. ブラウザが開かない

`--manual` を使い、表示されたURLを手動で開いて認証します。

### 7-3. 期待したサービスの権限が取れない

`--services` と `--force-consent` を併用して再認証します。

## 8. まとめ

gogはインストールさえ通れば、日々のGoogle Workspace操作を一気に自動化できます。まずは `gog auth manage` と `GOG_ACCOUNT` を固定して、定番コマンドから使い始めるのが安全です。

【結論】導入はHomebrewかGoビルドのどちらかでOK、あとはOAuthクライアント登録→認証で完了します。

---

References:
- https://gogcli.sh/
- https://github.com/steipete/gogcli
- https://github.com/steipete/gogcli#quick-start
- https://github.com/steipete/gogcli#installation
