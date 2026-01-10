---
title: "The script does not have permission to perform that action が出る原因と対処法【GAS】"
slug: gas-permission-error-jp
date: 2025-12-17 12:30:00
categories: 
  - gas-errors
  - gas
  - google-errors
tags: 
  - GAS
  - Google Apps Script
  - Error
  - Permission
status: publish
featured_image: ../images/2025/gas-permission-error.png
---

Google Apps Script（GAS）を使っていると、

`The script does not have permission to perform that action`

というエラーが突然表示されることがあります。

このエラーは、
**スクリプトの権限不足によって処理がブロックされている状態**を意味します。

一見すると分かりにくいですが、
発生パターンはほぼ決まっています。

この記事では、

*   このエラーが出る具体的なコード例
*   なぜ権限エラーになるのか
*   実務での正しい切り分け手順

を整理して解説します。

## このエラーが出る典型的なコード例

`The script does not have permission to perform that action` は、
特定の Google サービスにアクセスした瞬間に発生します。

以下は、実務で特に遭遇率が高い例です。

### Drive を操作したとき（最頻出）

```javascript
function testDrive() {
  const files = DriveApp.getFiles();
  while (files.hasNext()) {
    Logger.log(files.next().getName());
  }
}
```

**エラーが出る状況**

*   初回実行
*   トリガー実行
*   他人が作成したスクリプトをコピーした直後

**原因**

*   Google Drive へのアクセス権限が未承認

`DriveApp` を使うコードは、
必ず一度は手動実行して権限承認を通す必要があります。

### Gmail / Calendar を操作したとき

```javascript
function readMail() {
  const threads = GmailApp.search('is:unread');
  Logger.log(threads.length);
}

function createEvent() {
  CalendarApp.getDefaultCalendar()
    .createEvent('test', new Date(), new Date());
}
```

**ポイント**

*   Gmail / Calendar は権限要求が強い
*   トリガー実行時にエラーになりやすい

一度でも承認が抜けていると、
このエラーで止まります。

### 外部 API（UrlFetch）を使ったとき

```javascript
function fetchApi() {
  const res = UrlFetchApp.fetch('https://api.example.com');
  Logger.log(res.getContentText());
}
```

**原因**

*   外部アクセス権限が未承認
*   Webアプリ・トリガー実行

エディタから一度も実行していない状態だと、
ほぼ確実にこのエラーが出ます。

### 他人の Drive ファイルを操作しようとした場合

```javascript
function openOtherFile() {
  const ss = SpreadsheetApp.openById('他人のスプレッドシートID');
  Logger.log(ss.getName());
}
```

**原因**

*   編集権限がない
*   共有ドライブの権限不足

これは仕様どおりの挙動で、
コード側で回避する方法はありません。

### トリガー実行で突然出るケース

```javascript
function triggerFunc() {
  DriveApp.createFile('test.txt', 'hello');
}
```

**よくある原因**

*   トリガー作成者の権限削除
*   スクリプトのオーナー変更
*   コピー後に古いトリガーが残っている

この場合は、
トリガーを削除して作り直すことで解決します。

### Webアプリ（doGet / doPost）で出る例

```javascript
function doGet() {
  const files = DriveApp.getFiles();
  return ContentService.createTextOutput('ok');
}
```

**原因**

*   実行ユーザーが「アクセスしているユーザー」
*   匿名アクセスに Drive 権限がない

Webアプリでは、
実行ユーザー設定のミスが非常に多いです。

### Advanced Service（Drive API 等）を使った場合

```javascript
function listFiles() {
  const files = Drive.Files.list();
  Logger.log(files);
}
```

**原因**

*   Advanced Service が未有効
*   OAuth スコープ不足

サービス有効化と再承認が必須です。

## このエラーが出る原因の共通点

どのケースも共通しているのは、

**スクリプトが、自分の権限を超えた操作を実行しようとしている**

という点です。

コードの書き方が間違っていることは、ほぼありません。

## 実務での切り分け手順

エラーが出たら、次の順で確認します。

1.  どの行でエラーが出ているか
2.  その行で使っているサービスは何か
3.  そのサービスは権限が必要か
4.  誰の権限で実行されているか

この4点を整理すれば、
原因は必ず特定できます。

## まとめ

`The script does not have permission to perform that action` は、
Google Apps Script で非常によく出る権限エラーです。

多くの場合、

*   手動実行による再承認
*   トリガーの作り直し
*   実行ユーザー設定の見直し

これだけで解決します。

GAS のエラーに慣れてくると、
**「あ、権限だな」**で即判断できるようになります。
