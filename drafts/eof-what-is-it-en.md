---
title: "What Is EOF (End of File)? A Simple Explanation"
date:
categories:
  - general
tags:
  - EOF
  - programming
  - beginner
status: publish
slug: what-is-eof
---

EOF stands for **End of File** — a signal that indicates no more data is available to read from a file or data stream.

:::note
EOF is not a character stored inside the file. It is a **condition** detected by the OS or runtime when a read operation reaches the end.
:::

## How EOF Is Used in Programming

EOF is commonly used in read loops across many languages:

:::example
**C**
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

## Sending EOF from a Terminal

| OS | Key |
| --- | --- |
| Linux / macOS | `Ctrl+D` |
| Windows (cmd) | `Ctrl+Z` then Enter |

## Here-Documents (`<< EOF`)

In shell scripts, `<< EOF` introduces a here-document: *"treat everything until a line containing only EOF as standard input."*

:::note
The word `EOF` is just a convention. You can use any delimiter such as `END`, `EOS`, etc.
:::

## Summary

:::conclusion
EOF (End of File) is a fundamental concept that tells programs where data ends. It is used in read loops, terminal input, and shell here-documents across virtually every programming language.
:::
