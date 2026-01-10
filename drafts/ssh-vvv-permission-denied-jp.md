---
title: "ssh -vvv で見ても Permission denied (publickey) のままな理由"
date: 2026-01-04 12:00:00
categories: 
  - Network / Access Errors
tags: 
  - SSH
  - Troubleshooting
  - CLI
  - Debugging
status: publish
slug: ssh-vvv-permission-denied-jp
featured_image: ../images/image.png
---

ssh 接続が失敗し、
`Permission denied (publickey)` が出たので `ssh -vvv` を実行した。
ログを見ると 鍵は送っているように見える。
それでも接続できない――。

この記事は、その段階まで来た人向けに書いています。

**この記事の前提:**
*   パスワード認証ではなく、公開鍵認証を使っている
*   `ssh -vvv user@host` を実行できている
*   それでも原因が分からず詰まっている

「鍵の作り方」や「SSHとは何か」は扱いません。

---

## 問題のログ（これは“正常に失敗している”）

まず、今回のログをそのまま見ます。

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug3: send packet: type 50
debug2: we sent a publickey packet, wait for reply
debug1: Authentications that can continue: publickey
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug1: No more authentication methods to try.
Permission denied (publickey).
```

このログ、実はかなり情報量が多いです。

### 「Offering public key」が出ている＝認証成功、ではない

最初に一番大事なことを書きます。

:::warning
**Offering public key が出ていても、認証は成功していません。**
:::

この行は、
「クライアントがこの公開鍵を使って認証を試みた」
という意味であって、
「サーバーがその鍵を受け入れた」
という意味ではありません。

---

## ログを一行ずつ分解する

### ① 鍵を“出している”

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
```

*   クライアントは `id_rsa` に対応する **公開鍵**をサーバーに送った
*   鍵ファイルは読み込めている
*   SSH クライアント側に問題はほぼない

### ② 認証リクエストを送信

```text
debug3: send packet: type 50
debug2: we sent a publickey packet, wait for reply
```

*   公開鍵認証のパケットを送信
*   サーバーの応答待ち

### ③ サーバーは「publickey 認証は可能」と言っている

```text
debug1: Authentications that can continue: publickey
```

ここが重要です。

*   サーバーは **公開鍵認証そのものは有効だと言っている**
*   `PasswordAuthentication no` のような設定ではない

つまり、
**「方式は合っているが、その鍵はダメ」**
という状態です。

### ④ 同じ鍵をもう一度出して、終わる

```text
debug1: Offering public key: /home/user/.ssh/id_rsa RSA SHA256:xxxx
debug1: No more authentication methods to try.
Permission denied (publickey).
```

*   使える鍵はこれしかない
*   それが拒否された
*   結果として `Permission denied (publickey)`

---

## ここから分かること（重要）

このログから **確定できる事実**は次の通りです。

:::note
*   鍵ファイルは存在する
*   クライアントは正しく鍵を送っている
*   サーバーは公開鍵認証を受け付けている
*   **しかし、その鍵は認証に使えなかった**
:::

👉 問題は「鍵の出し方」ではありません。
👉 問題は **サーバー側でその鍵が“有効な鍵として登録されていない”**ことです。

---

## Offering public key → rejected になる典型パターン

ここからが実務的な原因です。

### 1. authorized_keys が別ユーザーのもの

一番多い原因です。

*   `ssh user@host` の `user`
*   `~/.ssh/authorized_keys` の **所有者**

これがズレていると、必ず拒否されます。

**例：**
*   実際に鍵を置いたのは `root`
*   ログインしているのは `deploy`

### 2. authorized_keys の中身が違う

*   鍵を貼り間違えた
*   古い鍵を消し忘れている
*   ローカルで鍵を作り直している

「同じ名前の鍵ファイル」でも、中身が違えば別の鍵です。
鍵ペアの確認方法は以下の記事で詳しく解説しています。

[SSH接続エラー？鍵ペアが本当に合っているか不安になったら、最初に確認すべきこと](/ssh-key-pair-verification-jp)

### 3. パーミッションがアウト

SSH は権限にかなり厳格です。

**最低限：**
```bash
~/.ssh            700
~/.ssh/authorized_keys 600
```

どれか一つでも緩いと、無言で拒否されます。
この場合も `Offering public key` → `Permission denied` になります。

### 4. sshd_config の設定

以下を変更している環境では要注意です。

*   `AuthorizedKeysFile` がデフォルトと違う
*   ホームディレクトリが通常と違う
*   `chroot` 環境

特に VPS や管理代行案件でありがちです。

---

## ここまで来た人が次にやるべきこと

`ssh -vvv` を見てこのログが出たら、次に見る場所はログではありません。

**見るべきは：**

:::step
1.  サーバー側の `~/.ssh/authorized_keys`
2.  そのファイルの所有者・権限
3.  本当にそのユーザーでログインしているか
:::

もし `root` で入れるなら、`authorized_keys` を **目視で確認する**のが一番早いです。

---

## まとめ

`ssh -vvv` で

*   `Offering public key` が出ている
*   それでも `Permission denied (publickey)`

この状態は、

:::conclusion
「鍵は正しく送られているが、サーバー側で“その鍵が正しいものとして扱われていない”」
:::

という、かなり限定された失敗です。

ここまで来ているなら、SSH の仕組みが分かっていないのではありません。
サーバー側の“現実の状態”と想定がズレているだけです。

ログはもう十分に語っています。
次は、サーバーの中身を見る番です。
