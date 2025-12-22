# 【GAS完パケ⑤】X（旧Twitter）への自動投稿GAS

## 仕様（完パケ）
- 投稿文はスプレッドシートで管理（1行=1投稿）
- 定期実行で「未投稿の1件」を投稿して、投稿済みに更新
- 連投事故を避けるため、1回の実行で最大1件だけ投稿

## シート仕様（固定）
シート名：`posts`

| A | B | C | D |
|---|---|---|---|
| text | status | postedAt | result |

- `status` は空欄なら未投稿、`DONE` なら投稿済み

## セットアップ手順
1. 認証〜API設定は[こちらの記事](https://www.zidooka.com/archives/2032)を参照してください。
2. スプレッドシートに `posts` という名前のシートを作成し、1行目に `text`, `status`, `postedAt`, `result` と入力します。
3. `setupTimeTrigger()` を実行して定期投稿を開始します。

## 直貼りコード

```javascript
/**
 * Package #5: X Auto Posting (Queue from Sheet)
 * - Requires X API credentials / OAuth (see your article)
 */

const CONFIG = {
  SHEET_NAME: "posts",
  INTERVAL_MINUTES: 60,
  // X API endpoint (v2)
  POST_URL: "https://api.twitter.com/2/tweets",

  // 認証：ここは「2032の記事」に寄せるのが一番強い
  // 例：PropertiesService に access token を保存して参照する運用
  ACCESS_TOKEN_PROP_KEY: "X_ACCESS_TOKEN",
};

function setupTimeTrigger() {
  const triggers = ScriptApp.getProjectTriggers();
  triggers.forEach(t => {
    if (t.getHandlerFunction() === "postNextTweetFromQueue") ScriptApp.deleteTrigger(t);
  });

  ScriptApp.newTrigger("postNextTweetFromQueue")
    .timeBased()
    .everyMinutes(CONFIG.INTERVAL_MINUTES)
    .create();
}

function postNextTweetFromQueue() {
  const ss = SpreadsheetApp.getActive();
  const sh = ss.getSheetByName(CONFIG.SHEET_NAME);
  if (!sh) throw new Error(`Sheet not found: ${CONFIG.SHEET_NAME}`);

  const values = sh.getDataRange().getValues();
  if (values.length < 2) return;

  const header = values[0].map(String);
  const colText = header.indexOf("text");
  const colStatus = header.indexOf("status");
  const colPostedAt = header.indexOf("postedAt");
  const colResult = header.indexOf("result");
  if (colText === -1 || colStatus === -1) throw new Error("Required columns: text, status");

  // 未投稿の先頭1件だけ
  for (let r = 1; r < values.length; r++) {
    const text = String(values[r][colText] || "").trim();
    const status = String(values[r][colStatus] || "").trim();
    if (!text || status) continue;

    const res = postToX_(text);
    const ts = Utilities.formatDate(new Date(), Session.getScriptTimeZone(), "yyyy-MM-dd HH:mm:ss");

    sh.getRange(r + 1, colStatus + 1).setValue("DONE");
    if (colPostedAt !== -1) sh.getRange(r + 1, colPostedAt + 1).setValue(ts);
    if (colResult !== -1) sh.getRange(r + 1, colResult + 1).setValue(JSON.stringify(res).slice(0, 30000));

    break;
  }
}

function postToX_(text) {
  const props = PropertiesService.getScriptProperties();
  const token = props.getProperty(CONFIG.ACCESS_TOKEN_PROP_KEY);
  if (!token) {
    throw new Error("X access token not set. See your auth article (2032) to store token in Script Properties.");
  }

  const payload = JSON.stringify({ text });

  const resp = UrlFetchApp.fetch(CONFIG.POST_URL, {
    method: "post",
    contentType: "application/json",
    payload,
    headers: {
      Authorization: `Bearer ${token}`,
    },
    muteHttpExceptions: true,
  });

  const code = resp.getResponseCode();
  const body = resp.getContentText();
  if (code < 200 || code >= 300) {
    throw new Error(`X API error: ${code} ${body}`);
  }
  return JSON.parse(body);
}
```
