---
title: "Slackの自分宛メンションをPythonで取得したらめっちゃ楽だった件"
date: 2026-01-07 10:00:00
categories: 
  - python
tags: 
  - Slack
  - API
  - Automation
  - Python
  - CSV
status: publish
slug: slack-mentions-python-export
featured_image: ../images/202601/image copy 11.png
---

毎日の業務でSlackを使っていると、「あの時の自分への依頼、どこだっけ？」「先週のフィードバックを見返したい」という場面がよくあります。
Slackの検索機能は優秀ですが、「自分宛のメンションをリスト化してCSVで保存したい」となると、標準機能ではなかなか手が届きません。

そこで、**Pythonを使ってSlackの自分宛メンションを期間指定で全取得し、CSVにエクスポートするスクリプト**を作成しました。
これが思いのほか便利で、業務日報の振り返りやタスクの抜け漏れチェックが爆速になったので共有します。

:::note
この記事では、Slack APIの `search.messages` メソッドを使用します。User Token (`xoxp-`) が必要になります。
:::

## なぜスクリプトが必要なのか？

Slackの標準機能でも「メンション＆リアクション」画面で履歴は追えますが、以下のような課題がありました。

- **一覧性が低い**: スクロールして過去に遡るのが大変
- **期間指定が面倒**: 検索コマンドを毎回打つ必要がある
- **データとして残らない**: CSVやExcelに貼り付けて分析できない

今回作成したスクリプトを使えば、**「2025年12月25日から2026年1月7日まで」のように期間をズバリ指定して、CSVファイルとして一瞬で保存**できます。

## 完成したスクリプト

以下のPythonコードを `export_mentions.py` などの名前で保存して使います。
`.env` ファイルにトークンを設定するだけで動くように設計しました。

:::step
**主な機能**
1. `.env` から設定を読み込み（セキュリティ配慮）
2. `USER_ID` が分からなくても自動取得
3. 検索クエリに `after:` `before:` を自動付与してAPI負荷を軽減
4. チャンネル名が取得できない場合（DMなど）のハンドリング
5. 結果をCSVにエクスポート
:::

```python
#!/usr/bin/env python3
"""
Slack自分宛メンション抽出ツール
期間指定して自分へのメンションをCSVでエクスポート
"""

import os
import csv
import requests
from datetime import datetime, timezone
from dotenv import load_dotenv

# .env ファイルから環境変数を読み込み
load_dotenv()

TOKEN = os.getenv("SLACK_TOKEN")
USER_ID = os.getenv("USER_ID")

if not TOKEN:
    print("❌ エラー: .env ファイルにSLACK_TOKENを設定してください")
    exit(1)

# USER_IDが設定されていない場合、APIから自動取得
if not USER_ID or USER_ID == "UXXXXXXXX":
    print("🔍 USER_IDを自動取得中...")
    auth_res = requests.get(
        "https://slack.com/api/auth.test",
        headers={"Authorization": f"Bearer {TOKEN}"}
    ).json()
    
    if not auth_res.get("ok"):
        print(f"❌ 認証エラー: {auth_res.get('error')}")
        print("   トークンが正しいか確認してください")
        exit(1)
    
    USER_ID = auth_res["user_id"]
    print(f"✅ USER_ID取得: {USER_ID}")
    print()

# 期間設定（ここを変更）
# 例: 2025年12月25日 ～ 2026年1月7日
START_DATE = datetime(2025, 12, 25, tzinfo=timezone.utc)
END_DATE = datetime(2026, 1, 7, 23, 59, 59, tzinfo=timezone.utc)

# クエリ用日付フォーマット
QUERY_AFTER = START_DATE.strftime('%Y-%m-%d')
QUERY_BEFORE = END_DATE.strftime('%Y-%m-%d')

START_TS = int(START_DATE.timestamp())
END_TS = int(END_DATE.timestamp())

print(f"📅 期間: {START_DATE.strftime('%Y-%m-%d')} ～ {END_DATE.strftime('%Y-%m-%d')}")
print(f"🔍 検索対象: <@{USER_ID}> へのメンション")
print()

# Slack API設定
url = "https://slack.com/api/search.messages"
headers = {"Authorization": f"Bearer {TOKEN}"}

# 全メッセージを取得（ページング対応）
all_matches = []
page = 1

print("⏳ Slack APIから取得中...")

while True:
    # after/before をクエリに含めることで検索精度向上とAPI負荷軽減
    query_str = f"<@{USER_ID}> after:{QUERY_AFTER} before:{QUERY_BEFORE}"
    
    params = {
        "query": query_str,
        "count": 100,
        "sort": "timestamp",
        "sort_dir": "asc",
        "page": page
    }
    
    res = requests.get(url, headers=headers, params=params).json()
    
    if not res.get("ok"):
        error = res.get("error", "unknown")
        print(f"❌ API エラー: {error}")
        
        if error == "missing_scope":
            print("   → User Token Scopesに search:read, channels:read, groups:read, im:read, mpim:read を追加してください")
        elif error == "not_allowed_token_type":
            print("   → Bot Token (xoxb-) ではなく User Token (xoxp-) を使ってください")
        elif error == "invalid_auth":
            print("   → トークンが無効です。再生成してください")
        
        exit(1)
    
    matches = res.get("messages", {}).get("matches", [])
    
    if not matches:
        break
        
    all_matches.extend(matches)
    
    pagination = res.get("messages", {}).get("pagination", {})
    total_pages = pagination.get("page_count", 1)
    
    print(f"   ページ {page}/{total_pages} 取得完了 ({len(matches)}件)")
    
    if page >= total_pages:
        break
    
    page += 1

print(f"✅ 全{len(all_matches)}件取得完了")
print()

# 厳密な期間フィルタリングとデータ整形
filtered = []
for m in all_matches:
    ts_val = float(m["ts"])
    if START_TS <= ts_val <= END_TS:
        # チャンネル名が取得できないケース（DMやPrivateなど）を考慮
        channel_info = m.get("channel", {})
        channel_name = channel_info.get("name", "DM/Private/Unknown")
        
        filtered.append({
            "timestamp": datetime.fromtimestamp(ts_val, tz=timezone.utc).strftime("%Y-%m-%d %H:%M:%S"),
            "channel": channel_name,
            "user": m.get("username", "unknown"),
            "text": m["text"].replace("\n", " ")[:200]  # 改行除去・200文字まで
        })

print(f"🎯 期間内のメンション: {len(filtered)}件")

if len(filtered) == 0:
    print("   該当するメンションが見つかりませんでした")
    exit(0)

# CSV出力
output_file = f"slack_mentions_{START_DATE.strftime('%Y%m%d')}_{END_DATE.strftime('%Y%m%d')}.csv"

with open(output_file, "w", encoding="utf-8-sig", newline="") as f:
    writer = csv.DictWriter(
        f,
        fieldnames=["timestamp", "channel", "user", "text"]
    )
    writer.writeheader()
    writer.writerows(filtered)

print(f"💾 CSVファイル出力: {output_file}")
print()
print("完了！")
```

