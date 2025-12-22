---
title: "【技術解説】GAS「Exceeded maximum execution time」の発生原理とバッチ処理テンプレート"
date: 2025-12-22 15:00:00
categories: 
  - GAS
tags: 
  - Google Apps Script
  - GAS Error
  - Exceeded maximum execution time
  - Code Snippet
status: publish
slug: gas-exceeded-time-technical-jp
featured_image: ../images/image copy 39.png
---

Google Apps Script (GAS) で最も厄介なエラーの一つである `Exceeded maximum execution time` について、技術的な発生条件と、それを回避するための**汎用的なコードパターン（テンプレート）**を解説します。

「なぜ落ちるのか」をコードレベルで理解し、コピペで使える対策パターンを知りたい方向けの記事です。

## 技術的な発生原因：6分の壁

このエラーの直接的な原因は、Google 側の**クォータ（制限）**による強制終了です。

| 実行タイプ | 最大実行時間 (無料/一般) | 最大実行時間 (Workspace) |
| :--- | :--- | :--- |
| **スクリプト実行** | **6 分** | **6 分** (一部30分) |
| **カスタム関数** | **30 秒** | **30 秒** |
| **シンプルトリガー** | **30 秒** | **30 秒** |

※ `onEdit` などのシンプルトリガーは 30秒 で死ぬ点に注意が必要です。

### コードレベルでの「遅延」の正体

GASの実行時間が伸びる要因は、主に **I/O（入出力）待機時間** です。

```javascript
// ❌ これが遅い原因
for (let i = 0; i < 1000; i++) {
  // 1回あたり 0.5秒〜1秒 かかる
  SpreadsheetApp.getActive().appendRow([i]); 
}
```

JavaScript の計算自体は一瞬ですが、`SpreadsheetApp` や `GmailApp`、`UrlFetchApp` などの **Google サービスを呼び出す通信時間** が積み重なって 6分 を超えます。
そのため、`Utilities.sleep()` で待機しても、実行時間は消費され続けるため逆効果です。

---

## 対策：バッチ処理化の抽象コードパターン

このエラーを回避する唯一の技術的解法は、**「時間を計測しながら処理し、タイムアウト前に中断して、次回に引き継ぐ」** ことです。

以下に、あらゆるループ処理に適用できる抽象的なテンプレートを提示します。

### 汎用バッチ処理テンプレート

このコードは、以下のロジックを実装しています。
1. 開始時間を記録
2. ループごとに経過時間をチェック
3. 5分を超えたら、現在のインデックスを保存して終了
4. トリガーで「続き」を自動予約

```javascript
function processInBatches() {
  // 1. 開始時刻を取得
  const startTime = new Date().getTime();
  const MAX_EXECUTION_TIME = 5 * 60 * 1000; // 5分 (安全マージン)

  // 2. 状態の復元 (PropertiesService)
  const props = PropertiesService.getScriptProperties();
  let currentIndex = Number(props.getProperty('CURRENT_INDEX')) || 0;

  // データ取得 (例: 10000件のデータ)
  const allData = getDataFromSource(); 
  
  // 3. ループ処理
  for (let i = currentIndex; i < allData.length; i++) {
    
    // --- ここに重い処理を書く ---
    processSingleItem(allData[i]);
    // ---------------------------

    // 4. 時間チェック (現在時刻 - 開始時刻)
    const currentTime = new Date().getTime();
    if (currentTime - startTime > MAX_EXECUTION_TIME) {
      
      // 5. 中断処理: 次回の開始位置を保存
      props.setProperty('CURRENT_INDEX', i + 1);
      
      // 6. トリガーセット: 1分後に再開
      ScriptApp.newTrigger('processInBatches')
               .timeBased()
               .after(1 * 60 * 1000)
               .create();
               
      console.log(`Time limit reached. Pausing at index ${i}. Next run scheduled.`);
      return; // ここで終了
    }
  }

  // 7. 完了処理
  console.log('All processing complete.');
  props.deleteProperty('CURRENT_INDEX'); // 保存した位置をクリア
}

// ダミー関数
function getDataFromSource() { return new Array(1000); }
function processSingleItem(item) { Utilities.sleep(100); }
```

### このパターンのメリット
*   **データ量に依存しない**: 1万件でも100万件でも、5分ごとに刻んで処理し続けます。
*   **安全**: 6分の強制終了（エラー）になる前に、自ら正常終了します。

---

## 実務での適用例

上記のコードは抽象的なテンプレートですが、実際に現場で「スプレッドシートの行処理」や「メール送信」でこのエラーが出た際に、どう判断してどう直したかの**実録ドキュメント**を別途公開しています。

具体的なイメージを掴みたい方は、こちらも合わせてご覧ください。

👉 **[Google Apps Scriptで「Exceeded maximum execution time」が出る原因と対処法｜実務ログベース解説](https://www.zidooka.com/?p=2704)**
