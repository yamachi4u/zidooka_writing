---
title: "Slack Codeは無料プランでも使える？料金・AIエージェント課金・APIを整理"
categories:
  - AI
tags:
  - Slack
  - Slack Code
  - AIエージェント
  - AIコーディング
  - Claude Code
  - Devin
status: publish
slug: slack-code-pricing-agents-api-2026
# republish-trigger: 2026-08-21
---

Slackが2026年8月20日に発表した「Slack Code」がかなり面白い。Slackの中にプロジェクト単位のコードチャンネルを作り、Claude CodeやDevinなどのコーディングエージェントを呼び出して、会話・コード差分・プレビュー・承認までチームで追える仕組みだ。

気になるのは「無料Slackでも使えるのか」「エージェント代は誰が払うのか」「API課金はどうなるのか」の3点。発表時点で確認できる範囲を整理する。

:::conclusion
Slack Codeそのものは、発表時点ではFreeを含む「any Slack plan」で利用可能と案内されている。ただし、そこで呼び出すClaude Code、Devin、GitHub Copilot、Vercel Agentなどの利用料金までSlackが無料で肩代わりする、という意味ではない。Slackは作業場所・オーケストレーション層で、実際のAIエージェント利用条件・クォータ・従量課金は各エージェント側の契約に依存すると考えるのが安全だ。
:::

## Slack Codeとは

Slack Codeでは、アイデアやバグ修正などの依頼時にコーディングエージェントをメンションすると、その作業専用のcode channelが立ち上がる。チャンネル内では、エージェントとの会話だけでなく、コードdiff、HTMLのライブプレビュー、フィードバック、出荷前の承認などを共有できる。作業完了後はチャンネルを自動アーカイブし、監査ログも残す設計とされている。

ローンチ時のパートナーとしてClaude Code、Devin、Vercel Agent、GitHub Copilotが挙げられている。

## 「APIでエージェントを差し替えられるハーネスをSlackに埋め込んだ」と考えると分かりやすい

Slack Codeの構造をざっくり捉えるなら、Slack自身が新しいコーディングAIモデルを提供したというより、**外部のコーディングエージェントをSlack上で扱うための共通ハーネスとUIを作った**と考えると分かりやすい。

イメージとしては次の3層だ。

1. **Slack Code**：指示、会話、diff、プレビュー、承認、監査、チャンネル管理を担当するフロントエンド／オーケストレーション層
2. **Claude Code・Devin・Copilot等**：コードを読んで推論し、編集・実行するエージェント層
3. **GitHub等**：実際のリポジトリや開発資産

つまりCodexやClaude Codeそのものというより、**複数のCodex系・コーディングエージェントを人間やチームが操作するための管制塔**に近い。

ただし、ここにはまだ重要な不明点がある。Slack Codeが最終的に

`Slack Code → 汎用Agent API → 好きなLLM／エージェント`

という完全にオープンなprovider差し替え型になるのか、それとも

`Slack Code → Claude Code adapter / Devin adapter / Copilot adapter`

のようにSlackが対応したサービスごとの個別コネクタ方式になるのかは、ローンチ時点の公開情報だけでは断定できない。

この違いはかなり重要だ。前者ならSlack Codeは本当に「エージェント・ハーネス」に近く、自作エージェントや好きなモデルを載せられる可能性がある。後者なら、Slackが用意した統合先を便利に切り替える「Agent Hub」に近い。

## Slack Freeでも使える？

ここは意外だった。

The Vergeのローンチ報道では、Slack Codeは「any Slack plan」で利用可能とされている。つまりSlack Codeという機能自体については、Freeプランも対象だ。

ただしSlack Freeには通常どおり制限がある。Slack公式料金表では、Freeは90日間のメッセージ履歴、最大10個のアプリなどの制限がある。コードチャンネルを長期の開発記録として使うなら、このあたりは効いてくる。

なお、Slack自身の「AI apps / Agents」開発機能については別扱いで、Slack Developer Docsは一部AI機能の開発・利用には有料プランが必要と案内している。無料で開発したい場合はDeveloper Programのフル機能sandboxを使える。

