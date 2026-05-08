# カレンダー下層ページ SEO 最適化（Meta / 構造化データ）

## 対象ページ

- 月別ビュー: `https://tools.zidooka.com/jp/calendar/YYYY/MM`
- 年別ビュー: `https://tools.zidooka.com/jp/calendar/YYYY`
- 個別吉日解説: `https://tools.zidooka.com/jp/calendar/taian` など
- TOP: `https://tools.zidooka.com/jp/calendar`（別エージェントと調整が必要な場合あり）

## 方針

- **ユーザー画面に新しい要素は増やさない**
- `<title>` / `<meta name="description">` / `<script type="application/ld+json">` のみ変更
- 構造化データは Google がリッチスニペットに使用する可能性のある `FAQPage` と `HowTo` を採用

---

## 1. Meta Tag 最適化

### 1.1 月別ビュー（/jp/calendar/2026/05）

| 項目 | 現状（推定） | 改善後 |
|---|---|---|
| `<title>` | `2026年5月のカレンダー｜吉日カレンダー` | `2026年5月の吉日カレンダー｜大安・一粒万倍日・天赦日が一目でわかる｜ZIDOOKA` |
| `<meta name="description">` | （JSレンダリングのため不定 or 汎用） | `2026年5月の吉日・大安・一粒万倍日・天赦日を一覧で確認。引越しや結婚式など用途別フィルタで最適な日取りがすぐ見つかります。` |

**改善の意図**:
- タイトルに主要な検索キーワード（大安・一粒万倍日・天赦日）を含める
- 「一目でわかる」「用途別フィルタ」で検索意図に応える
- 末尾にブランド名で信頼性を担保

### 1.2 年別ビュー（/jp/calendar/2026）

| 項目 | 改善後 |
|---|---|
| `<title>` | `2026年の吉日カレンダー｜一粒万倍日・大安・天赦日一覧｜ZIDOOKA` |
| `<meta name="description">` | `2026年の吉日カレンダー。一粒万倍日・大安・天赦日・寅の日などを月別に一覧表示。重要な予定の日取り選びに。` |

### 1.3 個別吉日解説（/jp/calendar/taian）

| 項目 | 改善後 |
|---|---|
| `<title>` | `大安とは？意味・由来・結婚式や引越しに選ばれる理由｜ZIDOOKA` |
| `<meta name="description">` | `大安（たいあん）は六曜の中で最も縁起の良い日。結婚式や引越しに選ばれる理由、他の吉日との違い、2026年の大安の日を解説。` |

### 1.4 TOPページ（/jp/calendar）

| 項目 | 改善後 |
|---|---|
| `<title>` | `吉日カレンダー｜大安・一粒万倍日・天赦日がすぐわかる｜ZIDOOKA` |
| `<meta name="description">` | `無料の吉日カレンダー。大安・一粒万倍日・天赦日を月別・年別で一覧表示。引越し・結婚式・開業など用途別に最適な日取りが見つかります。` |

---

## 2. 構造化データ（JSON-LD）

### 2.1 月別ビュー: `FAQPage` スキーマ

月別ビューには「この月のよくある質問」を JSON-LD で埋め込む。
ユーザー画面には FAQ セクションを追加しない（= 既存コンテンツから自動生成）。

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "2026年5月の大安はいつ？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "2026年5月の大安は5月8日（木）、5月15日（木）、5月22日（木）です。"
      }
    },
    {
      "@type": "Question",
      "name": "2026年5月に一粒万倍日はある？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "2026年5月の一粒万倍日は5月1日（金）、5月15日（木）、5月28日（水）です。"
      }
    },
    {
      "@type": "Question",
      "name": "2026年5月で大安と一粒万倍日が重なる日は？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "2026年5月15日（木）は大安と一粒万倍日が重なる縁起の良い日です。"
      }
    }
  ]
}
```

**実装方法**:
- 月別データから動的に生成（API レスポンスから `大安の日一覧`、`一粒万倍日一覧`、`重複日一覧` を抽出）
- `<script type="application/ld+json">` として `<head>` 内に挿入
- FAQ 項目数は 3〜5 問を目安

### 2.2 個別吉日解説: `HowTo` スキーマ

大安・一粒万倍日などの解説ページに「この吉日を選んで予定を立てる手順」を JSON-LD で埋め込む。

```json
{
  "@context": "https://schema.org",
  "@type": "HowTo",
  "name": "大安の日に結婚式の日取りを決める方法",
  "description": "大安は六曜の中で最も縁起の良い日。結婚式の日取りを大安に合わせる手順を解説します。",
  "totalTime": "PT10M",
  "step": [
    {
      "@type": "HowToStep",
      "name": "大安の日をカレンダーで確認",
      "text": "ZIDOOKAの吉日カレンダーで、結婚式を考えている月の大安の日を確認します。",
      "url": "https://tools.zidooka.com/jp/calendar/2026/05",
      "image": {
        "@type": "ImageObject",
        "url": "https://tools.zidooka.com/og-image-calendar.png"
      }
    },
    {
      "@type": "HowToStep",
      "name": "式場の空き状況を確認",
      "text": "大安の日が式場の予約可能日と重なるか、式場に問い合わせます。"
    },
    {
      "@type": "HowToStep",
      "name": "大安と他の吉日が重なる日を優先",
      "text": "大安と一粒万倍日や天赦日が重なる日があれば、さらに縁起が良い日取りとなります。"
    }
  ]
}
```

**実装方法**:
- ページの「シチュエーション別ガイド」（改修後の機能）から動的に生成
- シチュエーション（結婚式 / 引越し / 開業）ごとに `HowTo` を切り替え
- 改修前は固定の `HowTo`（上記のように一般的な手順）を設置しても可

### 2.3 全ページ共通: `WebSite` スキーマ（サイトリンク検索ボックス）

```json
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "url": "https://tools.zidooka.com/jp/calendar",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "https://tools.zidooka.com/jp/calendar/{search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
```

※ 検索ボックスの URL テンプレートは実際のカレンダー構造に合わせて調整

---

## 3. 実装チェックリスト

### Next.js (App Router) の場合

```tsx
// app/jp/calendar/[year]/[month]/page.tsx
import { Metadata } from 'next';

