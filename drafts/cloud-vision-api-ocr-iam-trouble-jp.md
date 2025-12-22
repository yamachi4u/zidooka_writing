---
title: "Cloud Vision API の OCR が IAM で通らない理由と、最終的に動かすまでの全記録"
date: 2025-12-17 10:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - IAM
  - GCP
  - トラブルシューティング
status: publish
slug: cloud-vision-api-ocr-iam-trouble-jp
featured_image: ../images/twitterposttool/cloud-vision-api-ocr.png
---

Cloud Vision API を使って OCR を実行しようとした際、
IAM 設定が原因で処理が一切進まないという状況に直面しました。

公式ドキュメントに従って設定しているはずなのに、
以下のようなエラーが何度も表示されます。

`INVALID_ARGUMENT: Role roles/vision.user is not supported for this resource.`

本記事では、

1. なぜこのエラーが発生するのか
2. 公式通りにやっても通らない理由
3. 最終的に OCR を「確実に動かす」ために取った判断

を、実体験ベースで整理します。

## やろうとしたこと

*   Cloud Vision API を使った OCR
*   対象は画像および PDF
*   ローカル環境（VSC）からの実行
*   単発処理・検証用途が目的

特別な構成ではなく、
多くの人が一度は通るであろうユースケースです。

## 公式ドキュメント通りに行った設定

### 1. Cloud Vision API を有効化

Cloud Vision API をプロジェクトで有効化し、
有効化状態も確認しました。

この時点で API 側の準備は問題ありません。

### 2. サービスアカウントを作成

OCR 実行用に専用のサービスアカウントを作成しました。

### 3. roles/vision.user を付与しようとした

公式ドキュメントでは、
Cloud Vision API User（`roles/vision.user`） を
サービスアカウントに付与するよう案内されています。

そのため、IAM（GUI / CLI）から
該当ロールの付与を試みました。

## 発生した問題

しかし、何度試しても結果は同じでした。

*   **GUI**：ロールが検索結果に出てこない
*   **CLI**：ロール指定時にエラーが発生する

表示されるエラーは次のようなものです。

`Role roles/vision.user is not supported for this resource.`

API は有効、サービスアカウントも正しい。
それでも **ロールが付けられない** 状態です。

## なぜこの問題が起きるのか

この挙動は、設定ミスというより GCP 側の仕様・状態依存によるものと考えられます。

具体的には、

*   プロジェクトの作成タイミング
*   組織ポリシーや IAM Conditions の有無
*   ロールの露出状態（GUI / CLI）

などの影響で、
`roles/vision.user` をプロジェクトに直接バインドできないケースが存在します。

しかもこの状態は、

*   公式ドキュメントに明確な説明がない
*   エラーメッセージから原因が分かりにくい

という点で、非常にハマりやすいポイントです。

## 最終的に取った判断

結論として、
`roles/vision.user` にこだわるのをやめました。

代わりに、検証用途として割り切り、

*   プロジェクトレベルで実行可能なロール
*   API 利用に必要な権限を内包するロール

を付与したところ、
OCR は問題なく実行できる状態になりました。

### なぜこの判断で問題なかったのか

Cloud Vision API の OCR 実行において重要なのは、

1.  API が有効化されていること
2.  正しく認証されたサービスアカウントであること
3.  プロジェクト内での実行権限があること

です。

単発処理や検証用途であれば、
ロールを厳密に分けることよりも
「確実に動かす」ことを優先する判断が現実的な場合もあります。

## 実際に動かした構成（概要）

*   **API**：Cloud Vision API（有効化済み）
*   **認証**：サービスアカウントキー（JSON）
*   **実行環境**：ローカル（VSC）
*   **OCR**：画像／PDF ともに正常動作

この構成で、OCR 処理は問題なく完了しました。

## 同じところで詰まっている人へ

*   `roles/vision.user` が付けられなくても、必ずしも詰みではありません
*   エラーは設定ミスではなく、プロジェクト側の状態によることがあります
*   検証・単発用途であれば、割り切った判断が必要な場面もあります

どうしても IAM を厳密に管理したい場合は、

*   OCR 専用のプロジェクトを切る
*   後からロールを整理する

といった方法も検討できます。

## まとめ

*   Cloud Vision API の IAM 設定は、公式通りに進まないケースがある
*   `roles/vision.user` が付与できない状況は実在する
*   動かすことを優先した判断で OCR は問題なく実行できる
*   実務では「正しさ」と「通る設定」を切り分けて考える必要がある

この記事が、同じところで立ち止まっている人の
判断材料になれば幸いです。

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
