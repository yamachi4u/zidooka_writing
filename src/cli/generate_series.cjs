const fs = require('fs');
const path = require('path');

const baseDir = 'drafts/clasp-ai-gas';
const imagePath = '../../images/clasp-ai-gas-thumb.png';
const date = new Date().toISOString(); // Publish immediately

const episodes = require('./series_data.cjs');

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

