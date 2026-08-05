<?php
/**
 * 広告管理（AdSense + A8.net）一元化モジュール
 *
 * すべての広告はここで定義された「プレースメント台帳」を通して配信される。
 * テンプレート側は `echo zidooka_render_ad('post_title_top');` を呼ぶだけ。
 *
 * 設定の優先順位:
 *   1. 管理画面 [外観 > Ads Settings] の JSON（option: zidooka_ads_json）
 *   2. このファイルの zidooka_ads_default_config()
 *
 * 計測イベント（PostHog, provider 横断で統一）:
 *   ad_impression { provider, placement, offer?, slot? }
 *   ad_click      { provider, placement, offer?, slot? }
 *   ad_unfilled   { provider: 'adsense', placement, slot }   ← fill率の監視用
 *
 * 設計ドキュメント: リポジトリの docs/ADS_MANAGEMENT.md
 */

if (!defined('ABSPATH')) exit;

// ---------------------------------------------------------------------------
// 台帳（デフォルト設定）
// ---------------------------------------------------------------------------

function zidooka_ads_default_config(): array {
    return [
        'placements' => [
            // AdSense: 記事タイトル直上（専用ユニット 2026-07-07 作成。位置別の効果測定用）
            'post_title_top' => [
                'provider' => 'adsense',
                'slot'     => '3775138171',
                'enabled'  => true,
            ],
            // AdSense: 本文直後
            'post_below_content' => [
                'provider' => 'adsense',
                'slot'     => '2410921395',
                'enabled'  => true,
            ],
            // AdSense: フロントページ記事リスト内（in-feed）
            'front_page_infeed' => [
                'provider'   => 'adsense',
                'slot'       => '1657762831',
                'format'     => 'fluid',
                'layout_key' => '-66+c1+y-11+h7',
                'enabled'    => true,
            ],
            // 緊急キャンペーン: 有効期間中はサイト上部へ優先配信
            'campaign_top' => [
                'provider'  => 'campaign',
                'campaigns' => ['prime_day_2026'],
                'enabled'  => true,
            ],
            // A8: 本文内（指定段落の後に挿入）。タグ一致した案件が無ければ出さない
            'post_in_content' => [
                'provider'   => 'a8',
                'offers'     => ['xserver_content'],
                'paragraphs' => [3, 5],
                'enabled'    => true,
            ],
            // A8: サイドバー。タグ一致 > default 案件の順で解決
            // A/B test (2026-07-09〜): デフォルト時は fp_cafe vs techgo_banner を50/50でランダム割当
            'sidebar' => [
                'provider' => 'a8',
                'offers'   => ['xserver_sidebar', 'fp_cafe', 'techgo_banner'],
                'ab_test'  => [
                    'variants' => ['fp_cafe', 'techgo_banner'],
                    'weights'  => [50, 50],
                ],
                'campaigns' => ['prime_day_2026'],
                'enabled'  => true,
            ],
        ],
        // 突発キャンペーン。期間・優先配置・計測をここ（または管理画面JSON）で管理
        'campaigns' => [
            'prime_day_2026' => [
                'label'      => 'Amazon Prime Day',
                'click'      => 'https://www.amazon.co.jp/primeday?tag=yamachi4u-22',
                'img'        => 'https://m.media-amazon.com/images/G/09/2026/x-site/primeday/P7qJSB8/03_ccm/PD26_CCM_004_LUDO_Paid_yahoo_dtmb_640x360.jpg',
                'width'      => 640,
                'height'     => 360,
                'start'      => '2026-07-10 00:00:00',
                'end'        => '2026-07-14 00:00:00',
                'status'     => 'active',
            ],
        ],
        // A8 案件テーブル。案件の追加・停止・差し替えはここ（または管理画面JSON）だけで完結させる
        // status: active | paused（paused は配信停止）
        // issued: A8でリンクコードを発行した年月（リンク切れ点検時の手がかり）
        'a8_offers' => [
            'xserver_content' => [
                'label'  => 'Xserver',
                'click'  => 'https://px.a8.net/svt/ejp?a8mat=45K9KW+9QOC36+CO4+6K735',
                'img'    => 'https://www25.a8.net/svt/bgt?aid=251208320589&wid=001&eno=01&mid=s00000001642001102000&mc=1',
                'pixel'  => 'https://www16.a8.net/0.gif?a8mat=45K9KW+9QOC36+CO4+6K735',
                'width'  => 336,
                'height' => 280,
                'tags'   => ['xserver'],
                'issued' => '2025-12',
                'status' => 'active',
            ],
            'xserver_sidebar' => [
                'label'  => 'Xserver',
                'click'  => 'https://px.a8.net/svt/ejp?a8mat=45K9KW+9QOC36+CO4+6PRPD',
                'img'    => 'https://www24.a8.net/svt/bgt?aid=251208320589&wid=001&eno=01&mid=s00000001642001128000&mc=1',
                'pixel'  => 'https://www12.a8.net/0.gif?a8mat=45K9KW+9QOC36+CO4+6PRPD',
                'width'  => 250,
                'height' => 250,
                'tags'   => ['xserver', 'server', 'hosting', 'wordpress', 'ドメイン', 'サーバー', 'レンタルサーバー'],
                'issued' => '2025-12',
                'status' => 'active',
            ],
            'techgo_banner' => [
                'label'   => 'TechGo',
                'click'   => 'https://px.a8.net/svt/ejp?a8mat=4B7VKV+CJALBM+5B0Y+HVNAP',
                'img'     => 'https://www27.a8.net/svt/bgt?aid=260707999758&wid=001&eno=01&mid=s00000024757003003000&mc=1',
                'pixel'   => 'https://www16.a8.net/0.gif?a8mat=4B7VKV+CJALBM+5B0Y+HVNAP',
                'width'   => 300,
                'height'  => 250,
                'tags'    => ['tech', 'engineer', '転職', 'プログラミング', 'エンジニア', 'キャリア', '開発'],
                'issued'  => '2026-07',
                'status'  => 'active',
            ],
            'fp_cafe' => [
                'label'   => 'FPカフェ',
                'click'   => 'https://px.a8.net/svt/ejp?a8mat=4B7VKV+CXKZUA+5ULO+5YZ75',
                'img'     => 'https://www29.a8.net/svt/bgt?aid=260707999782&wid=001&eno=01&mid=s00000027294001003000&mc=1',
                'pixel'   => 'https://www12.a8.net/0.gif?a8mat=4B7VKV+CXKZUA+5ULO+5YZ75',
                'width'   => 300,
                'height'  => 250,
                'tags'    => [],
                'default' => true,
                'issued'  => '2026-07',
                'status'  => 'active',
            ],
        ],
    ];
}

