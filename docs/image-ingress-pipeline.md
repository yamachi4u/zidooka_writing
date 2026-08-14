# Direct image ingress pipeline

The intended separation is:

`ChatGPT / client -> WordPress image ingress -> Media Library -> featured_media`

`ChatGPT -> GitHub -> GitHub Actions -> article body`

The image bytes do not need to be stored in GitHub. The WordPress plugin under `downloads/zidooka-image-ingress/` exposes an authenticated multipart endpoint, while `scripts/upload-image-ingress.mjs` is a reference client for environments that can access the local image file.

This removes the previous 480x270 workaround and avoids Base64 chunk transport through GitHub.

Security properties:

- WordPress Application Password authentication over HTTPS.
- `upload_files` capability required for uploads.
- `edit_post` capability required before setting a target post thumbnail.
- JPEG/PNG/WebP/GIF allowlist.
- 10 MiB plugin-level size limit.
- No arbitrary `source_url` fetch endpoint, avoiding SSRF-by-design.

The remaining integration point for ChatGPT mobile attachments is a connector/tool that can send the attachment bytes as multipart HTTP to the ingress endpoint. Once such a connector is available, no GitHub binary transport is required.
