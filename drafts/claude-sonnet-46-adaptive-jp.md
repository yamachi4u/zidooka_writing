---
title: "Claudeの「アダプティブ」とは？Sonnet 4.6の表示はadaptive thinkingのこと"
categories:
  - AI
tags:
  - Claude
  - Claude Sonnet 4.6
  - Anthropic
  - Adaptive Thinking
  - AI
status: publish
slug: claude-sonnet-46-adaptive-jp
featured_image: ../images/2026/04/claude-sonnet-46-adaptive-thumbnail.png
---

Claudeの画面で、モデル名の横に「Sonnet 4.6 アダプティブ」と出ていました。

一瞬、「アダプテxブ？」「adaptiveのこと？」と思ったのですが、調べるとこれはClaude API側で説明されている **adaptive thinking** と見てよさそうです。

:::conclusion
Claudeの「アダプティブ」は、Sonnet 4.6がタスクの難しさに応じて思考量を自動調整するモードを指している可能性が高いです。別モデル名ではなく、思考モードの表示だと考えるのが自然です。
:::

## adaptive thinkingとは

Anthropicの公式ドキュメントでは、adaptive thinkingは「Claudeがいつ、どれくらい拡張思考を使うかを動的に判断するモード」と説明されています。

APIでは次のように指定します。

```json
{
  "thinking": {
    "type": "adaptive"
  }
}
```

従来の拡張思考では、`budget_tokens` のように思考用トークン数を人間が指定する方式がありました。adaptive thinkingでは、Claude側がリクエストの複雑さを見て、思考するか、どの程度深く考えるかを調整します。

## Sonnet 4.6でも使われる

公式ドキュメントでは、adaptive thinkingの対応モデルに `claude-sonnet-4-6` が含まれています。

また、Sonnet 4.6では `effort` によって思考の深さを調整する考え方が出ています。`high` なら深く考えやすく、`medium` や `low` では簡単なタスクで思考を省略することがあります。

つまり、ClaudeのUIに「アダプティブ」と出ているなら、意味としては次のようなものです。

- 簡単な質問ではすばやく答える
- 複雑な問題では拡張思考を使う
- ツール利用やエージェント的な作業では途中で考え直しやすい
- 人間が固定の思考トークン数を指定する方式ではない

:::note
モデルはSonnet 4.6のままで、横の「アダプティブ」は思考モードを示している表示だと考えられます。
:::

## 「アダプティブ」は誤字ではない

画像の表示は「アダプティブ」です。

英語の `adaptive` を日本語UIでカタカナにしたものです。意味は「適応型」「状況に応じて変える」というニュアンスです。

Claudeの場合は、毎回同じだけ長く考えるのではなく、タスクの難易度に応じて思考量を変える、という意味になります。

## 何が便利なのか

便利なのは、ユーザーが「これは軽い質問」「これは深く考えて」と毎回細かく指定しなくても、Claude側がある程度判断してくれるところです。

たとえば、単純な翻訳や短い確認なら軽く答える。一方で、コード修正、長い調査、複数ステップの作業、ツールを使う作業では、より深く考える。

この切り替えが自動になるので、チャットUIでは「普通に使っているだけで、必要なときに考える」体験に近くなります。

## API利用者はeffortも見る

Claude APIで使う場合は、adaptive thinkingだけでなく `output_config.effort` も関係します。

```json
{
  "thinking": {
    "type": "adaptive"
  },
  "output_config": {
    "effort": "medium"
  }
}
```

Anthropicの説明では、`effort` は思考量に対するソフトな指示です。速度やコストを優先するなら `low`、品質を優先するなら `high` や `max` を使う、という考え方になります。

:::warning
APIで安定したレイテンシやコスト管理が必要な場合は、adaptive thinking任せにせず、`effort` と `max_tokens` を明示してテストした方がよいです。
:::

## まとめ

Claudeの「Sonnet 4.6 アダプティブ」という表示は、Claude Sonnet 4.6がadaptive thinkingモードで動いていることを示す表示だと考えられます。

これは「アダプテxブ」という謎の別機能ではなく、英語の `adaptive`、つまり「適応型」のことです。

:::conclusion
Claudeのアダプティブは、タスクに応じて思考量を変えるモードです。Sonnet 4.6の横に出ていても、モデルが変わったというより、思考モードの表示と見ればよさそうです。
:::

参考:

- [Adaptive thinking - Claude API Docs](https://platform.claude.com/docs/en/build-with-claude/adaptive-thinking)
- [Effort - Claude API Docs](https://platform.claude.com/docs/en/build-with-claude/effort)
- [Migration guide - Claude API Docs](https://platform.claude.com/docs/ja/about-claude/models/migration-guide)
