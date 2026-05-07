# ChatWorkをLLMパイプラインに統合するCLIツール「LLM-ChatWork-Reader-Writer」技術解説

## はじめに

ChatWorkは日本のビジネスコミュニケーションで広く使われていますが、APIを活用した自動化には課題があります。特にLLM（大規模言語モデル）との連携を考えた際、**HTMLタグの混入**や**データ形式の正規化**が重要になります。

今回ご紹介する **「LLM-ChatWork-Reader-Writer」** は、単なるAPIラッパーではなく、**LLMパイプラインに最適化されたデータ処理**を実装したCLIツールです。

**リポジトリ**: https://github.com/Zidooka-dev/LLM-ChatWork-Reader-Writer

---

## ツールの設計思想

### 1. 読み書きの分離（単一責任の原則）

```javascript
// readコマンド - メッセージ取得専用
node src/cli.mjs read --room 123456 --jsonl

// writeコマンド - メッセージ投稿専用  
node src/cli.mjs write --room 123456 --message "Hello"
```

責務を明確に分離することで、パイプラインの各段階で必要な処理だけを実行できます。

### 2. ChatWork特有のノイズ除去

ChatWorkのメッセージには特有のタグが含まれます：

```
[To:123456789] 山田さん
[rp aid=123 to=987654321-111] 
[info][title]重要なお知らせ[/title]
内容です
[/info]
```

このツールは **`--strip-tags`** オプションで以下を自動除去：

| タグ | 用途 |
|------|------|
| `[To:数字]` | メンション |
| `[rp aid=... to=...]` | リプライ |
| `[info]`, `[title]` | 情報ボックス |
| `[code]`, `[qt]` | コード・引用 |

実装は正規表現でシンプルに：

```javascript
function stripCommonChatworkTags(body) {
  return String(body || '')
    .replace(/\[To:\d+\]/g, '')
    .replace(/\[rp aid=\d+ to=\d+-\d+\]/g, '')
    .replace(/\[info\]|\[\/info\]|\[title\]|\[\/title\]|\[code\]|\[\/code\]|\[qt\]|\[\/qt\]/g, '')
    .trim();
}
```

---

## 実装のポイント

### 出力データの正規化

取得したメッセージは統一されたスキーマに変換されます：

```javascript
function normalizeReadMessage(roomId, rawMessage, stripTags) {
  const rawBody = String(rawMessage.body || '');
  const body = stripTags ? stripCommonChatworkTags(rawBody) : rawBody;
  const sendTimeUnix = Number(rawMessage.send_time || 0);
  
  return {
    roomId: String(roomId),
    messageId: String(rawMessage.message_id || ''),
    sendTimeUnix,
    sendTimeIso: sendTimeUnix ? new Date(sendTimeUnix * 1000).toISOString() : null,
    accountId: rawMessage.account?.account_id ?? null,
    accountName: rawMessage.account?.name ?? null,
    body,           // 加工後の本文
    rawBody,        // 元の本文（比較・デバッグ用）
  };
}
```

**ポイント**: `body` と `rawBody` を両方保持することで、必要に応じて元の形式も参照できます。

### 柔軟な時間指定

Unix時間だけでなく、人間が読みやすい形式も受け付けます：

```bash
# Unix時間
node src/cli.mjs read --room 123456 --since 1700000000

# 日付
node src/cli.mjs read --room 123456 --since 2026-02-01

# ISO8601
node src/cli.mjs read --room 123456 --since 2026-02-01T00:00:00Z
```

実装では `Date.parse()` を使って複数形式に対応：

```javascript
function parseUnixTime(input) {
  if (/^\d+$/.test(String(input))) return Number(input);
  const ms = Date.parse(String(input));
  if (Number.isNaN(ms)) throw new Error(`Invalid time: ${input}`);
  return Math.floor(ms / 1000);
}
```

### パイプライン対応の出力形式

```bash
# JSON配列（人間が読む・ファイル保存向け）
node src/cli.mjs read --room 123456 --json

# JSON Lines（ストリーミング・パイプライン向け）
node src/cli.mjs read --room 123456 --jsonl | jq '.body' | head -10
```

---

## 実用的なユースケース

### 1. LLMによる要約パイプライン

```bash
# 昨日以降のメッセージを取得→Claudeで要約
node src/cli.mjs read \
  --room 123456 \
  --since yesterday \
  --strip-tags \
  --jsonl | \
  jq -r '.body' | \
  claude --system "以下のChatWork会話を要約してください"
```

