---
title: "OpenCodeユーザーが久しぶりにHermes Agentを見て「ここまで来ていたのか」と思った話"
categories:
  - AI
tags:
  - AI Agent
  - Agent
  - OpenCode
  - Open Source
  - Comparison
  - Memory
  - Subagents
  - Automation
status: draft
slug: hermes-agent-opencode-view-ja
---

:::conclusion
OpenCodeで開発してきた身として、久しぶりにHermes Agentを触ってみた。「ここまで来ていたのか」というのが正直な感想だ。ただ結論は単純な乗り換えではない。コーディングはOpenCode、記憶・常駐・自動化・複数チャネルはHermes。用途で併用するのが一番しっくりくる。
:::

## 発端は、何気ない一言

最近、知人と「エージェントどうしてる？」という話になった。僕はOpenCodeをメインに使っている。コーディングエージェントとしての使い勝手が気に入っていて、それで十分だと思っていた。

そこで「Hermes Agentもいいよ」と言われた。聞いたことのある名前だった。ただ僕の記憶は、まだ「CLIで動くチャットボット」止まりだった。とりあえず公式サイトを見てみることにした。

## まず驚いたのは、記憶と自己改善のループ

サイトを開いて最初に目に飛び込んできたのが「The Agent That Grows With You」。エージェントが「育つ」という発想だ。

Hermesは永続メモリを持っている。`MEMORY.md` と `USER.md` に学んだことを書き、セッションをまたいで覚えている。複雑な作業を終えるとスキルを自動生成する。そのスキルを使いながら改善していく。過去の会話をFTS5で検索して振り返る仕組みもある。

OpenCodeにもスキルやAGENTS.mdはある。ただし、これは「開発プロジェクトの文脈」を整える道具だ。Hermesのループは少し違う。エージェント自身が長期間使い込むほど賢くなっていく。「この問題は前回どう解決したか」を覚えている存在になる方向性だ。

## 開発以外の領域が、思いのほか伸びていた

もうひとつ印象的だったのは、機能の広がりだ。

- デスクトップアプリ（macOS / Windows / Linux）
- Web検索、ブラウザ操作、画像生成、テキスト読み上げ
- Telegram / Discord / Slack / WhatsApp / Signal / Email、それにCLI
- 自然言語で書けるスケジュール実行
- サブエージェントへの委任、並列実行
- サンドボックスはローカル / Docker / SSH / Singularity / Modal の5種

「チャットするだけのボット」から、かなり進化していた。ボイスモードや「Hey Hermes」のウェイクワードもある。エージェントに話しかけたり、Discordのボイスチャンネルで会話したりするらしい。

複数チャネル対応が効いているのは、常駐型エージェントならではだ。PCを閉じても、ゲートウェイが動いていればTelegramから指示を出せる。OpenCodeのノリだと「ターミナルを開く→作業」という流れだけど、Hermesは「スマホから聞く→答えが返る」という接し方になる。この差は地味に大きい。

## モデルの自由度とライセンス

対応モデルも広い。OpenRouter、OpenAI、Nous Portal、自前のエンドポイントまで。`hermes model` で切り替える。特定ベンダーに縛られない。

ライセンスはMIT。コードを読めるし、フォークして育てることもできる。これもOpenCodeユーザーには馴染みやすいポイントだ。

ちなみにバージョン番号の進み方が速い。2026年6月から8月にかけて、0.15→0.16→0.17→0.18→0.19→0.20と、約2週間ペースで刻んでいる。トップページの案内とGitHub Releasesのタグが一時的にズレることもある。最新のv0.20.0は2026年8月3日付だ。

## OpenCodeとHermes、どこが違うのか

大雑把に整理すると、次の表になる。

| 観点 | OpenCode | Hermes Agent |
|------|----------|--------------|
| 得意分野 | コーディング作業 | 常駐・自動化・記憶 |
| 主な画面 | ターミナル / IDE | CLI、デスクトップ、各メッセージアプリ |
| 記憶 | プロジェクト単位 | セッションをまたいだ永続メモリ |
| 自動化 | スニペットやコマンド中心 | 自然言語スケジュール + ゲートウェイ |
| チャネル | ローカル中心 | Telegram等の複数チャネル |
| サンドボックス | ローカル前提 | Docker / SSH / Singularity / Modal等 |

開発中にファイルを編集してテストを回す、という文脈ではOpenCodeのほうが頭ひとつ抜けている。レスポンスも速いし、エディタ連携も自然だ。

一方、Hermesが強いのは「開発の外」だ。毎朝のレポート、定時バックアップ、Slack経由の質問への回答。寝ている間もエージェントが動く、という運用を求められたらHermesに軍配が上がる。

## 結局、どう使うか

「どちらかに一本化」という話ではない。役割が違う。

- 実装・デバッグ・リファクタリング → OpenCode
- スケジュール実行・常駐・チャネル対応・長期記憶 → Hermes

僕は開発はOpenCode、日常のルーチンはHermes、という組み合わせを試すつもりだ。久しぶりに開いた画面に「ここまで来ていたのか」と正直驚いた。次に開いたとき、また進化している気がする。

:::conclusion
Hermes Agentは「育つエージェント」という方向性に振り切れてきた。永続メモリ、スキルの自己改善、複数チャネル、スケジュール実行、多彩なサンドボックス。MITライセンスで、モデルも自由に選べる。OpenCodeは開発ハーネスとして今も頼れる。だから「乗り換え」ではなく「用途で併用」が、いちばん現実的な答えだと思う。
:::

## 参考

1. [Hermes Agent公式サイト](<https://hermes-agent.nousresearch.com/>)
2. [Features Overview](<https://hermes-agent.nousresearch.com/docs/user-guide/features/overview/>)
3. [NousResearch/hermes-agent（GitHub）](<https://github.com/NousResearch/hermes-agent>)
4. [Releases](<https://github.com/NousResearch/hermes-agent/releases>)
