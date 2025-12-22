const fs = require('fs');
const path = require('path');

const baseDir = 'drafts/clasp-ai-gas';
const imagePath = '../../images/clasp-ai-gas-thumb.png';
const date = new Date().toISOString(); // Publish immediately

const episodes = [
    {
        id: -1, // Intro
        slug: 'clasp-gas-beginner-intro',
        title_jp: '【固定記事】完全初心者のための Clasp × GAS 完全入門（ZIDOOKA！）',
        title_en: '[Pinned] Complete Clasp x GAS Guide for Absolute Beginners (ZIDOOKA!)',
        outline_jp: '連載の全体像と、各章へのリンク。',
        outline_en: 'Overview of the series and links to each chapter.'
    },
    {
        id: 0,
        slug: 'clasp-gas-beginner-ch00',
        title_jp: '第0章：そもそもGASとClaspって何？',
        title_en: 'Chapter 0: What are GAS and Clasp anyway?',
        outline_jp: `
- GASは「Googleサービス専用の自動化言語」
- Claspは「GASをPCで書くための道具」
- ブラウザで書くGASとの違い
- なぜ“あえて”Claspを使うのか
- 👉 まだ何もインストールしない
        `,
        outline_en: `
- GAS is an "automation language dedicated to Google services"
- Clasp is a "tool for writing GAS on a PC"
- Differences from writing GAS in a browser
- Why use Clasp "on purpose"?
- 👉 Don't install anything yet
        `
    },
    {
        id: 1,
        slug: 'clasp-gas-beginner-ch01',
        title_jp: '第1章：Claspを使う前に知っておく最低限のこと',
        title_en: 'Chapter 1: Minimum things to know before using Clasp',
        outline_jp: `
- ローカル / クラウドって何？
- ターミナル（黒い画面）は怖くない
- コマンド＝呪文ではなく「操作」
- この章を読んで分からなくても問題ない理由
- 👉 安心させる章
        `,
        outline_en: `
- What are Local / Cloud?
- The terminal (black screen) is not scary
- Commands = "Operations", not magic spells
- Why it's okay if you don't understand this chapter
- 👉 A chapter to reassure you
        `
    },
    {
        id: 2,
        slug: 'clasp-gas-beginner-ch02',
        title_jp: '第2章：Claspを使うための環境を作る',
        title_en: 'Chapter 2: Setting up the environment for Clasp',
        outline_jp: `
- Node.js とは何か（1分で）
- Node.js のインストール
- npm って何者？
- clasp をインストールする
- clasp -v で確認する
- 👉 スクショ多めゾーン
        `,
        outline_en: `
- What is Node.js (in 1 minute)
- Installing Node.js
- What is npm?
- Installing clasp
- Checking with clasp -v
- 👉 Zone with many screenshots
        `
    },
    {
        id: 3,
        slug: 'clasp-gas-beginner-ch03',
        title_jp: '第3章：GoogleアカウントとClaspをつなぐ',
        title_en: 'Chapter 3: Connecting Google Account and Clasp',
        outline_jp: `
- ClaspがGoogleにアクセスする仕組み
- clasp login の意味
- ブラウザが開く理由
- 権限エラーが出たらどうするか
- 👉 失敗してもOKな理由を明示
        `,
        outline_en: `
- How Clasp accesses Google
- Meaning of clasp login
- Why the browser opens
- What to do if a permission error occurs
- 👉 Explicitly stating why failure is OK
        `
    },
    {
        id: 4,
        slug: 'clasp-gas-beginner-ch04',
        title_jp: '第4章：最初のGASプロジェクトを作ってみる',
        title_en: 'Chapter 4: Creating your first GAS project',
        outline_jp: `
- 作業用フォルダを作る
- clasp create を実行
- 何が生成されたかを確認
- .clasp.json の正体
- 👉 「できた！」を作る章
        `,
        outline_en: `
- Creating a working folder
- Running clasp create
- Checking what was generated
- The identity of .clasp.json
- 👉 A chapter to create "I did it!"
        `
    },
    {
        id: 5,
        slug: 'clasp-gas-beginner-ch05',
        title_jp: '第5章：GASのコードを書いてみる',
        title_en: 'Chapter 5: Writing GAS code',
        outline_jp: `
- Code.gs を開く
- とりあえずコピペでOKなサンプル
- Logger.log とは何か
- 実行はどこからやる？
- 👉 理解より“体験”
        `,
        outline_en: `
- Opening Code.gs
- Sample code that is OK to copy and paste
- What is Logger.log?
- Where to run it?
- 👉 "Experience" over understanding
        `
    },
    {
        id: 6,
        slug: 'clasp-gas-beginner-ch06',
        title_jp: '第6章：push / pull を理解する',
        title_en: 'Chapter 6: Understanding push / pull',
        outline_jp: `
- push とは何か
- pull とは何か
- どっちをいつ使う？
- よくある事故例
- 👉 ここでClaspが分かり始める
        `,
        outline_en: `
- What is push?
- What is pull?
- When to use which?
- Common accident examples
- 👉 Clasp starts to make sense here
        `
    },
    {
        id: 7,
        slug: 'clasp-gas-beginner-ch07',
        title_jp: '第7章：GASを「実際の業務」に使うイメージ',
        title_en: 'Chapter 7: Image of using GAS for "Real Work"',
        outline_jp: `
- スプレッドシート自動化例
- メール送信の例
- 定期実行（トリガー）の存在
- 👉 「これ仕事になるやつだ」
        `,
        outline_en: `
- Spreadsheet automation example
- Email sending example
- Existence of periodic execution (triggers)
- 👉 "This is going to be a job"
        `
    },
    {
        id: 8,
        slug: 'clasp-gas-beginner-ch08',
        title_jp: '第8章：初心者が必ずハマるポイント集',
        title_en: 'Chapter 8: Points where beginners always get stuck',
        outline_jp: `
- 実行権限エラー
- 実行時間制限
- ファイル消したらどうなる？
- なぜ動いてたのに壊れた？
- 👉 EEAT爆上げゾーン
        `,
        outline_en: `
- Execution permission errors
- Execution time limits
- What happens if I delete a file?
- Why did it break when it was working?
- 👉 EEAT boosting zone
        `
    },
    {
        id: 9,
        slug: 'clasp-gas-beginner-ch09',
        title_jp: '第9章：次にやるべきこと',
        title_en: 'Chapter 9: What to do next',
        outline_jp: `
- 複数ファイル構成
- Git管理
- AIにGASを書かせる
- 次に読むべきZIDOOKA！記事
- 👉 道を示して終わる
        `,
        outline_en: `
- Multiple file structure
- Git management
- Letting AI write GAS
- ZIDOOKA! articles to read next
- 👉 End by showing the path
        `
    }
];

