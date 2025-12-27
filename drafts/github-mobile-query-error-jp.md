---
title: "GitHub Mobileで「Something went wrong...」が毎回出る理由（無視してOK）"
slug: "github-mobile-query-error-ignore"
status: "draft"
categories: 
  - "general"
  - "Network / Access Errors"
tags: 
  - "GitHub"
  - "GitHub Mobile"
  - "エラー"
  - "トラブルシューティング"
featured_image: "../images/github-mobile-query-error.png"
---

## はじめに

GitHub Mobileアプリ（iOS / Android）でリポジトリのフォルダ階層を移動していると、ほぼ毎回このエラーが表示されることがあります。

> **Something went wrong while executing your query.**
> This may be the result of a timeout, or it could be a GitHub bug.
> Please include 'XXXX:XXXX...' when reporting this issue.

「クエリの実行中に問題が発生しました」と言われますが、**一瞬待つか、再読み込みすると何事もなかったかのようにファイルが表示される**のが特徴です。

「自分の通信環境が悪いのかな？」「リポジトリが壊れた？」と不安になるかもしれませんが、

:::conclusion
結論から言うと**これはGitHubアプリの既知のバグ（仕様に近い挙動）であり、無視してOKです**。
:::

![GitHub Mobile Error](../images/github-mobile-query-error.png)

## 原因：GraphQLのタイムアウトと楽観的UI

このエラーが出る原因は、GitHub Mobileアプリの設計とAPIの制限の組み合わせにあります。

1.  **重いクエリを一括送信**: アプリでフォルダを開くと、その中のファイル一覧（Tree）、最新コミット情報、権限情報などをまとめて取得する「GraphQLクエリ」が発行されます。
2.  **10秒の壁**: GitHubのAPIには「計算に10秒以上かかるクエリはタイムアウトさせる」という制限があります。フォルダ階層が深かったり、ファイル数が多かったりすると、この制限に引っかかりやすくなります。
3.  **エラー表示が早すぎる**: アプリ側は「クエリが失敗した」という事実を即座にユーザーに伝えてしまいます（これが赤いエラー表示）。
4.  **裏では成功している**: 実はアプリはバックグラウンドで再試行（または分割取得）を行っており、2回目は成功してデータが返ってきます。その結果、「エラーが出たのに中身は見える」という奇妙な状態になります。

## 2022年からある既知の問題です

この問題は、GitHubの公式コミュニティでも2022年頃から報告されています。

[Something went wrong while executing your query. This may be the result of a timeout #24631](https://github.com/orgs/community/discussions/24631)

GitHubサポートの回答も「クエリがタイムアウトしている」「リクエストを分割する必要がある」というもので、アプリ側の実装がAPIの制限とうまく噛み合っていないことが示唆されています。
しかし、**「データ自体は壊れていない」「最終的には表示される」**ため、優先度が低く修正されずに残っているのが現状です。

## 対処法：気にしない

ユーザー側でできる対処法は**「気にしない」**ことです。

:::step
*   ❌ アプリの再インストール（直りません）
*   ❌ 通信環境の改善（直りません）
*   ❌ リポジトリ設定の変更（直りません）
:::

このエラーが出ても、あなたのリポジトリが壊れているわけでも、権限がないわけでもありません。
「あ、またGitHubが焦ってエラー出したな」くらいに思って、一呼吸置いてから画面を見てください。ちゃんとファイルは表示されているはずです。

## まとめ

*   GitHub Mobileでフォルダ移動時に出るエラーは**仕様に近いバグ**。
*   APIのタイムアウトとUIの表示タイミングの問題。
*   データは正常に取得できているので、**無視してOK**。

毎回出ると鬱陶しいですが、実害はないので安心して使い続けて大丈夫です。
