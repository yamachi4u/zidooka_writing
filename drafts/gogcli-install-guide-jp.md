---
title: "【超初心者向け】gog(gogcli)をWindows/Macにインストールする完全ガイド｜高校生でもできる"
slug: gogcli-install-guide-2026
date: 2026-01-31 17:35:00
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
status: publish
featured_image: ../images-agent-browser/powershell-search-2026-01-31.png
---

## この記事でわかること

gog（gogcli）というツールを使うと、黒い画面（ターミナル）からGmailやGoogleカレンダー、Driveを操作できます。

:::conclusion
【結論】この記事を読めば、パソコンに詳しくない人でも30分でgogを使い始められます。
:::

![gogcli公式サイト](../images-agent-browser/gogcli-home.png)

## gogって何？何ができるの？

### ざっくり言うと

gogは「Googleのサービスを黒い画面から操作するツール」です。

普段はブラウザ（ChromeとかSafari）でGmailを開いてメールをチェックしますよね？
gogを使うと、黒い画面に文字を打つだけで同じことができます。

### 具体的にできること

| やりたいこと | 普段の方法 | gogを使うと |
|------------|----------|------------|
| 未読メールを見る | Gmailを開いてクリック | `gog gmail labels list` |
| 今日の予定を確認 | カレンダーを開く | `gog calendar agenda --today` |
| Driveのファイルを探す | Driveを開いて検索 | `gog drive ls` |

:::example
【ポイント】ブラウザを開かなくても、コマンド1つで済むので作業が速くなります。
:::

## インストール前に知っておくこと

### ターミナル（黒い画面）って何？

ターミナルは「文字を打ってパソコンを操作する画面」のことです。

- **Windows** → PowerShell（パワーシェル）
- **Mac** → ターミナル

:::note
【ポイント】難しいプログラミングは不要です。コマンドをコピーして貼り付けるだけでOKです。
:::

![PowerShellの画面](../images-agent-browser/powershell-screenshot-2026-01-31.png)

### よく出てくる用語（覚えておくとスムーズ）

| 用語 | 簡単な説明 |
|-----|----------|
| **Go** | gogを「組み立てる」ための工具（Windowsの人だけ使います） |
| **Git** | インターネットからファイルを「ダウンロードする」道具 |
| **Homebrew** | Mac用のアプリストア（コマンドでアプリを入れられます） |
| **PATH** | どのフォルダにいてもコマンドを使えるようにする設定 |
| **OAuth** | Googleに「このアプリ使っていいですか？」と許可を取る仕組み |

## 全体の流れ（4ステップ）

```
【ステップ1】準備（10分）
   ↓
【ステップ2】gogをインストール（10分）
   ↓
【ステップ3】Googleと連携する設定（10分）
   ↓
【ステップ4】実際に使ってみる（5分）
```

【ポイント】初めてでも35分あれば完了します。焦らず1つずつ進めましょう。

---

## 【ステップ1】準備（Windows/Mac共通）

### 1-1. ターミナルを開く

**Windowsの場合：**
1. キーボードの「Windowsキー」を押す
2. 「PowerShell」と入力して検索
3. 「Windows PowerShell」をクリックして開く

![PowerShellの検索](../images-agent-browser/powershell-search-2026-01-31.png)

**Macの場合：**
1. 「Launchpad」（ロケットのアイコン）を開く
2. 「ターミナル」を検索してクリック

:::warning
【注意】以降のコマンドは全部この黒い画面に貼り付けてEnterキーを押すだけです。
:::

### 1-2. 自分のパソコンに何が入っているか確認

**Windowsの人はこれを打ってみてください：**

```powershell
winget --version
```

数字が表示されたらOKです。何も表示されない場合は、Microsoft Storeで「App Installer」を検索してインストールしてください。

**Macの人はこれを打ってみてください：**

```bash
brew --version
```

数字が表示されたらOKです。何も表示されない場合は、後でHomebrewをインストールします。

