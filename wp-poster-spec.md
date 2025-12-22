# WordPress自動執筆投稿ダッシュボード (ZIDOOKA-Writer UI) 仕様書

## 1. 概要
ローカル環境で動作するWebアプリケーション。
React製の直感的なUIで「テーマ入力」と「画像ドラッグ＆ドロップ」を行うだけで、バックエンドのAIエージェントが記事執筆から投稿までを完遂する。
ブラウザ上でプレビューや修正を行ってからWordPressへ送信できるため、品質担保と手軽さを両立する。

## 2. アーキテクチャ (Local Web App)
- **Frontend**: React (Vite) + Tailwind CSS
  - 記事作成ダッシュボード
  - 画像アップローダー
  - Markdown/HTMLプレビュー
- **Backend**: Node.js (Express)
  - AI Agent API (Writer, Vision, Editor)
  - WordPress API Client
  - ローカルファイル操作
- **起動**: 
pm start でフロントバックエンドが一括起動

## 3. ユーザー体験 (UX)
1. **Dashboard**: ブラウザで http://localhost:3000 を開く。
2. **Input**: 
   - 「記事のテーマ」を入力。
   - 記事に使いたい画像をエリアにドラッグ＆ドロップ。
3. **Generate**: 「AIで執筆開始」ボタンをクリック。
4. **Review**: 
   - 左側にMarkdownエディタ、右側にリアルタイムプレビューが表示される。
   - AIが生成した画像Alt/Captionもここで確認修正可能。
5. **Publish**: 「WordPressに投稿」ボタンで完了。

## 4. 機能要件

### Frontend (React)
- **Topic Input**: 記事のネタやキーワードを入力するフォーム。
- **Image Uploader**: ドラッグ＆ドロップで画像をバックエンドに一時保存。
- **Editor UI**: 生成された記事を修正するためのMarkdownエディタ (例: eact-markdown, monaco-editor)。
- **Status Indicator**: 「執筆中...」「画像解析中...」「投稿中...」のステータス表示。

### Backend (Node.js/Express)
- **POST /api/generate**: テーマと画像を受け取り、AIエージェントチェーンを実行して記事JSONを返す。
- **POST /api/publish**: 確定した記事データを受け取り、WordPressへ投稿する。
- **Agent Logic**: (前述のWriter/Vision/EditorロジックをAPI化)

## 5. ディレクトリ構成案
`
/zidooka-writing
   client/           # React Frontend
      src/
         components/
         App.jsx
         main.jsx
      package.json
   server/           # Node.js Backend
      src/
         agents/   # AI Logic
         routes/
         app.js
      package.json
   package.json      # 全体起動用 (Concurrently等)
   .env
`

## 6. 開発ステップ
1. **Step 1**: プロジェクトのスカフォールディング (React + Express)
2. **Step 2**: バックエンドのAIエージェント実装 (API化)
3. **Step 3**: フロントエンドのUI実装 (入力〜プレビュー)
4. **Step 4**: WordPress投稿機能の結合
