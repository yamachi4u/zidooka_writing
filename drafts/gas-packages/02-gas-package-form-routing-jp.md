# 【GAS完パケ②】フォーム内容に応じて通知先を自動振り分けするGAS

## 仕様
- フォーム回答のうち、特定の質問（例：「カテゴリ」）の回答値で通知先を切り替える
- 分岐テーブルはコード内に固定（完パケとして配りやすい）
- 未定義値はデフォルト宛先へ送信

## セットアップ手順
1. Googleフォームの「回答」タブ → スプレッドシート作成
2. そのスプレッドシートで「拡張機能 → Apps Script」を開く
3. 下のコードを貼り付ける
4. `CONFIG.ROUTING_QUESTION_TITLE` をフォームの実際の設問タイトル（例：「お問い合わせ種別」など）に書き換える
5. `ROUTING_MAP` を編集して、選択肢と送信先メールアドレスの対応を設定する
6. `setupTrigger()` を1回実行する

## 直貼りコード

```javascript
/**
 * Package #2: Form -> Email Notification with Routing
 * - Route email by a specific answer (e.g., category)
 */

const CONFIG = {
  DEFAULT_TO: "default@example.com",
  SUBJECT_PREFIX: "[フォーム振り分け]",
  ROUTING_QUESTION_TITLE: "カテゴリ", // ←フォームの設問タイトルを一致させる
  ROUTING_MAP: {
    "見積": "sales@example.com",
    "取材": "editor@example.com",
    "サポート": "support@example.com",
  },
};

function setupTrigger() {
  const triggers = ScriptApp.getProjectTriggers();
  triggers.forEach(t => {
    if (t.getHandlerFunction() === "onFormSubmit") ScriptApp.deleteTrigger(t);
  });

  ScriptApp.newTrigger("onFormSubmit")
    .forSpreadsheet(SpreadsheetApp.getActive())
    .onFormSubmit()
    .create();
}

function onFormSubmit(e) {
  try {
    const formResponse = e.response;
    const itemResponses = formResponse.getItemResponses();

    const title = formResponse.getForm().getTitle();
    const ts = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd HH:mm:ss");

    let routeValue = "";
    let body = `フォーム: ${title}\n受信日時: ${ts}\n\n`;

    itemResponses.forEach((ir, idx) => {
      const q = ir.getItem().getTitle();
      const a = normalizeAnswer_(ir.getResponse());
      body += `Q${idx + 1}. ${q}\n${a}\n\n`;
      if (q === CONFIG.ROUTING_QUESTION_TITLE) routeValue = String(a).trim();
    });

    const to = CONFIG.ROUTING_MAP[routeValue] || CONFIG.DEFAULT_TO;
    const subject = `${CONFIG.SUBJECT_PREFIX} ${title} [${routeValue || "未指定"}] (${ts})`;

    MailApp.sendEmail(to, subject, body);
  } catch (err) {
    console.error(err);
  }
}

function normalizeAnswer_(ans) {
  if (ans === null || ans === undefined) return "";
  if (Array.isArray(ans)) return ans.join(", ");
  if (typeof ans === "object") return JSON.stringify(ans, null, 2);
  return String(ans);
}
```
