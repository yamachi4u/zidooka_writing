---
title: "Claudeの「Service is temporarily unavailable. You can try again.」は何？現在ご利用いただけませんと出たときの見方"
slug: claude-service-temporarily-unavailable-20260513-jp
categories:
  - AI系エラー
tags:
  - Claude
  - Anthropic
  - AI
  - エラー
  - 障害
status: publish
featured_image: "C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-13 211111.png"
---

Claude を使っていると、画面上部に次のような警告が出ることがあります。

![Claude の一時利用不可エラー](C:/Users/user/Pictures/screenshots/スクリーンショット 2026-05-13 211111.png)

> 現在ご利用いただけません。後ほど再度お試しください。  
> Service is temporarily unavailable. You can try again.

:::conclusion
この表示は、基本的には Claude 側の一時的なサービス障害や混雑を示すエラーです。自分のPCやブラウザ設定だけが壊れた、というより Claude.ai 側でエラー率が上がっている可能性があります。
:::

## 何のエラー？

`Service is temporarily unavailable. You can try again.` は、Claude が一時的にリクエストを処理できないときに出るタイプのエラーです。

日本語表示では「現在ご利用いただけません。後ほど再度お試しください。」と出ます。意味としては、ユーザー側の入力ミスというより、Claude のWebアプリやバックエンド側が一時的に正常応答できていない状態に近いです。

## 2026年5月13日時点の状況

公式の Claude Status では、2026年5月13日に `Claude.ai is experiencing elevated error rates` というインシデントが掲載されています。

公式ページ上では、2026年5月13日 11:25 UTC に調査開始、11:48 UTC に回復傾向を確認して監視中、とされています。日本時間では同日夜の時間帯にあたります。

参考：

- [Claude Status](https://status.claude.com/)

:::note
ステータスページの表示は更新されます。この記事を読んでいる時点で復旧済みになっている場合もあります。
:::

## まず確認すること

このエラーが出たら、最初に公式ステータスを確認するのが早いです。

1. [Claude Status](https://status.claude.com/) を開く
2. `claude.ai` に障害や degraded performance が出ていないか見る
3. Claude Code や API だけでなく、Claude.ai の項目も確認する
4. 数分待ってから再読み込みする

同じ時間帯に公式ステータスで障害が出ているなら、ローカル環境を大きく変更する必要はありません。

## こちらでできる対処

まずは次の順で試すのが安全です。

1. 数分待って再試行する
2. ページを再読み込みする
3. 別タブやシークレットウィンドウで開く
4. Claude から一度ログアウトして再ログインする
5. VPNやプロキシを使っている場合は一時的に切る
6. 急ぎなら ChatGPT、Gemini、Perplexity など別のAIサービスを一時利用する

:::warning
公式ステータスで障害が出ている場合、ブラウザの設定変更やアカウント作り直しで根本解決する可能性は低いです。まずは復旧を待つのが無難です。
:::

## アカウント停止や料金制限とは違う？

この文面だけで、アカウント停止や利用上限到達と判断する必要はありません。

利用上限の場合は、通常は「limit」「usage」「message remaining」など、制限に関する別の案内が出ることが多いです。一方で `Service is temporarily unavailable` は、サービス側が一時的に使えないことを示す汎用的なエラーです。

ただし、長時間自分だけ同じ表示が続く場合は、ログアウト・再ログイン、別ブラウザ、ネットワーク変更を試す価値があります。

## まとめ

Claude の「Service is temporarily unavailable. You can try again.」は、Claude.ai 側の一時的なエラーや混雑で出ることが多い表示です。

まず公式ステータスを確認し、障害が出ていれば復旧を待ちましょう。急ぎの作業は、別のAIサービスやローカルに残っている下書きへ一時的に逃がすのが現実的です。