/**
 * 実効設定 = デフォルト + 管理画面JSONの上書き（placement / offer 単位のマージ）
 */
function zidooka_ads_config(): array {
    static $config = null;
    if ($config !== null) return $config;

    $config = zidooka_ads_default_config();
    $json = get_option('zidooka_ads_json', '');
    if (is_string($json) && trim($json) !== '') {
        $override = json_decode($json, true);
        if (is_array($override)) {
            foreach (['placements', 'campaigns', 'a8_offers'] as $section) {
                if (empty($override[$section]) || !is_array($override[$section])) continue;
                foreach ($override[$section] as $key => $cfg) {
                    if (!is_array($cfg)) continue;
                    $base = $config[$section][$key] ?? [];
                    $config[$section][$key] = array_merge($base, $cfg);
                }
            }
        }
    }
    return apply_filters('zidooka_ads_config', $config);
}

// ---------------------------------------------------------------------------
// 配信可否の共通ルール
// ---------------------------------------------------------------------------

function zidooka_adsense_client(): string {
    $client = defined('ADSENSE_CLIENT') ? constant('ADSENSE_CLIENT') : '';
    if (!$client) $client = 'ca-pub-5002038850592836';
    return apply_filters('zidooka_adsense_client', $client);
}

/**
 * このページで広告（provider別）を出してよいか。
 * ルールを変更したら docs/ADS_MANAGEMENT.md の出し分けマトリクスも更新すること。
 */
function zidooka_ads_page_allows(string $provider): bool {
    if (is_admin() || is_feed()) return false;

    // GAS配布ページは全広告オフ
    $gas_pt = defined('ZDK_GAS_POST_TYPE') ? constant('ZDK_GAS_POST_TYPE') : 'gas_script';
    if ($gas_pt && (is_singular($gas_pt) || is_post_type_archive($gas_pt))) return false;
    if (function_exists('get_query_var') && get_query_var('zdk_gas_download')) return false;

    // affiliate タグ記事は AdSense のみオフ（アフィ成約を優先、誤クリック回避）
    if ($provider === 'adsense' && is_singular() && has_tag('affiliate')) return false;

    return true;
}