つまり「Slack CodeがFreeで使える」と「Slack上で独自AIエージェントアプリをFreeワークスペースに自由に構築できる」は同じ話ではない。

## では、Claude CodeやDevinの料金は？

ここが重要。

Slack Codeは複数社のエージェントをSlack上で呼び出すための共通の作業面に近い。少なくともローンチ時点の公開情報では、Slack Code向けにSlackが一括で「AIトークン○○円」のような課金をする仕組みは示されていない。

したがって、基本的には接続するエージェント側の契約・利用枠を消費する、と見るのが妥当だ。

たとえばDevinは2026年時点でFreeの限定利用に加え、Pro $20/月、Max $200/月、Teams $80/月最低などの料金体系が案内されており、プランによってSlack連携や追加利用枠の条件が異なる。Claude CodeもClaudeの個人向けPro/Max等の利用枠や、Team/EnterpriseではAnthropic Console側の課金条件が関係する。

:::warning
「Slack Free + Slack Code = Claude CodeやDevinまで無料」という意味ではない。Slack側の利用料と、AIエージェント側の利用料は分けて考える必要がある。各社の料金・Slack連携条件は変更が速いので、実際に接続する時点のプラン表を確認した方がよい。
:::

## APIはどうなる？

APIも2層に分けると分かりやすい。

### 1. Slack API

通常のSlack AppからSlack Web API、Events API、webhookなどを使って外部サービスと連携すること自体はFreeプランでも可能な範囲がある。Slack公式のPlatform overviewでも、HTTP APIを使うnon-workflow appやサードパーティAPI/webhook連携はMinimum PlanがFreeとされている。

ただし、Slackの新しいAgents & AI Apps機能を使って「AIエージェントとしてSlack UIに統合する」場合は条件が別で、Developer Docsでは一部AI機能にpaid planが必要としている。

### 2. エージェント／LLM側のAPI・利用枠

Claude Code、Devinなどが実際にコードを読んで推論・実行する部分は、そのサービス側の契約に従う。APIキー課金型ならAPI利用料、サブスク型ならサブスクのクォータ、クレジット型ならクレジットを消費する。

Slack Codeは、この外部エージェントの計算資源を無料化するものではない。

## 個人開発だとかなり面白そう

個人で使う場合、「SlackそのものはFree」「Slack CodeもFree対象」「実行エージェントだけ既存のClaude CodeやDevin等の契約を使う」という構成が成立するならかなり魅力的だ。

特に、スマホからSlackで指示→エージェントがリポジトリを触る→diffやプレビューを見る→承認、という流れがまとまるなら、Slackが単なるチャットではなくAI開発のフロントエンドになる。

ただしローンチ直後なので、各パートナーの認証方法、既存サブスクリプションをそのまま持ち込めるのか、APIキーが必要なのか、Slack Code経由で追加課金が発生するのかは、エージェントごとの実装を確認する必要がある。

## まとめ

現時点では次の理解でよさそうだ。

- Slack Code自体はFreeを含む全Slackプランが対象
- Freeプラン固有の90日履歴・アプリ数などの制限は残る
- Claude CodeやDevinなどのエージェント利用料は別物
- Slack APIの一般的なアプリ連携はFreeでも可能
- SlackのAgents & AI Appsとして独自エージェントを構築する場合は有料プラン条件に注意
- 構造的には「Slackにエージェント・ハーネス／Agent Hubを埋め込んだ」と見ると理解しやすい
- 汎用provider APIなのか個別adapter方式なのかは現時点では不明
- 「Slack Code経由のエージェント課金」の細部はローンチ直後で、各パートナーの実装確認が必要

Slack Codeは「SlackがAIコーディングそのものを売る」というより、複数のコーディングエージェントをチームで扱うためのUI・協働レイヤーとして見ると分かりやすい。

## 参考

- Slack Pricing Plans: https://slack.com/pricing
- Slack Developer Docs, Developing an agent: https://docs.slack.dev/ai/developing-agents/
- Slack Platform overview: https://api.slack.com/automation/
- The Verge, “Slack is launching collaborative vibe-coding channels” (2026-08-20)
