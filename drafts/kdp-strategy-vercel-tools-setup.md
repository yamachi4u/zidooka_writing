---
title: "KDP戦略分析とVercelでのニッチツール開発・DNS設定トラブルシューティング"
date: 2026-01-02 15:00:00
categories: 
  - KDP
  - vercel
  - journal
tags: 
  - KDP
  - Next.js
  - Vercel
  - DNS
  - Muumuu Domain
  - Strategy
status: publish
slug: kdp-strategy-vercel-tools-setup
featured_image: ../images/202601/image copy 2.png
---

本日は、KDP（Kindle Direct Publishing）の売上分析と今後の戦略策定、そして新たな収益源としての「ニッチツール開発」に向けたVercel環境の構築を行いました。その過程での気づきと、DNS設定でのトラブルシューティングを記録します。

## 1. KDP売上分析と勝ち筋の特定

過去5年間のKDP実績（既読KENP）を分析した結果、明確な「勝ちパターン」が見えてきました。

:::note
**分析結果の要点:**
- **総KENP:** 約26万（年平均5.2万）
- **成功要因:** 「語学 × フラッシュカード × Kindle Unlimited（KU）」の組み合わせ
- **現状:** 2024年後半からベースラインが上昇し、構造的にKENPが発生する状態に入っている
:::

特に「TOEIC」「簿記」といった明確な需要があり、かつ「1000語」「1問1答」のように数字で区切られたスマホ学習向けのフォーマットが強みであることが判明しました。

### 今後の戦略：TOEICシリーズの量産

月3万円の収益を目指し、以下の戦略を実行します。

1.  **既存資産の再編集:** 既存の「TOEIC英単語1000」などをPart別、スコア別に分解・再構成する。
2.  **薄利多売モデル:** 1冊で大きく当てるのではなく、小分けにしたシリーズ（例：Part5特化、通勤5分版など）を展開し、面を広げる。
3.  **表紙はテンプレート化:** デザインに時間をかけず、統一感のあるシンプルな表紙で量産速度を優先する。

## 2. Vercel × Next.js によるニッチツール開発

KDPと並行して、ブラウザ完結型のニッチな計算機やツールを量産し、AdSense収益を狙う計画を始動しました。

### 技術スタックと構成

- **Framework:** Next.js (App Router)
- **Hosting:** Vercel (Hobby Plan)
- **Repository:** GitHub (モノレポ構成)
- **Domain:** `tools.zidooka.com` (サブドメイン運用)

:::step
**開発フロー:**
1. `create-next-app` でプロジェクト作成
2. `app/tool-name/page.tsx` を作成（1ツール = 1ディレクトリ）
3. GitHubへプッシュ → Vercelへ自動デプロイ
:::

この構成により、「思いついたら即公開」が可能になります。サーバーサイド処理を持たず、クライアントサイド（React）のみで完結させることで、保守コストを最小限に抑えます。

## 3. ムームードメイン × Vercel のDNS設定トラブル

Vercelでカスタムドメイン `tools.zidooka.com` を設定する際、DNS設定で少し躓きました。

### 発生したエラー
Vercel側で `Invalid Configuration` が続き、DNSレコードが正しく認識されない状態でした。

### 原因と解決策
ムームードメインの「ムームーDNS」設定において、CNAMEレコードの記述方法に注意が必要でした。

:::warning
**ムームーDNS設定時の注意点:**
Vercelから提示されるCNAMEの値（例: `cname.vercel-dns.com`）に対し、**末尾にドット（.）をつけてはいけません**。
:::

多くのDNS管理画面では末尾にドットが必要な場合がありますが、ムームーDNSの「セットアップ情報変更」画面では、末尾のドットを自動補完する仕様のようです。手動でドットをつけるとエラー（または不正なレコード）となります。

**正しい設定手順:**
1. ムームードメインのコントロールパネルで「DNSレコード設定」を開く。
2. **サブドメイン:** `tools`
3. **種別:** `CNAME`
4. **内容:** `cname.vercel-dns.com` （**末尾のドットなし**）
5. 設定を追加し、数分〜数十分待つ。

これにより、Vercel側で `Valid Configuration` となり、SSL証明書も発行されました。

## 4. 今後のアクション

- **KDP:** TOEIC Part5特化本の作成（既存コンテンツの再編集）
- **Dev:** `tools.zidooka.com` に最初のツール（Pomodoro Calculatorなど）をデプロイ
- **SEO:** ツールサイトへのインデックス登録とAdSense審査の準備

:::conclusion
既存のSEO流入とKDP資産、そして新規のツール開発を組み合わせることで、相互に送客し合う「収益エコシステム」の構築を目指します。
:::