---

## 【ステップ2】gogをインストールする

公式サイトのInstallセクションでは、Homebrewかソースビルドの2択です。

![gogcliインストールセクション](../images-agent-browser/gogcli-install.png)

### Windowsの場合

Windowsは「部品をダウンロードして、自分で組み立てる」感じです。

#### 2-1. GoとGitをインストール

PowerShellに以下をコピーして貼り付け、Enterを押します：

```powershell
winget install -e --id GoLang.Go
```

完了したら次も貼り付け：

```powershell
winget install -e --id Git.Git
```

:::note
【ポイント】Goは「工具」、Gitは「ダウンロード用の道具」です。1回入れたら次からは使えます。
:::

#### 2-2. パソコンを再起動（またはPowerShellを閉じて開き直す）

インストールした工具を使えるようにするため、PowerShellを一度閉じて、もう一度開き直してください。

#### 2-3. gogをダウンロードして組み立てる

以下のコマンドを順番に貼り付けてEnterを押します：

```powershell
cd ~
```

```powershell
git clone https://github.com/steipete/gogcli.git
```

```powershell
cd gogcli
```

```powershell
go mod download
```

```powershell
go build -o .\bin\gog.exe .\cmd\gog
```

:::step
【ポイント】`go build` は「gog.exe という実行ファイルを作る作業」です。これができると、もうすぐ使えます。
:::

#### 2-4. 動作確認

```powershell
.\bin\gog.exe --help
```

英語の説明がたくさん表示されたら成功です！

#### 2-5. どこからでも使えるように設定（PATH）

```powershell
setx PATH "%USERPROFILE%\gogcli\bin;%PATH%"
```

:::warning
【注意】この設定を反映するには、PowerShellを閉じてもう一度開き直す必要があります。
:::

PowerShellを閉じて、もう一度開き直したら、以下を試してみてください：

```powershell
gog --help
```

表示されたらインストール完了です！

---

### Macの場合

Macは「アプリストアから入れる」感じで簡単です。

#### 2-1. Homebrewをインストール（まだの人だけ）

以下のコマンドをターミナルに貼り付けてEnter：

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

:::note
【ポイント】HomebrewはMacでコマンドからアプリを入れるための「アプリストア」みたいなものです。
:::

#### 2-2. gogをインストール

```bash
brew install steipete/tap/gogcli
```

#### 2-3. 動作確認

```bash
gog --help
```

英語の説明が表示されたら成功です！

---

## 【ステップ3】Googleと連携する設定

これは「Googleアカウントでログインする」ための設定です。

### 3-1. Google Cloud Consoleで「鍵」を作る

公式ドキュメントの案内ページも貼っておきます。画面遷移のイメージが掴みやすくなります。

![Google Workspace OAuthクライアント作成](../images-agent-browser/google-workspace-create-credentials.png)
![Google Cloud OAuth 2.0 公式ページ](../images-agent-browser/google-cloud-oauth.png)

1. ブラウザで https://console.cloud.google.com/ を開く
2. 右上の「プロジェクト選択」→「新しいプロジェクト」をクリック
3. プロジェクト名に「gog-cli」（何でもOK）と入力→「作成」をクリック
4. 左メニューから「APIとサービス」→「OAuth同意画面」をクリック
5. 「外部」を選択→「作成」をクリック
6. アプリ名に「gog」（何でもOK）、メールアドレスを入力→「保存して次へ」
7. 「スコープを追加または削除」をクリック
8. 以下を検索してチェックを入れる：
   - `https://mail.google.com/`（Gmail）
   - `https://www.googleapis.com/auth/calendar`（カレンダー）
   - `https://www.googleapis.com/auth/drive`（Drive）
