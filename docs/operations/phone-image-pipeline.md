# スマホ添付画像 → GitHub 記事アセット導線（設計）

> Issue #7: スマホ添付画像を記事用アセットとしてGitHubへ受け渡す導線を作る

## 1. ゴール

スマホで ChatGPT に貼ったスクリーンショットを、ZIDOOKA の記事用アセットとして安全かつ再現可能に GitHub へ受け渡す。

```text
スマホで ChatGPT に画像を貼る
   → "この画像を ZIDOOKA の記事素材に使って。issue にして" と明示
   → ChatGPT が Issue #N を作成（本文に用途・alt案・注意点、画像リンクを Markdown 添付）
   → ローカル: node scripts/fetch-issue-images.mjs <N> で取得
   → images/YYYY/<slug>/ へ配置 + _manifest.json
   → 公開前にプライバシーチェック（必須）
   → 記事 Markdown に参照追加 → PR
```

## 2. 画像取得

`scripts/fetch-issue-images.mjs` を実装済み。

- `gh api` で issue 本文＋コメントを取得
- Markdown 画像リンク（`![](...)`）と `private-user-images.githubusercontent.com` の直接 URL を抽出
- 取得して `images/YYYY/<slug>/NN-<name>.<ext>` に保存
- 由来・取得日・alt案を `_manifest.json` に記録

```powershell
node scripts/fetch-issue-images.mjs 7 --slug ai-harness-comparison --dry-run
node scripts/fetch-issue-images.mjs 7 --slug ai-harness-comparison
```

## 3. ディレクトリ規約

記事単位で分ける（既存の `images/2026/` フラット構成と共存）。

```text
images/
  2026/
    <article-slug>/
      01-<name>.png
      02-<name>.webp
      _manifest.json   # 由来・alt案・プライバシーチェック必須フラグ
```

- ファイル名: `NN-` 連番 + 英数スネークケース（IMAGE_OPT_POLICY.md 準拠）
- 記事と画像の関係は `_manifest.json` が担保

## 4. プライバシー / 安全要件（公開前必須チェック）

Issue #6 の Privacy First 方針に従う。`_manifest.json` の `privacy_check_required: true` を見たら必ず確認する。

- [ ] メールアドレス
- [ ] アカウント名 / 本名
- [ ] 通知内容
- [ ] ファイルパス / ユーザー名
- [ ] APIキー・トークン・認証情報
- [ ] ブラウザタブやURLに含まれる個人情報
- [ ] 位置情報やEXIFメタデータ（sharp で除去）
- [ ] 公開意図のないチャット内容

問題があればマスキング・EXIF除去・トリミングしてから配置する。

## 5. 画像最適化

取得後、公開前に `docs/IMAGE_OPT_POLICY.md` に沿って処理する。

- PNG/JPEG → WebP（画質 75–85）
- EXIF / メタ情報除去
- リサイズ（幅 1600px 上限）
- `loading="lazy"` + `width`/`height` 明示（CLS防止）

## 6. 実装済み / 未実装

- [x] `scripts/fetch-issue-images.mjs` — Issue画像取得＋配置＋manifest
- [x] ディレクトリ規約・alt案・プライバシーチェック規約
- [ ] ChatGPT 用プロンプトテンプレート（KB / AGENTS.md に追記）
- [ ] 自動マスキング / EXIF除去のスクリプト化（sharp 利用）
- [ ] GitHub Actions での画像最適化ワークフロー（Issue #3 と接続）

## 7. 補足

- GitHub Issue の添付画像 URL（`private-user-images.githubusercontent.com`）は取得時に認証済み `gh api` 経由でなく、直接 fetch で取得可能な場合が多い。取得できない場合は `gh auth` 状態と、issue が同一リポジトリのものであることを確認する。
- 添付画像の永続性は GitHub の仕様次第。重要な素材は取得後すぐローカル配置する。
