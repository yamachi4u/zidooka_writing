<?php
/**
 * Plugin Name: ZIDOOKA Image Ingress
 * Description: Authenticated REST endpoint for uploading an image and optionally assigning it as a post featured image.
 * Version: 0.1.0
 * Author: ZIDOOKA
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Zidooka_Image_Ingress {
    private const REST_NAMESPACE = 'zidooka/v1';
    private const ROUTE = '/image';
    private const MAX_BYTES = 10 * 1024 * 1024;

    public static function boot(): void {
        add_action('rest_api_init', [self::class, 'register_routes']);
    }

    public static function register_routes(): void {
        register_rest_route(self::REST_NAMESPACE, self::ROUTE, [
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => [self::class, 'upload'],
            'permission_callback' => [self::class, 'can_upload'],
            'args' => [
                'post_id' => ['required' => false, 'type' => 'integer', 'sanitize_callback' => 'absint'],
                'post_slug' => ['required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_title'],
                'alt_text' => ['required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'title' => ['required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'],
                'set_featured' => ['required' => false, 'type' => 'boolean', 'default' => true],
            ],
        ]);
    }

    public static function can_upload(): bool {
        return current_user_can('upload_files');
    }

    public static function upload(WP_REST_Request $request) {
        $files = $request->get_file_params();
        if (empty($files['file']) || !is_array($files['file'])) {
            return new WP_Error('zidooka_missing_file', 'Multipart field "file" is required.', ['status' => 400]);
        }

        $file = $files['file'];
        if (!empty($file['error'])) {
            return new WP_Error('zidooka_upload_error', 'PHP upload error: ' . (int) $file['error'], ['status' => 400]);
        }

        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return new WP_Error('zidooka_invalid_upload', 'Invalid uploaded file.', ['status' => 400]);
        }

        $size = isset($file['size']) ? (int) $file['size'] : 0;
        if ($size <= 0 || $size > self::MAX_BYTES) {
            return new WP_Error('zidooka_file_size', 'Image must be between 1 byte and 10 MiB.', ['status' => 413]);
        }

        $name = isset($file['name']) ? sanitize_file_name($file['name']) : 'image';
        $checked = wp_check_filetype_and_ext($file['tmp_name'], $name);
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (empty($checked['type']) || !in_array($checked['type'], $allowed, true)) {
            return new WP_Error('zidooka_file_type', 'Only JPEG, PNG, WebP, and GIF images are accepted.', ['status' => 415]);
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $post_id = self::resolve_post_id($request);
        if (is_wp_error($post_id)) {
            return $post_id;
        }

        $attachment_id = media_handle_upload('file', $post_id ?: 0, [], [
            'test_form' => false,
            'mimes' => [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
            ],
        ]);

        if (is_wp_error($attachment_id)) {
            return $attachment_id;
        }

        $title = $request->get_param('title');
        if ($title) {
            wp_update_post(['ID' => $attachment_id, 'post_title' => $title]);
        }

        $alt_text = $request->get_param('alt_text');
        if ($alt_text) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
        }

        $featured_set = false;
        if ($post_id && rest_sanitize_boolean($request->get_param('set_featured'))) {
            if (!current_user_can('edit_post', $post_id)) {
                wp_delete_attachment($attachment_id, true);
                return new WP_Error('zidooka_cannot_edit_post', 'You may upload files but cannot edit the target post.', ['status' => 403]);
            }
            $featured_set = set_post_thumbnail($post_id, $attachment_id);
        }

        $metadata = wp_get_attachment_metadata($attachment_id);

        return new WP_REST_Response([
            'ok' => true,
            'media_id' => $attachment_id,
            'source_url' => wp_get_attachment_url($attachment_id),
            'mime_type' => get_post_mime_type($attachment_id),
            'width' => isset($metadata['width']) ? (int) $metadata['width'] : null,
            'height' => isset($metadata['height']) ? (int) $metadata['height'] : null,
            'filesize' => self::attachment_filesize($attachment_id),
            'post_id' => $post_id ?: null,
            'featured_set' => (bool) $featured_set,
        ], 201);
    }

    private static function resolve_post_id(WP_REST_Request $request) {
        $post_id = absint($request->get_param('post_id'));
        if ($post_id) {
            return get_post($post_id) ? $post_id : new WP_Error('zidooka_post_not_found', 'Target post_id was not found.', ['status' => 404]);
        }

        $slug = sanitize_title((string) $request->get_param('post_slug'));
        if (!$slug) {
            return 0;
        }

        $posts = get_posts([
            'name' => $slug,
            'post_type' => 'any',
            'post_status' => ['publish', 'future', 'draft', 'private'],
            'numberposts' => 1,
            'suppress_filters' => false,
        ]);

        if (!$posts) {
            return new WP_Error('zidooka_post_not_found', 'Target post_slug was not found.', ['status' => 404]);
        }

        return (int) $posts[0]->ID;
    }

    private static function attachment_filesize(int $attachment_id): ?int {
        $path = get_attached_file($attachment_id);
        if (!$path || !is_file($path)) {
            return null;
        }
        $size = filesize($path);
        return $size === false ? null : (int) $size;
    }
}

Zidooka_Image_Ingress::boot();
