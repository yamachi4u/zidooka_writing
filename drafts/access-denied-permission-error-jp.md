---
title: "Access Denied｜You don't have permission to access this server #18などが出る原因と対処法"
slug: access-denied-permission-error-jp
date: 2025-12-22T11:00:00
categories: 
  - Network / Access Errors
tags: 
  - Access Denied
  - 403 Forbidden
  - Akamai
  - WAF
  - Troubleshooting
status: publish
thumbnail: images/2025/403-error-1.png
---

![Access Denied Error](images/403-error-1.png)

Webサイトを閲覧中に、突然次のようなエラー画面が表示されることがあります。

> **Access Denied**
> You don't have permission to access "http://www.example-site.com/jp/" on this server.
> Reference #18.xxxx.xxxx.xxxx

これは、アプリやWordPressのエラーではなく、**インフラ層（CDN / WAF）での拒否**を示しています。

特に以下が絡むケースが多いです。
*   **CDN**：Akamai
*   **WAF**（Web Application Firewall）
*   **国・IP・UA**（User-Agent）ベースのアクセス制御
*   ボット対策 / 不正トラフィック遮断

ポイントは、**「サーバーに到達する前に落とされている」**ということです。

## なぜ出るのか（原因パターン）

### ① Akamai（errors.edgesuite.net）配下での拒否
Akamai CDN + WAF で弾かれているケースです。
日本向けの `/jp/` パスのみ、海外からのアクセスを制限している場合などによく見られます。

Akamaiのエラーについては、以下の記事でも詳しく解説しています。
https://www.zidooka.com/archives/2590

### ② IPアドレス・地域制限
「自分は何もしていないのに出る」代表例です。
*   **VPN / 海外IP**：セキュリティのため、海外IPを遮断しているサイトは多いです。
*   **クラウド回線（AWS / GCP / Azure）**：データセンターからのアクセスはBotとみなされやすいです。
*   **企業ネットワーク**：特定のIP帯域がブラックリスト入りしている可能性があります。

### ③ User-Agent（ブラウザ判定）
*   `curl` / `wget` などのコマンドラインツール
*   Headless Chrome
*   古いブラウザ
*   Botのような挙動をした場合

### ④ 一時的なレート制限・不正検知
*   短時間に何度もアクセスした
*   リロードを連打した
*   特定パスへの集中アクセス

## 対処法（ユーザー側でできること）

サーバー管理者でない場合は、正直できることは限られますが、以下を試す価値はあります。

1.  **VPNを切る**：VPN経由だと弾かれることが多いため、通常の回線に戻します。
2.  **別回線（スマホ回線）で試す**：Wi-FiのIPが制限されている場合、スマホの4G/5Gなら繋がることがあります。
3.  **シークレットモード**：Cookieやキャッシュが原因の場合、シークレットモードで解決することがあります。
4.  **時間を置く**：一時的なレート制限であれば、数分〜数十分待てば解除されます。
5.  **管理者に問い合わせ**：どうしても閲覧が必要な場合は、エラー画面にある **Reference ID** を添えて管理者に連絡します。

## 管理者側（もし自分のサイトなら）

自分のサイトでこのエラーが出ている場合、以下を確認してください。
*   **Akamai WAF のルール確認**
*   **国別 / IP制限の解除**
*   **Bot対策の誤検知調整**
*   `/jp/` などの特定パスだけ弾く設定になっていないか

このエラーは「アプリではなく、門番（WAF/CDN）に止められている」状態です。ここを理解すると、無駄なアプリ改修をせずに済みます。
