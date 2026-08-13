---
title: "Uploading Images to WordPress Using Only ChatGPT on a Phone: Automating Featured Images with GitHub Actions"
slug: mobile-chatgpt-wordpress-image-pipeline-en
status: publish
categories:
  - ChatGPT
  - Wordpress
tags:
  - ChatGPT
  - GitHub Actions
  - WordPress REST API
  - image upload
  - automation
---

I confirmed that it is possible to upload not only article text but also a photo to WordPress, and set that photo as the featured image, starting entirely from ChatGPT on a smartphone.

The experiment began by attaching a photo of a Keychron R3 in the Android ChatGPT app and asking whether it could be used as the thumbnail for the latest article. From there, the image was moved into GitHub, a GitHub Actions workflow uploaded it through the WordPress REST API, and the returned media ID was assigned to the existing post as `featured_media`.

:::conclusion
The complete route worked: Android ChatGPT → GitHub → GitHub Actions → WordPress Media API → featured image. I did not need to use a PC.
:::

## What the pipeline does

The working flow is:

1. Take a photo on a phone.
2. Attach it to ChatGPT.
3. Have ChatGPT commit the image and required changes to the GitHub repository.
4. Merge the pull request.
5. GitHub Actions detects the image.
6. The workflow uploads it to the WordPress `/wp/v2/media` endpoint.
7. WordPress returns a media ID, which is assigned to the post's `featured_media` field.
8. A separate verification workflow reads the WordPress API again and confirms that the image is actually attached.

The image is not embedded in the article as a huge base64 string. It remains an ordinary image file in the WordPress Media Library, while the post stores the WordPress media ID.

## What the verification showed

For the Keychron R3 test, the Japanese post was post ID 4657 with `featured_media=4661`. The English post was post ID 4659 with `featured_media=4662`.

The verification workflow fetched each post by slug, checked that `featured_media` was non-zero, then fetched the media object itself and confirmed that it was a JPEG.

```text
OK keychron-r3-impressions-jp: post=4657 featured_media=4661 mime=image/jpeg
OK keychron-r3-impressions-en: post=4659 featured_media=4662 mime=image/jpeg
```

This matters because a successful upload command is not quite the same thing as confirming the expected state on the published WordPress site. The second workflow makes that state machine-checkable.

## The WordPress side is simple

Conceptually, WordPress only needs two REST operations. First, upload the media file. Then use the returned ID when updating the post.

```text
POST /wp/v2/media
  ↓
media ID = 4661
  ↓
POST /wp/v2/posts/4657
{
  "featured_media": 4661
}
```

The WordPress credentials remain in GitHub Secrets. They do not need to be pasted into the ChatGPT conversation or committed to the public repository.

## The first image looked terrible

The pipeline worked, but the first test thumbnail was visibly over-compressed. That is not an inherent limitation of WordPress REST API or GitHub Actions. It came from the image-processing choices before upload.

The next improvement is to preserve substantially more source resolution, target roughly 1600–2000 pixels on the long edge, use JPEG quality around 85, and avoid enlarging or recompressing an image that is already suitable.

The current JP/EN flow also uploads the same source image twice, producing separate media IDs. Reusing a single uploaded media item for both language variants would be cleaner.

:::note
The important result of this experiment is not the quality of the first thumbnail. It is that a local photo handed to ChatGPT on a phone can be transported all the way into the WordPress Media Library through a conversational workflow.
:::

## The phone becomes the CMS control surface

Once both text and images can use this route, the smartphone no longer needs to be a cramped WordPress administration terminal. It can instead be the device where the user supplies source material and states the desired outcome.

A practical interaction can be as simple as:

> Use this photo, write a short article, set the photo as the thumbnail, and publish it.

Drafting, version control, media upload, WordPress publishing, featured-image assignment, and verification can then happen in cloud-side tooling.

In this architecture, ChatGPT is the operation interface, GitHub stores the content and change history, GitHub Actions is the execution environment, and WordPress remains the publication target.

## What comes next

The route itself is proven. The next work is operational quality: better resizing and JPEG compression, stable EXIF rotation handling, media reuse between language variants, logging source and uploaded dimensions and file size, verifying dimensions after upload, and optionally supporting WebP.

Once those pieces are tightened, “take a photo → send it to ChatGPT → publish it to WordPress” stops being a demo and becomes a practical publishing workflow.
