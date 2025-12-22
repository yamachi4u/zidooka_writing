# 【GAS完パケ③】フォーム送信からPDFを自動生成するGAS（請求書・申込書向け）

## 仕様
- フォーム回答をテンプレート（Googleドキュメント）に差し込む
- PDF化してGoogle Driveの指定フォルダへ保存する
- 必要ならメール添付で送付する（設定でON/OFF可能）

## 前提（完パケとして渡す条件）
- テンプレートDocにプレースホルダを入れる（例：`{{氏名}}`, `{{金額}}`）
- フォーム設問タイトルとプレースホルダ名を合わせると運用が簡単です（案件ごとの調整が減ります）

## セットアップ手順
1. PDFテンプレート用のGoogleドキュメントを作る（例：請求書）
2. 置換したい箇所を `{{設問タイトル}}` という形式で記述する
3. 保存先フォルダを用意して、そのフォルダIDを取得する
4. スプレッドシートのGASエディタに以下のコードを貼り付ける
5. `CONFIG.TEMPLATE_DOC_ID`, `CONFIG.OUTPUT_FOLDER_ID` を設定する
6. `setupTrigger()` を実行する

## 直貼りコード

```javascript
/**
 * Package #3: Form -> PDF Generation (Template Doc -> PDF)
 */

const CONFIG = {
  TEMPLATE_DOC_ID: "YOUR_TEMPLATE_DOC_ID",
  OUTPUT_FOLDER_ID: "YOUR_OUTPUT_FOLDER_ID",
  SEND_EMAIL: false,
  EMAIL_TO: "your-email@example.com", // SEND_EMAIL=true のとき使用
  SUBJECT_PREFIX: "[PDF自動生成]",
  FILE_NAME_PREFIX: "document",
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

    // 置換用の map を作る（{{設問タイトル}} -> 回答）
    const map = {};
    itemResponses.forEach(ir => {
      const key = ir.getItem().getTitle();
      map[`{{${key}}}`] = normalizeAnswer_(ir.getResponse());
    });

    const ts = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyyMMdd_HHmmss");
    const formTitle = formResponse.getForm().getTitle();
    const fileBaseName = `${CONFIG.FILE_NAME_PREFIX}_${sanitize_(formTitle)}_${ts}`;

    // テンプレをコピーして置換
    const copiedFile = DriveApp.getFileById(CONFIG.TEMPLATE_DOC_ID).makeCopy(`${fileBaseName}_work`);
    const doc = DocumentApp.openById(copiedFile.getId());
    const body = doc.getBody();

    Object.keys(map).forEach(placeholder => {
      body.replaceText(escapeForRegex_(placeholder), map[placeholder]);
    });

    doc.saveAndClose();

    // PDF化して指定フォルダへ移動
    const pdfBlob = copiedFile.getAs(MimeType.PDF).setName(`${fileBaseName}.pdf`);
    const folder = DriveApp.getFolderById(CONFIG.OUTPUT_FOLDER_ID);
    const pdfFile = folder.createFile(pdfBlob);

    // 作業用docは残す/消すは運用次第。完パケは消す寄りにする
    copiedFile.setTrashed(true);

    if (CONFIG.SEND_EMAIL) {
      const subject = `${CONFIG.SUBJECT_PREFIX} ${formTitle} (${ts})`;
      const bodyText = `PDFを自動生成しました。\n\nファイル: ${pdfFile.getName()}\n`;
      MailApp.sendEmail(CONFIG.EMAIL_TO, subject, bodyText, { attachments: [pdfBlob] });
    }
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

function sanitize_(s) {
  return String(s).replace(/[\\\/:*?"<>|]/g, "_").slice(0, 80);
}

function escapeForRegex_(s) {
  // replaceText は正規表現扱いになるのでエスケープ
  return String(s).replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}
```
