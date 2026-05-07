---
title: "GitHub Copilot CLIはStudent Planでどのモデルが使える？2026年3月24日時点の公式情報まとめ"
slug: "github-copilot-student-cli-models-jp"
status: "publish"
categories:
  - "AI"
tags:
  - "GitHub Copilot"
  - "Copilot CLI"
  - "GitHub Education"
  - "Student Plan"
  - "AI"
---

# GitHub Copilot CLIはStudent Planでどのモデルが使える？2026年3月24日時点の公式情報まとめ

GitHub Copilot CLI を学生特典で使っていると、いちばん気になるのは「結局どのモデルまで選べるのか」だと思います。

以前は「Student でも Copilot 自体は使える」という説明だけで終わりがちでしたが、2026年3月24日時点の GitHub 公式ドキュメントでは、`Copilot Student` は独立したプランとして整理されており、`premium models in Copilot Chat` と `Copilot CLI` の両方が案内されています。

この記事では、2026年3月24日時点の公式情報をもとに、Student で押さえておくべきモデル範囲と注意点を整理します。

:::conclusion
2026年3月24日時点では、GitHub Copilot Student でも Copilot CLI は利用可能です。公式の対応モデル一覧には Claude / Gemini / GPT 系を含む複数モデルが並んでいますが、実際に自分の CLI で選べる候補は `/model` で確認するのが確実です。
:::

## まず前提：Student はいまどういう扱いか

GitHub Docs のプラン比較では、`Copilot Student` は `verified student` 向けのプランとして案内されています。

案内内容をそのまま整理すると、Student では次の点が重要です。

- `unlimited completions`
- `premium models in Copilot Chat`
- `Copilot coding agent`
- `300 premium requests per month`
- `Copilot CLI`

つまり、Student は「無料版に少し毛が生えたもの」ではなく、かなり実用的な個人向けプランとして扱われています。

## 2026年3月24日時点で公式一覧に出ているモデル

GitHub Docs の `Supported AI models in GitHub Copilot` では、`Copilot Student` の列を含む形で対応モデル一覧が掲載されています。2026年3月24日時点で確認できるモデル名は次のとおりです。

### Anthropic 系

- `Claude Haiku 4.5`
- `Claude Opus 4.5`
- `Claude Opus 4.6`
- `Claude Opus 4.6 (fast mode) (preview)`
- `Claude Sonnet 4`
- `Claude Sonnet 4.5`
- `Claude Sonnet 4.6`

### Google 系

- `Gemini 2.5 Pro`
- `Gemini 3 Flash`
- `Gemini 3 Pro`
- `Gemini 3.1 Pro`

### OpenAI 系

- `GPT-4.1`
- `GPT-5 mini`
- `GPT-5.1`
- `GPT-5.1-Codex`
- `GPT-5.1-Codex-Mini`
- `GPT-5.1-Codex-Max`
- `GPT-5.2`
- `GPT-5.2-Codex`
- `GPT-5.3-Codex`
- `GPT-5.4`
- `GPT-5.4 mini`

### その他

- `Grok Code Fast 1`
- `Raptor mini`
- `Goldeneye`

:::note
ここで重要なのは、「公式の対応モデル一覧に Student の列があり、2026年3月24日時点で上記モデル名が掲載されている」という点です。今後の追加・削除・段階提供で見える候補は変わる可能性があります。
:::

## Copilot CLI ではどう見ればいいか

Copilot CLI の公式説明では、CLI は `all Copilot plans` で使えるとされています。Student もこの対象に含まれます。

さらに、Copilot CLI のモデル利用説明では、次の点が明記されています。

- デフォルトモデルは `Claude Sonnet 4.5`
- CLI の対話中に `/model` で変更できる
- 起動時に `--model` でも指定できる
- 送信ごとに `premium requests` を消費する
- 消費量はモデルごとの倍率で変わる

Student は月 `300 premium requests` なので、CLI を重めのモデルで常用すると、VS Code 側のチャット利用分と合算で減っていく点は意識したほうがいいです。

## 「全部使える」と言い切っていいのか

ここは少し慎重に見たほうが安全です。

GitHub のプラン比較では、`Copilot Pro+` について `full access to all available models in Copilot Chat` と書かれています。つまり、将来も含めて常に全モデルが同じ条件で Student に開くとは限りません。

実運用では、次の3つを分けて考えると混乱しにくいです。

1. 公式ドキュメント上で Student 向け対応表に載っているか
2. 自分のアカウントで実際にロールアウトされているか
3. CLI と IDE のどちらで使っているか

:::step
いちばん確実なのは、Copilot CLI を起動して `/model` を実行し、現時点で自分のアカウントに表示されるモデル名を直接確認することです。
:::

## Student で使うなら、どう考えるのが実務的か

「Student で何が使えるか」を実務目線でまとめると、次の理解で十分です。

- Copilot CLI 自体は Student でも使える
- 公式一覧には Claude / Gemini / GPT 系を含む複数モデルが掲載されている
- ただし実際に今選べる候補はアカウント画面で確認するのが最終的に正しい
- Student は `300 premium requests/month` なので、CLI ではモデル倍率も気にしたほうがいい

特に、CLI で大きなコンテキストを投げる使い方をする人ほど、モデル名だけでなく「倍率」を見たほうが失敗しにくいです。

## まとめ

2026年3月24日時点の公式情報では、GitHub Copilot Student でも Copilot CLI は問題なく利用できます。対応モデル一覧には Claude / Gemini / GPT 系を含むかなり広いモデル群が掲載されています。

ただし、実際の利用可否はアカウントへの段階提供やクライアント差分の影響を受けることがあります。なので、最終確認は `Copilot CLI の /model` で行うのが一番確実です。

References:
1. Plans for GitHub Copilot
<https://docs.github.com/en/copilot/get-started/plans>
2. Supported AI models in GitHub Copilot
<https://docs.github.com/en/copilot/reference/ai-models/supported-models>
3. About GitHub Copilot CLI
<https://docs.github.com/en/copilot/concepts/agents/copilot-cli/about-copilot-cli>
4. Install GitHub Copilot CLI
<https://docs.github.com/copilot/how-tos/set-up/install-copilot-cli>
