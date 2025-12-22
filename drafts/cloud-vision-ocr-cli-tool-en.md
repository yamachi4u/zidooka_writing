---
title: "A Practical Guide to Building a CLI Tool for Cloud Vision OCR"
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
slug: cloud-vision-ocr-cli-tool-en
featured_image: ../images/twitterposttool/cloud-vision-api-ocr.png
---

The Cloud Vision API's OCR is highly accurate and excellent for processing entire PDFs at once. However, using the official samples as-is is a bit lacking for continuous practical use.

In this article, I will organize the design concepts and implementation examples of an OCR tool I built for myself that **"throws a PDF from the CLI and ultimately leaves readable text in hand."**

## Why CLI instead of GUI?

Initially, I tried OCR using the GCP Console (GUI). It was sufficient for verifying operation, but once I started processing PDFs repeatedly, the following points began to bother me:

*   I have to open the browser every time.
*   It is difficult to check the results of asynchronous OCR.
*   The output remains as JSON and is not intended for human reading.

As a result, I often ended up in a state where:

:::note
I managed to do the OCR, but I don't know how to use the result.
:::

Therefore, this time, I built it as a CLI tool with the aim of **"treating OCR as a command rather than a task."**

## Workflow of the Tool

This tool operates in the following flow:

:::step
1.  Check for the existence of the GCS bucket (create if it doesn't exist).
2.  Upload the target PDF for OCR to GCS.
3.  Execute asynchronous OCR with the Cloud Vision API.
4.  Retrieve all output JSON files.
5.  Concatenate the text in page order.
6.  Finally, save it as a single text file.
7.  Clean up unnecessary notation specific to OCR in post-processing.
:::

The point is that **I consider OCR processing and "formatting for human reading" separately.**

## Implementation of Asynchronous PDF OCR

Since OCR for PDFs takes time when the number of pages increases, I use the asynchronous API instead of synchronous processing.

```python
from google.cloud import vision

feature = vision.Feature(
    type_=vision.Feature.Type.DOCUMENT_TEXT_DETECTION
)
```

The configuration involves inputting the PDF on GCS and saving the output to GCS as well.

```python
gcs_source = vision.GcsSource(uri=gcs_source_uri)
input_config = vision.InputConfig(
    gcs_source=gcs_source,
    mime_type="application/pdf"
)
```

With this configuration, there are benefits such as:

*   Results remain even if the local environment crashes.
*   It is relatively stable even with large PDFs.
*   Results can be retrieved any number of times later.

## Processing to Retrieve and Combine OCR Results

Cloud Vision's PDF OCR outputs page-based JSON as multiple files. Since they are difficult to handle as is, I perform the following processing:

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

In this way, I make it into a **form that can be read as a single text for the entire PDF.**

## Subtle but Important "Cleanup of Final Output"

When converting OCR results directly to text, unnecessary notations like the following may be mixed in:

```
(ocr_results/output-12-to-13.json)
```

This has meaning mechanically, but is completely unnecessary for human reading purposes. Therefore, I prepared a **script dedicated to text formatting** separately from the OCR processing.

```python
import re

new_content = re.sub(
    r'\s*\(ocr_results\/output-[\d]+-to-[\d]+\.json\)',
    '',
    content
)
```

The reasons for separating this processing are:

*   I want to separate responsibilities from the OCR main body.
*   I want to easily adjust post-processing rules later.

Just adding this one step **significantly changes the readability of the final text.**

## Benefits Felt from Practical Use

Actually running OCR with this configuration, the following points were particularly good:

*   I can treat OCR as a "command" rather than an "operation."
*   The procedure does not change at all even if the number of PDFs increases.
*   I can leverage the balance between Cloud Vision's accuracy and cost.
*   I can obtain text that can be passed directly to LLM summarization or reprocessing.

Considering the uses after OCR (summarization, search, reconstruction), **it was ultimately easier to make it consistent to this extent.**

## Summary

Cloud Vision OCR often ends with "amazing" just by looking at the accuracy. However, in practice, value is only generated after designing:

:::conclusion
*   How to run it.
*   How to keep running it.
*   How to save it in a usable form.
:::

The CLI tool this time is the **minimum configuration for incorporating OCR into daily work flows.**

If you perform PDF OCR frequently, I feel that the CLI results in less stress than the GUI.

---

### Cloud Vision OCR Series Articles

This article is part of a series on setting up and operating OCR using Cloud Vision API.

*   **[Summary] Cloud Vision OCR x CLI x GCP Setup & Troubleshooting Log](/archives/ocr-series-summary-en)**
*   **Setup:** [Why Cloud Vision API OCR Failed with IAM and How I Finally Got It Working](/archives/cloud-vision-api-ocr-iam-trouble-en)
*   **Operation:** [Why Running Cloud Vision OCR via CLI Was the Easiest Method](/archives/cloud-vision-ocr-cli-best-practice-en)
*   **Workflow:** [Why GCP x VSC x CLI is the Correct Route for Frequent OCR](/archives/frequent-ocr-gcp-vsc-cli-workflow-en)
*   **Tools:** [Explanation of Practical Tools Created to Run Cloud Vision OCR via CLI](/archives/cloud-vision-ocr-cli-tool-en)
*   **Code:** [Free Code: Minimal Script to Safely Run Cloud Vision OCR via CLI](/archives/cloud-vision-ocr-minimal-script-en)
*   **Extra:** [When Stuck in GCP GUI, Escaping to Cloud Shell + CUI Was Faster (Failure Story)](/archives/gcp-gui-vs-cloud-shell-failure-story-en)
