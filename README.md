# ZIDOOKA-Writer (Agent CLI Mode)

## 概要
チャット上のAIエージェント（GitHub Copilot）がコマンドラインツールを操作して、記事の執筆・画像処理・WordPress投稿を行う。
ユーザーはチャットで指示を出し、エージェントが裏でNode.jsスクリプトを実行する。

## ワークフロー
1. **執筆**: ユーザーがテーマを指定 → エージェントが `src/writer.js` を実行 → `drafts/` にMarkdown生成。
2. **確認**: エージェントがMarkdownを表示 → ユーザーが承認。
3. **投稿**: エージェントが `src/poster.js` を実行 → WordPressに投稿完了。

## ディレクトリ構成
```
/zidooka-writing
  ├── drafts/           # 生成された記事 (Markdown)
  ├── images/           # 画像素材置き場
  ├── src/
  │   ├── index.js      # メインエントリ
  │   ├── writer.js     # 記事生成ロジック (OpenAI)
  │   ├── vision.js     # 画像解析ロジック (OpenAI Vision)
  │   ├── wp.js         # WordPress APIクライアント
  │   └── utils.js      # ユーティリティ
  ├── data/
  │   └── metadata.json # カテゴリ・タグのキャッシュ
  ├── .env              # APIキー
  └── package.json
```

## コマンド (エージェント用)
- `node src/index.js write "テーマ" --images ./images` : 記事を作成
- `node src/index.js post drafts/article.md` : 記事を投稿
- `node src/index.js sync-tags` : WordPressからタグ・カテゴリを取得してキャッシュ
