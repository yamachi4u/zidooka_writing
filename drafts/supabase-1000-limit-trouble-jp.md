---
title: "【Supabase】1000件しか取得できない！全件取得する解決策【2時間溶かした】"
date: 2026-01-14 10:00:00
categories: 
  - WEB制作
tags: 
  - Supabase
  - JavaScript
  - TypeScript
  - WebAPI
  - トラブルシューティング
status: publish
slug: supabase-1000-rows-limit-fix
featured_image: ../images/supabase-limit-trouble.png
---

この記事では、Supabaseを利用していて「データが1000件しか取得できない」という問題に直面し、解決までに2時間近くを費やしてしまった経験と、その具体的な対処法を共有します。

もしあなたが `supabase.from('table').select('*')` で全データを取得しようとして、なぜか1000件で止まってしまう現象に悩まされているなら、この記事が解決の助けになるはずです。

## なぜ1000件しか取得できないのか？

結論から言うと、これは **Supabase（というかPostgREST）のデフォルト設定によるAPI制限** です。

SupabaseのJSクライアントで `.select('*')` を実行した際、明示的に範囲を指定しないと、デフォルトで「最大1000件まで」という制限がかかります。これはサーバーの負荷を防ぎ、パフォーマンスを維持するためのセーフティ機能です。

私がハマったときは、この仕様を忘れており、以下の点を疑って時間を浪費してしまいました。

*   **Row Level Security (RLS) の設定ミス？**（特定のユーザーにしか見えないデータがあるのでは？）
*   **ネットワーク不良？**（途中でタイムアウトしている？）
*   **データの不整合？**（インポートが失敗している？）

しかし、原因は単純なAPIのデフォルト制限でした。

> 【結論】Supabaseの `.select()` はデフォルトで1000件制限がある。
> RLSやデータ不整合を疑う前に、まずは取得件数の設定を確認する。

## 解決策：全件（または1001件以上）取得する方法

この制限を回避する方法はいくつかありますが、手っ取り早いのは `range()` メソッドを使うことです。

### 1. range() で取得範囲を広げる

`range(start, end)` を追加することで、取得するインデックスの範囲を指定できます。

```javascript
const { data, error } = await supabase
  .from('your_table')
  .select('*')
  .range(0, 9999); // 0件目から9999件目までを取得
```

これで最大10000件まで取得できるようになります（サーバー側の最大設定に依存しますが、多くの場合はこれで解決します）。

### 2. 1万件を超える場合のアプローチ

もしデータが数万件、数十万件ある場合は、一度に全件取得するのはパフォーマンス的に推奨されません。以下のようなアプローチを検討してください。

*   **ページネーション（分割取得）:** ループを使って `range(0, 999)`, `range(1000, 1999)`... と順番に取得する。
*   **ストリーミング:** 大量のデータを扱う場合は、クライアントサイドではなくサーバーサイド（Edge Functionsなど）で処理するか、CSVエクスポート機能を利用する。

## まとめ

Supabaseは非常に便利ですが、こうした「知っていれば一瞬、知らないとハマる」仕様がいくつかあります。1000件の壁にぶつかったら、まずは `range()` を試してみてください。

> 【対処】1000件以上取得したい場合は `.range(0, N)` を明示的に指定する。
> 大量データの場合はページネーション処理を実装する。

References:
1. Supabase Documentation - Using filters
https://supabase.com/docs/guides/database/api/filtering
2. PostgREST Documentation - Resource Embedding
https://postgrest.org/en/stable/references/api/resource_embedding.html
