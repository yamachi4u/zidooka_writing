---
title: "Gemini API (Imagen 3) で画像生成すると勝手に「透かし」が入る問題と、その回避策"
date: 2026-02-16 09:00:00
categories: 
  - AI
tags: 
  - Gemini
  - Imagen 3
  - Python
  - OpenCV
  - 生成AI
status: publish
slug: gemini-imagen3-watermark-issue
featured_image: ../images-agent-browser/gemini-deepmind.png
---

Google の Gemini API で利用可能な最新の画像生成モデル「Imagen 3 (gemini-3-pro-preview)」は、驚くほどフォトリアルな画像を生成できる強力なモデルです。しかし、API経由で生成した画像に、Googleのロゴ（スパークマーク）や学習データ由来の透かし（Watermark）がランダムに混入するという問題が報告されています。

この記事では、なぜGemini APIで透かしが入ってしまうのか、その原因と、PythonとOpenCVを使ってクリーンな画像だけを自動的に選別する回避策について解説します。

## Gemini API で発生する「透かし」問題とは

Gemini API (`google-genai` SDK) を使用して、特に「写真のような（Photorealistic）」スタイルで画像を生成すると、出力されたJPEG画像に以下のようなアーティファクトが含まれることがあります。

1.  **Google AI スパークマーク:** GoogleのAI生成コンテンツであることを示す、キラキラした3つの星のようなアイコン。
2.  **学習データ由来の透かし:** ストックフォトサービス（Getty Imagesなど）のロゴや、著作権表記のような文字。

これらはプロンプトで「透かしを入れないで」と指示しても、完全に防ぐことは難しく、ランダムに発生します。商用利用やポートフォリオ作成を目的としている場合、これらが混入した画像は使用できないため、大きな課題となります。

## なぜ透かしが入るのか

この現象には、大きく分けて2つの原因が考えられます。

### 1. Google の AI 生成コンテンツ識別ポリシー
Google は責任あるAIの開発を掲げており、生成されたコンテンツがAIによるものであることを明示するために、可視透かし（Visible Watermark）や不可視透かし（SynthID）を付与する仕組みを導入しています。特にWeb上の Gemini Advanced などでは顕著ですが、API経由でも特定の条件下でスパークマークが付与される仕様になっている可能性があります。

### 2. 学習データの過学習（Dataset Pollution）
「高画質」「スタジオ撮影」「RAW写真」といったプロンプトを使用すると、モデルは高品質なストックフォトのデータを強く参照します。その結果、学習データに含まれていたストックフォトサービスの透かしやロゴを「高画質な写真の一部」として誤って学習・再現してしまうことがあります。これは画像生成AI特有の過学習（Overfitting）の一種です。

:::note
Imagen 3 はテキスト描画能力が高いため、従来のモデルよりも「透かしの文字」をくっきりと正確に描いてしまう傾向があります。
:::

## 解決策1：プロンプトエンジニアリングで抵抗する

最も手軽な対策は、プロンプトの中で「透かしを描かないこと」を強く指示することです。日本語よりも英語の指示の方が効果が高い傾向にあります。

以下のような **Negative Constraint（否定制約）** をプロンプトの末尾に追加してみてください。

```text
... (メインのプロンプト) ...

IMPORTANT: Do not include any watermarks, logos, text overlays, signatures, date stamps, or copyright notices in the image. The image should be clean and free of any text or artifacts.
```

:::warning
Gemini API には Stable Diffusion のような `negative_prompt` パラメータがありません（2025年2月時点）。そのため、通常のプロンプトテキスト内で否定命令を行う必要がありますが、効果は限定的です。
:::

## 解決策2：OpenCV で検知して自動再生成する（推奨）

プロンプトだけでは完全に防げない場合、**「透かしが入った画像は失敗とみなし、即座に再生成する」** というアプローチが最も確実です。

Python の画像処理ライブラリ OpenCV を使用すれば、生成された画像に特定のパターン（スパークマークなど）が含まれているかを高速に判定できます。

### 実装例

あらかじめ、よく出る「スパークマーク」や「透かし」の部分を切り抜いた画像をテンプレートとして用意します（`template_watermark.png`）。

```python
import cv2
import numpy as np
from pathlib import Path

def has_watermark(image_path: str, template_path: str, threshold: float = 0.8) -> bool:
    """
    生成された画像に透かしテンプレートが含まれているか判定する
    """
    # 画像をグレースケールで読み込み
    img = cv2.imread(image_path, 0)
    template = cv2.imread(template_path, 0)
    
    if img is None or template is None:
        return False

    # テンプレートマッチング実行
    result = cv2.matchTemplate(img, template, cv2.TM_CCOEFF_NORMED)
    min_val, max_val, min_loc, max_loc = cv2.minMaxLoc(result)
    
    # 一致度が閾値を超えたら「透かしあり」と判定
    if max_val >= threshold:
        return True
    
    return False

# 使用例（生成ループの中で呼ぶ）
# if has_watermark("generated.jpg", "spark_mark_template.png"):
#     print("透かしを検知しました。再生成します。")
#     # リトライ処理へ
```

この処理を画像生成パイプラインに組み込むことで、寝ている間に大量の画像を生成しても、フォルダにはクリーンな画像だけが残るようになります。

:::step
**テンプレート画像の作り方**
1. 実際に透かしが入ってしまった生成画像を1枚用意する。
2. ペイントソフトなどで、透かし部分（スパークマーク等）だけを矩形で切り抜く。
3. `template_watermark.png` として保存する。背景は透過せず、そのまま切り抜いた画像を使用する方がマッチング精度が高くなります。
:::