const constructionNoticeJp = `
> **⚠️ この記事は現在執筆中です（工事中）**
> 
> 現在、アウトラインのみを公開しています。近日中に詳細な解説を追記・更新する予定です。
> ブックマークしてお待ちください！
`;

const constructionNoticeEn = `
> **⚠️ This article is currently under construction**
> 
> Only the outline is currently available. Detailed explanations will be added and updated soon.
> Please bookmark and wait!
`;

function generateContent(ep, lang) {
    const isJp = lang === 'jp';
    let title = isJp ? ep.title_jp : ep.title_en;
    title = title.replace(/"/g, '\\"'); // Escape quotes
    const outline = isJp ? ep.outline_jp : ep.outline_en;
    const notice = isJp ? constructionNoticeJp : constructionNoticeEn;
    const slug = `${ep.slug}-${lang}`;
    
    let parentField = '';
    if (ep.id >= 0) {
        const parentSlug = `clasp-gas-beginner-intro-${lang}`;
        parentField = `parent: "${parentSlug}"\n`;
    }
    
    // Generate Navigation Links
    let nav = isJp ? '## 連載目次\n\n' : '## Series Index\n\n';
    episodes.forEach(e => {
        const eTitle = isJp ? e.title_jp : e.title_en;
        const eSlug = `${e.slug}-${lang}`;
        if (e.id === ep.id) {
            nav += `- **${eTitle}** (Current)\n`;
        } else {
            // Link to other chapters
            // Since we are generating new files, we can just use the relative path or slug
            // For simplicity in this "draft" phase, we use the filename we are about to generate
            let filename = '';
            if (e.id === -1) {
                filename = `00-intro-${lang}.md`;
            } else {
                filename = `0${e.id}-ch${e.id.toString().padStart(2, '0')}-${lang}.md`;
            }
            nav += `- [${eTitle}](./${filename})\n`;
        }
    });

    return `---
title: "${title}"
slug: "${slug}"
status: "publish"
date: "${date}"
categories: 
  - "Clasp-AI入門"
  - "Tech"
tags: 
  - "GAS"
  - "Clasp"
  - "Beginner"
  - "Automation"
featured_image: "${imagePath}"
${parentField}---

${notice}

# ${title}

${isJp ? 'こんにちは、ZIDOOKAです。' : 'Hello, this is ZIDOOKA.'}

${outline}

${nav}
`;
}

// Clean up old files first? No, user said reuse/rename. 
// But to ensure clean state for this new structure, let's just overwrite/create new.

episodes.forEach(ep => {
    // JP
    const contentJp = generateContent(ep, 'jp');
    let filenameJp = '';
    if (ep.id === -1) {
        filenameJp = `00-intro-jp.md`;
    } else {
        filenameJp = `0${ep.id}-ch${ep.id.toString().padStart(2, '0')}-jp.md`;
    }
    
    fs.writeFileSync(path.join(baseDir, filenameJp), contentJp);
    console.log(`Created ${filenameJp}`);

    // EN
    const contentEn = generateContent(ep, 'en');
    let filenameEn = '';
    if (ep.id === -1) {
        filenameEn = `00-intro-en.md`;
    } else {
        filenameEn = `0${ep.id}-ch${ep.id.toString().padStart(2, '0')}-en.md`;
    }
    
    fs.writeFileSync(path.join(baseDir, filenameEn), contentEn);
    console.log(`Created ${filenameEn}`);
});

