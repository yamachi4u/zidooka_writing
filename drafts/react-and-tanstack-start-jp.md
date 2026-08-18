---
title: "ReactとTanStack Startの違いを、層に分けて理解する"
slug: react-and-tanstack-start
status: publish
categories:
  - Web開発
tags:
  - React
  - TanStack Start
  - TypeScript
  - フレームワーク
---

ReactとTanStack Startの違いがよく分からない。そう感じるのは自然です。そもそも、この2つは同じ種類のものではありません。

結論から言うと、Reactは主に「画面を組み立てるためのライブラリ」です。TanStack Startは、Reactを使ってWebアプリ全体を作るための「フルスタック・フレームワーク」です。

たとえば、Reactがエンジンや車輪のような中心部品だとすると、TanStack Startは、ルーティングやサーバー処理まで含めた自動車全体の設計に近いものです。

## Reactは何をするのか

Reactの中心にあるのは、コンポーネントです。ボタン、検索欄、記事カード、ナビゲーションといった画面の部品を、JavaScriptの関数として書き、それらを組み合わせて画面を作ります。

クリックに反応して表示を変える、入力欄の内容に応じて検索結果を絞る、といったUIの状態管理もReactの得意分野です。

一方で、ReactだけではWebアプリ全体の設計は決まりません。URLと画面をどう対応させるか、データをどこで取得するか、サーバーでHTMLを生成するか、ログイン処理をどこで行うか、どのようにビルドして公開するかは、別の道具を選ぶ必要があります。

React公式も、アプリ全体を作る場合にはフルスタックReactフレームワークを使う方向を案内しています。

## TanStack Startは何をするのか

TanStack Startは、Reactを土台にしてWebアプリ全体を動かす枠組みです。TanStack Routerを中心に、型安全なルーティング、ローダーによるデータ取得、サーバーサイドレンダリング、ストリーミング、サーバー関数、クライアント用・サーバー用のビルドなどを扱います。

つまり、Reactが「この部品をどう表示するか」を担当するのに対して、TanStack Startは「この画面をどのURLで、どのデータを使って、サーバーとブラウザのどちらで動かすか」まで担当します。

## 模式図

```
Webアプリ全体
┌─────────────────────────────────────┐
│ TanStack Start                      │
│ ルーティング / SSR / データ取得      │
│ サーバー関数 / ビルド / 公開          │
│  ┌───────────────────────────────┐  │
│  │ TanStack Router               │  │
│  │ URLと画面・データの対応        │  │
│  │  ┌─────────────────────────┐  │  │
│  │  │ React                   │  │  │
│  │  │ コンポーネント / 状態 / UI │  │  │
│  │  └─────────────────────────┘  │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

この図は、TanStack Startの中でReactを書く、という関係を示しています。ReactとTanStack Startが横並びの競合製品なのではなく、担当する層が違うのです。

## 何を選べばよいのか

既存のHTMLページにインタラクティブな部品を一つ置きたいだけなら、Reactだけでも十分です。必要な周辺機能を自分で選べるため、構成を小さく保てます。

一つのWebアプリを作り、URL、データ取得、サーバー処理、ページ表示、デプロイまでまとめて設計したいなら、TanStack Startのようなフレームワークを選びます。その場合もReactを捨てるわけではありません。TanStack Startの中でReactのコンポーネントを書きます。

| 観点 | React | TanStack Start |
|---|---|---|
| 種類 | UIライブラリ | フルスタックWebフレームワーク |
| 中心 | コンポーネントと状態 | ルーティングとアプリ全体の実行 |
| URL | 別途選択 | TanStack Routerを利用 |
| サーバー処理 | 別途構成 | サーバー関数などを統合 |
| 向いている用途 | UI部品、既存ページへの導入 | 本格的なWebアプリ |

## Next.jsとの関係

TanStack Startは、Reactそのものの代替ではありません。比較するなら、Next.jsやReact Routerのような、Reactを使ったアプリケーションフレームワークと比較するものです。

TanStack Startは、TanStack Routerをアプリの契約として置き、型安全性や明示的な構成、デプロイ先の自由度を重視する方向のフレームワークです。現時点ではRC（リリース候補）なので、採用する場合は公式ドキュメントと更新状況を確認するのが安全です。

## まとめ

Reactは画面を作る部品と仕組みです。TanStack Startは、そのReactを使って、ルーティング、データ取得、サーバー処理、ビルドまで含むWebアプリを作るための枠組みです。

したがって、「ReactとTanStack Startのどちらを選ぶか」ではなく、「Reactだけで足りるか、それともReactを含むアプリ全体の枠組みが必要か」と考えると、違いが見えやすくなります。

References:
1. React
https://react.dev/
2. TanStack Start Overview
https://tanstack.com/start/latest/docs/framework/react/overview
3. TanStack Start Comparison
https://tanstack.com/start/latest/docs/framework/react/comparison

