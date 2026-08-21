---
title: "Git LFS vs Cloudflare R2 vs Backblaze B2 vs Google Drive vs Amazon S3 [August 2026]"
slug: large-file-storage-comparison-2026-08-en
status: publish
categories:
  - Web Development
tags:
  - Git LFS
  - Cloudflare R2
  - Backblaze B2
  - Google Drive
  - Amazon S3
  - Storage
  - 2026
---

Once you start storing large collections of PDFs, images, audio, video, datasets, or other binary assets, a normal Git repository becomes a poor fit. GitHub blocks regular Git files larger than 100 MiB, so Git LFS is the obvious option when large files need to remain part of a Git-based workflow.

But if the real requirement is simply to store large files and retrieve them when a person or program needs them, object storage such as Cloudflare R2 or Backblaze B2 can be a better architecture.

This article compares Git LFS, Cloudflare R2, Backblaze B2, Google Drive, and Amazon S3 as of August 2026.

## The short version

| Service | Best fit | Free / low-cost characteristics | Main caveat |
|---|---|---|---|
| GitHub Git LFS | Versioning large files together with Git | Free/Pro include 10 GiB storage and 10 GiB monthly bandwidth | Old versions continue consuming storage |
| Cloudflare R2 | Object storage read frequently by applications and agents | 10 GB-month Standard storage free; no Internet egress fee | Request charges matter at scale |
| Backblaze B2 | Low-cost hundreds-of-GB to multi-TB storage | First 10 GB free; starts at $6.95/TB/month | Free egress is generally capped at 3x stored data |
| Google Drive | Human-oriented document storage and sharing | Google accounts include 15 GB; Google One adds larger tiers | Not an S3-style storage primitive |
| Amazon S3 | Production systems and AWS-native infrastructure | Extremely broad feature set | Pricing can become complex |

The first question is whether the files need to be part of Git history or simply stored reliably as objects.

## Git LFS: the tightest Git integration

Git LFS keeps a small pointer in Git while storing the actual binary object in LFS storage.

As of August 2026, GitHub Free and GitHub Pro include 10 GiB of Git LFS storage and 10 GiB of download bandwidth per month. GitHub Team and Enterprise Cloud include 250 GiB of each.

The per-file limit is 2 GB on Free and Pro, 4 GB on Team, and 5 GB on Enterprise Cloud.

For usage beyond the included quota, GitHub's pricing calculator lists additional storage at $0.07/GiB/month and outbound transfer at $0.0875/GiB.

The important catch is version history. If you push a 500 MB file and later change it slightly, the old 500 MB object and the new 500 MB object are both retained. Large PDFs, media files, or datasets that change frequently can therefore consume much more storage than their current working-tree size suggests.

GitHub Actions downloads of LFS objects also count against LFS bandwidth.

That makes Git LFS excellent when large files genuinely belong in a versioned Git workflow, but less attractive as a static archive containing tens of thousands of mostly immutable documents.

## Cloudflare R2: strong for frequently accessed object storage

Cloudflare R2 provides an S3-compatible object storage API.

Its Standard storage class includes 10 GB-month of storage per month for free, along with one million Class A operations and ten million Class B operations. Direct Internet egress from R2 is free.

Beyond the free tier, Standard storage costs $0.015/GB-month. If you store 100 GB for a full month, a simple estimate after the 10 GB free allowance is about $1.35/month for storage, before any billable operations.

R2 also has an Infrequent Access class in 2026. Storage is $0.01/GB-month, but there is no free tier for this class, reads incur a $0.01/GB retrieval charge, and there is a 30-day minimum storage duration.

R2's major advantage is free egress. If an AI agent, CI pipeline, web application, or script repeatedly reads the same files, the cost is easier to predict than with services where outbound bandwidth is a major billing dimension.

## Backblaze B2: inexpensive bulk storage

Backblaze B2 is another S3-compatible object storage service.

As of August 2026, the first 10 GB is free and standard B2 Cloud Storage starts at $6.95/TB/month. For hundreds of gigabytes or multiple terabytes retained for long periods, the storage cost is among the lower mainstream object-storage options.

