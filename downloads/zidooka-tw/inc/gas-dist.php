<?php
// GAS script distribution (Custom Post Type)

// --- GAS script distribution (Custom Post Type) ---
// Publish small, reusable Google Apps Script snippets and drive consulting leads.

define('ZDK_GAS_POST_TYPE', 'gas_script');
define('ZDK_GAS_META_VERSION', '_zdk_gas_version');
define('ZDK_GAS_META_FILENAME', '_zdk_gas_filename');
define('ZDK_GAS_META_CODE', '_zdk_gas_code');
define('ZDK_GAS_META_BUNDLE', '_zdk_gas_bundle');
define('ZDK_GAS_QV_DOWNLOAD', 'zdk_gas_download');

function zdk_gas_normalize_newlines($text) {
    if (!is_string($text) || $text === '') return '';
    return str_replace(["\r\n", "\r"], "\n", $text);
}

function zdk_gas_sanitize_zip_path($path) {
    $path = (string) $path;
    if ($path === '') return '';

    $path = str_replace('\\', '/', $path);
    $parts = explode('/', $path);
    $clean = [];
    foreach ($parts as $p) {
        $p = trim($p);
        if ($p === '' || $p === '.') continue;
        if ($p === '..') continue;
        $p = sanitize_file_name($p);
        if ($p === '') continue;
        $clean[] = $p;
    }
    return implode('/', $clean);
}

function zdk_gas_parse_bundle($bundle) {
    $text = zdk_gas_normalize_newlines((string) $bundle);
    if ($text === '') return [];

    $pattern = '/^\s*---\s*file:\s*(.+?)\s*---\s*$/m';
    if (!preg_match_all($pattern, $text, $m, PREG_OFFSET_CAPTURE)) return [];

    $files = [];
    $count = count($m[0]);
    for ($i = 0; $i < $count; $i++) {
        $name = trim((string) $m[1][$i][0]);
        if ($name === '') continue;

        $start = $m[0][$i][1] + strlen($m[0][$i][0]);
        $end = ($i + 1 < $count) ? $m[0][$i + 1][1] : strlen($text);
        $content = substr($text, $start, $end - $start);
        if ($content !== '' && $content[0] === "\n") $content = substr($content, 1);

        $files[] = [
            'name' => $name,
            'content' => (string) $content,
        ];
    }
    return $files;
}

function zdk_gas_get_dist_files($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) return [];

    $bundle = (string) get_post_meta($post_id, ZDK_GAS_META_BUNDLE, true);
    $files = zdk_gas_parse_bundle($bundle);
    if (!empty($files)) return $files;

    $code = (string) get_post_meta($post_id, ZDK_GAS_META_CODE, true);
    if ($code === '') return [];

    $filename = (string) get_post_meta($post_id, ZDK_GAS_META_FILENAME, true);
    if ($filename === '') $filename = 'Code.gs';
    if (!preg_match('/\.(gs|js)$/i', $filename)) $filename .= '.gs';

    return [[
        'name' => $filename,
        'content' => $code,
    ]];
}

add_action('init', function () {
    $labels = [
        'name' => 'GAS配布',
        'singular_name' => 'GAS配布',
        'menu_name' => 'GAS配布',
        'name_admin_bar' => 'GAS配布',
        'add_new' => '新規追加',
        'add_new_item' => '新しいGAS配布を追加',
        'edit_item' => 'GAS配布を編集',
        'new_item' => '新しいGAS配布',
        'view_item' => 'GAS配布を表示',
        'search_items' => 'GAS配布を検索',
        'not_found' => 'GAS配布が見つかりません',
        'not_found_in_trash' => 'ゴミ箱にGAS配布はありません',
        'all_items' => 'GAS配布一覧',
    ];

    register_post_type(ZDK_GAS_POST_TYPE, [
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-editor-code',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        // Reuse existing site taxonomies (so CTA mapping by category slug can work as-is)
        'taxonomies' => ['category', 'post_tag'],
        'has_archive' => true,
        'rewrite' => [
            'slug' => 'gas-works',
            'with_front' => false,
        ],
    ]);

    // Allow CLI (REST API) to write meta fields.
    $can_edit = function () { return current_user_can('edit_posts'); };
    register_post_meta(ZDK_GAS_POST_TYPE, ZDK_GAS_META_VERSION, [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => $can_edit,
    ]);
    register_post_meta(ZDK_GAS_POST_TYPE, ZDK_GAS_META_FILENAME, [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => $can_edit,
    ]);
    $passthrough = function ($value) { return is_string($value) ? $value : ''; };
    register_post_meta(ZDK_GAS_POST_TYPE, ZDK_GAS_META_CODE, [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => false,
        'sanitize_callback' => $passthrough,
        'auth_callback' => $can_edit,
    ]);
    register_post_meta(ZDK_GAS_POST_TYPE, ZDK_GAS_META_BUNDLE, [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => false,
        'sanitize_callback' => $passthrough,
        'auth_callback' => $can_edit,
    ]);

    // Pretty download endpoint: /gas-works/<slug>/download/
    add_rewrite_rule(
        '^gas-works/([^/]+)/download/?$',
        'index.php?' . ZDK_GAS_POST_TYPE . '=$matches[1]&' . ZDK_GAS_QV_DOWNLOAD . '=1',
        'top'
    );
});

