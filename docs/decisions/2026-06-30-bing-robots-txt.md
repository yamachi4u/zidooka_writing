---
created: 2026-06-30
status: pending
verify_date: 2026-07-14
title: Bing クロールエラー対策 — robots.txt 最適化
---

## 判断
Bing のクロールエラー率が慢性的に 40-60% に達している。
原因は WordPress 内部パス（wp-json, attachments, search）のクロール。
robots.txt を改善して無駄なクロールを削減する。

## 根拠
- Bing crawl 44日間のデータで常に 40-60% エラー率（改善ループレポート確認済み）
- `/wp-admin/` のみ disallow → `/wp-json/`, `/xmlrpc.php`, 検索結果などが露呈
- エラー URL の多くは 401/403/404 で、コンテンツとしての価値がない

## 改善案（robots.txt）

現在:
```
User-agent: *
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php

Sitemap: https://www.zidooka.com/sitemap.xml
Sitemap: https://www.zidooka.com/sitemap.rss
```

改善後:
```
User-agent: *
Disallow: /wp-admin/
Disallow: /wp-json/
Disallow: /xmlrpc.php
Disallow: /*?s=
Disallow: /*?amp=
Disallow: /archives/*/attachment/
Disallow: /archives/*/trackback/
Allow: /wp-admin/admin-ajax.php

Sitemap: https://www.zidooka.com/sitemap.xml
Sitemap: https://www.zidooka.com/sitemap.rss
```

## 実装方法
1. Lolipop 管理画面 → robots.txt 編集 または
2. WordPress SEO プラグイン (All in One SEO) の robots.txt 編集
3. もしくは `downloads/zidooka-tw/robots.txt` を作成し FTP で配置

## 期待効果
- クロールエラー率 40-60% → 10% 未満
- クロール予算が実コンテンツに集中 → 新着記事のインデックス速度向上

## 検証日
2026-07-14

## 結果（事後記入）
-
