---
title: "【GAS】「Exceeded maximum execution time」エラーの原因と、6分の壁を突破する回避策"
slug: "gas-exceeded-maximum-execution-time"
status: "future"
date: "2025-12-13T09:00:00"
categories: 
  - "Google Apps Script"
  - "Troubleshooting"
tags: 
  - "GAS"
  - "Timeout"
  - "DriveApp"
  - "Optimization"
featured_image: "http://www.zidooka.com/wp-content/uploads/2025/12/6af991af5a4842d25f325abb320dbf8f.png"
---

# 結論：GASの「6分ルール」に引っかかっています

こんにちは、ZIDOOKA! です。
Google Apps Script (GAS) を運用していて、ある日突然こんなエラーメールが届くようになったことはありませんか？

**「Exceeded maximum execution time」**

![GAS Timeout Error](http://www.zidooka.com/wp-content/uploads/2025/12/6af991af5a4842d25f325abb320dbf8f.png)

初心者の方がこれを見ると「コードが間違っているのかな？」と焦るかもしれませんが、安心してください。**コードの文法ミスではありません。**

これは、スクリプトの実行時間が **Googleの定めた制限時間（無料アカウントなら6分）** を超えてしまったために、Google側から「はい、時間切れです！」と強制終了されたことを意味します。

## 私の失敗談：ファイルが増えたら突然死んだ

実は私も最近、このエラーに悩まされました。
Googleドライブ内のファイルを整理するスクリプトを作っていたのですが、**最初は数秒で終わっていた処理が、ある日突然エラーを吐くようになった**のです。

原因はシンプルで、**「運用しているうちにファイル数が膨大になっていたから」** でした。
コード自体は正しいのに、処理すべきデータ量が増えたせいで、制限時間内にゴールできなくなってしまったのです。

この記事では、そんな「GASの6分の壁」を乗り越えるための、**王道の対策** と **奥の手** を紹介します。

# 対策1：【王道】検索クエリで「処理する数」を減らす

まず最初に疑うべきは、「無駄なループをしていないか？」です。
特に `DriveApp` を使う場合、以下のようなコードは非常に危険です。

```javascript
// ❌ 悪い例：ドライブ内の「全ファイル」を取得してから、if文で選別している
function processAllFiles() {
  // これだと、関係ないファイルも含めて何万件も取得してしまう！
  const files = DriveApp.getFiles(); 
  
  while (files.hasNext()) {
    const file = files.next();
    // ここで名前チェックなどをすると、チェックだけで時間が溶ける
    if (file.getName().includes("請求書")) {
       // 処理...
    }
  }
}
```

これだと、目的のファイルにたどり着く前に6分が経過してしまいます。
そこで、`DriveApp.searchFiles` を使って、**最初から必要なファイルだけ** をGoogleに探してもらいましょう。

```javascript
// ✅ 良い例：最初から条件に合うファイルだけを取得する
function processRecentFiles() {
  // 例：「今日更新された」かつ「スプレッドシート」だけ欲しい
  const today = new Date();
  const dateString = Utilities.formatDate(today, Session.getScriptTimeZone(), "yyyy-MM-dd");
  
  // 検索クエリ（SQLのようなもの）で絞り込む
  // mimeType = スプレッドシート
  // modifiedDate > 今日の日付
  const params = `mimeType = 'application/vnd.google-apps.spreadsheet' and modifiedDate > '${dateString}'`;
  
  const files = DriveApp.searchFiles(params);
  
  while (files.hasNext()) {
    const file = files.next();
    console.log(file.getName()); // ここに来るのは対象ファイルだけ！
  }
}
```

これだけで、処理対象が「全ファイル（数万件）」から「今日のファイル（数件）」に激減し、一瞬で処理が終わるようになります。
**基本的には、この「絞り込み」だけで解決することがほとんどです。**

# 対策2：【奥の手】「ContinuationToken」で続きから再開する

「検索で絞っても、どうしても対象が数千件ある…」
「全ファイルのバックアップだから、絞り込みようがない…」

そんな時の **奥の手** がこれです。
**「5分働いたら今の場所をメモして休憩し、また後でそこから再開する」** という仕組みを作ります。

Google Drive の機能には、しおり（ContinuationToken）を挟む機能が用意されています。

## 実装のイメージ

1.  処理開始時にストップウォッチを押す。
2.  1つ処理するたびに「今何分経った？」と確認する。
3.  5分経っていたら、**「次はここから！」というチケット（トークン）** を保存して、スクリプトをわざと終了させる。
4.  **「1分後にまた起動してね」** とトリガー（目覚まし時計）をセットする。

```javascript
function processLargeAmountOfFiles() {
  // スクリプトのプロパティ（保存場所）を取得
  const scriptProperties = PropertiesService.getScriptProperties();
  
  // 1. 「前回の続き」があるかチェック
  const token = scriptProperties.getProperty('CONTINUATION_TOKEN');
  let files;
  
  if (token) {
    // 続きがあるなら、そこから再開！
    files = DriveApp.continueFileIterator(token);
  } else {
    // ないなら、最初からスタート
    files = DriveApp.getFiles(); 
  }
  
  const startTime = new Date().getTime(); // ストップウォッチ開始
  
  while (files.hasNext()) {
    const file = files.next();
    
    // --- ここに重い処理を書く ---
    console.log('処理中: ' + file.getName());
    // ---------------------------
    
    // 2. 経過時間をチェック (例: 5分 = 300,000ミリ秒 経過したら中断)
    const currentTime = new Date().getTime();
    if (currentTime - startTime > 300000) {
      
      // 3. 「次はここから」というトークンを発行して保存
      const newToken = files.getContinuationToken();
      scriptProperties.setProperty('CONTINUATION_TOKEN', newToken);
      
      // 4. 1分後に自分自身を再実行するトリガーをセット
      ScriptApp.newTrigger('processLargeAmountOfFiles')
        .timeBased()
        .after(1 * 60 * 1000) // 1分後
        .create();
        
      console.log('時間切れです。続きは1分後に自動実行します。');
      return; // ここで一旦終了！
    }
  }
  
  // 全て完了したら、保存していたトークンとトリガーをお掃除
  scriptProperties.deleteProperty('CONTINUATION_TOKEN');
  clearTriggers('processLargeAmountOfFiles'); // ※トリガー削除関数は別途用意
  console.log('全ての処理が完了しました。');
}
```

このコードは少し複雑ですが、これを使えば理論上は何万ファイルあっても、6分ごとに休憩しながら永遠に処理を続けることができます。

# まとめ

「Exceeded maximum execution time」が出たら、まずは焦らず以下の順で対応しましょう。

1.  **【基本】** `DriveApp.getFiles()` をやめて、`searchFiles` で対象を絞れないか考える。
2.  **【奥の手】** どうしても減らせないなら、`ContinuationToken` で分割実行させる。

特に初心者のうちは、1番の「絞り込み」を覚えるだけで、スクリプトの速度と安定性が劇的に向上しますよ！

