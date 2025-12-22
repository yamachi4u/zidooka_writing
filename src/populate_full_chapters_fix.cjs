const fs = require('fs');
const path = require('path');
const baseDir = path.join(process.cwd(), 'drafts', 'clasp-ai-gas');
const files = fs.readdirSync(baseDir).filter(f => f.endsWith('.md'));

const templates = {
  intro: {
    jp: "このシリーズは、GASを仕事で使えるようにするための実践ガイドです。各章で手順と設計を学べます。",
    en: "This series is a practical guide to using GAS for real work. Each chapter teaches steps and design."
  },
  ch00: {
    jp: "GASはGoogleサービスを自動化するためのJavaScript実行環境です。claspはそのコードをローカルで管理し、GASプロジェクトと同期するツールです。ブラウザとclaspの違い、利点を具体例で説明します。",
    en: "GAS is a JavaScript runtime to automate Google services. clasp lets you manage code locally and sync with GAS projects. This chapter shows differences and benefits with examples."
  },
  ch01: {
    jp: "ローカル/クラウド、ターミナル、コマンドの扱い方など、基礎用語と心構えを説明します。最初は手を動かすことを優先してください。",
    en: "Covers basic terms and mindset: local vs cloud, terminal basics, and treating commands as operations. Prioritize doing over perfect understanding."
  },
  ch02: {
    jp: "Node.jsとnpmの導入、claspのインストール手順を実践的に示します。主要なコマンド例も掲載。",
    en: "Practical steps to install Node.js, npm, and clasp. Includes key commands and troubleshooting tips."
  },
  ch03: {
    jp: "`clasp login`のOAuthフローや権限管理、よくあるエラーとその対処法を扱います。複数アカウント対策も解説。",
    en: "Explains clasp login OAuth flow, permission scopes, common errors and fixes, and multi-account handling."
  },
  ch04: {
    jp: "最初のプロジェクトを作って実行するまでを手順で示します。`clasp create`の使い方、生成ファイルの意味を説明。",
    en: "Step-by-step: create your first project and run it. How to use clasp create and what generated files mean."
  },
  ch05: {
    jp: "Code.gsを編集して実行、Loggerの使い方、実行方法（エディタ/ローカル）を説明します。サンプルコード付。",
    en: "Edit Code.gs and run it. Use Logger, run from editor or locally. Includes sample code."
  },
  ch06: {
    jp: "`clasp push`と`clasp pull`の違い、運用ルール、よくある事故例（上書き・コンフリクト）と対策を紹介します。",
    en: "Explain clasp push vs pull, recommended workflow, and common mistakes like overwrites and conflicts, with mitigations."
  },
  ch07: {
    jp: "業務での具体的な利用例（スプレッドシート自動化、メール送信、集計）を紹介し、設計のヒントを示します。",
    en: "Practical use cases: Sheets automation, scheduled emails, aggregation. Design tips for production usage."
  },
  ch08: {
    jp: "初心者がはまりやすいポイント集：実行権限、実行時間、壊れた時の調査手順などを整理します。",
    en: "Common pitfalls: permission errors, execution time limits, debugging when something breaks. Practical fixes included."
  },
  ch09: {
    jp: "次に学ぶべきこと：複数ファイル構成、Git、テスト、AI活用、受注や内製化の進め方を提案します。",
    en: "Next steps: multi-file projects, Git, tests, AI assistance, and how to turn skills into paid work or internal automation."
  }
};

files.forEach(file => {
  try {
    const s = fs.readFileSync(path.join(baseDir, file), 'utf8');
    const m = s.match(/^(---[\s\S]*?---)\s*/);
    const fm = m ? m[1] : '';
    const rest = m ? s.slice(m[0].length) : s;

    // Determine chapter and lang
    const lang = file.endsWith('-en.md') ? 'en' : 'jp';
    let body = '';
    if (file.includes('intro')) body = templates.intro[lang];
    else {
      const ch = file.match(/(\d{2})-ch(\d{2})-(jp|en)\.md$/);
      if (ch) {
        const idx = parseInt(ch[2], 10);
        const key = 'ch' + idx.toString().padStart(2, '0');
        body = templates[key] ? templates[key][lang] : templates['ch00'][lang];
      } else {
        body = templates['ch00'][lang];
      }
    }

    // Build new content: frontmatter + body + existing nav (preserve last section starting '## 連載目次' or '## Series Index')
    const navIndex = rest.indexOf('\n## ');
    const nav = navIndex >= 0 ? rest.slice(navIndex) : '\n\n';
    const newFile = fm + '\n\n' + body + '\n\n' + nav;
    fs.writeFileSync(path.join(baseDir, file), newFile, 'utf8');
    console.log('Updated', file);
  } catch (e) {
    console.error('Failed', file, e.message);
  }
});