add_filter('query_vars', function ($vars) {
    $vars[] = ZDK_GAS_QV_DOWNLOAD;
    return $vars;
});

add_action('admin_init', function () {
    if (get_option('zdk_gas_rewrite_flushed') === '1') return;
    flush_rewrite_rules(false);
    update_option('zdk_gas_rewrite_flushed', '1');
});

function zdk_gas_get_download_url($post_id) {
    $permalink = get_permalink($post_id);
    if (!$permalink) return '';
    return trailingslashit($permalink) . 'download/';
}

add_action('template_redirect', function () {
    if (!get_query_var(ZDK_GAS_QV_DOWNLOAD)) return;

    $post = get_queried_object();
    if (!$post || !($post instanceof WP_Post) || $post->post_type !== ZDK_GAS_POST_TYPE) {
        status_header(404);
        exit;
    }

    $bundle = (string) get_post_meta($post->ID, ZDK_GAS_META_BUNDLE, true);
    $files = zdk_gas_parse_bundle($bundle);
    $is_bundle = !empty($files);

    // Backward compatibility: single file meta
    $single_code = (string) get_post_meta($post->ID, ZDK_GAS_META_CODE, true);

    if (!$is_bundle && $single_code === '') {
        status_header(404);
        exit;
    }

    $filename = (string) get_post_meta($post->ID, ZDK_GAS_META_FILENAME, true);

    // Bundle mode: ZIP preferred
    if ($is_bundle) {
        $zip_name = $filename !== ''
            ? $filename
            : (($post->post_name ? $post->post_name : ('gas-' . $post->ID)) . '.zip');

        $zip_name = sanitize_file_name($zip_name);
        if (!preg_match('/\.zip$/i', $zip_name)) $zip_name .= '.zip';
        if ($zip_name === '') $zip_name = 'gas.zip';

        // Try ZIP. If ZipArchive is unavailable, fallback to bundle text.
        if (class_exists('ZipArchive')) {
            $tmp = function_exists('wp_tempnam') ? wp_tempnam($zip_name) : tempnam(sys_get_temp_dir(), 'zdk-gas-');
            if ($tmp && file_exists($tmp)) @unlink($tmp);

            $zip = new ZipArchive();
            $res = $tmp ? $zip->open($tmp, ZipArchive::CREATE) : false;
            if ($res === true) {
                foreach ($files as $f) {
                    $name = isset($f['name']) ? zdk_gas_sanitize_zip_path($f['name']) : '';
                    $content = isset($f['content']) ? (string) $f['content'] : '';
                    if ($name === '') continue;
                    $zip->addFromString($name, $content);
                }
                $zip->close();

                if ($tmp && file_exists($tmp)) {
                    nocache_headers();
                    header('Content-Type: application/zip');
                    header('X-Content-Type-Options: nosniff');
                    header('Content-Disposition: attachment; filename="' . $zip_name . '"');
                    header('Content-Length: ' . filesize($tmp));
                    readfile($tmp);
                    @unlink($tmp);
                    exit;
                }
            }
        }

        $fallback = $bundle !== '' ? $bundle : '';
        nocache_headers();
        header('Content-Type: text/plain; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Content-Disposition: attachment; filename="' . preg_replace('/\.zip$/i', '.txt', $zip_name) . '"');
        echo $fallback;
        exit;
    }

    // Single-file mode: .gs download
    $out_name = $filename !== ''
        ? $filename
        : (($post->post_name ? $post->post_name : ('gas-' . $post->ID)) . '.gs');
    $out_name = sanitize_file_name($out_name);
    if ($out_name === '') $out_name = 'code.gs';
    if (!preg_match('/\.(gs|js)$/i', $out_name)) $out_name .= '.gs';

    nocache_headers();
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('Content-Disposition: attachment; filename="' . $out_name . '"');
    echo $single_code;
    exit;
});

