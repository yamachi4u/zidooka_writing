---
title: "EOF（End of File）とは？意味と使い方をわかりやすく解説"
date:
categories:
  - 用語
tags:
  - EOF
  - プログラミング用語
  - 初心者向け
status: publish
slug: eof-meaning
---

## EOF とは？

**EOF** は **End of File**（ファイルの終端）の略です。ファイルやデータストリームの **「ここでデータが終わっているよ」** という合図です。

:::note
EOF それ自体は「文字」ではなく、プログラムがデータの終わりを検出するための **状態（condition）** です。
:::

## プログラミングでの使われ方

EOF はさまざまな言語でデータ読み取りの終了判定に使われます。

:::example
**C 言語**
```c
int c;
while ((c = getchar()) != EOF) {
    putchar(c);
}
```

**Python**
```python
for line in sys.stdin:
    print(line, end="")
```

**Bash**
```bash
while IFS= read -r line; do
    echo "$line"
done < file.txt
```
:::

## ターミナルで EOF を送る方法

キーボードから入力の終わりを伝えるときにも EOF が使われます。

| OS | キー |
| --- | --- |
| Linux / macOS | `Ctrl+D` |
| Windows（cmd） | `Ctrl+Z` → Enter |

## ヒアドキュメントと EOF

シェルスクリプトで見かける `<< EOF` も同じ考え方です。「EOF と書かれた行がくるまで、このコードを標準入力として扱え」という意味になります。

:::note
`EOF` という文字列自体は慣習的なもので、`EOS` や `END` など任意の文字列で代用できます。
:::

## まとめ

:::conclusion
EOF（End of File）は「データの終わり」を示す状態です。プログラミングにおける読み取りループやターミナル操作において、データの終端を正しく扱うための基本概念です。
:::