export async function generateMetadata({ params }): Promise<Metadata> {
  const { year, month } = params;
  return {
    title: `${year}年${month}月の吉日カレンダー｜大安・一粒万倍日・天赦日が一目でわかる｜ZIDOOKA`,
    description: `${year}年${month}月の吉日・大安・一粒万倍日・天赦日を一覧で確認。引越しや結婚式など用途別フィルタで最適な日取りがすぐ見つかります。`,
    openGraph: {
      title: `${year}年${month}月の吉日カレンダー`,
      description: `${year}年${month}月の大安・一粒万倍日・天赦日を一覧表示`,
      url: `https://tools.zidooka.com/jp/calendar/${year}/${month}`,
    },
  };
}
```

```tsx
// JSON-LD 挿入例
export default function CalendarPage({ params }) {
  const { year, month } = params;
  const jsonLd = generateFaqJsonLd(year, month); // 2.1 の関数

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />
      {/* 既存のカレンダーUI */}
    </>
  );
}
```

### Next.js (Pages Router) の場合

```tsx
// pages/jp/calendar/[year]/[month].tsx
import Head from 'next/head';

export default function CalendarPage({ year, month, faqJsonLd }) {
  return (
    <>
      <Head>
        <title>{year}年{month}月の吉日カレンダー｜大安・一粒万倍日・天赦日が一目でわかる｜ZIDOOKA</title>
        <meta name="description" content={`${year}年${month}月の吉日・大安・一粒万倍日・天赦日を一覧で確認。引越しや結婚式など用途別フィルタで最適な日取りがすぐ見つかります。`} />
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(faqJsonLd) }}
        />
      </Head>
      {/* 既存のカレンダーUI */}
    </>
  );
}
```

---

## 4. 検証方法

### 4.1 Google リッチテスト

各ページの URL を以下に入力して構造化データの有効性を確認：
https://search.google.com/test/rich-results

### 4.2 Meta Tag 確認

```bash
# タイトルと description の確認
curl -s https://tools.zidooka.com/jp/calendar/2026/05 | grep -E "<title>|<meta name=\"description\""
```

### 4.3 GA4 での効果測定

Meta Tag 最適化・構造化データ導入後、以下を 2〜4 週間で比較：

| 指標 | 測定方法 |
|---|---|
| CTR 向上 | GSC の「検索パフォーマンス」レポートで導入前後比較 |
| リッチスニペット表示 | Google 検索結果で `site:tools.zidooka.com/jp/calendar` を確認 |
| Organic Search 流入増 | GA4 のランディングページレポートで `/jp/calendar` 系のセッション変化 |

---

## 5. やらないこと

- ページ本文への FAQ セクション追加（JSON-LD のみ）
- ページ本文への HowTo セクション追加（JSON-LD のみ）
- 新しいコンテンツブロックの追加
- 広告（AdSense）の設置

---

## 関連ドキュメント

- `docs/CALENDAR_SUBPAGE_IMPLEMENTATION.md` — GA4 イベント実装手順
- `drat/20260507_calendar_subpage_monetization_plan.md` — 収益化の土台設計
- GitHub Issue #1 — 本改修のマスター Issue
