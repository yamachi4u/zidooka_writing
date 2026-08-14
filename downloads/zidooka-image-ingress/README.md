# ZIDOOKA Image Ingress

Authenticated WordPress REST endpoint for image uploads from external clients.

## Endpoint

`POST /wp-json/zidooka/v1/image`

Authenticate with a normal WordPress Application Password over HTTPS. The authenticated user must have `upload_files`; assigning a featured image additionally requires `edit_post` for the target post.

Use `multipart/form-data` with field `file`.

Optional fields:

- `post_id`: target WordPress post ID.
- `post_slug`: target post slug when `post_id` is omitted.
- `set_featured`: defaults to `true` when a target post is provided.
- `alt_text`: attachment alt text.
- `title`: attachment title.

Accepted image types: JPEG, PNG, WebP, GIF. Maximum upload size enforced by the plugin: 10 MiB, in addition to the server/PHP upload limits.

Example:

```bash
curl --fail-with-body \
  --user "$WP_USER:$WP_APP_PASSWORD" \
  -F "file=@images/keychron-r3-impressions.jpg" \
  -F "post_slug=keychron-r3-impressions" \
  -F "set_featured=true" \
  -F "alt_text=Keychron R3 keyboard" \
  "https://www.zidooka.com/wp-json/zidooka/v1/image"
```

Successful responses include `media_id`, `source_url`, pixel dimensions, file size, target `post_id`, and whether `featured_set` succeeded.

## Installation

Copy the `zidooka-image-ingress` directory into `wp-content/plugins/`, then activate **ZIDOOKA Image Ingress** in WordPress.

The endpoint deliberately does not accept arbitrary remote URLs. This avoids turning WordPress into an SSRF-capable fetch proxy. Clients upload the actual image body directly.