add_action('add_meta_boxes', function () {
    add_meta_box(
        'zdk_gas_dist_meta',
        'GAS 配布設定',
        'zdk_gas_dist_meta_box_cb',
        ZDK_GAS_POST_TYPE,
        'normal',
        'high'
    );
});

function zdk_gas_dist_meta_box_cb($post) {
    if (!$post || !($post instanceof WP_Post)) return;
    wp_nonce_field('zdk_gas_dist_meta_save', 'zdk_gas_dist_meta_nonce');

    $version = (string) get_post_meta($post->ID, ZDK_GAS_META_VERSION, true);
    $filename = (string) get_post_meta($post->ID, ZDK_GAS_META_FILENAME, true);
    $code = (string) get_post_meta($post->ID, ZDK_GAS_META_CODE, true);
    $bundle = (string) get_post_meta($post->ID, ZDK_GAS_META_BUNDLE, true);
    $download_url = function_exists('zdk_gas_get_download_url') ? zdk_gas_get_download_url($post->ID) : '';

    ?>
    <p>
        <label for="zdk_gas_version"><strong>バージョン</strong></label><br>
        <input type="text" class="regular-text" id="zdk_gas_version" name="zdk_gas_version" value="<?php echo esc_attr($version); ?>" placeholder="例: 1.0.0">
    </p>
    <p>
        <label for="zdk_gas_filename"><strong>ダウンロードファイル名</strong></label><br>
        <input type="text" class="regular-text" id="zdk_gas_filename" name="zdk_gas_filename" value="<?php echo esc_attr($filename); ?>" placeholder="例: Code.gs">
        <span class="description">未指定の場合は 単体なら <code>&lt;slug&gt;.gs</code> / ファイルセットなら <code>&lt;slug&gt;.zip</code> になります。</span>
    </p>
    <p>
        <label for="zdk_gas_bundle"><strong>配布ファイルセット（複数ファイル / appsscript.json 対応）</strong></label><br>
        <textarea id="zdk_gas_bundle" name="zdk_gas_bundle" style="width: 100%; min-height: 220px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, 'Liberation Mono', monospace;" placeholder="--- file: appsscript.json ---&#10;{&#10;  &quot;timeZone&quot;: &quot;Asia/Tokyo&quot;&#10;}&#10;--- file: Code.gs ---&#10;function main(){&#10;  Logger.log('hi');&#10;}"><?php echo esc_textarea($bundle); ?></textarea>
        <span class="description">上の形式で貼り付けると ZIP 配布になります（空なら単体コードを使用）。</span>
    </p>
    <p>
        <label for="zdk_gas_code"><strong>配布コード（単体・後方互換）</strong></label><br>
        <textarea id="zdk_gas_code" name="zdk_gas_code" style="width: 100%; min-height: 260px; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, 'Liberation Mono', monospace;"><?php echo esc_textarea($code); ?></textarea>
        <span class="description">単体配布の場合に使用します（複数ファイルが必要なら上のファイルセットを使ってください）。</span>
    </p>
    <?php if ($download_url) : ?>
        <p>
            <strong>ダウンロードURL:</strong>
            <code><?php echo esc_html($download_url); ?></code>
        </p>
    <?php endif; ?>
    <?php
}

