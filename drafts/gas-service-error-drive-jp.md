---
title: "【GAS】「Exception: Service error: Drive」エラーの原因ランキングと回避策"
slug: gas-service-error-drive-jp
date: 2026-01-13 10:00:00
categories: 
  - GAS
  - GAS Tips
tags: 
  - troubleshooting
  - tips
  - Google Apps Script
  - GAS
  - Google Drive
  - Error
status: publish
featured_image: ../images/gas-service-error-drive.png
---

Google Apps Script (GAS) で DriveApp を操作していると、突然発生する **「Exception: Service error: Drive」**。
コードは変えていないのに急に動かなくなったり、特定のファイルだけエラーになったりと、原因が掴みにくい厄介なエラーです。

この記事では、このエラーが発生する「よくある原因ランキング」と、スクリプトを止まらせないための「正しい回避策」を解説します。

## エラーの正体

```
Exception: Service error: Drive
```

このエラーは、GAS の `DriveApp` や `Advanced Drive API` が、Google Drive 側からエラー応答を受け取ったことを意味しています。
「コードの文法ミス」ではなく、「Drive 側との通信や状態に問題がある」ケースが大半です。

## よくある原因ランキング（重要順）

### 1位：一時的な Google 側の障害
実はかなり多いのがこのパターンです。Drive API が一瞬不安定になっているだけで、同じコードでも再実行すると通ることがあります。

- 【対処】まずはスクリプトを再実行して、再現するか確認する。

### 2位：対象ファイル・フォルダが存在しない
`DriveApp.getFileById(fileId)` などで指定した ID が無効になっているケースです。

- ID の指定ミス（コピペミスなど）。
- 既に削除されている。
- ゴミ箱に入っている。

一覧取得処理の途中で、別の誰かがファイルを削除した場合などに即座にこのエラーが出ます。

### 3位：権限不足
スクリプト実行ユーザーが、そのファイルやフォルダに触れる権限を持っていない場合です。

- 共有フォルダに対して「閲覧権限」しか持っていない。
- オーナーが別アカウントである。
- 共有ドライブ（Shared Drive）の権限設定で制限されている。

DriveApp は「見えるけれど触れない（編集できない）」状態の操作に弱く、エラーになりがちです。

### 4位：クォータ・レート制限（API叩きすぎ）
短時間に大量の Drive 操作を行うと発生します。

- `DriveApp.searchFiles`
- `getFiles()` / `getParents()`
- `getBlob()`

これらをループ内で大量に回していると、突然サービスエラーが返されます。

### 5位：Advanced Drive API の設定漏れ
`Drive.Files.list` などを利用する場合、共有ドライブ内のファイルを扱うには必須のパラメータがあります。

- `supportsAllDrives: true`
- `includeItemsFromAllDrives: true`

これらを付け忘れると、共有ドライブに関わった瞬間に即死します。

---

## まずやるべきチェックと修正（即効）

エラーが出てもスクリプト全体を止めないための、堅牢な書き方を紹介します。

### 1. try-catch で「死に場所」を特定する

特定のファイルが原因で落ちているか判別するため、Drive 操作は必ず `try-catch` で囲みます。

```javascript
try {
  const file = DriveApp.getFileById(fileId);
  // 何らかの処理
} catch (e) {
  Logger.log('NG fileId: ' + fileId);
  Logger.log(e); // エラー詳細をログに残す
}
```

- 【ポイント】どのファイル ID で落ちたかログに残すことで、原因（権限切れ、削除済みなど）を特定できます。

### 2. ループ処理は「1件スキップ」設計にする

ファイル一覧を処理する場合、1つのファイルでエラーが出ても次へ進めるようにします。

```javascript
files.forEach(f => {
  try {
    // Drive操作（名前変更、移動など）
    f.setName('processed_' + f.getName());
  } catch (e) {
    Logger.log('SKIP: ' + f.getId());
  }
});
```

これを行わないと、数千件の処理中に1件でもゴミ箱入りファイルが混ざると、そこで全処理が中断してしまいます。

### 3. Advanced Drive API のパラメータ確認

共有ドライブを扱う可能性がある場合は、必ずオプション指定を追加してください。

```javascript
// Advanced Drive API を使ってるなら必須
const files = Drive.Files.list({
  q: "title contains 'report'",
  supportsAllDrives: true,        // これがないとエラーになることが多い
  includeItemsFromAllDrives: true
});
```

### 4. スリープを入れる（レート制限対策）

大量のファイルを一気に処理する場合、意図的にウェイトを入れます。

```javascript
files.forEach(f => {
  try {
    f.makeCopy();
    Utilities.sleep(200); // 200ミリ秒待機
  } catch (e) {
    // ...
  }
});
```

- 【対処】API は連打に弱い。`Utilities.sleep(200)` 程度のアソビを入れるだけで安定性が劇的に向上します。

## 正しいエラーハンドリング構造

UI（ダイアログ）を表示する場合、処理の深部で `UI.alert` を出すのは避けましょう。
「処理エラー」と「ユーザーへの通知」はレイヤーを分けます。

**悪い例（処理が止まり、エラー画面が出る）：**

```javascript
// ❌ 良くない例
function processFile(id) {
  // ここで失敗すると例外未処理のまま落ちる
  const file = DriveApp.getFileById(id); 
}
```

**良い例（最後まで走り切り、結果を通知）：**

```javascript
function exportFileListToSheetManual() {
  try {
    // メイン処理を呼び出し
    exportFileListToSheetCore();
    
    // 成功時のみ完了メッセージ
    SpreadsheetApp.getUi().alert('完了しました');
  } catch (e) {
    // エラー時はメッセージを表示して正常終了させる
    SpreadsheetApp.getUi().alert('エラーが発生しました:\n' + e.message);
  }
}

function exportFileListToSheetCore() {
  // ここでは UI を操作せず、エラーは throw して親に任せる
  // 個別のファイル処理のエラーは内部で try-catch してスキップしても良い
}
```

## まとめ

- 【結論】`Service error: Drive` はコードミスとは限らない。不慮の事故（ファイル削除、通信障害）を前提にする。
- 【注意】一括処理での `alert` は事故の元。`try-catch` と「スキップ設計」が必須。
- 【ポイント】共有ドライブを扱う際は `supportsAllDrives: true` を忘れないこと。

Drive 連携はエラーがつきものです。「エラーが出ないようにする」のではなく、「エラーが出ても止まらない」スクリプトを目指しましょう。