// ---------------------------------------------------------------------------
// 共通レンダラ
// ---------------------------------------------------------------------------

/**
 * プレースメントIDを指定して広告HTMLを返す。出せない場合は空文字。
 */
function zidooka_render_ad(string $placement_id): string {
    $config = zidooka_ads_config();
    $p = $config['placements'][$placement_id] ?? null;
    if (!$p || empty($p['enabled'])) return '';

    $provider = $p['provider'] ?? '';
    if (!zidooka_ads_page_allows($provider)) return '';

    $campaign = zidooka_resolve_campaign($p['campaigns'] ?? []);
    if ($campaign) return zidooka_render_campaign($placement_id, $campaign);
    if ($provider === 'campaign') return '';

    if ($provider === 'adsense') return zidooka_render_adsense($placement_id, $p);
    if ($provider === 'a8')      return zidooka_render_a8($placement_id, $p);
    return '';
}

function zidooka_resolve_campaign(array $campaign_ids): ?array {
    if (!$campaign_ids) return null;
    $config = zidooka_ads_config();
    $tz = function_exists('wp_timezone') ? wp_timezone() : new DateTimeZone('Asia/Tokyo');
    $now = new DateTimeImmutable('now', $tz);
    foreach ($campaign_ids as $id) {
        $campaign = $config['campaigns'][$id] ?? null;
        if (!is_array($campaign) || ($campaign['status'] ?? 'active') !== 'active' || (($campaign['enabled'] ?? true) === false)) continue;
        try { $start = new DateTimeImmutable((string)($campaign['start'] ?? ''), $tz); $end = new DateTimeImmutable((string)($campaign['end'] ?? ''), $tz); } catch (Exception $e) { continue; }
        if ($now >= $start && $now < $end) { $campaign['id'] = $id; return $campaign; }
    }
    return null;
}

function zidooka_render_campaign(string $placement_id, array $campaign): string {
    $e_place = esc_attr($placement_id); $e_id = esc_attr($campaign['id'] ?? 'campaign');
    $e_click = esc_url($campaign['click'] ?? ''); $e_img = esc_url($campaign['img'] ?? '');
    if (!$e_click || !$e_img) return '';
    $props = wp_json_encode(['provider' => 'campaign', 'placement' => $placement_id, 'campaign' => $campaign['id'] ?? 'campaign']);
    $label = esc_attr($campaign['label'] ?? 'Sponsored'); $width = (int)($campaign['width'] ?? 640); $height = (int)($campaign['height'] ?? 360);
    return <<<HTML
<div class="zdk-ad zdk-ad--campaign zdk-ad--{$e_place}" data-ad-placement="{$e_place}" data-ad-campaign="{$e_id}">
  <span class="zdk-ad__label">広告</span>
  <a href="{$e_click}" target="_blank" rel="sponsored noopener noreferrer" class="zdk-ad__link" onclick="try{posthog.capture('ad_click',{$props})}catch(e){}">
    <img width="{$width}" height="{$height}" alt="{$label}" src="{$e_img}" loading="eager" decoding="async" />
  </a>
  <script>try{posthog.capture('ad_impression',{$props})}catch(e){}</script>
</div>
HTML;
}
function zidooka_render_adsense(string $placement_id, array $p): string {
    $client = zidooka_adsense_client();
    $slot   = $p['slot'] ?? '';
    if (!$client || !$slot) return '';

    $e_client = esc_attr($client);
    $e_slot   = esc_attr($slot);
    $e_place  = esc_attr($placement_id);

    if (($p['format'] ?? '') === 'fluid') {
        $e_layout = esc_attr($p['layout_key'] ?? '');
        $ins = "<ins class=\"adsbygoogle\" style=\"display:block\" data-ad-format=\"fluid\""
             . ($e_layout ? " data-ad-layout-key=\"{$e_layout}\"" : '')
             . " data-ad-client=\"{$e_client}\" data-ad-slot=\"{$e_slot}\"></ins>";
    } else {
        $ins = "<ins class=\"adsbygoogle\" style=\"display:block;max-width:100%;overflow:hidden;\""
             . " data-ad-client=\"{$e_client}\" data-ad-slot=\"{$e_slot}\""
             . " data-ad-format=\"auto\" data-full-width-responsive=\"true\"></ins>";
    }

    // ラベルは AdSense ポリシー準拠の「スポンサーリンク」固定
    return "<div class=\"zdk-ad zdk-ad--adsense zdk-ad--{$e_place}\" data-ad-placement=\"{$e_place}\">"
         . "<span class=\"zdk-ad__label\">スポンサーリンク</span>"
         . $ins
         . "<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>"
         . "</div>";
}

