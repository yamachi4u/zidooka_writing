---
title: "Discord APIで発生する「40333: Internal Network Error」の原因とCloudflare Workersによる回避策"
slug: discord-api-40333-internal-network-error-cloudflare
date: 2026-01-26 10:00:00
categories:
  - Network / Access Errors
tags:
  - Discord
  - API
  - Cloudflare
  - Workers
  - Troubleshooting
status: draft
---

Discord Botの開発中や、APIを直接叩くスクリプトの実行中に、見慣れないエラーコード `40333` に遭遇することがあります。メッセージには `internal network error` とあり、一見するとDiscord側のサーバー障害のように見えますが、実はクライアント（Bot）側の設定に起因することが多い問題です。

この記事では、このエラーの正体と、その解決策の一つであるCloudflare Workersをプロキシとして利用する方法について解説します。

## エラーの内容

Discord APIに対してリクエストを送った際、以下のようなJSONレスポンスが返ってくることがあります。

```json
{
    "message": "internal network error",
    "code": 40333
}
```

「Internal network error（内部ネットワークエラー）」という文言から、Discordのサーバーダウンや一時的な障害を疑いたくなりますが、多くの場合はそうではありません。

## 原因：CloudflareによるUser-Agentブロック

このエラーの実態は、Discordの前段にある **Cloudflareによるアクセスブロック** です。

以前はCloudflareのHTMLページ（Access Deniedなど）が返されていましたが、最近のDiscordの構成変更により、代わりにこのJSONエラーが返されるようになったようです。

### なぜブロックされるのか？

主な原因は、リクエストヘッダーの **User-Agent（UA）** です。
DiscordのBot APIに対して、**一般的なWebブラウザ（ChromeやSafariなど）のUser-Agent** を偽装してリクエストを送ると、セキュリティ対策としてCloudflareにブロックされる場合があります。

GitHubのIssue報告によると、例えば以下のようなブラウザのUAを含めたcurlリクエストでこの現象が再現します。

```bash
# ブロックされる例（ブラウザのUser-Agentを付与）
curl 'https://canary.discord.com/api/v9/channels/...' \
  -H 'authority: canary.discord.com' \
  -H 'authorization: Bot ...' \
  -H 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) ... Chrome/108.0.5359.215 ...'
```

Botやスクリプトを作成する際、アクセス拒否を回避しようとして「とりあえずブラウザのUAを入れておく」という手法を取ることがありますが、Discord APIにおいてはそれが裏目に出る形です。

## 試みた回避策：Cloudflare Workersの利用（失敗）

IP制限を回避する定石として、**Cloudflare Workers** をプロキシとして利用する方法があります。しかし、**この方法でも解決しないケースがあること**が判明しました。

Cloudflare Workersからのリクエストは、当然ながらCloudflareのIP帯域から送信されます。Discord側（こちらもCloudflareを利用）の設定において、**「Cloudflare Workersからの直接アクセス」自体が警戒対象になっている**、あるいは「Cloudflare to Cloudflare」の通信において何らかのフィルタが働いている可能性があります。

実際、Workers経由でUser-Agentを適切に設定し直してリクエストを送っても、同様に `40333` エラーが返される現象が確認されています。

同様に、**Google Apps Script (GAS)** からのアクセスも、共有IPアドレスのレピュテーションの問題でブロックされやすい傾向にあります。

## 現時点での結論

残念ながら、無料のサーバーレス環境（GASやCloudflare Workers）を使ってこの問題を確実に回避する「万能な方法」は見つかっていません。

もし `40333` エラーが出続ける場合、以下のいずれかの対応が必要になります。

1.  **固定IPを持つVPSを利用する**:
    AWS EC2やDigitalOceanなどのVPSから、クリーンなIPアドレスでアクセスする。
2.  **自宅サーバー/ローカルPCで動かす**:
    ISPのIPアドレスであれば、極端なBot判定を受けにくい場合があります。
3.  **レジデンシャルプロキシを利用する**:
    どうしてもクラウド上で動かす必要がある場合は、信頼できるプロキシサービスを挟むことを検討する必要があります。

「Cloudflare Workersを通せばIPロンダリングできる」という手法は、ことDiscord APIにおいては通用しなくなってきているようです。

## まとめ

- エラーコード `40333` の `internal network error` は、Discord（Cloudflare）によるブロック。
- User-Agentの修正だけでは直らない場合がある。
- **Cloudflare WorkersやGAS経由でもブロックされる可能性が高い。**
- 確実な解決には、信頼できるIPアドレスを持つ環境への移行が必要。

## 参考文献
1. Misleading API response for Cloudflare blocked requests · Issue #6473 · discord/discord-api-docs
https://github.com/discord/discord-api-docs/issues/6473
