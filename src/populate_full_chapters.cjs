const fs = require('fs');
const path = require('path');
const baseDir = path.join(process.cwd(), 'drafts', 'clasp-ai-gas');
const files = fs.readdirSync(baseDir).filter(f => f.endsWith('.md'));

function readFrontmatter(file) {
  const s = fs.readFileSync(path.join(baseDir, file), 'utf8');
  const m = s.match(/^(---[\s\S]*?---)\s*/);
  return { fm: m ? m[1] : '', rest: m ? s.slice(m[0].length) : s };
}

function contentFor(slug, lang) {
  // minimal bodies per slug
  const isJp = lang === 'jp';
  switch (slug) {
    case 'ch00':
      return isJp ? `この章ではGASとclaspの役割をさらに具体例で説明します。

GASは業務でよく使う"スプレッドシートの自動化"や"定期メール送信"などに向く軽量スクリプトです。簡単な処理なら数行で実現できます。

claspを使うメリットは、ローカルで編集→バージョン管理→デプロイが可能になることです。複数人での開発や、履歴管理、静的解析、テスト導入がやりやすくなります。

次章から端末操作やインストールに進みます。` : `This chapter expands on GAS and clasp with practical examples.

GAS excels at automating Sheets, sending scheduled emails, and simple workflows. A few lines can automate repetitive tasks.

Using clasp lets you edit locally, version-control your code, and deploy from your machine—making team workflows and testing possible.

Next chapter we cover terminal basics and setup.`;
    case 'ch01':
      return isJp ? `この章は用語と心構えの整理です。ローカル／クラウド、ターミナルの基本、コマンドは"操作"であることを体験的に説明します。

ポイント：失敗しても学べる、最初は理解より慣れを重視して進めてください。` : `This chapter covers essential terms and mindset: local vs cloud, the terminal, and treating commands as operations. Focus on learning by doing; it's okay to not understand everything at first.`;
    case 'ch02':
      return isJp ? `ここではNode.js、npm、claspのインストール手順をスクリーンショットやコマンド付きで示します。

主な手順：

1. Node.jsを公式サイトからインストール
2. ターミナルで` + "`npm install -g @google/clasp`" + ` を実行
3. バージョン確認：` + "`clasp -v`" + `

問題が出たらノードのPATHや権限を確認してください。` : `This chapter provides step-by-step installation of Node.js, npm and clasp with commands:

1. Install Node.js from the official site
2. Run ` + "`npm install -g @google/clasp`" + `
3. Check version: ` + "`clasp -v`" + `

Troubleshoot PATH and permissions if needed.`;
    case 'ch03':
      return isJp ? `clasp login の仕組みとブラウザ連携、OAuthの許可画面の意味を説明します。

トラブル例：権限不足や複数アカウントでのログイン混在。対処法：` + "`clasp login --no-localhost`" + ` やキャッシュ消去を試してください。` : `Explain how ` + "`clasp login`" + ` works (OAuth flow), browser opening, and permission scopes. Troubles: multi-account conflicts; try ` + "`clasp login --no-localhost`" + ` or clearing credentials.`;
    case 'ch04':
      return isJp ? `最速で「作る→動かす」を体験します。

手順：作業フォルダ作成 → ` + "`clasp create --type sheets --title my-app`" + ` → 生成ファイル確認 → ` + "`.clasp.json`" + ` の確認。

成功体験を優先して、まずは1つのスクリプトを動かしましょう。` : `Hands-on: create a project and run it.

Commands: make folder → ` + "`clasp create --type sheets --title my-app`" + ` → inspect generated files and `.clasp.json`. Get the joy of "it worked" quickly.`;
    case 'ch05':
      return isJp ? `Code.gsを編集して簡単な関数を実行します。

例：` + "`function hello(){ Logger.log('hello'); }`" + ` を実行し、ログを確認する流れ。

実行はエディタかGASの実行メニュー、あるいは` + "`clasp run`" + ` を利用します。` : `Edit Code.gs and run a simple function, e.g. ` + "`function hello(){ Logger.log('hello'); }`" + `. Run via editor or ` + "`clasp run`" + ` and check logs.`;
    case 'ch06':
      return isJp ? `push/pullの意味とよくある事故（上書きやコンフリクト）について説明します。

ルール例：
- ローカルで編集→テスト→` + "`clasp push`" + `
- 他人の変更がある場合は` + "`clasp pull`" + ` から始める

Gitとの合わせ技も推奨します。` : `Explain push/pull meaning and common mishaps (overwrites, conflicts).

Rules: edit locally → test → ` + "`clasp push`" + `. If others changed, ` + "`clasp pull`" + ` first. Use Git together.`;
    case 'ch07':
      return isJp ? `業務での活用例を紹介します：スプレッドシート自動化、定期メール、フォーム集計など。構成例と簡単なコードスニペットを示します。

目的は「これが仕事になる」と実感してもらうことです。` : `Show practical use cases: Sheets automation, scheduled emails, form aggregation. Provide snippets and architectures so you can see how this becomes real work.`;
    case 'ch08':
      return isJp ? `よくあるトラブル集：

- 実行権限エラー → スコープ確認
- 実行時間制限 → バッチ化やトリガーの分割
- ファイルを削除したら → claspの同期挙動

原因と対処を具体的に示します。` : `Common pitfalls: permission errors (check scopes), execution time limits (batching), removing files (clasp sync behavior). Give causes and fixes.`;
    case 'ch09':
      return isJp ? `次のステップとしては：複数ファイル化、Git管理、テスト、自動デプロイ、AIを使ったコード生成とリファクタリングなどをすすめます。

この連載で学んだことを小さな案件で試すのが最短の道です。` : `Next steps: multi-file projects, Git, tests, CI/CD, AI-assisted code generation and refactoring. Try a small client project to consolidate learning.`;
    default:
      return isJp ? `本文を順次追記します。` : `Content will be expanded.`;
  }
}

files.forEach(file => {
  const { fm } = readFrontmatter(file);
  const m = file.match(/(\d{2})-ch(\d{2})-(jp|en)\.md$/) || file.match(/00-intro-(jp|en)\.md$/);
  let slugKey = '';
  let lang = 'jp';
  if (m) {
    if (m[3]) {
      slugKey = 'ch' + (parseInt(m[2],10)).toString().padStart(2,'0');
      lang = m[3];
    } else {
      // intro
      lang = m[1];
      slugKey = 'intro';
    }
  } else {
    // fallback: detect jp/en
    lang = file.endsWith('-en.md') ? 'en' : 'jp';
  }

  let body = '';
  if (file.includes('intro')) {
    body = lang === 'jp' ? `このシリーズは、GASを仕事で使えるようにするための実践ガイドです。各章で手順と設計を学べます。` : `This series is a practical guide to using GAS for real work. Each chapter teaches steps and design.`;
  } else if (slugKey.startsWith('ch')) {
    const idx = slugKey.replace('ch','');
    body = contentFor('ch' + idx, lang === 'jp' ? 'jp' : 'en');
  } else {
    body = 'Content will be added.';
  }

  const newContent = fm + '\n\n' + body + '\n\n' + fs.readFileSync(path.join(baseDir, file), 'utf8').split('\n').slice(-20).join('\n');
  fs.writeFileSync(path.join(baseDir, file), newContent, 'utf8');
  console.log('Wrote', file);
});