/**
 * A8 案件の解決: 候補リストを順に見て、タグ一致 > default の優先で選ぶ。
 * placement_config に ab_test が指定されている場合、default 時に重み付きランダム割当を行う。
 */
function zidooka_resolve_a8_offer(array $offer_ids, ?array $placement_config = null): ?array {
    $config = zidooka_ads_config();
    $post_tags = [];
    if (is_singular('post')) {
        $post_tags = wp_get_post_tags(get_queried_object_id(), ['fields' => 'slugs']);
        if (!is_array($post_tags)) $post_tags = [];
    }

    $ab_test = $placement_config['ab_test'] ?? null;
    $fallback = null;
    $ab_candidates = [];

    foreach ($offer_ids as $id) {
        $offer = $config['a8_offers'][$id] ?? null;
        if (!$offer || ($offer['status'] ?? 'active') !== 'active') continue;
        $tags = $offer['tags'] ?? [];

        // タグ一致 → 最優先で返す
        if ($tags && array_intersect($tags, $post_tags)) {
            $offer['id'] = $id;
            return $offer;
        }

        // A/Bテスト対象（タグ不一致時の候補）
        if ($ab_test && in_array($id, $ab_test['variants'] ?? [], true)) {
            $offer['id'] = $id;
            $ab_candidates[] = $offer;
            continue;
        }

        // 通常のdefault
        if (!empty($offer['default']) && $fallback === null) {
            $offer['id'] = $id;
            $fallback = $offer;
        }
    }

    // A/Bテスト: 重み付きランダム選択
    if ($ab_test && count($ab_candidates) > 0) {
        $weights = $ab_test['weights'] ?? array_fill(0, count($ab_candidates), 1);
        $total = array_sum($weights);
        if ($total > 0) {
            $rand = mt_rand(1, $total);
            $cum = 0;
            foreach ($ab_candidates as $i => $candidate) {
                $cum += $weights[$i] ?? 1;
                if ($rand <= $cum) return $candidate;
            }
        }
        return $ab_candidates[0];
    }

    return $fallback;
}

function zidooka_render_a8(string $placement_id, array $p): string {
    $offer = zidooka_resolve_a8_offer($p['offers'] ?? [], $p);
    if (!$offer) return '';

    $e_place = esc_attr($placement_id);
    $e_offer = esc_attr($offer['id']);
    $e_click = esc_url($offer['click'] ?? '');
    $e_img   = esc_url($offer['img'] ?? '');
    $e_pixel = esc_url($offer['pixel'] ?? '');
    $e_name  = esc_attr($offer['label'] ?? $offer['id']);
    $w = (int)($offer['width'] ?? 300);
    $h = (int)($offer['height'] ?? 250);
    if (!$e_click || !$e_img) return '';

    $event_props = [
        'provider'  => 'a8',
        'placement' => $placement_id,
        'offer'     => $offer['id'],
    ];
    // A/B実験中なら variant 情報を付与
    if (!empty($p['ab_test'])) {
        $event_props['ab_experiment'] = $placement_id;
        $event_props['ab_variant'] = $offer['id'];
    }
    $props_json = wp_json_encode($event_props);
    $e_props_attr = esc_attr($props_json);

    // 景表法ステマ規制対応: すべてのA8枠に広告ラベルを必ず表示する
    return <<<HTML
<div class="zdk-ad zdk-ad--a8 zdk-ad--{$e_place}" data-ad-placement="{$e_place}" data-ad-offer="{$e_offer}">
  <span class="zdk-ad__label">広告</span>
  <a href="{$e_click}" target="_blank" rel="nofollow sponsored noopener" class="zdk-ad__link"
     onclick="try{posthog.capture('ad_click',{$e_props_attr})}catch(e){}">
    <img width="{$w}" height="{$h}" alt="{$e_name}" src="{$e_img}" loading="lazy" decoding="async" />
    <img width="1" height="1" src="{$e_pixel}" alt="" aria-hidden="true" class="zdk-ad__pixel" />
  </a>
  <script>try{posthog.capture('ad_impression',{$props_json})}catch(e){}</script>
</div>
HTML;
}

