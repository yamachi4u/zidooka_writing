---
title: "GCPのGUIで詰んだら、Cloud Shell＋CUIに逃げたほうが早かった話（失敗談）"
date: 2025-12-17 13:00:00
categories: 
  - Journal
tags: 
  - GCP
  - Cloud Shell
  - CLI
  - Troubleshooting
  - Security
status: publish
slug: gcp-gui-vs-cloud-shell-failure-story-jp
featured_image: ../images/image copy 24.png
---

正直に言うと、
GCPのGUIだけで何とかしようとして、かなり消耗しました。

IAM、API有効化、権限エラー。
画面を行ったり来たりして、

*   どこで何を設定したのか分からなくなる
*   エラーメッセージが抽象的で原因が見えない
*   クリック操作の履歴が残らない

この状態で「たぶんここかな？」を繰り返して、
気づいたら結構な時間が経っていました。

## 転機：Cloud Shell を開いた瞬間

途中でふと、

:::note
あ、これ Cloud Shell 起動して gcloud 叩いたほうが早いのでは？
:::

と思って、GCP内で Cloud Shell を起動。

そこからは一気に状況が変わりました。

:::step
1.  `gcloud config get-value project` で今どのプロジェクトを触ってるか即確認
2.  `gcloud services enable xxx.googleapis.com` でAPI有効化を明示的に実行
3.  IAMの付与エラーも、CLIのエラー文のほうがまだ意味が分かる
:::

何より大きかったのは、
**その場でAIにCUIのログを貼って相談できること**。

GUIのスクショより、
コマンド＋エラーメッセージのほうが圧倒的に話が早い。

## 後から思ったこと

今振り返ると、

:::conclusion
*   GUIは「設定が分かっている人」向け
*   トラブルシュートはCUIのほうが圧倒的に強い
*   GCPは最初から Shell前提で考えたほうが精神衛生にいい
:::

という結論でした。

GCPのGUIで死にかけている人ほど、
一度 Cloud Shell を起動して、

*   `gcloud`
*   ログ
*   エラーメッセージ

をそのままAIに投げながら進めたほうが、
結果的に早く・正確に・安全に進められると思います。

これは完全に、
自分が遠回りして気づいた失敗談です。

:::warning
**【重要】AIにログを投げる際の注意点**
CUIに逃げるのは効率的ですが、セキュリティには十分注意してください。
APIキー、サービスアカウントキー（JSONの中身）、パスワードなどの**機密情報は絶対にAIに貼り付けない**ようにしましょう。
ログを貼る前に、必ず機密情報が含まれていないか確認する癖をつけてください。
:::

---

### Cloud Vision OCR シリーズ記事一覧

本記事は、Cloud Vision API を使った OCR 環境構築・運用に関するシリーズ記事の一部です。

*   **[【まとめ】Cloud Vision OCR × CLI × GCP 導入・トラブルシュート全記録](/archives/ocr-series-summary-jp)**
*   **導入編:** [Cloud Vision API の OCR が IAM で通らない理由と、最終的に動かすまでの全記録](/archives/cloud-vision-api-ocr-iam-trouble-jp)
*   **運用編:** [Cloud Vision OCRをCLIで回すのが一番ラクだった理由](/archives/cloud-vision-ocr-cli-best-practice-jp)
*   **ワークフロー編:** [頻繁にOCRするなら GCP × VSC × CLI が正解ルートになる理由](/archives/frequent-ocr-gcp-vsc-cli-workflow-jp)
*   **ツール編:** [Cloud Vision OCRをCLIで回すために作った実務向けツール解説](/archives/cloud-vision-ocr-cli-tool-jp)
*   **コード編:** [【コード無料】Cloud Vision OCRをCLIで安全に回す最小構成スクリプト](/archives/cloud-vision-ocr-minimal-script-jp)
*   **番外編:** [GCPのGUIで詰んだら、Cloud Shell＋CUIに逃げたほうが早かった話（失敗談）](/archives/gcp-gui-vs-cloud-shell-failure-story-jp)
