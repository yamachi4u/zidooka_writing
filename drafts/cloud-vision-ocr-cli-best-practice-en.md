---
title: "Why Running Cloud Vision OCR via CLI Was the Easiest Solution"
date: 2025-12-17 11:00:00
categories: 
  - OCR
tags: 
  - Cloud Vision API
  - OCR
  - CLI
  - GCP
  - Python
  - VS Code
status: publish
slug: cloud-vision-ocr-cli-best-practice-en
featured_image: ../images/twitterposttool/cloud-vision-api-ocr.png
---

OCR using Google Cloud Vision API might look like a task where you just "configure it in the GUI, click a button, and you're done" at first glance.

Before actually trying it, I thought so too.

However, when I tried to run OCR in earnest, I came to the conclusion that trying to complete everything solely within the GCP GUI is quite exhausting.

In this article, based on my experience actually using Cloud Vision OCR, I will summarize why the configuration running via CLI (CUI) was ultimately the easiest.

## GCP GUI Allows "Configuration" But Obscures the "Flow"

The GCP management console is well-designed when looking at individual functions.

*   Enabling APIs
*   IAM settings
*   Creating Service Accounts
*   Creating Cloud Storage buckets

Each of these can certainly be operated via the GUI.

However, in cases like OCR where:

*   Vision API
*   IAM
*   Storage
*   Local environment (Python)

are involved simultaneously, to be honest, it is difficult to understand where the settings are taking effect.

"The settings should be correct, but it's not working." This state continued for a long time.

## IAM Errors Are Hard to Diagnose in the GUI

The most common pitfall in Cloud Vision OCR is around IAM.

*   Even though `roles/vision.user` was assigned, it doesn't go through.
*   The bucket exists, but `bucket.exists()` returns an error.
*   The API is enabled, but permission errors occur.

Even if it looks "configured" on the GUI, it is hard to trace which process is actually using those permissions.

This necessitates trial and error while looking at logs.

## Switching to Cloud Shell + CLI Made Things Instantly Easier

I changed my way of thinking halfway through.

Instead of struggling with the GUI, wouldn't it be faster to open a shell inside GCP and organize things with a CLI-first approach?

As a result, this was the correct answer.

Using Cloud Shell as a starting point:

1.  Explicitly place the service account key (`key.json`).
2.  Manually specify `GOOGLE_APPLICATION_CREDENTIALS`.
3.  Execute the Python script directly.

As soon as I adopted this configuration, it became instantly visible "which authentication," "which API," and "which permissions" were being used.

## The Peace of Mind of "Manually Specifying" Credentials

This is the biggest benefit of running via CLI.

```python
os.environ["GOOGLE_APPLICATION_CREDENTIALS"] = "key.json"
```

Just having this one line makes it clear:

*   Which service account is being used.
*   Whether it is being affected by the GUI state.
*   Whether the permission trouble is on the code side or the setting side.

With the GUI, "which authentication is currently running" tends to be ambiguous, but with the CLI, there is a sense that **you are specifying everything yourself**.

This was also mentally much easier.

## OCR is a "Repetitive Task," So It Suits CLI

OCR is not a process that ends once it runs.

1.  Replace the PDF.
2.  Change settings slightly.
3.  Check the resulting JSON.
4.  Re-run.

You repeat this loop many times.

Rather than performing GUI operations every time, being able to run it immediately with:

```bash
python ocr_pdf.py
```

is overwhelmingly faster.

Especially for accuracy verification and isolating failure causes, being able to immediately re-run via CLI was a major benefit in itself.

## Conclusion: "CLI-First" is the Easiest for OCR

Cloud Vision OCR itself is powerful.
However, if you try to complete it solely with the GCP GUI, the configuration becomes invisible, and you are more likely to get stuck when troubles occur.

*   Explicitly state authentication.
*   Simplify the execution path.
*   Check logs on the spot.

If these conditions are met, the configuration of Cloud Shell + CLI + local script was ultimately the easiest.

If you are in a state of "struggling with the GUI but it won't work for some reason," I recommend trying to shift to CLI once.

At least for me, that was when I was finally able to move forward with OCR.

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
