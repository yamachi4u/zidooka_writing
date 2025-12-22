---
title: "【WordPress】過去記事のタグ付けをNode.jsで全自動化した話"
slug: "auto-tagging-nodejs"
status: "future"
date: "2025-12-14T09:00:00"
categories: 
  - "Automation"
  - "WordPress"
tags: 
  - "Node.js"
  - "WordPress REST API"
  - "Efficiency"
featured_image: "../images/task-scheduler.png"
---

# タグ付け、サボってませんか？

こんにちは、ZIDOOKA! です。
記事を書くのは楽しいけど、**「タグ付け」** って地味に面倒ですよね。
過去の記事に新しいタグを追加したいときなんて、1記事ずつ編集画面を開くのは苦行でしかありません。

そこで、**「記事の本文をスキャンして、既存のタグが含まれていたら勝手にタグ付けするスクリプト」** を作りました。

## 仕組み

1. WordPressから「全タグ」と「全記事」を取得。
2. 記事の本文にタグ名（例: "VS Code"）が含まれているかチェック。
3. 含まれているのにタグが付いていない場合、API経由でタグを追加。

これを Node.js と WordPress REST API で実装しました。

## 実際のコード

```javascript
// 記事本文にタグが含まれているかチェックするロジック
for (const tag of allTags) {
  if (content.includes(tag.name.toLowerCase())) {
    if (!newTagIds.has(tag.id)) {
      newTagIds.add(tag.id);
    }
  }
}
```

たったこれだけですが、効果は絶大です。
コマンド一発で、数百記事のタグ付けが数秒で終わりました。

## さらに自動化（タスクスケジューラ）

このスクリプトを Windows の **タスクスケジューラ** に登録しておけば、
「寝ている間に勝手にサイトが整理整頓される」という夢のような環境が構築できます。

![タスクスケジューラの設定](../images/task-scheduler.png)

## まとめ

「ZIDOOKA CLI」に続いて、また一つ管理業務が消滅しました。
空いた時間で、また新しい自動化ツールを作ろうと思います。
