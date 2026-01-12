GA4 イベント定義（CV/クリック等）草案

更新日: 2026-01-12

■ 命名規約
- 英小文字・スネークケース、意味が一意に分かる短い名前
- `event_category` 等のUA的概念は使わず、パラメータで意味付け

■ 主要イベント
- `generate_lead`
  - 用途: お問い合わせ/資料請求/外部フォーム送信
  - パラメータ: `method`（例: 'contact_form_7' | 'wp_form' | 'external'）, `location`（例: 'header' | 'footer' | 'sidebar'）
- `select_content`
  - 用途: ヒーローCTA/記事カード/バナーのクリック
  - パラメータ: `content_type`（'hero_cta' | 'post_card' | 'banner'）, `content_id`（スラッグ/ID）, `position`（1始まりの序数）
- `view_item`
  - 用途: 記事詳細の閲覧（自動計測で足りない場合に手動送信）
  - パラメータ: `item_id`（投稿ID/スラッグ）, `category`, `tag`
- `file_download`
  - 用途: 資料/画像/サンプルのDL
  - パラメータ: `file_name`, `file_ext`, `file_size`

■ 実装（gtag直貼り例）
```html
<script>
// 例: ヒーローCTAクリック
document.addEventListener('click', function(e){
  const a = e.target.closest('a[data-ga-cta]');
  if (!a) return;
  const id  = a.getAttribute('data-ga-id') || '';
  const pos = a.getAttribute('data-ga-pos') || '';
  gtag('event', 'select_content', {
    content_type: 'hero_cta',
    content_id: id,
    position: pos
  });
});
</script>
```

■ 実装（GTM推奨）
- クリックトリガー（属性 `data-ga-*` を条件）→ GA4イベントタグにマッピング
- 送信先は本番プロパティの計測ID

■ 検証
- GA4 DebugViewでイベント流入を確認
- 重要CVはConversionsに昇格

■ ドキュメント化
- イベント名/発火条件/パラメータ/配置箇所/運用者を本ファイルで管理