## まとめ

Gemini API (Imagen 3) の透かし問題は、プロンプトの工夫だけでは回避しきれないのが現状です。しかし、OpenCVなどを使った検知ロジックを組み合わせることで、業務レベルの品質を担保することは十分に可能です。

AIモデルの特性（不確実性）を、従来のプログラミング（確実性）でカバーするハイブリッドな設計が、実用的なAIアプリケーション開発の鍵となります。

References:
1. Google DeepMind - Imagen 3
https://deepmind.google/technologies/imagen-3/
2. OpenCV - Template Matching
https://docs.opencv.org/4.x/d4/dc6/tutorial_py_template_matching.html

---

# How to Avoid Random Watermarks in Gemini API (Imagen 3) Image Generation

The latest image generation model available via the Google Gemini API, "Imagen 3 (gemini-3-pro-preview)," is a powerful tool capable of generating incredibly photorealistic images. However, developers have reported an issue where Google's logo (spark marks) or watermarks derived from training data randomly appear in images generated via the API.

This article explains why watermarks appear in Gemini API outputs and provides a workaround using Python and OpenCV to automatically filter for clean images.

## The Watermark Issue in Gemini API

When generating images using the Gemini API (`google-genai` SDK), especially with "Photorealistic" styles, the output JPEG images may contain the following artifacts:

1.  **Google AI Spark Marks:** A sparkling three-star icon indicating Google AI-generated content.
2.  **Training Data Watermarks:** Logos or copyright text from stock photo services (e.g., Getty Images).

Even if you explicitly instruct the model not to include watermarks in the prompt, these artifacts can occur randomly. For commercial use or portfolio creation, images containing these marks are unusable, posing a significant challenge.

## Why Do Watermarks Appear?

There are two main reasons for this phenomenon:

### 1. Google's AI Content Identification Policy
Google is committed to responsible AI development and employs mechanisms like visible watermarks and invisible watermarks (SynthID) to identify AI-generated content. While most prominent in web-based tools like Gemini Advanced, the API may also be configured to apply spark marks under certain conditions.

### 2. Dataset Pollution (Overfitting)
When prompts include keywords like "High Quality," "Studio Lighting," or "RAW Photo," the model strongly references high-quality stock photo data. Consequently, the model may mistakenly learn and reproduce the watermarks or logos found in the training data as part of a "high-quality photo." This is a form of overfitting specific to image generation AI.

:::note
Because Imagen 3 has superior text rendering capabilities compared to previous models, it tends to draw "watermark text" more clearly and accurately.
:::

## Solution 1: Prompt Engineering

The simplest measure is to strongly instruct the model in the prompt not to draw watermarks. Instructions in English tend to be more effective than in other languages.

Try adding a **Negative Constraint** like the following to the end of your prompt:

```text
... (Main Prompt) ...

IMPORTANT: Do not include any watermarks, logos, text overlays, signatures, date stamps, or copyright notices in the image. The image should be clean and free of any text or artifacts.
```

:::warning
As of February 2025, the Gemini API does not have a `negative_prompt` parameter like Stable Diffusion. Therefore, you must include negative instructions within the standard prompt text, but the effectiveness is limited.
:::

## Solution 2: Auto-Retry with OpenCV Detection (Recommended)

If prompts alone cannot completely prevent watermarks, the most reliable approach is to **"treat images with watermarks as failures and immediately regenerate them."**

Using the Python image processing library OpenCV, you can quickly determine if a generated image contains specific patterns (such as spark marks).

### Implementation Example

Prepare a template image (`template_watermark.png`) by cropping the "spark mark" or "watermark" from a failed image.

```python
import cv2
import numpy as np
from pathlib import Path

def has_watermark(image_path: str, template_path: str, threshold: float = 0.8) -> bool:
    """
    Determines if the generated image contains the watermark template.
    """
    # Load images in grayscale
    img = cv2.imread(image_path, 0)
    template = cv2.imread(template_path, 0)
    
    if img is None or template is None:
        return False

    # Execute Template Matching
    result = cv2.matchTemplate(img, template, cv2.TM_CCOEFF_NORMED)
    min_val, max_val, min_loc, max_loc = cv2.minMaxLoc(result)
    
    # If the match score exceeds the threshold, watermark is present
    if max_val >= threshold:
        return True
    
    return False

# Usage example (call inside generation loop)
# if has_watermark("generated.jpg", "spark_mark_template.png"):
#     print("Watermark detected. Regenerating...")
#     # Retry logic here
```

By integrating this process into your image generation pipeline, you can ensure that only clean images remain in your output folder, even after batch processing overnight.

:::step
**How to Create a Template Image**
1. Find a generated image that actually contains the watermark.
2. Use paint software to crop only the watermark part (e.g., the spark mark).
3. Save it as `template_watermark.png`. Do not use a transparent background; using the cropped image as-is yields better matching accuracy.
:::

## Conclusion

The watermark issue in Gemini API (Imagen 3) cannot currently be completely avoided through prompt engineering alone. However, by combining detection logic using tools like OpenCV, it is entirely possible to guarantee business-level quality.

Designing hybrid systems that cover the "uncertainty" of AI models with the "certainty" of traditional programming is key to practical AI application development.

References:
1. Google DeepMind - Imagen 3
https://deepmind.google/technologies/imagen-3/
2. OpenCV - Template Matching
https://docs.opencv.org/4.x/d4/dc6/tutorial_py_template_matching.html
