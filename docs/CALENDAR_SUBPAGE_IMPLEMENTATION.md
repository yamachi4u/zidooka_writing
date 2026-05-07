# カレンダー下層ページ GA4 実装手順書

## 概要

本ドキュメントは `tools.zidooka.com`（Next.js）のカレンダー下層ページに GA4 カスタムイベントを実装する手順をまとめたものです。

## ファイル構成

| ファイル | 役割 |
|---|---|
| `src/calendar/gtag-events.js` | gtag カスタムイベント送信ユーティリティ |
| `scripts/ga4-admin-setup.mjs` | GA4 管理画面へキーイベントを自動登録する CLI |

---

## 1. gtag-events.js の組み込み方（Next.js）

### 1.1 ファイル配置

`src/calendar/gtag-events.js` を `tools.zidooka.com` のソースリポジトリの同じパスに配置してください。

```
lib/gtag-calendar.js   (または任意のパス)
```

### 1.2 月別ビュー（/jp/calendar/YYYY/MM）での使い方

```tsx
import {
  trackPurposeFilterUsed,
  trackDateDetailOpen,
  trackDateConfirmed,
  markDateOpened,
  incrementDateOpenCount,
  trackRecommendationCardClick,
  trackNextActionLinkClick,
} from '@/lib/gtag-calendar';

// --- 用途フィルタ変更時 ---
function onFilterChange(purpose) {
  trackPurposeFilterUsed(purpose);
}

// --- 日付セルクリック時 ---
function onDateClick(date, luckydayInfo) {
  markDateOpened(date);
  incrementDateOpenCount();

  trackDateDetailOpen({
    date,
    luckydayCount: luckydayInfo.count,
    overlapScore: luckydayInfo.overlapScore,
    luckydayTypes: luckydayInfo.types,
  });

  if (luckydayInfo.overlapScore >= 2) {
    trackOverlapDetailView({
      date,
      overlapScore: luckydayInfo.overlapScore,
      luckydayTypes: luckydayInfo.types,
    });
  }
}

// --- 日付詳細を閉じる or 30秒経過時 ---
function onDateDetailClose(date, luckydayInfo, purpose) {
  markDateConfirmed({
    date,
    luckydayTypes: luckydayInfo.types,
    purpose,
  });
}

// --- 推奨日カードクリック時 ---
function onRecommendationCardClick(date, rank, purpose) {
  trackRecommendationCardClick({ date, rank, purpose });
}

// --- 「次のアクション」リンククリック時 ---
function onNextActionClick(actionType, situation, targetUrl) {
  trackNextActionLinkClick({ actionType, situation, targetUrl });
}
```

### 1.3 個別解説ページ（/jp/calendar/taian 等）での使い方

```tsx
import {
  trackGlossaryEntry,
  trackSituationSelected,
  trackGlossaryScroll,
  trackGuideScrollCompletion,
  trackGlossaryCtaClick,
} from '@/lib/gtag-calendar';

// --- ページ読み込み時（来訪経路に応じて） ---
useEffect(() => {
  trackGlossaryEntry({
    entryFrom: 'calendar_cell', // or 'external_search'
    luckydayType: 'taian',
  });
}, []);

// --- シチュエーション選択ボタンクリック時 ---
function onSituationClick(situation) {
  trackSituationSelected({
    luckydayType: 'taian',
    situation, // 'marriage' | 'moving' | 'business' | 'other'
  });
}

// --- スクロール深度計測（IntersectionObserver） ---
useEffect(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const depth = Number(entry.target.dataset.scrollDepth);
          trackGlossaryScroll({ luckydayType: 'taian', depthPercent: depth });
        }
      });
    },
    { threshold: 0.5 }
  );

  [25, 50, 75, 100].forEach((depth) => {
    const el = document.querySelector(`[data-scroll-depth="${depth}"]`);
    if (el) observer.observe(el);
  });

  return () => observer.disconnect();
}, []);

// --- ガイド最下部到達時 ---
function onGuideComplete(timeOnGuideSeconds) {
  trackGuideScrollCompletion({
    luckydayType: 'taian',
    situation: selectedSituation,
    timeOnGuideSeconds,
  });
}

// --- カレンダーに戻る CTA クリック時 ---
function onCtaClick(targetPath) {
  trackGlossaryCtaClick({
    luckydayType: 'taian',
    targetPath,
  });
}
```

