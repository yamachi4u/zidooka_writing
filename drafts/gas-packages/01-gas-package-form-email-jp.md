# 【GAS完パケ①】フォーム送信内容をそのままメール通知するGAS

## 仕様（完パケとして渡す前提）
- Googleフォーム送信をトリガーに、回答内容を整形してメール送信
- 送信先は固定（1つ）でOK（完パケらしく最小構成）
- 件名にフォームタイトル＋日時を入れる

## セットアップ手順
1. Googleフォームの「回答」タブ → スプレッドシート作成
2. そのスプレッドシートで「拡張機能 → Apps Script」を開く
3. 下のコードを貼り付ける
4. `CONFIG.NOTIFY_TO` を自分のメールアドレスに変更する
5. `setupTrigger()` を1回実行する（権限許可が求められるので許可する）
   → 以後、フォームが送信されると自動で通知が届きます。

## 直貼りコード

```javascript
/**
 * Package #1: Form -> Email Notification (Minimal)
 * - Installable trigger: onFormSubmit
 */

const CONFIG = {
  NOTIFY_TO: "your-email@example.com",
  SUBJECT_PREFIX: "[フォーム通知]",
  INCLUDE_EDIT_RESPONSE_LINK: false, // 返信編集リンクを入れるか（任意）
};

function setupTrigger() {
  // 既存トリガー重複を避ける
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
    const formResponse = e.response; // FormResponse
    const itemResponses = formResponse.getItemResponses();

    const title = formResponse.getForm().getTitle();
    const ts = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd HH:mm:ss");

    let body = "";
    body += `フォーム: ${title}\n`;
    body += `受信日時: ${ts}\n\n`;

    itemResponses.forEach((ir, idx) => {
      const q = ir.getItem().getTitle();
      const a = normalizeAnswer_(ir.getResponse());
      body += `Q${idx + 1}. ${q}\n`;
      body += `${a}\n\n`;
    });

    if (CONFIG.INCLUDE_EDIT_RESPONSE_LINK) {
      body += `編集リンク: ${formResponse.getEditResponseUrl()}\n`;
    }

    const subject = `${CONFIG.SUBJECT_PREFIX} ${title} (${ts})`;
    MailApp.sendEmail(CONFIG.NOTIFY_TO, subject, body);
  } catch (err) {
    // 失敗時にログだけ残す（完パケなので過剰な再送はしない）
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
