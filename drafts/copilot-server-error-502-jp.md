---
title: "VS Code Copilotで「Server error: 502」が出る原因と対処法 ― 504・Stream terminatedとの違い"
date: 2025-12-22 11:00:00
categories: 
  - Copilot &amp; VS Code Errors
tags: 
  - GitHub Copilot
  - VS Code
  - Server error 502
  - Troubleshooting
status: publish
slug: copilot-server-error-502-jp
featured_image: ../images/image copy 37.png
---

VS Code で GitHub Copilot を使っていると、突然以下のエラーが出てチャットが応答しなくなることがあります。

```
Sorry, your request failed. Please try again.
Copilot Request id: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
Reason: Server error: 502
```

「502 Bad Gateway」と呼ばれるこのエラーは、**あなたのPCの設定ミスではなく、Copilot側のサーバー通信トラブル** であることがほとんどです。

この記事では、ZIDOOKA! の実環境で発生した事例をもとに、**502エラーの正体** と、似ているエラー（504 / Stream terminated）との違い、そして **今すぐできる対処法** を解説します。

## 結論：Server error: 502 とは何か

一言で言うと、**「Copilotの中継サーバーが、AIモデルからの応答を受け取れなかった」** 状態です。

Copilot の仕組みはざっくり以下のようになっています。

1. **VS Code (あなた)** がリクエストを送る
2. **Copilot API (中継役)** が受け取る
3. **AIモデル (推論サーバー)** に処理を投げる

**502エラー** は、2番目の「中継役」までは届いたけれど、3番目の「AIモデル」から正常な返事が返ってこなかった（または通信が切れた）時に発生します。

### 似ているエラーとの違い

Copilot にはよく似たエラーがいくつかあります。違いを知っておくと対処が早くなります。

| エラー | エラーメッセージ | 主な原因 | 対処法 |
| :--- | :--- | :--- | :--- |
| **502** | `Server error: 502` | **中継失敗** (Bad Gateway) | **待つ / 再起動** |
| **504** | `Server error: 504` | **タイムアウト** (処理遅延) | **再送するだけで直る** |
| **Stream** | `Stream terminated` | **モデル不安定** (切断) | **モデルを変更する** |

- **504 (Gateway Timeout)** は「処理に時間がかかりすぎた」だけなので、もう一度同じ指示を送れば通ることが多いです。
- **Stream terminated** は Gemini 3 Pro (Preview) などの特定モデルで起きやすく、モデルを Claude や GPT-4o に変えると直ります。
- **今回の 502** は、これらよりも「サーバー側の調子が悪い」度合いが強く、ユーザー側でコントロールしにくいのが特徴です。

---

## 対処法：502エラーが出たときにやること

「サーバー側の問題なら待つしかないの？」と思うかもしれませんが、以下の手順で直ることもあります。

### 1. VS Code を再起動する（一番効く）
セッション情報が中途半端に残っていると、502が出続けることがあります。
一度 VS Code を完全に終了し、立ち上げ直してみてください。これだけで直るケースが 7割 です。

### 2. Copilot からサインアウト・サインイン
認証トークンの不整合が起きている可能性があります。

1. VS Code 左下のユーザーアイコンをクリック
2. **GitHub からサインアウト** を選択
3. 再度サインインして Copilot を有効化

### 3. モデルを切り替えてみる
もし Gemini 3 Pro (Preview) などを使っている場合、そのモデルの推論サーバーだけが落ちている可能性があります。
Copilot Chat のモデル選択プルダウンから **GPT-4o** や **Claude 3.5 Sonnet** に切り替えて試してみてください。

### 4. それでもダメなら「待つ」
上記を試しても直らない場合、GitHub Copilot のサービス全体（またはあなたの地域のエッジサーバー）で障害が起きている可能性が高いです。
15分〜1時間ほど待ってから再試行するのが賢明です。

---

## まとめ

`Server error: 502` が出たら、まずは **「自分のコードのせいではない」** と安心してください。

1. **VS Code を再起動**
2. **モデルを変えてみる**
3. **ダメならコーヒーでも飲んで待つ**

これが ZIDOOKA! 流の最適解です。

---

**関連するエラー記事:**
- [Copilot「Server error: 504」が発生した原因と対処法](https://www.zidooka.com/archives/549)
- [Gemini 3 Pro で「Server error. Stream terminated」が出たときの原因](https://www.zidooka.com/archives/1219)