9. 「更新」→「保存して次へ」→「保存して次へ」→「ダッシュボードに戻る」
10. 左メニューから「認証情報」→「認証情報を作成」→「OAuthクライアントID」
11. アプリケーションの種類で「デスクトップアプリ」を選択
12. 名前に「gog-desktop」（何でもOK）と入力→「作成」
13. 「JSONをダウンロード」をクリック

:::warning
【注意】ダウンロードしたJSONファイルは「鍵」です。絶対に他人に見せないでください。
:::

### 3-2. gogに「鍵」を登録する

ダウンロードしたJSONファイルの場所を確認します。

**Windowsの場合：**

ダウンロードフォルダにあるはずなので、以下のコマンドを打ちます（`XXXX`の部分は実際のファイル名に合わせてください）：

```powershell
gog auth credentials ~/Downloads/client_secret_XXXX.json
```

**Macの場合：**

```bash
gog auth credentials ~/Downloads/client_secret_XXXX.json
```

### 3-3. Googleアカウントを追加する

```bash
gog auth add you@gmail.com
```

:::note
【ポイント】`you@gmail.com`の部分は、実際に使いたいGmailアドレスに変更してください。
:::

ブラウザが開いて「このアプリにアクセスを許可しますか？」と聞かれるので、「許可」をクリックします。

:::warning
【対処】ブラウザが開かない場合は、以下のように `--manual` を付けてください：

```bash
gog auth add you@gmail.com --manual
```

表示されたURLを手動でブラウザに貼り付けて開き、表示されたコードをターミナルに貼り付けます。
:::

---

## 【ステップ4】実際に使ってみる

### 4-1. 使いたいアカウントを指定する

**Windowsの場合：**

```powershell
$env:GOG_ACCOUNT = "you@gmail.com"
```

**Macの場合：**

```bash
export GOG_ACCOUNT=you@gmail.com
```

:::note
【ポイント】`you@gmail.com`は実際のアドレスに変更してください。
:::

### 4-2. Gmailのラベル一覧を表示

```bash
gog gmail labels list
```

ラベル（受信トレイ、送信済み、ゴミ箱など）の一覧が表示されたら成功です！

### 4-3. 他にも試せるコマンド

```bash
# 今日の予定を表示
gog calendar agenda --today

# Driveのファイルを3件だけ表示
gog drive ls --max 3

# 未読メールの件数を確認
gog gmail labels list | grep UNREAD
```

---

## よくある困りごとと解決法

### 「gog」が見つからないと言われる

**Windowsの場合：**
1. PowerShellを閉じて、もう一度開き直す
2. それでもダメなら、以下でPATHを確認：

```powershell
$env:PATH
```

`C:\Users\（ユーザー名）\gogcli\bin` が含まれているか確認してください。

**Macの場合：**

```bash
echo $PATH
```

`/opt/homebrew/bin` または `/usr/local/bin` が含まれているか確認してください。

### ブラウザ認証ができない

`--manual` オプションを使います：

```bash
gog auth add you@gmail.com --manual
```

表示されたURLをコピーしてブラウザで開き、表示されたコードをターミナルに貼り付けます。

### 権限が足りないと言われる

以下のように `--force-consent` を付けて再認証：

```bash
gog auth add you@gmail.com --services gmail,calendar,drive --force-consent
```

---

## まとめ

お疲れさまでした！これでgogが使えるようになりました。

:::conclusion
【結論】
1. WindowsはGoでビルド、MacはHomebrewでインストール
2. Google Cloudで「鍵（JSON）」を作って登録
3. `gog auth add` でアカウント連携
4. `gog gmail labels list` で動作確認

これで黒い画面からGmailやカレンダー、Driveを操作できるようになりました！
:::

慣れてくると、ブラウザを開く手間が省けて作業が速くなります。まずは毎朝のメールチェックや予定確認から試してみてください。

---

## 参考リンク

- gog公式サイト：https://gogcli.sh/
- GitHubリポジトリ：https://github.com/steipete/gogcli
- Google Cloud Console：https://console.cloud.google.com/
