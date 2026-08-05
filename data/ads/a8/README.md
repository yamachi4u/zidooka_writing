# A8.net 成果データ置き場

月1回、A8管理画面 → レポート → 成果報酬 からCSVをダウンロードして
`YYYY-MM.csv` の名前でこのディレクトリに置く（例: `2026-07.csv`）。

目的: AdSenseとA8を同じ物差し（プレースメント別の収益効率）で比較できるようにする。
運用ルールの詳細は `docs/ADS_MANAGEMENT.md` を参照。

チェックリスト（月次）:
1. CSVをここに保存
2. `npm run ads:check` でA8リンクの死活確認
3. 発生ゼロが2ヶ月続いた案件は差し替え候補（Ads Settings の a8_offers で status: paused に）