Egress is free up to three times average monthly storage, with additional egress generally priced at $0.01/GB. Backblaze also provides free egress through a number of CDN and compute partners, including Cloudflare.

The practical distinction is straightforward: R2 is especially attractive when you expect unpredictable or heavy reads, while B2 is compelling when low storage cost matters most and your download volume is reasonably predictable.

## Google Drive: better when humans manage the files directly

Google Drive is a different category of product.

A Google account includes 15 GB shared across Gmail, Google Drive, and Google Photos. Google One expands that with storage tiers such as 100 GB and 200 GB, plus larger AI-oriented plans.

Its strongest advantage is the human interface. Browsing folders, previewing PDFs, searching from a phone, sharing a link, and opening a document directly are far easier than working with a raw object-storage bucket.

The trade-off is that Drive is not designed as an S3-compatible application storage layer. For automated systems that need to read and write very large numbers of objects through predictable infrastructure APIs, R2, B2, or S3 is usually easier to integrate cleanly.

## Amazon S3: the standard enterprise choice, with more billing dimensions

Amazon S3 is one of the de facto standards for object storage. It integrates with IAM, lifecycle policies, versioning, archive classes such as Glacier, and the rest of the AWS platform.

Its cost, however, is not just storage. Pricing depends on storage class, requests, retrieval, data transfer, region, and optional features.

In an AWS-published architecture example for the Tokyo region, S3 Standard is calculated at $0.025/GB. For a simple document archive, that can be more expensive than R2 or B2. For a production application already built around AWS, however, S3's integration and control model can easily justify the difference.

## Choosing by scale and workflow

A deliberately simplified decision table looks like this:

| Situation | First option to consider |
|---|---|
| A few GB and the files belong in Git history | Git LFS |
| 10 to several hundred GB, frequently read by APIs or AI agents | Cloudflare R2 |
| Hundreds of GB to multiple TB, with storage cost as the priority | Backblaze B2 |
| People mainly browse and read the files from phones and PCs | Google Drive |
| The files are part of an AWS production system | Amazon S3 |

These are not hard boundaries. R2 and B2 both expose S3-compatible interfaces and can cover a wide range of workloads.

## A useful architecture: metadata in GitHub, binaries in R2 or B2

For large PDF or research-document collections, separating Git metadata from binary storage is often cleaner than putting everything in LFS.

```text
GitHub
├─ README.md
├─ bibliography.csv
├─ metadata/
│  ├─ 001.yaml
│  └─ 002.yaml
└─ scripts/
   └─ fetch_documents.py

R2 / B2
├─ papers/
├─ books/
├─ scans/
└─ images/
```

GitHub stores titles, authors, citations, object keys, checksums, notes, and scripts. The actual PDFs live in R2 or B2.

```yaml
id: example-1972
title: Example Paper
object_key: papers/example-1972.pdf
sha256: ...
```

This keeps the repository small and diff-friendly while allowing scripts or agents to retrieve only the objects they actually need.

:::conclusion
Git LFS solves the problem of versioning large files with Git. R2 and B2 solve the different problem of storing large files as objects. Separating those two requirements makes the choice much easier.

As of August 2026, a practical default is Git LFS for small Git-native datasets, R2 for frequently accessed API-driven storage, B2 for inexpensive bulk storage, Google Drive for human-first document management, and S3 for AWS-native production systems.
:::

References:
1. GitHub Docs - Git Large File Storage billing
https://docs.github.com/en/billing/concepts/product-billing/git-lfs
2. GitHub Docs - About Git Large File Storage
https://docs.github.com/en/repositories/working-with-files/managing-large-files/about-git-large-file-storage
3. GitHub Pricing Calculator
https://github.com/pricing/calculator
4. Cloudflare R2 Pricing
https://developers.cloudflare.com/r2/pricing/
5. Backblaze B2 Pricing
https://www.backblaze.com/cloud-storage/pricing
6. Google One
https://one.google.com/intl/ja_jp/about/
7. Amazon S3 Pricing
https://aws.amazon.com/s3/pricing/
