<?php
// Category-based CTA system + revenue mode

function zidooka_cta_default_map() {
    $default = [
            'heading' => 'この記事の内容、60分で一緒に解決できます。',
            'sub' => '「詰まって進めない」「社内で対応できない」など、状況を聞いて最短ルートを提案します。',
            'note' => '初回5,000円〜／事前見積りで安心。',
            'heading_en' => 'Stuck on this topic? I can help.',
            'sub_en' => 'We can solve your specific issue in a short, focused session.',
            'note_en' => 'Clear estimate provided before we start.',
            'primary' => [
                'label' => 'サービス詳細を見る',
                'label_en' => 'View services',
                'url' => 'https://www.zidooka.com/lp2025',
                'url_en' => 'https://www.zidooka.com/lp2025en',
                'target' => '_self',
                'ga_label' => 'lp2025',
            ],
            'secondary' => [
                'label' => 'この記事の相談フォームを開く',
                'label_en' => 'Open consult form',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform',
                'target' => '_blank',
                'ga_label' => 'form',
            ],
        ];
    $gas = [
            'heading' => 'GASの詰まり、最短で解決します。',
            'sub' => '原因の切り分け→暫定回避→再発防止までまとめて支援します。',
            'note' => '初回5,000円〜／事前見積りで安心。',
            'primary' => [
                'label' => 'GAS相談の詳細を見る',
                'url' => 'https://www.zidooka.com/lp2025',
                'target' => '_self',
                'ga_label' => 'gas_lp',
            ],
            'secondary' => [
                'label' => 'GAS相談フォームを開く',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform',
                'target' => '_blank',
                'ga_label' => 'gas_form',
            ],
        ];
    $wordpress = [
            'heading' => 'WordPressの不具合、再発防止まで支援します。',
            'sub' => '原因調査→修正→保守まで一貫して対応します。',
            'note' => '初回5,000円〜／事前見積りで安心。',
            'primary' => [
                'label' => 'WP相談の詳細を見る',
                'url' => 'https://www.zidooka.com/lp2025',
                'target' => '_self',
                'ga_label' => 'wp_lp',
            ],
            'secondary' => [
                'label' => 'WP相談フォームを開く',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform',
                'target' => '_blank',
                'ga_label' => 'wp_form',
            ],
        ];
    $ai = [
            'heading' => 'AI導入・自動化の詰まり、整理します。',
            'sub' => '要件整理→設計→実装支援まで最短ルートで進めます。',
            'note' => '初回5,000円〜／事前見積りで安心。',
            'primary' => [
                'label' => 'AI相談の詳細を見る',
                'url' => 'https://www.zidooka.com/lp2025',
                'target' => '_self',
                'ga_label' => 'ai_lp',
            ],
            'secondary' => [
                'label' => 'AI相談フォームを開く',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform',
                'target' => '_blank',
                'ga_label' => 'ai_form',
            ],
        ];
    $error = [
            'heading' => '緊急トラブル、即時で原因を特定します。',
            'sub' => 'ログ解析→暫定回避→恒久対策まで一括で支援します。',
            'note' => '初回5,000円〜／事前見積りで安心。',
            'primary' => [
                'label' => 'トラブル相談の詳細を見る',
                'url' => 'https://www.zidooka.com/lp2025',
                'target' => '_self',
                'ga_label' => 'error_lp',
            ],
            'secondary' => [
                'label' => 'トラブル相談フォームを開く',
                'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform',
                'target' => '_blank',
                'ga_label' => 'error_form',
            ],
        ];

    return [
        'default' => $default,
        // GAS系
        'gas' => $gas,
        'gas-tips' => $gas,
        'gastips' => $gas,
        // WordPress系
        'wordpress' => $wordpress,
        'wordpresstips' => $wordpress,
        // AI系
        'ai' => $ai,
        'chatgpt' => $ai,
        // Error系
        'errors' => $error,
        'gas-errors' => $error,
        'ai-error' => $error,
        'wordpress-errors' => $error,
        'google-errors' => $error,
        'copilot-error' => $error,
        'naerror' => $error,
        'win-error' => $error,
        'python-errors' => $error,
    ];
}

function zidooka_cta_load_override() {
    $json = get_option('zidooka_cta_json', '');
    if (!$json || !is_string($json)) return [];
    $data = json_decode($json, true);
    if (!is_array($data)) return [];
    return $data;
}

function zidooka_cta_get_map() {
    $map = zidooka_cta_default_map();
    $override = zidooka_cta_load_override();
    if ($override) {
        $map = array_replace_recursive($map, $override);
    }
    return apply_filters('zidooka_cta_map', $map);
}

function zidooka_cta_pick_key($map, $post_id) {
    $cats = get_the_category($post_id);
    if (!$cats) return 'default';
    foreach ($cats as $cat) {
        $slug = $cat->slug;
        if (isset($map[$slug])) return $slug;
    }
    return isset($map['default']) ? 'default' : array_key_first($map);
}

