# カレンダー下層ページ その他の改修案

## 方針

- ユーザー画面に目立つ要素は増やさない
- 技術的・裏側の改善で UX・SEO・分析精度を向上
- すべて GA4 イベントと組み合わせて効果測定可能

---

## 案A: URL State Sync（クエリパラメータ連携）

### 問題

用途フィルタを選択しても URL が変わらないため：
- フィルタ適用状態を他者と共有できない
- ブラウザの「戻る」でフィルタがリセットされる
- GA4 でどのフィルタが人気か URL ベースで分析できない

### 改善

フィルタ変更時に URL を更新：

```
/jp/calendar/2026/05?purpose=moving
/jp/calendar/2026/05?purpose=marriage
/jp/calendar/2026/05?filter=taian,ichiryumanbai
```

### 効果

- ユーザー同士で「引越し向けの日」が共有できる
- ページ離脱後の「戻る」でフィルタが復元される
- GSC / GA4 で `?purpose=` 別のパフォーマンスが分析できる
- 将来的に「引越し 吉日」検索からのランディングをこの URL にできる

### GA4計測

```js
trackPurposeFilterUsed(purpose); // 既存イベント
// + URL 変更により、landingPagePlusQueryString に purpose 含まれる
```

---

## 案B: キーボードナビゲーション

### 問題

Desktop 88% のユーザーのみに影響。カレンダーの日付をマウスでポチポチ移動する必要がある。

### 改善

矢印キーで日付間を移動、Enter で詳細表示：

```
↑ ↓ ← → : カレンダーグリッド内を移動
Enter     : 選択中日付の詳細を開く
Esc       : 詳細を閉じる
T         : 今日の日付へジャンプ
```

### 効果

- アクセシビリティ向上（スクリーンリーダー利用者にも恩恵）
- Desktop ユーザーの操作効率向上
- 視覚的な変更なし（focus ring のみ、CSS で調整）

### GA4計測

```js
// 新規イベント
trackKeyboardNav({ direction: 'ArrowRight', date: selectedDate });
trackTodayJump(); // 既存イベントが発火
```

---

## 案C: サーバーサイドログ（アドブロック対策）

### 問題

GA4 はアドブロックでブロックされるユーザが一定数いる（推定 10〜30%）。フィルタ使用率などの行動データが欠落する。

### 改善

API サーバー側でフィルタ選択・日付クリックをログに記録：

```json
{
  "timestamp": "2026-05-07T12:34:56Z",
  "endpoint": "/api/calendar/2026/05",
  "query": { "purpose": "moving" },
  "referer": "https://www.google.com/...",
  "userAgent": "...",
  "sessionId": "abc123"
}
```

### 効果

- アドブロック環境でも行動データが取得できる
- GA4 データとサーバーログを突合して「計測漏れ率」を算出
- フィルタ使用率などの裏付けデータとして活用

### 注意

- PII（個人識別情報）は記録しない
- ログ保存期間は 90 日程度で十分

---

## 案D: スマートプリロード

### 問題

翌月・前年へのナビゲーション時に API フェッチが発生し、待ち時間が生じる。

### 改善

ユーザーの閲覧パターンを予測して先読み：

```
優先順位:
1. 翌月データ（次に見る確率が最も高い）
2. 前年同月データ（年越し検索に備える）
3. 現在の月の「個別吉日解説ページ」（日付クリック後の遷移先）
```

実装: `requestIdleCallback` or `setTimeout(低優先)` でバックグラウンドフェッチ

### 効果

- ナビゲーションの体感速度向上
- Core Web Vitals の INP（Interaction to Next Paint）改善
- サーバー負荷は閑散時に分散される

### GA4計測

```js
// プリロードのヒット率を計測
navigateToNextMonth(); // プリロード済みならキャッシュヒット
trackCacheHit({ type: 'preload', target: '2026/06' });
```

---

## 案E: Feature Flag 基盤（A/B テスト用）

### 問題

推奨日カードの「どのロジックが最適か」は現段階では不明。デプロイごとにコード変更するのは非効率。

### 改善

簡易的な Feature Flag を導入：

```js
// config/flags.js
const FLAGS = {
  recommendationAlgorithm: 'v1', // 'v1' | 'v2' | 'v3'
  showOverlapScore: true,
  nextActionLinkPosition: 'bottom', // 'bottom' | 'inline'
};

// 使用例
const recs = FLAGS.recommendationAlgorithm === 'v2'
  ? calculateRecommendationsV2(data)
  : calculateRecommendationsV1(data);
```

### 効果

- 推奨アルゴリズムをデプロイなしで切り替え可能
- A/B テストを小規模に実施してから全量展開
- 収益化導線の配置（位置・文言）を最適化できる

### GA4計測

```js
// 全イベントに flag バージョンを付与
sendEvent('calendar_date_confirmed', {
  ...params,
  flag_rec_algo: FLAGS.recommendationAlgorithm,
  flag_link_pos: FLAGS.nextActionLinkPosition,
});
```

---

## 案F: Print-Friendly CSS

### 問題

ユーザーが「カレンダーを印刷して冷蔵庫に貼る」ユースケースがある可能性。現状の UI は印刷に不向き（ナビゲーション・フィルタ UI が余分に出る）。

### 改善

```css
@media print {
  .filter-panel,
  .month-nav,
  .share-buttons,
  footer {
    display: none !important;
  }

  .calendar-grid {
    page-break-inside: avoid;
  }

  .luckyday-label {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}
```

### 効果

- 「印刷して持ち歩く」ニーズに対応（特に高齢層に有効か）
- 視覚的変更なし（印刷時のみ適用）
- ブランド露出の機会増（印刷物に ZIDOOKA ロゴが残る）

### GA4計測

```js
window.addEventListener('beforeprint', () => {
  sendEvent('calendar_print', { month: currentMonth });
});
```

---

## 優先順位の提案

| 順位 | 案 | コスト | 効果 | 理由 |
|---|---|---|---|---|
| 1 | **A: URL State Sync** | 小 | 大 | 共有・SEO・分析の3方で効果。実装も軽い |
| 2 | **E: Feature Flag** | 小 | 大 | 推奨カード導入後の改善ループを高速化 |
| 3 | **C: サーバーサイドログ** | 小 | 中 | GA4 の穴埋め。10%のデータ差は運用判断に影響 |
| 4 | **D: スマートプリロード** | 中 | 中 | UX 向上。ただしモバイルの通信量には注意 |
| 5 | **B: キーボードナビ** | 小 | 小 | アクセシビリティ重視なら優先。収益直結ではない |
| 6 | **F: Print-Friendly CSS** | 極小 | 小 | 1行の CSS。やらない理由もない |

---

## まとめ

推奨カードやアフィリエイト導線は「表層」の改善。こちらは「裏側」の改善。両方を組み合わせることで、ユーザー体験・SEO・データ品質・運用効率の4軸で相乗効果が出る。
