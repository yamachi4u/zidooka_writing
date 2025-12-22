---
title: "Cloud Vision OCRをCLIで回すために作った実務向けツール解説"
date: 2025-12-17 15:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - CLI
  - Python
  - Tooling
status: publish
slug: cloud-vision-ocr-cli-tool-jp
featured_image: ../images/twitterposttool/cloud-vision-api-ocr.png
---

Cloud Vision API の OCR は精度が高く、PDF 全体をまとめて処理できる点でも非常に優秀です。ただし、公式サンプルをそのまま使うだけでは、実務で継続的に使うには少し物足りません。

この記事では、自分用に作った **「PDF を CLI から投げて、最終的に“読めるテキスト”を手元に残す」** OCR ツールについて、構成の考え方と実装例を整理します。

## なぜ GUI ではなく CLI なのか

最初は GCP コンソール（GUI）で OCR を試しました。動作確認としては十分ですが、実際に何度も PDF を処理するようになると、次の点が気になり始めます。

*   毎回ブラウザを開く必要がある
*   非同期 OCR の結果確認が分かりづらい
*   出力が JSON のままで、人が読む前提になっていない

結果として、

:::note
OCR はできたけれど、このあとどう使えばいいのか分からない
:::

という状態になりがちです。

そこで今回は、
**「OCR を作業ではなくコマンドとして扱う」** ことを目的に、CLI ツールとして組みました。

## ツール全体の流れ

このツールは、次の流れで動きます。

:::step
1.  GCS バケットの存在チェック（なければ作成）
2.  OCR 対象の PDF を GCS にアップロード
3.  Cloud Vision API で非同期 OCR を実行
4.  出力された JSON をすべて取得
5.  ページ順にテキストを結合
6.  最終的に 1 つのテキストファイルとして保存
7.  OCR 特有の不要な表記を後処理でクリーンアップ
:::

ポイントは、**OCR 処理と「人が読むための整形」を分けて考えている**点です。

## PDF OCR を非同期で回す実装

PDF の OCR はページ数が多くなると時間がかかるため、同期処理ではなく非同期 API を使っています。

```python
from google.cloud import vision

feature = vision.Feature(
    type_=vision.Feature.Type.DOCUMENT_TEXT_DETECTION
)
```

OCR の入力は GCS 上の PDF、出力も GCS に保存する構成です。

```python
gcs_source = vision.GcsSource(uri=gcs_source_uri)
input_config = vision.InputConfig(
    gcs_source=gcs_source,
    mime_type="application/pdf"
)
```

この構成にしておくと、

*   ローカル環境が落ちても結果が残る
*   大きな PDF でも比較的安定する
*   後から何度でも結果を取得できる

といったメリットがあります。

## OCR 結果をまとめて取得・結合する処理

Cloud Vision の PDF OCR は、ページ単位の JSON を複数ファイルとして出力します。そのままでは扱いづらいため、次の処理を行っています。

```python
for blob in blobs:
    if blob.name.endswith(".json"):
        blob.download_to_filename(local_json_path)
        with open(local_json_path, "r", encoding="utf-8") as f:
            response = json.load(f)

        for i, res in enumerate(response['responses']):
            if 'fullTextAnnotation' in res:
                text = res['fullTextAnnotation']['text']
                full_text += f"\n\n--- Page {i+1} ---\n{text}"
```

このようにして、
**PDF 全体を 1 つのテキストとして読める形** にしています。

## 地味だけど重要な「最終出力のクリーンアップ」

OCR 結果をそのままテキスト化すると、次のような不要な表記が混ざることがあります。

```
(ocr_results/output-12-to-13.json)
```

機械的には意味がありますが、人が読む用途では完全に不要です。そこで、OCR 処理とは別に **テキスト整形専用のスクリプト** を用意しました。

```python
import re

new_content = re.sub(
    r'\s*\(ocr_results\/output-[\d]+-to-[\d]+\.json\)',
    '',
    content
)
```

この処理を分離している理由は、

*   OCR 本体と責務を分けたい
*   後処理ルールを後から簡単に調整したい

という点にあります。

この一手間を入れるだけで、**最終的なテキストの読みやすさがかなり変わります**。

## 実務で使ってみて感じたメリット

実際にこの構成で OCR を回してみて、次の点が特に良かったです。

*   OCR を「操作」ではなく「コマンド」として扱える
*   PDF が増えても手順が一切変わらない
*   Cloud Vision の精度とコストのバランスを活かせる
*   LLM 要約や再加工にそのまま渡せるテキストが手に入る

OCR 後の用途（要約・検索・再構成）まで考えると、
**このくらい一気通貫にしておく方が結果的に楽** でした。

## まとめ

Cloud Vision OCR は、精度だけを見ると「すごい」で終わりがちです。しかし実務では、

:::conclusion
*   どう回すか
*   どう回し続けるか
*   どう使う形で残すか
:::

まで設計して初めて価値が出ます。

今回の CLI ツールは、
**OCR を日常的な作業フローに組み込むための最小構成** です。

PDF OCR を頻繁に行う場合、GUI よりも CLI の方が結果的にストレスが少なくなると感じています。

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
