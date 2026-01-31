---
title: "スネークケースとキャメルケースの違いと使い分け"
date: 2026-01-21 12:00:00
categories:
  - 用語
tags:
  - コーディング規約
  - 命名規則
status: publish
slug: snake-vs-camel-case-ja
---

【結論】`snake_case` と `camelCase` はどちらも可読性を高める命名規則で、言語やプロジェクトの慣習に合わせて一貫して使うのが重要です。

概要
----
プログラミングやデータ設計でよく出てくる命名スタイルに、スネークケース（snake_case）とキャメルケース（camelCase）があります。本記事は日本語で違い、例、使い分けの目安をわかりやすく解説します。

スネークケース（snake_case）とは
---------------------------
- 説明：単語を小文字でつなぎ、単語間をアンダースコア（`_`）で区切る書き方。
- 例：`user_name`, `total_count`, `file_path`。
- 用途例：Python の変数・関数名、データベースの列名、設定ファイルなど。

キャメルケース（camelCase）とは
---------------------------
- 説明：単語の区切りで大文字を用いる書き方。最初の単語は小文字（`camelCase`）か大文字（`PascalCase`）にする派閥がある。
- 例：`userName`, `totalCount`（camelCase）、`UserName`, `TotalCount`（PascalCase）。
- 用途例：JavaScript の変数・メソッド名、Java のローカル変数・メソッド、TypeScript のプロパティ名、クラス名には PascalCase。

言語ごとの慣習（目安）
------------------
- Python: `snake_case`（関数・変数）、`PascalCase`（クラス）
- JavaScript / TypeScript: `camelCase`（変数・関数・メソッド）、`PascalCase`（クラス）
- Java / C#: `camelCase`（ローカル変数、メソッド）、`PascalCase`（クラス）
- データベース（SQL）: 多くは `snake_case`（ただしプロジェクトによる）

使い分けのポイント
----------------
- 規約に従う：プロジェクトや言語のコーディングスタイルを優先する。チームで統一することが最重要。
- 可読性重視：単語の区切りが分かりやすいスタイルを選ぶ。複数単語で構成される識別子ではどちらでも可読性は上がる。
- 一貫性：同じファイルや同じプロジェクト内で混在させない。

実例
-----
- JavaScript:

```javascript
// good
const totalCount = 42;
function getUserName(id) { /* ... */ }

// avoid mixing
const user_name = 'alice'; // プロジェクトが camelCase なら避ける
```

- Python:

```python
# good
def get_user_name(id):
    return 'alice'

# avoid mixing
def getUserName(id):
    pass  # プロジェクトが snake_case なら避ける
```

変換ツールやエディタ設定
---------------------
- エディタのリネーム機能やスニペットで自動補完を活用する。
- 小さなスクリプトで`snake_case` ↔ `camelCase` を相互変換できる。例: 多くのコードフォーマッタやLint（`prettier`, `eslint`, `black`など）で命名規則を補助可能。

まとめ（短く）
---------------
【ポイント】`snake_case` はアンダースコアで単語を区切り、`camelCase` は大文字で区切る。言語・チームの慣習に従い、一貫して使うことが生産性と可読性を高めます。

追記
----
この記事は `PIPELINE_MANUAL.md` に従って `drafts/` に下書きとして配置してください。必要であればカテゴリ・タグを調整して `node src/index.js post drafts/snake_vs_camel_ja.md` で投稿できます。
