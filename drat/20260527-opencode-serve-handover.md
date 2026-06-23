# OpenCode Serve 橋渡し Handover

## 目的

司令塔エージェント（Codex / Claude Code）が安価な子モデル（DeepSeek V4 Flash）と対話的に連携するための `opencode serve` 構築手順。

## アーキテクチャ

```text
司令塔（Codex / Claude Code 等）
  │  webfetch で HTTP POST
  ▼
opencode serve（localhost:4096 で待機）
  │  同一セッションにメッセージを積み重ねる
  ▼
子モデル（DeepSeek V4 Flash / Kimi K2.6）
  ├─ 1ターン目「このコード解析して」
  ├─ 2ターン目「修正案出して」
  └─ 3ターン目「じゃあ実行して」
```

## 構築手順

### 1. opencode serve を起動

```powershell
# バックグラウンド起動
Start-Process -WindowStyle Hidden -FilePath "opencode" -ArgumentList "serve --port 4096 --hostname 127.0.0.1"

# 確認
Invoke-RestMethod -Uri "http://127.0.0.1:4096/global/health" -Method Get
# → {"healthy": true, "version": "..."}
```

または常時起動用のスクリプトを作る：
```powershell
# start-serve.ps1
$proc = Get-Process -Name "opencode" -ErrorAction SilentlyContinue | Where-Object { $_.CommandLine -like "*serve*" }
if (-not $proc) {
  Start-Process -WindowStyle Hidden -FilePath "opencode" -ArgumentList "serve --port 4096 --hostname 127.0.0.1"
  Write-Host "opencode serve started on port 4096"
} else {
  Write-Host "opencode serve already running"
}
```

### 2. セッションを作成

```powershell
$session = Invoke-RestMethod `
  -Uri "http://127.0.0.1:4096/session" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"title": "session-name"}'

$sessionId = $session.id
```

### 3. メッセージを送信（1ターン目）

```powershell
$body = @{
  parts = @(@{
    type = "text"
    text = "ここにタスクの説明"
  })
} | ConvertTo-Json -Compress

$response = Invoke-RestMethod `
  -Uri "http://127.0.0.1:4096/session/$sessionId/message" `
  -Method Post `
  -ContentType "application/json" `
  -Body $body

# レスポンステキストを取り出す
$response.parts | Where-Object { $_.type -eq "text" } | ForEach-Object { $_.text }
```

### 4. 続けてメッセージを送信（2ターン目以降）

同じ `$sessionId` に対して再度 `POST /session/:id/message` を送るだけ。文脈は自動維持。

```powershell
$body2 = @{
  parts = @(@{
    type = "text"
    text = "その結果を踏まえて、修正を適用して"
  })
} | ConvertTo-Json -Compress

$response2 = Invoke-RestMethod `
  -Uri "http://127.0.0.1:4096/session/$sessionId/message" `
  -Method Post `
  -ContentType "application/json" `
  -Body $body2
```

### 5. セッション一覧を確認

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:4096/session" -Method Get
```

### 6. セッションを削除

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:4096/session/$sessionId" -Method Delete
```

## 子セッション（フォーク）の作成

```powershell
# 方式A: フォーク（メッセージは継承されるが親子リンクは切れる）
$fork = Invoke-RestMethod `
  -Uri "http://127.0.0.1:4096/session/$parentId/fork" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{}'

# 方式B: 明示的な子セッション（親子リンクは張られるがメッセージは空）
$child = Invoke-RestMethod `
  -Uri "http://127.0.0.1:4096/session" `
  -Method Post `
  -ContentType "application/json" `
  -Body "{`"title`":`"child-name`",`"parentID`":`"$parentId`"}"

# 子セッション一覧
Invoke-RestMethod -Uri "http://127.0.0.1:4096/session/$parentId/children" -Method Get
```

## JS SDKを使う場合

```powershell
npm install @opencode-ai/sdk
```

```typescript
import { createOpencodeClient } from "@opencode-ai/sdk"

const client = createOpencodeClient({ baseUrl: "http://localhost:4096" })

// セッション作成
const session = await client.session.create({ body: { title: "my-task" } })
const sid = session.id

// 1ターン目
const r1 = await client.session.prompt({
  path: { id: sid },
  body: {
    parts: [{ type: "text", text: "このコードを解析して" }],
  },
})

// 2ターン目（文脈維持）
const r2 = await client.session.prompt({
  path: { id: sid },
  body: {
    parts: [{ type: "text", text: "結果を踏まえて修正して" }],
  },
})
```

## 注意点

- `opencode run` 経由では聞き返せない（ワンショット）。対話が必要なら必ず serve API を使う。
- 同一セッションに連続POSTするのが最も確実な文脈維持方法。
- Codex / Claude Code にこのパターンを指示するときは「webfetch で localhost:4096 の API を叩け」と明示的に伝える。
- ラッパースクリプトを用意すると、司令塔エージェントへの指示がシンプルになる。
- serve が落ちたら再起動が必要。死活監視があれば安心。

## 検証済み環境

- OpenCode version: 1.15.10
- テスト日: 2026-05-27
- 3ターンの連続対話、文脈維持を確認済み
- 子セッションのfork（メッセージ継承あり）とcreate（親子リンクあり）の2方式を確認
