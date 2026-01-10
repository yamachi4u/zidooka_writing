---
title: "xserver vps Permission denied (publickey)｜SSH鍵を登録したのに接続できない原因"
date: 2026-01-04 11:00:00
categories: 
  - Network / Access Errors
tags: 
  - Xserver
  - VPS
  - SSH
  - Troubleshooting
  - Permission denied
status: publish
slug: xserver-vps-ssh-permission-denied-jp
featured_image: ../images/xserver-ssh.png
---

xserver vps で SSH 公開鍵を登録したのに、

```text
Permission denied (publickey)
```

というエラーが出てログインできない。
IP、ユーザー名、ポートも合っているはずなのに接続できない。

このトラブルは、xserver vps で非常によくある初期設定ミスです。
結論から言うと、SSH鍵は登録されていても、OS側で有効になっていないことが原因です。

---

## 結論：xserver vps は「鍵登録＝SSH接続可能」ではない

xserver vps では、

*   管理画面で SSH 公開鍵を登録した
*   それだけで SSH 接続できる

と考えてしまいがちですが、これは **誤り** です。

:::warning
管理画面でのSSH鍵登録だけでは、一般ユーザーでのSSH接続は成立しません。
:::

### なぜ Permission denied (publickey) が出るのか

このエラーが出るとき、SSH自体が壊れているわけではありません。
問題は次のズレです。

*   **管理画面**：SSH鍵は登録されている
*   **サーバー内部**：`authorized_keys` が正しく設定されていない

つまり、**「鍵はあるが、使われていない」** 状態です。

---

## xserver vps で起きやすい勘違い

### 1. 公開鍵を登録しただけで使えると思っている

xserver vps の管理画面で行う SSH鍵登録は、OS内部のユーザー設定を自動で完了させるものではありません。
特に以下のケースでは要注意です。

*   VPS再インストール直後
*   後からユーザーを作成した場合

### 2. 公開鍵と秘密鍵の役割を混同している

```text
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAA...
```

これは **公開鍵** です。

SSH接続に必要なのは、

*   **クライアント側**：秘密鍵
*   **サーバー側**：対応する公開鍵

公開鍵だけが登録されていても、秘密鍵が合っていなければ `Permission denied (publickey)` になります。

### 3. パーミッションが原因なのに誰も見ていない

xserver vps では、以下が1つでもズレると即エラーになります。

*   `.ssh` ディレクトリ：`700`
*   `authorized_keys`：`600`
*   所有者：対象ユーザー

1文字でもズレると SSH は容赦なく拒否します。

---

## 正しい対処方法

### root 権限で OS 側を正しく設定する

管理画面ではなく、サーバー内部で設定する必要があります。

```bash
mkdir -p /home/USER/.ssh
chmod 700 /home/USER/.ssh

echo "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAA..." >> /home/USER/.ssh/authorized_keys

chmod 600 /home/USER/.ssh/authorized_keys
chown -R USER:USER /home/USER/.ssh
```

これで初めて、以下の条件が揃います。

1.  SSH
2.  公開鍵認証
3.  ユーザー接続

### それでも接続できない場合に確認すること

*   使用している秘密鍵は本当に対応しているか
*   鍵ファイルの改行・欠損はないか
*   `sshd_config` で公開鍵認証が無効化されていないか

特に、鍵ペアが一致していないケースは非常に多いです。

---

## 公式仕様としての注意点

エックスサーバー の VPS では、SSH鍵登録後に OS 側での設定が必要であることが公式にも示されています。

> **新規申込み・OS再インストール時の鍵認証設定**
> 新規申込みやOS再インストール時に、サーバーへ鍵認証を設定する手順です。
> この手順で登録する鍵は、rootユーザー用のものです。
>
> ...
>
> **ご利用中のサーバーへの鍵認証設定**
> ご利用中のサーバーのログイン方法を「鍵認証」に変更する手順です。
> ...
> 4. 公開鍵の登録
> サーバーのSSH公開鍵管理ファイルに、コピーした公開鍵を登録します。
>
> 出典: [SSH Key - Xserver VPS マニュアル](https://vps.xserver.ne.jp/support/manual/man_server_ssh.php)

「登録したのに使えない」のではなく、**「登録しただけでは使えない」** という理解が正解です。

---

## まとめ｜xserver vps Permission denied (publickey) の正体

xserver vps で `Permission denied (publickey)` が出る場合、原因の多くは次のどれかです。

:::conclusion
*   管理画面のSSH鍵登録で止まっている
*   OS側の `authorized_keys` が未設定
*   パーミッション不備
*   鍵ペア不一致
:::

SSHトラブルは設定よりも **切り分け** が重要です。
まずは「鍵がOSで本当に使われているか」を確認してください。

---

**画像出典:**
[Xserver VPS マニュアル - SSH Key](https://vps.xserver.ne.jp/support/manual/man_server_ssh.php)
