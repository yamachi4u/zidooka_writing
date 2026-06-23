---
title: "Codex（ChatGPT）のバンク済みレート制限リセットをCLIで使う — aaamosh/codex-reset"
slug: codex-reset-cli-jp
status: publish
categories:
  - AI
  - ChatGPT
tags:
  - Codex
  - OpenAI
  - CLI
  - レート制限
  - Python
---

2026年6月12日、OpenAIはCodexの「バンク済みレート制限リセット」機能をロールアウトした。ChatGPTの各プラン（Go / Plus / Pro / Business）に1回分の無料リセットクレジットが付与され、紹介プログラムでさらに追加も可能になった。

問題は、このクレジットを使うための「今すぐリセット」ボタンがデスクトップアプリとVS Code / Cursor / Windsurf拡張機能にしか存在しないことだ。Rust CLI（`codex`）にはまだこの機能が実装されておらず、Linux環境では拡張機能のリセットプロンプト自体が表示されないという報告もある。

そこで登場したのが **aaamosh/codex-reset** だ。

:::conclusion
codex-resetは、OpenAI Codexのバンク済みレート制限リセットをコマンドラインから実行するPythonスクリプト。依存関係ゼロ、公式拡張機能が使う未公開エンドポイントを直接叩く。WSLやサーバー、ターミナル環境でCodexを使っている人にはまさに救命器具。
:::

## 何ができるのか

`codex-reset` は以下の操作を提供する:

| コマンド | 動作 |
|---------|------|
| `codex-reset` | 利用可能なクレジットと現在の使用量を表示 |
| `codex-reset consume` | クレジットを1つ消費（確認あり） |
| `codex-reset consume --yes` | 確認なしで即時消費 |
| `codex-reset consume --dry-run` | ドライラン（実際には消費しない） |
| `codex-reset --auth PATH` | 別の認証ファイルを指定 |
| `codex-reset status --json` | 機械可読なJSON出力 |
| `codex-reset invite-status` | 紹介プログラムの診断情報 |

実行イメージはこんな感じ:

```
$ codex-reset
banked credits: 1 available
  ● RateLimitResetCredit_…  status=available  granted=2026-06-12T01:33:14Z  expires=2026-07-12T01:33:14Z
      «One free rate limit reset»

current usage:
  primary  : 1% used, window=5.0h, resets in 5.0h
  secondary: 100% used, window=7.0d, resets in 3.2d

run `codex-reset consume` to redeem one credit now.

$ codex-reset consume
about to redeem:
  credit_id : RateLimitResetCredit_…
  reset_type: codex_rate_limits
proceed? [y/N] y

consumed. windows_reset=1, code=reset, redeemed_at=2026-06-13T13:12:31Z

new usage:
  primary  : 1% used, window=5.0h, resets in 5.0h
  secondary: 0% used, window=7.0d, resets in 7.0d
```

## インストール

Python 3.9+ があれば、たった1行でインストールできる:

```bash
curl -fsSL https://raw.githubusercontent.com/aaamosh/codex-reset/main/codex_reset.py \
  -o ~/.local/bin/codex-reset && chmod +x ~/.local/bin/codex-reset
```

またはリポジトリをクローンして `python3 codex_reset.py` を直接実行する。サードパーティの依存関係は一切ない。

Windowsの場合は、PowerShellでスクリプトを適当なディレクトリに保存して実行すればよい:

```powershell
curl.exe -fsSL -o codex-reset.py https://raw.githubusercontent.com/aaamosh/codex-reset/main/codex_reset.py
python codex-reset.py
```

## 認証の仕組み

`codex-reset` は認証情報を自前で取得しない。すでに `codex login` で作成された `~/.codex/auth.json` から `access_token` と `account_id` を読み取る。

このファイルさえあれば、スクリプトは `https://chatgpt.com/backend-api` 配下のエンドポイントに直接リクエストを送る:

| エンドポイント | メソッド | 用途 |
|--------------|---------|------|
| `/wham/rate-limit-reset-credits` | GET | バンク済みクレジットの一覧 |
| `/wham/rate-limit-reset-credits/consume` | POST | クレジットを消費 |
| `/wham/usage` | GET | 現在のレート制限ウィンドウ |
| `/referrals/invite/eligibility` | GET | 紹介プログラムの資格確認 |

すべてのリクエストには2つのヘッダーが必要:

```
Authorization: Bearer <access_token>
ChatGPT-Account-Id: <account_id>
```

これらのエンドポイントは、公式VS Code拡張機能のwebviewバンドルから抽出されたものだ。

## 紹介プログラムとの連携

codex-resetは紹介クレジットの資格確認もサポートしている。OpenAIの紹介プロモーションは**2026年6月24日**まで有効で、Plus/Proユーザーが新規Codexユーザーを招待するごとにバンク済みリセットが1回追加される。

紹介の資格確認にはブラウザのCookieヘッダーが必要になる場合がある（通常のCodex Bearer認証だけでは403が返るケースが確認されている）:

```bash
codex-reset invite-status --cookie-file ./cookie-header.txt
```

:::warning
資格確認と実際の招待送信は別のエンドポイントだ。`codex-reset` の `invite-status` は読み取り専用で、実際に招待メールを送信することはない。安心して使える。
:::

codex-reset と併せて、紹介ペアリング用の Telegram bot `@codexHuddbot` も運用されている。詳細は <https://github.com/aaamosh/codex-hud> を参照。

## 注意点

:::note
codex-reset は OpenAI の非公開・非サポートのエンドポイントに依存している。OpenAI がこれらのエンドポイントを改名、制限、削除した場合は動作しなくなる。その時は Issue を立てれば作者が再解析するとのこと。
:::

その他、押さえておくべきポイント:

- **バイパスツールではない**: あくまでOpenAIが明示的に付与したクレジットを消費するだけ。`available_count: 0` なら何もできない
- **APIキーユーザーは対象外**: バンク済みリセットはChatGPTサブスクリプションに紐づいており、APIキー利用者はこの機能を持たない
- **消費は不可逆**: POSTが200を返した時点でクレジットは消える。アプリのボタンをクリックした場合と同じ挙動

## ブラウザコンパニオン

CLIだけでなく、ブラウザで動作するコンパニオンHTMLも用意されている:

<https://github.com/aaamosh/codex-reset/raw/main/codex-reset.html>

このHTMLファイルは `connect-src 'none'` のContent Security Policyを持ち、外部通信は一切行わない。コマンドのビルドやインストール手順の確認、紹介先メールアドレスの正規化などを行うためのツールだ。

:::warning
このツールは登場したばかりで、Star 18・Fork 0・リリースはv0.1.0のみと、まだ十分に検証されていない。Pythonのコードは300行程度で中身はまっとうに見えるが、動作は保証しない。自己責任で使ってほしい。
:::

## まとめ

codex-reset は、OpenAIが用意した機能を「UIがないから使えない」という状況を解決する、過不足のないツールだ。

- 良いところ: Python 3.9+ だけで動く、依存関係ゼロ、認証は既存の `auth.json` をそのまま使う、コードが短くて監査しやすい
- 気をつけるところ: 非公開エンドポイント依存、Windowsでは auth.json のパスが異なる可能性あり、消費は不可逆

ターミナルでCodexを使ってレート制限に悩まされているなら、一度試してみる価値はある。READMEにも明記されているが、このスクリプト自体もClaude Opus 4.7がVS Code拡張機能のバンドルをリバースエンジニアリングして1セッションで書き上げたという逸話も面白い。
