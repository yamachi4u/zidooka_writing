---
title: "PlaywrightでGoogleログインが「安全でない」エラーで失敗する問題"
date: 2026-02-14 10:00:00
categories:
  - アプリ開発
tags:
  - Playwright
  - Google
  - 自動化
  - トラブルシューティング
status: publish
slug: playwright-google-login-error
featured_image: ../images/2026/playwright-google-login-error.png
---

PlaywrightでChromiumブラウザを使ってGoogleアカウントにログインしようとすると、「ログインできませんでした」というエラーが表示されることがあります。

![ログインエラーのスクリーンショット](../../Pictures/screenshots/スクリーンショット%202026-02-14%20234824.png)

## エラーの内容

エラーメッセージには以下の内容が表示されます。

> このブラウザまたはアプリは安全でない可能性があります。

これはGoogleのセキュリティシステムが、自動化ツール（PlaywrightやSeleniumなど）からのログインを検出してブロックしているためです。

## なぜブロックされるのか

Googleは不正アクセス防止のため、以下の特徴を持つブラウザを自動化ツールとみなしてログインを拒否しています。

- `navigator.webdriver` プロパティが `true` になっている
- ブラウザのUser-Agentに自動化ツールの痕跡がある
- Chrome DevTools Protocol（CDP）経由での操作を検出している

:::warning
この制限はセキュリティ上の理由から設けられており、Googleの利用規約に抵触する可能性があります。個人のGoogleアカウントで自動ログインを試みる際は注意が必要です。
:::

## 対処方法

### 方法1: User-Agentの変更

Playwright起動時に通常のブラウザと同じUser-Agentを設定します。

```javascript
const { chromium } = require('playwright');

const browser = await chromium.launch({
  headless: false,
});

const context = await browser.newContext({
  userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
});
```

### 方法2: webdriverフラグの無効化

CDPを使って `navigator.webdriver` フラグを削除します。

```javascript
const page = await context.newPage();

// webdriverフラグを削除
await page.addInitScript(() => {
  Object.defineProperty(navigator, 'webdriver', {
    get: () => undefined
  });
});
```

### 方法3: 既存のChromeプロファイルを使用

新規プロファイルではなく、通常使用しているChromeのプロファイルを指定します。

```javascript
const browser = await chromium.launchPersistentContext(
  'C:/Users/ユーザー名/AppData/Local/Google/Chrome/User Data',
  {
    headless: false,
    args: ['--profile-directory=Default']
  }
);
```

:::note
この方法では既存のクッキーやログイン状態が引き継がれるため、すでにログイン済みの状態であれば問題が発生しにくくなります。
:::

## 推奨されるアプローチ

:::conclusion
Googleの自動化検出を回避する最も確実な方法は、既存のChromeプロファイルを使用するか、Google API（OAuth 2.0）を使用して認証することです。
:::

Webスクレイピングや自動化が必要な場合は、Googleログインを回避する設計を検討してください。例えば以下のような方法があります。

- Google API（OAuth 2.0）を使用する
- 事前に手動でログインしたクッキーを保存・再利用する
- Googleサービスの操作が必要な場合は、公式APIを利用する

---

## まとめ

- Playwright等の自動化ツールからGoogleログインはセキュリティ上ブロックされる
- User-Agent変更やwebdriverフラグの削除で回避可能な場合もある
- 確実な方法は既存プロファイルの使用か、Google APIの利用

技術的には回避可能ですが、Googleの利用規約を遵守し、適切な方法で自動化を行うようにしてください。