// ---------------------------------------------------------------------------
// AdSense ローダー（wp_head）
// ---------------------------------------------------------------------------

add_action('wp_head', function () {
    if (!zidooka_ads_page_allows('adsense')) return;
    $client = zidooka_adsense_client();
    if (!$client) return;
    printf(
        "<script async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=%s\" crossorigin=\"anonymous\"></script>\n",
        esc_attr($client)
    );
}, 25);

// ---------------------------------------------------------------------------
// A8 本文内挿入（the_content フィルタ）
// ---------------------------------------------------------------------------

add_filter('the_content', function ($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) return $content;
    if (is_admin() || is_feed()) return $content;

    $config = zidooka_ads_config();
    $p = $config['placements']['post_in_content'] ?? null;
    if (!$p || empty($p['enabled'])) return $content;
    if (!zidooka_ads_page_allows('a8')) return $content;

    $banner = zidooka_render_ad('post_in_content');
    if (!$banner) return $content; // タグ一致する案件なし

    $paras = explode('</p>', $content);
    $para_count = 0;
    foreach ($paras as $para) {
        if (trim($para) !== '') $para_count++;
    }

    $positions = $p['paragraphs'] ?? [3, 5];
    // 読みやすさガード: 段落が少ない記事には同一バナーを繰り返さない（最初の位置のみ）
    if ($para_count < 10 && count($positions) > 1) {
        $positions = [reset($positions)];
    }

    $new_content = '';
    $idx = 0;
    $last = count($paras) - 1;
    foreach ($paras as $i => $para) {
        $new_content .= $para;
        if ($i !== $last) $new_content .= '</p>';
        if (trim($para) === '') continue;
        $idx++;
        if (in_array($idx, $positions, true)) {
            $new_content .= $banner;
        }
    }
    return $new_content;
});

// ---------------------------------------------------------------------------
// PostHog 計測（AdSense: impression / unfilled / click プロキシ）
// ---------------------------------------------------------------------------

add_action('wp_footer', function () {
    $key = defined('POSTHOG_KEY') ? constant('POSTHOG_KEY') : '';
    $key = apply_filters('zidooka_posthog_key', $key);
    if (!$key) return;
    if (!zidooka_ads_page_allows('adsense')) return;
    ?>
<script>
(function () {
    function cap(ev, props) { try { posthog.capture(ev, props); } catch (e) {} }
    function place(el) {
        var w = el.closest('.zdk-ad');
        return w ? (w.getAttribute('data-ad-placement') || 'unknown') : 'unknown';
    }
    function onStatus(ins) {
        if (ins._zdkSeen) return;
        var st = ins.getAttribute('data-ad-status');
        if (!st) return;
        ins._zdkSeen = true;
        var props = { provider: 'adsense', placement: place(ins), slot: ins.getAttribute('data-ad-slot'), path: location.pathname };
        if (st === 'filled') {
            cap('ad_impression', props);
        } else {
            cap('ad_unfilled', props);
            var w = ins.closest('.zdk-ad');
            if (w) w.classList.add('zdk-ad--unfilled'); // 空き枠は畳む（:has 未対応ブラウザ用の保険）
            var slot = ins.closest('.zdk-ad-slot');
            if (slot) slot.classList.add('zdk-ad-slot--unfilled'); // グリッドのスロットごと畳む
        }
    }
    document.addEventListener('DOMContentLoaded', function () {
        var list = document.querySelectorAll('ins.adsbygoogle');
        if (!list.length) return;
        list.forEach(function (ins) {
            onStatus(ins);
            new MutationObserver(function () { onStatus(ins); })
                .observe(ins, { attributes: true, attributeFilter: ['data-ad-status'] });
        });
        // クリックのプロキシ: 広告iframeへフォーカスが移った = クリックとみなす
        window.addEventListener('blur', function () {
            setTimeout(function () {
                var el = document.activeElement;
                if (!el || el.tagName !== 'IFRAME') return;
                var ins = el.closest('ins.adsbygoogle');
                if (!ins || ins._zdkClicked) return;
                ins._zdkClicked = true;
                cap('ad_click', { provider: 'adsense', placement: place(ins), slot: ins.getAttribute('data-ad-slot'), path: location.pathname });
            }, 0);
        });
    });
})();
</script>
    <?php
}, 30);