## 使い方

### 1. 準備

まず、必要なライブラリをインストールします。

```powershell
pip install requests python-dotenv
```

次に、プロジェクトフォルダに `.env` ファイルを作成し、Slackのトークンを記述します。
ここでのトークンは **User Token (xoxp-から始まるもの)** である必要があります。

```ini
SLACK_TOKEN=xoxp-1234567890-1234567890-......
# USER_IDは省略可（自動取得されます）
# USER_ID=U12345678
```

:::warning
Bot Token (`xoxb-`) では `search.messages` APIを利用できないケースが多いです。必ずUser Tokenを使用してください。また、Scopesには `search:read` が必須です。
:::

### 2. 実行

スクリプトを実行すると、自動的にAPIを叩いてデータを取得してくれます。

```powershell
python export_mentions.py
```

実行結果の例：
```text
✅ USER_ID取得: U012345678

📅 期間: 2025-12-25 ～ 2026-01-07
🔍 検索対象: <@U012345678> へのメンション

⏳ Slack APIから取得中...
   ページ 1/2 取得完了 (100件)
   ページ 2/2 取得完了 (42件)
✅ 全142件取得完了

🎯 期間内のメンション: 142件
💾 CSVファイル出力: slack_mentions_20251225_20260107.csv

完了！
```

これで、フォルダ内にCSVファイルが生成されます。Excelで開けば、いつ誰からどんなメンションが来たか一目瞭然です。

## 工夫したポイント

このスクリプトは単にAPIを叩くだけでなく、実務で使いやすいようにいくつか工夫を入れています。

### 検索精度の向上
`search.messages` APIを叩く際、クエリ文字として `after:2025-12-25` や `before:2026-01-07` を自動で埋め込むようにしました。これにより、**関係ない過去のログを大量に取得してAPI制限に引っかかる**のを防いでいます。

:::example
Python側でのフィルタリングだけでなく、APIへのリクエスト段階で絞るのが高速化のコツです。
:::

### エラーハンドリングの強化
「あ、トークン間違えた」「スコープ足りなかった」というAPIエラーは、JSONレスポンスの `error` フィールドを見るまで気づきにくいものです。
このスクリプトでは、よくあるエラー（`missing_scope` など）を検知して、日本語で「〇〇を追加してください」と案内するようにしました。

## まとめ

Slackのログは宝の山ですが、流れていってしまうと価値が半減します。
こうして定期的に自分宛のメンションをCSV化しておくと、「あの件どうなったっけ？」が激減しますし、自分のタスク管理精度も上がります。

ぜひ試してみてください。

:::conclusion
**今回のポイント:**
1. Slack APIの `search.messages` は強力だがUser Tokenが必要
2. `python-dotenv` を使ってトークン管理を安全に
3. 検索クエリに日付フィルタを入れるとGood
:::
