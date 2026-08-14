# ZIDOOKA Image Ingress

Authenticated WordPress REST endpoint for image uploads from external clients.

## Endpoint

`POST /wp-json/zidooka/v1/image`

Authenticate with a WordPress Application Password over HTTPS. The authenticated user must have `upload_files`; assigning a featured image additionally requires `edit_post` for the target post.

Send `multipart/form-data` with field `file`.

Optional fields:
- `post_id`: target WordPress post ID
- `post_slug`: target post slug when `post_id` is omitted
- `set_featured`: defaults to `true` when a target post is provided
- `alt_text`: attachment alt text
- `title`: attachment title

Accepted image types: JPEG, PNG, WebP, GIF. The plugin enforces a 10 MiB maximum in addition to PHP/server upload limits.

```bash
curl --fail-with-body \
  --user "$WP_USER:$WP_APP_PASSWORD" \
  -F "file=@images/keychron-r3-impressions.jpg" \
  -F "post_slug=keychron-r3-impressions" \
  -F "set_featured=true" \
  -F "alt_text=Keychron R3 keyboard" \
  "https://www.zidooka.com/wp-json/zidooka/v1/image"
```

Successful responses include `media_id`, `source_url`, dimensions, file size, target `post_id`, and `featured_set`.

## Installation

Copy the `zidooka-image-ingress` directory into `wp-content/plugins/` and activate **ZIDOOKA Image Ingress**.

The endpoint intentionally does not accept arbitrary remote URLs. This avoids creating an SSRF-capable fetch proxy; clients send the actual image bytes directly.
