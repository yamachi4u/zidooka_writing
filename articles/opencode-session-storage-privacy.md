# opencode のセッションストレージとプライバシー設計

## セッションデータはどこに保存されるのか

opencode のセッション（会話履歴）は、ユーザーのローカルマシン上の SQLite データベースに保存される。

**保存場所（Windows）:**
```
C:\Users\{ユーザー}\.local\share\opencode\opencode.db
```

このファイル1つに、全てのセッション・メッセージ・プロジェクト情報が格納されている。インターネット上のサーバーには送信されない。クラウド同期の機能もオフになっている。

## ストレージの二層構造

opencode は SQLite をプライマリとしつつ、一部のデータをファイルシステム上にもキャッシュする二層構造を取っている。

| データ | 保存先 | 形式 |
|-------|--------|------|
| セッション一覧 | `opencode.db` → `session` テーブル | SQLite |
| メッセージ一覧 | `opencode.db` → `message` テーブル | SQLite |
| メッセージ本文 | `opencode.db` → `part` テーブル | SQLite |
| パーツのファイルキャッシュ | `storage/part/` | JSON |
| セッションのエクスポート | `storage/session/global/` | JSON |

SQLite が正規のストレージで、JSON ファイル群はバックアップ・エクスポート用の補助的な位置づけである。

## セッションは他人に見られるのか

**デフォルトでは見られない。**

全270セッション（実測値）のうち、共有URLが設定されていたのはわずか1件であった。残りの269件は完全にローカルに閉じている。

opencode のセッションには `permission` カラムがあり、以下の権限制御が可能:
- `todowrite` / `todoread` / `task` の各操作を `deny`
- サブエージェント単位での権限設定
- ワイルドカードパターンによる一括設定

## 共有機能の仕組み

opencode には `/share` コマンドでセッションを共有する機能が存在する。その実装は以下の通り:

1. `session_share` テーブルに UUID の secret と URL を記録
2. 共有URLは `https://opncd.ai/share/{short_id}` の形式
3. URLにアクセスがあった時点でデータが遅延アップロードされる（lazy publish）と推測される
4. アカウント連携が設定されていない限り、データが外部に送信されることはない

実際の調査では、`account` テーブルは空であり、どのクラウドアカウントも連携されていなかった。つまり、共有URLが生成されている Zustand でも、実際にそのURLを知る者がアクセスしない限りデータが外部に出ることはない。

## セッション一覧の確認方法

スラッシュコマンド:
```
/sessions
```

実行すると SQLite の `session` テーブルをクエリし、全セッションが時系列で表示される。これにより、過去の会話を遡ることができる。

カラム構造:
- `id`: 内部ID（`ses_` プレフィックス）
- `title`: セッションタイトル（自動生成またはユーザー設定）
- `time_created` / `time_updated`: 作成・更新時刻
- `share_url`: 共有URL（未共有の場合はNULL）
- `permission`: 権限設定JSON
- `agent`: 使用エージェント
- `model`: 使用モデル

## プライバシーまとめ

| 観点 | 結果 |
|------|------|
| 保存場所 | ローカル SQLite |
| クラウド同期 | なし（アカウント未連携時） |
| デフォルト公開 | されない |
| 共有機能 | 明示的な `/share` が必要 |
| 外部漏洩リスク | ローカルファイルへの物理アクセス、または共有URLの漏洩 |

## 技術的補足: SQLite のテーブル構成

実際のデータベースには17のテーブルが存在する:

- `session` / `message` / `part` — 会話の3層構造
- `session_share` — 共有設定
- `permission` — 権限設定
- `todo` / `project` — プロジェクト管理
- `control_account` / `account` / `account_state` — アカウント連携
- `event_sequence` / `event` — イベント追跡
- `workspace` — ワークスペース管理
- `session_message` — セッション-メッセージ関連
- `data_migration` — マイグレーション履歴

アカウント関連のテーブルがすべて空であることから、opencode はローカルファーストで設計されており、クラウド連携はオプトインであることがわかる。

---

# opencode Session Storage and Privacy Design

## Where Is Session Data Stored?

opencode stores all session (conversation history) data in a local SQLite database on the user's machine.

**Location (Windows):**
```
C:\Users\{username}\.local\share\opencode\opencode.db
```

This single file contains all sessions, messages, and project information. Data is never sent to remote servers by default. Cloud sync is disabled unless explicitly configured.

## Two-Layer Storage Architecture

opencode uses a dual-layer approach: SQLite as the primary store, with file-based JSON caching for certain data.

| Data | Location | Format |
|------|----------|--------|
| Session index | `opencode.db` → `session` table | SQLite |
| Message index | `opencode.db` → `message` table | SQLite |
| Message content | `opencode.db` → `part` table | SQLite |
| Part file cache | `storage/part/` | JSON |
| Session exports | `storage/session/global/` | JSON |

SQLite is the authoritative storage layer. JSON files serve as auxiliary export/backup copies.

## Can Others See Your Sessions?

**No, by default they cannot.**

Out of 270 sessions (measured), only 1 had a share URL configured. The remaining 269 were fully local.

The `session` table includes a `permission` column supporting granular access control:
- `todowrite` / `todoread` / `task` operations can be set to `deny`
- Permissions can be scoped to specific sub-agents
- Wildcard patterns are supported for bulk configuration

## How Sharing Works

opencode provides a `/share` command to share sessions. The implementation:

1. Records a UUID secret and URL in the `session_share` table
2. The share URL follows the format `https://opncd.ai/share/{short_id}`
3. Data is assumed to be lazily uploaded when the URL is first accessed
4. Without a connected cloud account, no data leaves the machine

Investigation confirmed that the `account` table was empty — no cloud accounts were linked. Even if a share URL has been generated, no external transmission occurs unless someone actually accesses that URL.

## Listing Sessions

Slash command:
```
/sessions
```

This queries the `session` table in SQLite and displays all sessions in chronological order.

Key columns:
- `id`: Internal ID (`ses_` prefix)
- `title`: Auto-generated or user-defined title
- `time_created` / `time_updated`: Timestamps
- `share_url`: Share link (NULL if not shared)
- `permission`: Permission JSON
- `agent`: Agent type used
- `model`: Model used

## Privacy Summary

| Aspect | Status |
|--------|--------|
| Storage | Local SQLite |
| Cloud sync | None (when not authenticated) |
| Public by default | No |
| Sharing | Requires explicit `/share` command |
| Leak risk | Physical access to the file, or leaked share URL |

## Technical Appendix: SQLite Table Structure

The database contains 17 tables:

- `session` / `message` / `part` — Three-layer conversation model
- `session_share` — Share configuration
- `permission` — Permission settings
- `todo` / `project` — Task and project management
- `control_account` / `account` / `account_state` — Account linking
- `event_sequence` / `event` — Event tracking
- `workspace` — Workspace management
- `session_message` — Session-message relations
- `data_migration` — Migration history

All account-related tables were empty, confirming that opencode is designed local-first, with cloud features as opt-in.
