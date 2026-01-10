---
title: "Stuck in GCP GUI? Escaping to Cloud Shell + CUI Was Faster (A Failure Story)"
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
slug: gcp-gui-vs-cloud-shell-failure-story-en
featured_image: ../images/2025/image copy 24.png
---

To be honest,
I wore myself out trying to manage everything solely through the GCP GUI.

IAM, enabling APIs, permission errors.
Going back and forth between screens,

*   Losing track of what I configured and where
*   Error messages being abstract, obscuring the root cause
*   No history of click operations left behind

Repeating "Maybe it's this?" in this state,
I realized a significant amount of time had passed.

## The Turning Point: Opening Cloud Shell

Halfway through, it suddenly hit me,

:::note
Wait, wouldn't it be faster to launch Cloud Shell and hit `gcloud`?
:::

Thinking that, I launched Cloud Shell within GCP.

From there, the situation changed instantly.

:::step
1.  Instantly confirm which project I'm touching with `gcloud config get-value project`
2.  Explicitly execute API enablement with `gcloud services enable xxx.googleapis.com`
3.  Even for IAM assignment errors, CLI error messages make more sense
:::

The biggest factor was,
**being able to paste CUI logs to AI for consultation right there**.

Compared to GUI screenshots,
command + error message is overwhelmingly faster to discuss.

## Reflections

Looking back now,

:::conclusion
*   GUI is for "people who know the settings"
*   CUI is overwhelmingly stronger for troubleshooting
*   It's better for mental health to assume Shell from the start with GCP
:::

That was my conclusion.

For those dying in the GCP GUI,
I think it's better to launch Cloud Shell once, and proceed while throwing:

*   `gcloud`
*   Logs
*   Error messages

directly to AI.
It will likely be faster, more accurate, and safer in the end.

This is entirely a failure story I realized after taking the long way around.

:::warning
**[Important] Precautions when sending logs to AI**
Escaping to CUI is efficient, but please be very careful about security.
Make sure **never to paste sensitive information** like API keys, service account keys (JSON content), or passwords to AI.
Please make it a habit to check that no sensitive information is included before pasting logs.
:::

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