function zidooka_get_cta_for_post($post_id, $is_english_only = false) {
    $map = zidooka_cta_get_map();
    if (!$map || !is_array($map)) return null;
    $key = zidooka_cta_pick_key($map, $post_id);
    if (!$key || !isset($map[$key]) || !is_array($map[$key])) return null;
    $cta = $map[$key];
    $cta['key'] = $key;

    if ($is_english_only) {
        $default = isset($map['default']) && is_array($map['default']) ? $map['default'] : [];
        if (!empty($cta['heading_en'])) {
            $cta['heading'] = $cta['heading_en'];
        } elseif (!empty($default['heading_en'])) {
            $cta['heading'] = $default['heading_en'];
        }
        if (!empty($cta['sub_en'])) {
            $cta['sub'] = $cta['sub_en'];
        } elseif (!empty($default['sub_en'])) {
            $cta['sub'] = $default['sub_en'];
        }
        if (!empty($cta['note_en'])) {
            $cta['note'] = $cta['note_en'];
        } elseif (!empty($default['note_en'])) {
            $cta['note'] = $default['note_en'];
        }
        if (!empty($cta['primary']['label_en'])) {
            $cta['primary']['label'] = $cta['primary']['label_en'];
        } elseif (!empty($default['primary']['label_en'])) {
            $cta['primary']['label'] = $default['primary']['label_en'];
        }
        if (!empty($cta['primary']['url_en'])) {
            $cta['primary']['url'] = $cta['primary']['url_en'];
        } elseif (!empty($default['primary']['url_en'])) {
            $cta['primary']['url'] = $default['primary']['url_en'];
        }
        if (!empty($cta['secondary']['label_en'])) {
            $cta['secondary']['label'] = $cta['secondary']['label_en'];
        } elseif (!empty($default['secondary']['label_en'])) {
            $cta['secondary']['label'] = $default['secondary']['label_en'];
        }
        if (!empty($cta['secondary']['url_en'])) {
            $cta['secondary']['url'] = $cta['secondary']['url_en'];
        } elseif (!empty($default['secondary']['url_en'])) {
            $cta['secondary']['url'] = $default['secondary']['url_en'];
        }
    }

    return $cta;
}

add_action('admin_init', function () {
    register_setting('zidooka_cta_settings', 'zidooka_cta_json');
    register_setting('zidooka_cta_settings', 'zidooka_revenue_mode');
});

add_action('admin_menu', function () {
    add_theme_page('CTA Settings', 'CTA Settings', 'manage_options', 'zidooka-cta-settings', 'zidooka_cta_settings_page');
});

function zidooka_cta_settings_page() {
    if (!current_user_can('manage_options')) return;
    $default = json_encode(zidooka_cta_default_map(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $value = get_option('zidooka_cta_json', '');
    $mode = get_option('zidooka_revenue_mode', 'cta');
    ?>
    <div class="wrap">
        <h1>CTA Settings</h1>
        <h2>収益モード</h2>
        <form method="post" action="options.php">
            <?php settings_fields('zidooka_cta_settings'); ?>
            <table class="form-table">
                <tr>
                    <th>モード切替</th>
                    <td>
                        <label><input type="radio" name="zidooka_revenue_mode" value="cta" <?php checked($mode, 'cta'); ?>> <strong>受注モード</strong> — 相談CTAを表示。広告は普段通り</label><br>
                        <label><input type="radio" name="zidooka_revenue_mode" value="ads" <?php checked($mode, 'ads'); ?>> <strong>広告モード</strong> — 相談CTAを非表示。広告収益を最大化</label>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Mode'); ?>
        </form>
        <hr style="margin:24px 0;">
        <h2>CTA カテゴリ設定</h2>
        <p>カテゴリのスラッグに合わせてCTAを切り替えます。未指定の場合は <code>default</code> を使用します。</p>
        <form method="post" action="options.php">
            <?php settings_fields('zidooka_cta_settings'); ?>
            <textarea name="zidooka_cta_json" rows="18" style="width: 100%; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, \"Liberation Mono\", monospace;"><?php echo esc_textarea($value); ?></textarea>
            <p>空のまま保存するとデフォルト設定が使われます。下はデフォルトの例です。</p>
            <textarea rows="18" style="width: 100%; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, \"Liberation Mono\", monospace;" readonly><?php echo esc_textarea($default); ?></textarea>
            <?php submit_button('Save CTA'); ?>
        </form>
    </div>
    <?php
}

function zidooka_is_ad_mode() {
    return get_option('zidooka_revenue_mode', 'cta') === 'ads';
}

add_filter('body_class', function($classes){
    if (zidooka_is_ad_mode()) {
        $classes[] = 'zdk-mode-ads';
    } else {
        $classes[] = 'zdk-mode-cta';
    }
    return $classes;
});

add_action('wp_footer', function(){
    if (!zidooka_is_ad_mode()) return;
    ?>
<script>
(function(){var s='.zenn-cta-container,.zenn-cta-section,.zenn-cta-footer,.zenn-consult-cta';document.querySelectorAll(s).forEach(function(e){e.style.display='none'});var o=new MutationObserver(function(){document.querySelectorAll(s).forEach(function(e){e.style.display='none'})});o.observe(document.body,{childList:true,subtree:true});setTimeout(function(){o.disconnect()},8000)})();
</script>
    <?php
}, 99);
