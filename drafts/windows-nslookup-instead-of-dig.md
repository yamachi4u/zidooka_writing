---
title: "Windowsでdigコマンドが使えない？nslookupでDNSレコードを確認する方法"
date: 2026-01-02 22:30:00
categories: 
  - Windows / Desktop Errors
  - WEB制作
  - Network / Access Errors
tags: 
  - nslookup
  - dig
  - DNS
  - Windows
  - Command Prompt
status: publish
slug: windows-nslookup-instead-of-dig
featured_image: ../images/202601/nslookup-windows.png
---

サーバー移転やドメイン設定、Search Consoleの所有権確認などで「DNSレコードが正しく反映されているか確認したい」という場面があります。
技術記事ではよく「`dig` コマンドで確認しましょう」と書かれていますが、Windowsの標準環境（コマンドプロンプトやPowerShell）で `dig` を実行するとエラーになります。

```powershell
dig : 用語 'dig' は、コマンドレット、関数、スクリプト ファイル、または操作可能なプログラムの名前として認識されません。
```

Windowsには `dig` が標準搭載されていないためです。代わりに、標準搭載されている **`nslookup`** コマンドを使用しましょう。

## nslookup の基本的な使い方

`nslookup` は、ドメイン名からIPアドレスを調べたり、特定のDNSレコードを確認したりできるコマンドです。

### 特定のレコード（TXTなど）を確認する場合

Search Consoleの認証などで「TXTレコード」を確認したい場合は、オプションを指定する必要があります。

**コマンド例（1行で実行する場合）:**
```powershell
nslookup -type=TXT sub.example.com
```

* `-type=TXT`: 確認したいレコード種別（A, CNAME, MX, TXTなど）を指定します。
* `sub.example.com`: 確認したいドメイン名です。

:::note
**PowerShellでの注意点:**
PowerShellでは `-type=TXT` のような引数の解釈がうまくいかない場合があります。その場合は `nslookup -q=TXT sub.example.com` とするか、次項の対話モードを使用してください。
:::

## 対話モードでの実行（おすすめ）

コマンドプロンプトやPowerShellで `nslookup` とだけ入力してEnterを押すと、対話モードに入ります。続けてコマンドを入力できます。

:::step
**Step 1: nslookupを起動**
```powershell
nslookup
```
:::

:::step
**Step 2: レコード種別を指定**
```powershell
set type=TXT
```
（Aレコードを見たい場合は `set type=A`、CNAMEなら `set type=CNAME`）
:::

:::step
**Step 3: ドメインを入力**
```powershell
sub.example.com
```
これで結果が表示されます。
:::

:::step
**Step 4: 終了**
```powershell
exit
```
:::

## 実行結果の見方

成功すると、以下のように表示されます。

```text
Server:  UnKnown
Address:  192.168.1.1

Non-authoritative answer:
sub.example.com text =

        "google-site-verification=xxxxxxxxxxxxxxxx"
```

`text =` の後に表示されているのが、現在DNSサーバーから返ってきているTXTレコードの値です。ここに設定した値が表示されていれば、DNSの伝搬は完了しています。

## まとめ

:::conclusion
Windowsで「digコマンドが見つからない」となったら、迷わず `nslookup` を使いましょう。
特にTXTレコードの確認は `set type=TXT` を忘れずに行うのがポイントです。
:::