// ---------------------------------------------------------------------------
// 管理画面 [外観 > Ads Settings]
// ---------------------------------------------------------------------------

add_action('admin_init', function () {
    register_setting('zidooka_ads_settings', 'zidooka_ads_json');
});

add_action('admin_menu', function () {
    add_theme_page('Ads Settings', 'Ads Settings', 'manage_options', 'zidooka-ads-settings', 'zidooka_ads_settings_page');
});

function zidooka_ads_settings_page() {
    if (!current_user_can('manage_options')) return;
    $config  = zidooka_ads_config();
    $default = json_encode(zidooka_ads_default_config(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $value   = get_option('zidooka_ads_json', '');
    $json_error = '';
    if (is_string($value) && trim($value) !== '' && json_decode($value, true) === null) {
        $json_error = 'JSONのパースに失敗しています。上書きは無効化され、デフォルト設定で動作中です。';
    }
    ?>
    <div class="wrap">
        <h1>Ads Settings</h1>
        <p>広告プレースメントの台帳。設計と運用ルールはリポジトリの <code>docs/ADS_MANAGEMENT.md</code> を参照。</p>
        <?php if ($json_error): ?><div class="notice notice-error"><p><?php echo esc_html($json_error); ?></p></div><?php endif; ?>

        <h2>現在の実効設定</h2>
        <table class="widefat striped" style="max-width:900px;">
            <thead><tr><th>Placement</th><th>Provider</th><th>Enabled</th><th>Slot / Offers</th></tr></thead>
            <tbody>
            <?php foreach ($config['placements'] as $id => $p): ?>
                <tr>
                    <td><code><?php echo esc_html($id); ?></code></td>
                    <td><?php echo esc_html($p['provider'] ?? ''); ?></td>
                    <td><?php echo !empty($p['enabled']) ? '✔' : '—'; ?></td>
                    <td><?php echo esc_html($p['slot'] ?? implode(', ', (array)($p['offers'] ?? []))); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="margin-top:24px;">キャンペーン</h2>
        <table class="widefat striped" style="max-width:900px;">
            <thead><tr><th>Campaign</th><th>Label</th><th>期間</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach (($config['campaigns'] ?? []) as $id => $campaign): ?>
                <tr>
                    <td><code><?php echo esc_html($id); ?></code></td>
                    <td><?php echo esc_html($campaign['label'] ?? ''); ?></td>
                    <td><?php echo esc_html(($campaign['start'] ?? '—') . ' → ' . ($campaign['end'] ?? '—')); ?></td>
                    <td><?php echo esc_html($campaign['status'] ?? 'active'); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2 style="margin-top:24px;">A8 案件</h2>
        <table class="widefat striped" style="max-width:900px;">
            <thead><tr><th>Offer</th><th>Label</th><th>Size</th><th>Status</th><th>発行</th><th>タグ条件</th></tr></thead>
            <tbody>
            <?php foreach ($config['a8_offers'] as $id => $o): ?>
                <tr>
                    <td><code><?php echo esc_html($id); ?></code></td>
                    <td><?php echo esc_html($o['label'] ?? ''); ?></td>
                    <td><?php echo esc_html(($o['width'] ?? '?') . '×' . ($o['height'] ?? '?')); ?></td>
                    <td><?php echo esc_html($o['status'] ?? 'active'); ?><?php echo !empty($o['default']) ? '（default）' : ''; ?></td>
                    <td><?php echo esc_html($o['issued'] ?? '—'); ?></td>
                    <td><?php echo esc_html(implode(', ', (array)($o['tags'] ?? []))); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 style="margin-top:24px;">上書きJSON</h2>
        <p>placement / offer 単位でデフォルトにマージされます。例: <code>{"placements":{"post_title_top":{"enabled":false}}}</code></p>
        <form method="post" action="options.php">
            <?php settings_fields('zidooka_ads_settings'); ?>
            <textarea name="zidooka_ads_json" rows="12" style="width:100%;max-width:900px;font-family:ui-monospace,Consolas,monospace;"><?php echo esc_textarea($value); ?></textarea>
            <?php submit_button('Save Ads Config'); ?>
        </form>

        <details style="margin-top:16px;max-width:900px;">
            <summary>デフォルト設定（読み取り専用）</summary>
            <textarea rows="24" style="width:100%;font-family:ui-monospace,Consolas,monospace;" readonly><?php echo esc_textarea($default); ?></textarea>
        </details>
    </div>
    <?php
}
