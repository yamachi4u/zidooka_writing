---
title: "Specified permissions are not sufficient to call xxx が出る原因と対処法【GAS】"
date: 2025-12-21 12:00:00
categories: 
  - GAS Tips
tags: 
  - GAS
  - Google Apps Script
  - エラー
  - トラブルシューティング
status: publish
slug: gas-specified-permissions-error-jp
featured_image: ../images/2025/image copy 34.png
---

Google Apps Script（GAS）で開発をしていると、以下のようなエラーメッセージに遭遇することがあります。

```
Specified permissions are not sufficient to call DriveApp.getRootFolder. Required permissions: (https://www.googleapis.com/auth/drive.readonly || https://www.googleapis.com/auth/drive)
```

このエラーは、**「マニフェストファイル（appsscript.json）で指定された権限（スコープ）だけでは、その処理を実行するのに不十分である」** ということを意味しています。

この記事では、このエラーが発生する原因と、具体的な修正手順を解説します。

## エラーの意味と発生原因

このエラーは、主に **`appsscript.json` の `oauthScopes` を手動で設定している場合** に発生します。

GASは通常、コード内で使用されているサービス（SpreadsheetApp, DriveAppなど）を自動検出し、必要な権限（スコープ）を自動で設定してくれます。しかし、`appsscript.json` に `oauthScopes` を明記すると、**自動検出が無効になり、記述されたスコープのみが有効** になります。

その状態で、記述されていないスコープを必要とするメソッド（例：`DriveApp.getRootFolder()`）を実行すると、「指定された権限では足りません」と怒られるわけです。

### よくある発生パターン

1. **マニフェストを手動管理している**
   `appsscript.json` に `oauthScopes` を記述しているが、新しく追加した機能に必要なスコープを追記し忘れた場合。

2. **コード例**
   例えば、マニフェストで「スプレッドシートの権限」しか許可していないのに、「Googleドライブ」を操作しようとした場合です。

   **appsscript.json（設定）:**
   ```json
   {
     "timeZone": "Asia/Tokyo",
     "exceptionLogging": "STACKDRIVER",
     "oauthScopes": [
       "https://www.googleapis.com/auth/spreadsheets"
     ]
   }
   ```
   ※ Drive系のスコープが含まれていません。

   **コード（実行）:**
   ```javascript
   function triggerPermissionError_ScopeMissing() {
     // Drive を触る（上の oauthScopes に drive が無いと落ちる）
     const root = DriveApp.getRootFolder();
     Logger.log(root.getName());
   }
   ```

   この状態で `triggerPermissionError_ScopeMissing` を実行すると、冒頭のエラーが発生します。

## 似ているエラー・警告との違い

### 1. "This project requires your permission..."（警告）
これはエラーではなく、実行前の「警告」です。「このスクリプトを実行するには権限が必要ですよ」と教えてくれています。これを無視して無理やり実行しようとすると、今回のエラーや `You do not have permission` に繋がります。

### 2. "You do not have permission to call xxx"
本質的には今回のエラーと同じですが、`Specified permissions...` の方が「マニフェストで指定された範囲では足りない」というニュアンスが強く、具体的に不足しているスコープURLを教えてくれることが多いです。

## 対処法：不足しているスコープを追加する

解決策はシンプルで、エラーメッセージに表示されている「必要な権限（Required permissions）」を `appsscript.json` に追加することです。

### 手順1：エラーメッセージを確認する
エラー文の中に、必要なURLが書かれています。

> Required permissions: (https://www.googleapis.com/auth/drive.readonly || https://www.googleapis.com/auth/drive)

この場合、`drive.readonly`（読み取り専用）または `drive`（フルアクセス）のどちらかが必要です。

### 手順2：appsscript.json を編集する
エディタの「プロジェクトの設定」から `appsscript.json` を開き、`oauthScopes` の配列にURLを追加します。

**修正後の appsscript.json:**
```json
{
  "timeZone": "Asia/Tokyo",
  "exceptionLogging": "STACKDRIVER",
  "oauthScopes": [
    "https://www.googleapis.com/auth/spreadsheets",
    "https://www.googleapis.com/auth/drive.readonly" 
  ]
}
```
※ カンマ区切りを忘れないように注意してください。

### 手順3：再実行して承認する
ファイルを保存した後、再度関数を実行します。すると、新しい権限（この場合はGoogleドライブへのアクセス）を求める承認ダイアログが表示されます。これを許可すれば、エラーは解消され、スクリプトが正常に動作します。

## まとめ

`Specified permissions are not sufficient to call xxx` エラーが出たときは、コードのバグではなく **「マニフェストファイル（appsscript.json）の設定不足」** です。

1. エラーメッセージにある `Required permissions` のURLをコピーする。
2. `appsscript.json` の `oauthScopes` に追加する。
3. 再実行して権限を承認する。

この手順で確実に解決できます。

Category: GAS Tips

References:
1. Google Apps Script - Manifests
https://developers.google.com/apps-script/concepts/manifests
2. Google Apps Script - Scopes
https://developers.google.com/apps-script/concepts/scopes
