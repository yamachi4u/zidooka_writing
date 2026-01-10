---
title: "【コード無料】Cloud Vision OCRをCLIで安全に回す最小構成スクリプト"
date: 2025-12-17 16:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - Python
  - CLI
  - Code Snippet
status: publish
slug: cloud-vision-ocr-minimal-script-jp
featured_image: ../images/2025/image copy 25.png
---

Cloud Vision API の OCR を実務で使う際、
「公式ドキュメントのサンプルだと断片的すぎて、結局どう繋げばいいか分からない」
ということがよくあります。

特に、

*   GCS へのアップロード
*   非同期 OCR の実行
*   結果 JSON の取得と結合

これらを一連の流れとして実行できるスクリプトがあると便利です。

今回は、私が実際に使用している **「Cloud Vision OCR を CLI で安全に回す最小構成スクリプト」** を公開します。

セキュリティに配慮し、認証情報をハードコードしない設計にしています。
コピペして、ご自身の環境に合わせて使ってください。

## 最小構成スクリプト (Python)

このスクリプトは以下の機能を持っています。

1.  GCS バケットの作成（なければ）
2.  PDF のアップロード
3.  非同期 OCR の実行
4.  結果 JSON のダウンロードとテキスト結合

```python
import os
from google.cloud import vision
from google.cloud import storage
import json

# ==============================
# Configuration (ANONYMIZED)
# ==============================

# NOTE:
# - Do NOT hardcode real credential file names in public code
# - Use environment variables or placeholders

# Example (set outside this script):
# export GOOGLE_APPLICATION_CREDENTIALS="/path/to/your/service-account.json"

# os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = "your-service-account.json"  # ❌ avoid in public code


def create_bucket_if_missing(bucket_name: str):
    """Create a GCS bucket if it does not exist."""
    storage_client = storage.Client()
    bucket = storage_client.bucket(bucket_name)

    if not bucket.exists():
        print(f"Bucket '{bucket_name}' not found. Creating...")
        storage_client.create_bucket(bucket)
        print(f"Bucket '{bucket_name}' created.")
    else:
        print(f"Bucket '{bucket_name}' already exists.")


def upload_blob(bucket_name: str, source_file: str, destination_blob: str):
    """Upload a local file to GCS."""
    storage_client = storage.Client()
    bucket = storage_client.bucket(bucket_name)
    blob = bucket.blob(destination_blob)

    blob.upload_from_filename(source_file)
    print(f"Uploaded '{source_file}' to 'gs://{bucket_name}/{destination_blob}'.")


def async_detect_document(gcs_source_uri: str, gcs_destination_uri: str):
    """Run async OCR (PDF/TIFF) using Cloud Vision API."""

    client = vision.ImageAnnotatorClient()

    feature = vision.Feature(
        type_=vision.Feature.Type.DOCUMENT_TEXT_DETECTION
    )

    input_config = vision.InputConfig(
        gcs_source=vision.GcsSource(uri=gcs_source_uri),
        mime_type="application/pdf",
    )

    output_config = vision.OutputConfig(
        gcs_destination=vision.GcsDestination(uri=gcs_destination_uri),
        batch_size=1,
    )

    request = vision.AsyncAnnotateFileRequest(
        features=[feature],
        input_config=input_config,
        output_config=output_config,
    )

    print(f"Running OCR for: {gcs_source_uri}")
    operation = client.async_batch_annotate_files(requests=[request])
    operation.result(timeout=300)
    print("OCR completed.")


def list_blobs(bucket_name: str, prefix: str):
    """List blobs in a bucket with a given prefix."""
    storage_client = storage.Client()
    return storage_client.list_blobs(bucket_name, prefix=prefix)


def fetch_ocr_results(gcs_destination_uri: str):
    """Download OCR result JSON files and merge extracted text."""

    if not gcs_destination_uri.startswith("gs://"):
        raise ValueError("Destination URI must start with gs://")

    path = gcs_destination_uri.replace("gs://", "")
    bucket_name, prefix = path.split("/", 1)

    output_dir = "ocr_output"
    os.makedirs(output_dir, exist_ok=True)

    full_text = ""

    for blob in list_blobs(bucket_name, prefix):
        if not blob.name.endswith(".json"):
            continue

        local_path = os.path.join(output_dir, os.path.basename(blob.name))
        blob.download_to_filename(local_path)

        with open(local_path, "r", encoding="utf-8") as f:
            data = json.load(f)

        for idx, page in enumerate(data.get("responses", []), start=1):
            annotation = page.get("fullTextAnnotation")
            if not annotation:
                continue

            text = annotation.get("text", "")
            full_text += f"\n\n--- Page {idx} ---\n{text}"

    with open("final_text_output.txt", "w", encoding="utf-8") as f:
        f.write(full_text)

    print("Merged OCR text saved to final_text_output.txt")


if __name__ == "__main__":
    # ==============================
    # Example placeholders ONLY
    # ==============================

    BUCKET_NAME = "your-ocr-bucket-name"
    INPUT_PDF = "input.pdf"
    OUTPUT_PREFIX = "ocr_results/"

    gcs_source = f"gs://{BUCKET_NAME}/{INPUT_PDF}"
    gcs_output = f"gs://{BUCKET_NAME}/{OUTPUT_PREFIX}"

    # create_bucket_if_missing(BUCKET_NAME)
    # upload_blob(BUCKET_NAME, INPUT_PDF, INPUT_PDF)
    # async_detect_document(gcs_source, gcs_output)
    fetch_ocr_results(gcs_output)
```

## このコードのポイント

### 1. 認証情報をハードコードしない
`GOOGLE_APPLICATION_CREDENTIALS` は環境変数で渡す前提にしています。
コード内に `key.json` のパスや中身を書かないことで、誤って GitHub 等に公開しても事故が起きないようにしています。

### 2. OCR 結果の結合処理
Cloud Vision API はページごとに分割された JSON を出力します。
`fetch_ocr_results` 関数では、それらを自動でダウンロードし、ページ順に結合して `final_text_output.txt` に保存します。
実務では「結局テキストが欲しい」というケースがほとんどなので、ここまで自動化しておくと非常に楽です。

### 3. 再実行のしやすさ
`if __name__ == "__main__":` ブロック内で、必要な処理だけをコメントアウト解除して実行できるようにしています。
OCR は時間がかかるため、「OCR は終わっているから結果取得だけしたい」という場合に便利です。

## 使い方

1.  必要なライブラリをインストール
    ```bash
    pip install google-cloud-vision google-cloud-storage
    ```
2.  認証情報を環境変数にセット
    ```bash
    export GOOGLE_APPLICATION_CREDENTIALS="/path/to/your-service-account.json"
    ```
3.  スクリプト内の `BUCKET_NAME` などを書き換えて実行

:::warning
**注意点**
出力される `ocr_output/` フォルダや `final_text_output.txt` には、OCR 結果（機密情報を含む可能性）が含まれます。
これらを Git 管理に含めないよう、`.gitignore` に追加することを推奨します。
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