---

## 2. GA4 管理画面へのキーイベント登録

### 2.1 事前確認

サービスアカウントに対し、対象 GA4 プロパティで **編集者** 以上のロールが付与されていることを確認してください。

### 2.2 実行（dry-run で確認）

```bash
npm run ga4:admin-setup -- --dry-run
```

### 2.3 実行（本番）

```bash
npm run ga4:admin-setup
```

### 2.4 package.json への追記

```json
{
  "scripts": {
    "ga4:admin-setup": "node scripts/ga4-admin-setup.mjs"
  }
}
```

---

## 3. イベント一覧（管理画面で確認する名前）

| イベント名 | 用途 | キーイベント登録 |
|---|---|---|
| `calendar_purpose_filter_used` | 用途フィルタ使用計測 | なし |
| `calendar_date_detail_open` | 日付詳細表示計測 | なし |
| `calendar_overlap_detail_view` | 重複日詳細表示計測 | なし |
| `calendar_date_confirmed` | 日取り決定の代理指標 | **あり** |
| `calendar_share` | 共有行動計測 | **あり** |
| `calendar_next_action_link_click` | 次アクション導線計測 | **あり** |
| `calendar_situation_selected` | シチュエーション選択計測 | なし |
| `calendar_guide_scroll_completion` | ガイド完読計測 | なし |
| `calendar_date_compared_3plus` | 迷いの否定的シグナル | なし |
| `calendar_filter_switched_3plus` | フィルタ迷いの否定的シグナル | なし |

---

## 4. 動作確認

### 4.1 開発環境

`gtag-events.js` は `gtag` が存在しない場合、開発環境では `console.log` にフォールバックします。ブラウザの DevTools Console でイベント名とパラメータが出力されることを確認してください。

### 4.2 GA4 リアルタイムレポート

イベント送信後、GA4 管理画面の **リアルタイム** レポートに反映されるまで最大 1 分程度かかる場合があります。

### 4.3 DebugView（推奨）

Next.js 開発サーバー起動時に以下を実行すると GA4 DebugView で個別イベントを確認できます：

```tsx
// _app.tsx または gtag 初期化コード
if (typeof window !== 'undefined') {
  window.gtag('config', 'G-VNF3D5QY6E', { debug_mode: true });
}
```

---

## 5. 推奨日カードの実装例（参考）

```tsx
function RecommendationCard({ month, purpose, recommendations }) {
  useEffect(() => {
    // IntersectionObserver で表示計測
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          trackRecommendationCardView({ month, purpose });
        }
      },
      { threshold: 0.5 }
    );
    observer.observe(ref.current);
    return () => observer.disconnect();
  }, [month, purpose]);

  return (
    <div className="recommendation-card">
      {recommendations.map((rec, index) => (
        <div
          key={rec.date}
          onClick={() =>
            trackRecommendationCardClick({
              date: rec.date,
              rank: index + 1,
              purpose,
            })
          }
        >
          <span>{rec.rankLabel}</span>
          <span>{rec.date}</span>
          <span>{rec.luckydaySummary}</span>
          <a
            href={rec.nextActionUrl}
            onClick={(e) => {
              trackNextActionLinkClick({
                actionType: rec.actionType,
                situation: purpose,
                targetUrl: rec.nextActionUrl,
              });
            }}
          >
            {rec.nextActionLabel}
          </a>
        </div>
      ))}
    </div>
  );
}
```

---

## 6. 注意事項

- **トップページ（`/jp/calendar`）は別エージェント管轄** — 本実装の対象外
- **AdSense はツールページに設置しない** — 現行ポリシー維持
- **キーイベント登録には `analytics.edit` スコープが必要** — サービスアカウントの権限を確認
- **イベント名は GA4 で一度登録すると変更が困難** — 本番投入前に dry-run で検証

---

## 関連ドキュメント

- `drat/20260507_calendar_subpage_deep_dive.md` — 痛みの根本原因分析
- `drat/20260507_calendar_subpage_events_plan.md` — GA4 イベント設計詳細
- `drat/20260507_calendar_subpage_monetization_plan.md` — 収益化の土台設計
- GitHub Issue #1 — 本改修のマスター Issue