### 2. 特定キーワードの監視

```bash
# 「緊急」を含むメッセージを検出
node src/cli.mjs read \
  --room 123456 \
  --contains "緊急" \
  --json
```

### 3. リプライ形式での返信

```bash
# 特定メッセージへの返信（引用形式）
node src/cli.mjs write \
  --room 123456 \
  --message "承知しました" \
  --reply-account 111222 \
  --reply-message 999888777
```

これにより以下の形式で投稿されます：

```
[rp aid=111222 to=123456-999888777]
承知しました
```

### 4. dry-runでの確認

本番投稿前に内容を確認：

```bash
# 送信せずに内容をプレビュー
node src/cli.mjs write \
  --room 123456 \
  --message "テスト" \
  --to 111,222 \
  --dry-run

# 出力:
# [To:111]
# [To:222]
# テスト
```

### 5. ファイルからの一括投稿

```bash
# ファイル内容をメッセージとして投稿
node src/cli.mjs write \
  --room 123456 \
  --file ./report.md \
  --to 111222
```

またはパイプラインで：

```bash
cat report.md | node src/cli.mjs write --room 123456
```

---

## 技術実装の詳細

### APIクライアントの設計

`chatwork-client.mjs` はシンプルなfetchラッパー：

```javascript
export class ChatworkClient {
  constructor({ token, baseUrl = 'https://api.chatwork.com/v2' }) {
    this.token = token;
    this.baseUrl = baseUrl.replace(/\/$/, '');
  }

  async request(method, path, { query = {}, form = {} } = {}) {
    const headers = {
      Accept: 'application/json',
      'X-ChatWorkToken': this.token,
    };

    // POST時はform-data形式
    let body;
    if (method !== 'GET') {
      const formBody = new URLSearchParams();
      Object.entries(form).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          formBody.set(key, String(value));
        }
      });
      body = formBody;
      headers['Content-Type'] = 'application/x-www-form-urlencoded';
    }

    const response = await fetch(url, { method, headers, body });
    // エラーハンドリング...
  }
}
```

**ポイント**:
- ChatWork APIは **form-urlencoded** を使用
- トークンは **X-ChatWorkToken** ヘッダーで送信
- Node.js 18+ の組み込み `fetch` を使用

### トークン解決の優先順位

複数の設定方法に対応：

```javascript
function resolveToken(options, envFromFile) {
  return (
    options.token ||                    // 1. CLI引数
    process.env.CHATWORK_API_TOKEN ||   // 2. 環境変数
    process.env.CHATWORK_TOKEN ||       // 3. 後方互換
    envFromFile.CHATWORK_API_TOKEN ||   // 4. .envファイル
    envFromFile.CHATWORK_TOKEN
  );
}
```

---

## なぜこのツールが必要か

### 既存のChatWorkクライアントとの違い

| 機能 | 一般的なクライアント | LLM-ChatWork-Reader-Writer |
|------|---------------------|---------------------------|
| JSON出力 | ❌ | ✅ |
| JSONL出力 | ❌ | ✅ |
| タグ除去 | ❌ | ✅ |
| 時間フィルタ | 限定的 | 柔軟（複数形式） |
| 部分文字列検索 | ❌ | ✅ |
| dry-run | ❌ | ✅ |
| stdin対応 | ❌ | ✅ |

### LLMパイプラインとの親和性

```bash
# シンプルなパイプライン例
node src/cli.mjs read --room 123 --jsonl | \
  jq 'select(.body | contains("AI"))' | \
  jq -r '.body' | \
  head -5 | \
  claude --system "これらを分類してください"
```

構造化されたJSON出力により、標準的なCLIツール（`jq`, `grep`, `awk` など）との連携が容易になります。

---

## まとめ

「LLM-ChatWork-Reader-Writer」は単なるAPIラッパーではなく、**LLM時代のChatWorkワークフロー**を見据えた設計が特徴です：

1. **ノイズ除去** - ChatWork特有のタグを除去してクリーンなテキストを提供
2. **データ正規化** - 統一されたスキーマで後続の処理を容易に
3. **パイプライン連携** - JSONL出力でストリーミング処理をサポート
4. **安全性** - dry-runモードでの事前確認

ChatWorkのデータをLLMパイプラインに組み込みたい開発者にとって、最適な入り口となるツールです。

---

## 参考リンク

- **GitHub**: https://github.com/Zidooka-dev/LLM-ChatWork-Reader-Writer
- **ChatWork API**: https://developer.chatwork.com/

Happy Coding! 🚀
