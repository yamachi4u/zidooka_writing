---
title: "GitHub Freeは何個アカウントを持てる？複数アカウントの規約を確認した"
categories:
  - WEB制作
tags:
  - GitHub
  - GitHub Free
  - アカウント
status: publish
slug: github-free-multiple-accounts
---

GitHubを仕事用・個人用・自動化用などで使い分けていると、「無料アカウントって何個まで作っていいんだろう？」という疑問が出てきます。

結論からいうと、==GitHubの利用規約上、1人または1法人が維持できる無料アカウントは原則1つです==。

## 無料の個人アカウントは原則1つ

GitHub Terms of Service の Account Requirements では、1人または1法人が維持できる無料アカウントは1つまで、とされています。

つまり、用途を分けたいからといって、同じ人が通常の GitHub Free 個人アカウントを2個、3個と作って運用するのは、規約上は避けるべきです。

一方で、GitHub Free 自体は公開リポジトリだけでなく、プライベートリポジトリも数の上では無制限に作れます。単にプロジェクトを分けたいだけなら、アカウントを増やす必要はあまりありません。

## 例外：machine account は追加で1つ持てる

規約には例外もあります。無料の Personal Account に加えて、==自動処理専用の machine account を1つまで維持できます==。

ただし、これは普通のサブアカウントとして使ってよい、という意味ではありません。規約上は「machineを動かすため」のアカウントです。

GitHub Actions以外のボットやサービス連携などで専用ユーザーが必要になるケースを想定するとわかりやすいでしょう。

:::warning
machine account を「仕事用のもう1つの自分」「別名義の個人アカウント」として日常的に使うのは、規約の趣旨とは異なります。
:::

## 仕事用と個人用を分けたい場合は？

GitHub自身は、Enterprise Managed User など会社側が管理するアカウントが必要なケースを除き、GitHub.com上の仕事・個人・OSS活動を1つの Personal Account にまとめることを推奨しています。

仕事のリポジトリを分離したい場合は、Personal Accountを増やすより、Organizationを使う設計が基本です。1つの Personal Account は複数の Organization に参加できます。

なお、企業が Enterprise Managed Users を使っている場合などは、個人の Personal Account と会社管理の Managed User Account を併用するケースがあります。GitHubには複数アカウントを切り替えるためのアカウントスイッチャーも用意されています。

## まとめ

:::conclusion
GitHub Free の通常の個人アカウントは、規約上は1人1つが原則です。さらに自動処理専用の無料 machine account を1つ持つことは認められています。用途を分けたいだけなら、複数の無料個人アカウントを作るのではなく、Organizationやリポジトリで分離するのが素直です。
:::

## 参考

- GitHub Terms of Service: https://docs.github.com/en/site-policy/github-terms/github-terms-of-service
- GitHub Docs「Personal account management」: https://docs.github.com/en/account-and-profile/concepts/account-management
- GitHub Docs「GitHub's plans」: https://docs.github.com/en/get-started/learning-about-github/githubs-plans