add_action('save_post_' . ZDK_GAS_POST_TYPE, function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['zdk_gas_dist_meta_nonce']) || !wp_verify_nonce($_POST['zdk_gas_dist_meta_nonce'], 'zdk_gas_dist_meta_save')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $version = isset($_POST['zdk_gas_version']) ? sanitize_text_field(wp_unslash($_POST['zdk_gas_version'])) : '';
    $filename = isset($_POST['zdk_gas_filename']) ? sanitize_file_name(wp_unslash($_POST['zdk_gas_filename'])) : '';
    $code = isset($_POST['zdk_gas_code']) ? (string) wp_unslash($_POST['zdk_gas_code']) : '';
    $bundle = isset($_POST['zdk_gas_bundle']) ? (string) wp_unslash($_POST['zdk_gas_bundle']) : '';

    if ($version !== '') update_post_meta($post_id, ZDK_GAS_META_VERSION, $version);
    else delete_post_meta($post_id, ZDK_GAS_META_VERSION);

    if ($filename !== '') update_post_meta($post_id, ZDK_GAS_META_FILENAME, $filename);
    else delete_post_meta($post_id, ZDK_GAS_META_FILENAME);

    if ($code !== '') update_post_meta($post_id, ZDK_GAS_META_CODE, $code);
    else delete_post_meta($post_id, ZDK_GAS_META_CODE);

    $bundle = zdk_gas_normalize_newlines($bundle);
    if ($bundle !== '') update_post_meta($post_id, ZDK_GAS_META_BUNDLE, $bundle);
    else delete_post_meta($post_id, ZDK_GAS_META_BUNDLE);
});

add_shortcode('zdk_gas_download', function ($atts = []) {
    if (!is_singular(ZDK_GAS_POST_TYPE)) return '';
    $post_id = get_the_ID();
    if (!$post_id) return '';

    $files = function_exists('zdk_gas_get_dist_files') ? zdk_gas_get_dist_files($post_id) : [];
    if (empty($files)) return '';

    $atts = shortcode_atts([
        'label' => 'コードをダウンロード',
    ], $atts, 'zdk_gas_download');

    $url = zdk_gas_get_download_url($post_id);
    if (!$url) return '';

    $label = (string) $atts['label'];
    return '<a href="' . esc_url($url) . '" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors no-underline">' . esc_html($label) . '</a>';
});

add_shortcode('zdk_gas_code', function () {
    if (!is_singular(ZDK_GAS_POST_TYPE)) return '';
    $post_id = get_the_ID();
    if (!$post_id) return '';

    $files = function_exists('zdk_gas_get_dist_files') ? zdk_gas_get_dist_files($post_id) : [];
    if (empty($files)) return '';

    ob_start();
    ?>
    <div class="space-y-4">
        <?php foreach ($files as $i => $f) :
            $name = isset($f['name']) ? (string) $f['name'] : '';
            $content = isset($f['content']) ? (string) $f['content'] : '';
            if ($content === '') continue;
            if ($name === '') $name = 'Code.gs';

            $suffix = $post_id . '-' . $i;
            $code_id = 'zdk-gas-code-' . $suffix;
            $btn_id = 'zdk-gas-copy-' . $suffix;
        ?>
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2 border-b border-slate-200 bg-slate-50">
                    <span class="text-xs font-mono text-slate-600"><?php echo esc_html($name); ?></span>
                    <button type="button" id="<?php echo esc_attr($btn_id); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                        コピー
                    </button>
                </div>
                <pre class="m-0 p-4 overflow-x-auto text-sm leading-relaxed bg-slate-900 text-slate-100"><code id="<?php echo esc_attr($code_id); ?>"><?php echo esc_html($content); ?></code></pre>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const pairs = [
            <?php foreach ($files as $i => $f) :
                $content = isset($f['content']) ? (string) $f['content'] : '';
                if ($content === '') continue;
                $suffix = $post_id . '-' . $i;
            ?>
            { btn: <?php echo json_encode('zdk-gas-copy-' . $suffix); ?>, code: <?php echo json_encode('zdk-gas-code-' . $suffix); ?> },
            <?php endforeach; ?>
        ];
        pairs.forEach(function (p) {
            const btn = document.getElementById(p.btn);
            const codeEl = document.getElementById(p.code);
            if (!btn || !codeEl) return;
            btn.addEventListener('click', async function () {
                const text = codeEl.innerText || '';
                try {
                    await navigator.clipboard.writeText(text);
                    const old = btn.innerText;
                    btn.innerText = 'コピーしました';
                    setTimeout(() => { btn.innerText = old; }, 1200);
                } catch (e) {
                    const range = document.createRange();
                    range.selectNodeContents(codeEl);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(range);
                    document.execCommand('copy');
                    sel.removeAllRanges();
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
});
