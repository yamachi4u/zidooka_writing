# 【GAS完パケ④】Google Driveフォルダの増減をメール通知するGAS

## 仕様
- 指定フォルダの「ファイル一覧」を定期チェックする
- 前回の一覧（PropertiesServiceに保存）と比較して、以下をメール通知する
    - 追加されたファイル
    - 削除されたファイル

## どう使える？
- 「納品フォルダに上がったら通知」＝外注管理に刺さる
- 「請求書提出フォルダが更新されたら通知」＝経理で刺さる
- 「共有フォルダの更新監視」＝少人数チームで刺さる
- 「誰かが消した」検知にも使える（地味に需要ある）

## セットアップ手順
1. 監視対象フォルダIDを入れる
2. `setupTimeTrigger()` を実行（例：15分ごと）
3. 以後は自動で監視されます

## 直貼りコード

```javascript
/**
 * Package #4: Drive Folder Change Monitor -> Email
 * - Time trigger based
 */

const CONFIG = {
  FOLDER_ID: "YOUR_FOLDER_ID",
  NOTIFY_TO: "your-email@example.com",
  SUBJECT_PREFIX: "[Drive増減通知]",
  INTERVAL_MINUTES: 15,
};

function setupTimeTrigger() {
  // 重複防止
  const triggers = ScriptApp.getProjectTriggers();
  triggers.forEach(t => {
    if (t.getHandlerFunction() === "checkDriveFolderChanges") ScriptApp.deleteTrigger(t);
  });

  ScriptApp.newTrigger("checkDriveFolderChanges")
    .timeBased()
    .everyMinutes(CONFIG.INTERVAL_MINUTES)
    .create();
}

function checkDriveFolderChanges() {
  try {
    const folder = DriveApp.getFolderById(CONFIG.FOLDER_ID);
    const current = listFiles_(folder); // {id: {name, url}}
    const props = PropertiesService.getScriptProperties();

    const prevJson = props.getProperty("prevFilesJson") || "{}";
    const prev = JSON.parse(prevJson);

    const added = [];
    const removed = [];

    Object.keys(current).forEach(id => {
      if (!prev[id]) added.push(current[id]);
    });
    Object.keys(prev).forEach(id => {
      if (!current[id]) removed.push(prev[id]);
    });

    if (added.length === 0 && removed.length === 0) return;

    const ts = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd HH:mm:ss");
    let body = `監視フォルダ: ${folder.getName()}\n検知日時: ${ts}\n\n`;

    if (added.length > 0) {
      body += "【追加】\n";
      added.forEach(f => body += `- ${f.name}\n  ${f.url}\n`);
      body += "\n";
    }
    if (removed.length > 0) {
      body += "【削除】\n";
      removed.forEach(f => body += `- ${f.name}\n  ${f.url}\n`);
      body += "\n";
    }

    const subject = `${CONFIG.SUBJECT_PREFIX} ${folder.getName()} (${ts})`;
    MailApp.sendEmail(CONFIG.NOTIFY_TO, subject, body);

    props.setProperty("prevFilesJson", JSON.stringify(current));
  } catch (err) {
    console.error(err);
  }
}

function listFiles_(folder) {
  const it = folder.getFiles();
  const out = {};
  while (it.hasNext()) {
    const f = it.next();
    out[f.getId()] = { name: f.getName(), url: f.getUrl() };
  }
  return out;
}
```
