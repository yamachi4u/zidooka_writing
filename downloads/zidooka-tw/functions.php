<?php
/*
        _               _                  _____        _     _ _     _   _   _                         
       (_)             | |                | ____|      | |   (_) |   | | | | | |                        
  _ __  _  ___ ___  ___| |_ _ __ __ _ _ __| |__     ___| |__  _| | __| | | |_| |__   ___ _ __ ___   ___ 
 | '_ \| |/ __/ _ \/ __| __| '__/ _` | '_ \___ \   / __| '_ \| | |/ _` | | __| '_ \ / _ \ '_ ` _ \ / _ \
 | |_) | | (_| (_) \__ \ |_| | | (_| | |_) |__) | | (__| | | | | | (_| | | |_| | | |  __/ | | | | |  __/
 | .__/|_|\___\___/|___/\__|_|  \__,_| .__/____/   \___|_| |_|_|_|\__,_|  \__|_| |_|\___|_| |_| |_|\___|
 | |                                 | |                                                                
 |_|                                 |_|                                                                

                                                       
*************************************** WELCOME TO PICOSTRAP ***************************************

********************* THE BEST WAY TO EXPERIENCE SASS, BOOTSTRAP AND WORDPRESS *********************

    PLEASE WATCH THE VIDEOS FOR BEST RESULTS:
    https://www.youtube.com/playlist?list=PLtyHhWhkgYU8i11wu-5KJDBfA9C-D4Bfl

*/

// Production safeguard: third-party plugins can emit PHP 8.x deprecated/warning output
// during admin bootstrap, which then breaks headers. Keep debug visibility when WP_DEBUG
// is enabled, but suppress on normal runtime so wp-admin remains usable.
if (PHP_SAPI !== 'cli' && (!defined('WP_DEBUG') || !WP_DEBUG)) {
    @ini_set('display_errors', '0');
    @ini_set('display_startup_errors', '0');
    $current_error_reporting = error_reporting();
    if ($current_error_reporting) {
        error_reporting($current_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_WARNING & ~E_USER_WARNING);
    }
}

// Dequeue Bootstrap from parent if registered (harmless if not present)
add_action( 'wp_print_scripts', function(){
    wp_dequeue_script( 'bootstrap5' );
}, 100 );

// Enqueue stylesheets — Tailwind production build + theme styles
add_action( 'wp_enqueue_scripts', function() {
    // Tailwind production build (replaces CDN)
    $tw_path = get_stylesheet_directory() . '/assets/tailwind.css';
    if (file_exists($tw_path)) {
        wp_enqueue_style('tailwind-output', get_stylesheet_directory_uri() . '/assets/tailwind.css', array(), filemtime($tw_path));
    }

    // Theme custom styles
    $style_path = get_stylesheet_directory() . '/style.css';
    $ver = file_exists( $style_path ) ? filemtime( $style_path ) : null;
    wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), $ver );

    // A/B experiment CSS
    $exp_css = '
        .exp-font-large { font-size: 20px; }
        .exp-line-loose { line-height: 1.9; }
        .exp-toc-sticky { position: sticky; top: 1rem; }
        .exp-related-grid4 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .exp-ad-early .zidooka-xserver-ad:first-of-type { display: none; }
    ';
    wp_add_inline_style('theme-style', $exp_css);
});

// Disable WordPress emoji/embed bloat
add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_filter('the_content', 'convert_smilies');
    remove_filter('excerpt_more', 'convert_smilies');
    add_filter('xmlrpc_enabled', '__return_false');
});

// Register menu locations used by header.php
add_action( 'after_setup_theme', function(){
    register_nav_menus([
        'primary' => __('Primary Menu', 'zidooka-tw'),
    ]);
});

// Enable dynamic <title> tag output handled by WordPress
add_action( 'after_setup_theme', function(){
    add_theme_support( 'title-tag' );
});

// Google Analytics 4 (GA4) injection
// Provide your Measurement ID via either:
// - Define('GA_MEASUREMENT_ID', 'G-XXXXXXX') in wp-config.php, or
// - add_filter('zidooka_ga4_id', fn(){ return 'G-XXXXXXX'; }); in a plugin/snippet
add_action('wp_head', function(){
    $ga4_id = '';
    if (defined('GA_MEASUREMENT_ID')) {
        $ga4_id = constant('GA_MEASUREMENT_ID');
    }
    $ga4_id = apply_filters('zidooka_ga4_id', $ga4_id);
    // Default to provided stream if not overridden
    if (!$ga4_id) {
        $ga4_id = 'G-VNF3D5QY6E';
    }
    if (!$ga4_id) return;

    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . esc_attr($ga4_id) . "\"></script>\n";
    echo "<script>\n";
    echo "window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '" . esc_js($ga4_id) . "');\n";
    echo "</script>\n";
}, 30);

// PostHog feature flags & analytics injection
// Configure via:
// - define('POSTHOG_KEY', 'phc_xxxxx') in wp-config.php, or
// - add_filter('zidooka_posthog_key', fn(){ return 'phc_xxxxx'; });
// Requires 'person_profiles: identified_only' for GDPR compliance.
add_action('wp_head', function(){
    $key = '';
    if (defined('POSTHOG_KEY')) {
        $key = constant('POSTHOG_KEY');
    }
    $key = apply_filters('zidooka_posthog_key', $key);
    if (!$key) return;
    ?>
<script>
    !function(t,e){var o,n,p,r;e.__SV||(window.posthog&&window.posthog.__loaded)||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.crossOrigin="anonymous",p.async=!0,p.src=s.api_host.replace(".i.posthog.com","-assets.i.posthog.com")+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people (stub)"},o="Di ji init en nn Ar tn an Yi capture calculateEventProperties dn register register_once register_for_session unregister unregister_for_session gn getFeatureFlag getFeatureFlagPayload getFeatureFlagResult isFeatureEnabled reloadFeatureFlags updateFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSurveysLoaded onSessionId getSurveys getActiveMatchingSurveys renderSurvey displaySurvey cancelPendingSurvey canRenderSurvey canRenderSurveyAsync mn identify setPersonProperties group resetGroups setPersonPropertiesForFlags resetPersonPropertiesForFlags setGroupPropertiesForFlags resetGroupPropertiesForFlags reset setIdentity clearIdentity get_distinct_id getGroups get_session_id get_session_replay_url alias set_config startSessionRecording stopSessionRecording sessionRecordingStarted captureException addExceptionStep captureLog startExceptionAutocapture stopExceptionAutocapture loadToolbar get_property getSessionProperty fn hn createPersonProfile setInternalOrTestUser pn Ji opt_in_capturing opt_out_capturing has_opted_in_capturing has_opted_out_capturing get_explicit_consent_status is_capturing clear_opt_in_out_capturing un debug $r vn getPageViewId captureTraceFeedback captureTraceMetric Zi".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);
    posthog.init('<?php echo esc_js($key); ?>', {
        api_host: 'https://us.i.posthog.com',
        person_profiles: 'identified_only',
    });
</script>
    <?php
}, 26);

// PostHog ad click tracking — captures clicks on filled adsbygoogle elements.
add_action('wp_head', function(){
    $key = '';
    if (defined('POSTHOG_KEY')) { $key = constant('POSTHOG_KEY'); }
    $key = apply_filters('zidooka_posthog_key', $key);
    if (!$key) return;
    ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var attempts = 0;
    var poll = setInterval(function () {
        if (++attempts > 20) { clearInterval(poll); return; }
        var ads = document.querySelectorAll('ins.adsbygoogle[data-ad-status="filled"]');
        if (ads.length === 0) return;
        clearInterval(poll);
        ads.forEach(function (ad) {
            if (ad._adClickTracked) return;
            ad._adClickTracked = true;
            ad.addEventListener('click', function () {
                try { posthog.capture('ad_click', { slot: ad.getAttribute('data-ad-slot'), path: location.pathname }); } catch (e) {}
            });
        });
    }, 500);
});
</script>
    <?php
}, 27);

// Temporary GA4 probe for `(not set)` landing-page investigation.
// Fires only on single posts with search/chat/cross-site referrers.
add_action('wp_head', function(){
    if (is_admin() || !is_single()) {
        return;
    }
    ?>
<script>
(function () {
  if (typeof window.gtag !== 'function') return;

  var ref = document.referrer || '';
  if (!ref) return;

  var host = '';
  try {
    host = new URL(ref).hostname.toLowerCase();
  } catch (error) {
    return;
  }

  var targets = [
    'google.',
    'bing.com',
    'duckduckgo.com',
    'search.yahoo.',
    'chatgpt.com',
    'copilot.com',
    'teams.cdn.office.net'
  ];

  var matched = targets.some(function (needle) {
    return host.indexOf(needle) !== -1;
  });
  if (!matched) return;

  var baseParams = {
    page_location: window.location.href,
    page_referrer: ref
  };
  var sent = {};

  function send(name, extra) {
    if (sent[name]) return;
    sent[name] = true;
    window.gtag('event', name, Object.assign({}, baseParams, extra || {}));
  }

  send('zdk_debug_search_boot');

  if (document.visibilityState && document.visibilityState !== 'visible') {
    send('zdk_debug_search_hidden', {
      visibility_state: document.visibilityState
    });
  }

  if (document.prerendering) {
    send('zdk_debug_search_prerender');
    document.addEventListener('prerenderingchange', function () {
      send('zdk_debug_search_activate', {
        visibility_state: document.visibilityState || ''
      });
    }, { once: true });
  }

  window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
      send('zdk_debug_search_bfcache');
    }
  }, { once: true });
})();
</script>
    <?php
}, 31);

// Google AdSense injection (migrated from old header)
// Configure via:
// - define('ADSENSE_CLIENT', 'ca-pub-5002038850592836'); in wp-config.php, or
// - add_filter('zidooka_adsense_client', fn(){ return 'ca-pub-XXXX'; });
// Skips on posts tagged 'affiliate' (same behavior as before)
add_action('wp_head', function(){
    if (is_admin()) return;
    // Do not serve AdSense on GAS distribution pages.
    $gas_pt = defined('ZDK_GAS_POST_TYPE') ? constant('ZDK_GAS_POST_TYPE') : 'gas_script';
    if ($gas_pt && (is_singular($gas_pt) || is_post_type_archive($gas_pt))) return;
    if (function_exists('get_query_var') && get_query_var('zdk_gas_download')) return;
    if (is_singular() && has_tag('affiliate')) return;

    $client = '';
    if (defined('ADSENSE_CLIENT')) {
        $client = constant('ADSENSE_CLIENT');
    }
    // Default to previous client from base theme if not overridden
    if (!$client) $client = 'ca-pub-5002038850592836';
    $client = apply_filters('zidooka_adsense_client', $client);
    if (!$client) return;

    printf(
        "<script async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=%s\" crossorigin=\"anonymous\"></script>\n",
        esc_attr($client)
    );
}, 25);

// PostHog experiments JS
add_action( 'wp_enqueue_scripts', function() {
    if (!is_singular('post') && !is_page()) return;
    wp_enqueue_script(
        'zdk-posthog-experiments',
        get_stylesheet_directory_uri() . '/assets/posthog-experiments.js',
        array(),
        file_exists(get_stylesheet_directory() . '/assets/posthog-experiments.js') ? filemtime(get_stylesheet_directory() . '/assets/posthog-experiments.js') : null,
        array('strategy' => 'defer', 'in_footer' => true)
    );
}, 102);

// OPTIONAL: ADD MORE NAV MENUS
//register_nav_menus( array( 'third' => __( 'Third Menu', 'picostrap' ), 'fourth' => __( 'Fourth Menu', 'picostrap' ), 'fifth' => __( 'Fifth Menu', 'picostrap' ), ) );
// THEN USE SHORTCODE:  [lc_nav_menu theme_location="third" container_class="" container_id="" menu_class="navbar-nav"]


// CHECK PARENT THEME VERSION
/*
add_action( 'admin_notices', function  () {
    if (!function_exists('pico_get_parent_theme_version')) return;
    if( (pico_get_parent_theme_version())>=3.0) return; 
	$message = __( 'This Child Theme requires at least Picostrap Version 3.0.0  in order to work properly. Please update the parent theme.', 'picostrap' );
	printf( '<div class="%1$s"><h1>%2$s</h1></div>', esc_attr( 'notice notice-error' ), esc_html( $message ) );
} );
*/

// OPTIONAL: FOR SECURITY: DISABLE APPLICATION PASSWORDS. Uncomment if needed
//add_filter( 'wp_is_application_passwords_available', '__return_false' );

// ADD YOUR CUSTOM PHP CODE DOWN BELOW /////////////////////////

// --- Theme helper fallbacks (avoid fatal on search/archive templates) ---
if (!function_exists('zidooka_tw_post_thumbnail')) {
    function zidooka_tw_post_thumbnail() {
        if (!has_post_thumbnail()) return;
        echo '<div class="post-thumbnail">';
        the_post_thumbnail('large', ['class' => 'zidooka-thumb', 'loading' => 'lazy', 'decoding' => 'async']);
        echo '</div>';
    }
}

if (!function_exists('zidooka_tw_content_class')) {
    function zidooka_tw_content_class($class = 'entry-content') {
        $class = $class ? $class : 'entry-content';
        echo 'class="' . esc_attr($class) . '"';
    }
}

if (!function_exists('zidooka_tw_entry_footer')) {
    function zidooka_tw_entry_footer() {
        if ('post' !== get_post_type()) return;
        $cats = get_the_category_list(', ');
        $tags = get_the_tag_list('', ', ');
        if ($cats) {
            echo '<span class="cat-links">' . $cats . '</span>';
        }
        if ($tags) {
            echo ' <span class="tag-links">' . $tags . '</span>';
        }
    }
}

if (!function_exists('zidooka_tw_the_posts_navigation')) {
    function zidooka_tw_the_posts_navigation() {
        if (!function_exists('the_posts_navigation')) return;
        echo '<nav class="navigation posts-navigation" aria-label="Posts">';
        the_posts_navigation();
        echo '</nav>';
    }
}

if (!function_exists('zidooka_tw_html5_comment')) {
    function zidooka_tw_html5_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        echo '<li '; comment_class('comment'); echo ' id="comment-'; comment_ID(); echo '">';
        echo '<article class="comment-body">';
        echo '<footer class="comment-meta">';
        echo get_avatar($comment, 48);
        echo '<b class="fn">' . get_comment_author_link() . '</b>';
        echo '<span class="comment-metadata"><a href="' . esc_url(get_comment_link($comment->comment_ID)) . '">';
        echo esc_html(get_comment_date()) . '</a></span>';
        echo '</footer>';
        echo '<div class="comment-content">';
        comment_text();
        echo '</div>';
        if ($comment->comment_approved == '0') {
            echo '<em>' . esc_html__('Your comment is awaiting moderation.', 'zidooka-tw') . '</em>';
        }
        echo '</article>';
    }
}

// --- Category-based CTA system ---
if (!function_exists('zidooka_is_english_post')) {
    function zidooka_is_english_post($post_id) {
        if (!$post_id) return false;

        $slug = get_post_field('post_name', $post_id);
        if ($slug && preg_match('/(^|-)en($|-)/', $slug)) return true;

        $cats = get_the_category($post_id);
        if (!empty($cats)) {
            foreach ($cats as $cat) {
                if (!empty($cat->slug) && preg_match('/(^|-)en($|-)/', $cat->slug)) return true;
            }
        }

        $tags = get_the_tags($post_id);
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if (!empty($tag->slug) && preg_match('/(^|-)en($|-)/', $tag->slug)) return true;
            }
        }

        $title = get_the_title($post_id);
        return $title ? !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $title) : false;
    }
}

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
        'copiloterro' => $error,
        'naerror' => $error,
        'win-errror' => $error,
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
});

add_action('admin_menu', function () {
    add_theme_page('CTA Settings', 'CTA Settings', 'manage_options', 'zidooka-cta-settings', 'zidooka_cta_settings_page');
});

function zidooka_cta_settings_page() {
    if (!current_user_can('manage_options')) return;
    $default = json_encode(zidooka_cta_default_map(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $value = get_option('zidooka_cta_json', '');
    ?>
    <div class="wrap">
        <h1>CTA Settings</h1>
        <p>カテゴリのスラッグに合わせてCTAを切り替えます。未指定の場合は <code>default</code> を使用します。</p>
        <form method="post" action="options.php">
            <?php settings_fields('zidooka_cta_settings'); ?>
            <textarea name="zidooka_cta_json" rows="18" style="width: 100%; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, \"Liberation Mono\", monospace;"><?php echo esc_textarea($value); ?></textarea>
            <p>空のまま保存するとデフォルト設定が使われます。下はデフォルトの例です。</p>
            <textarea rows="18" style="width: 100%; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, \"Liberation Mono\", monospace;" readonly><?php echo esc_textarea($default); ?></textarea>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Simple Like System
add_action('wp_ajax_nopriv_process_simple_like', 'process_simple_like');
add_action('wp_ajax_process_simple_like', 'process_simple_like');

function process_simple_like() {
    // Security check
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if (!wp_verify_nonce($nonce, 'simple-like-nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    if ($post_id > 0) {
        $likes = get_post_meta($post_id, '_post_like_count', true);
        $likes = $likes ? intval($likes) : 0;
        $likes++;
        update_post_meta($post_id, '_post_like_count', $likes);
        wp_send_json_success(array('likes' => $likes));
    }
    wp_send_json_error('Invalid post ID');
}


// --- SEO small improvements ---
// 1) Fallback canonical/meta/OG/Twitter if no SEO plugin is active
add_action('wp_head', function () {
    if (!is_singular('post')) return;
    if (function_exists('wpseo_head') || defined('RANK_MATH_VERSION')) return; // Skip if major SEO plugin present

    $post_id = get_the_ID();
    $title = get_the_title($post_id);
    $url = get_permalink($post_id);
    $desc = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words(wp_strip_all_tags(get_post_field('post_content', $post_id)), 30, '...');

    echo '<link rel="canonical" href="' . esc_url($url) . '" />' . "\n";
    echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";

    $thumb_id = get_post_thumbnail_id($post_id);
    $og_img = '';
    if ($thumb_id) {
        $img = wp_get_attachment_image_src($thumb_id, 'full');
        if ($img) {
            $og_img = $img[0];
            echo '<meta property="og:image:width" content="' . intval($img[1]) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . intval($img[2]) . '" />' . "\n";
        }
    }
    if (!$og_img) {
        $og_img = 'https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png';
    }
    echo '<meta property="og:image" content="' . esc_url($og_img) . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($og_img) . '" />' . "\n";

    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '" />' . "\n";

    echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c', $post_id)) . '" />' . "\n";
    echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c', $post_id)) . '" />' . "\n";

    $cats = get_the_category($post_id);
    if ($cats) echo '<meta property="article:section" content="' . esc_attr($cats[0]->name) . '" />' . "\n";
    $tags = get_the_tags($post_id);
    if ($tags) {
        foreach ($tags as $t) echo '<meta property="article:tag" content="' . esc_attr($t->name) . '" />' . "\n";
    }

    // Multipage rel prev/next for paginated posts
    global $page, $numpages;
    if ($numpages > 1) {
        if ($page > 1) echo '<link rel="prev" href="' . esc_url(get_pagenum_link($page - 1)) . '" />' . "\n";
        if ($page < $numpages) echo '<link rel="next" href="' . esc_url(get_pagenum_link($page + 1)) . '" />' . "\n";
    }
}, 5);

// 2) Image attributes: ensure alt fallback and decoding=async
add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment) {
    if (empty($attr['alt'])) {
        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        if (!$alt) $alt = get_the_title();
        $attr['alt'] = $alt;
    }
    $attr['decoding'] = 'async';
    return $attr;
}, 10, 2);
// 2.1) Front page LCP: preload hero (static front page) and set high priority
add_action('wp_head', function(){
    if (!is_front_page()) return;
    $front_id = (int) get_option('page_on_front');
    if ($front_id <= 0) return; // only when a static front page is set
    $hero_id = get_post_thumbnail_id($front_id);
    if (!$hero_id) return;
    $src_full = wp_get_attachment_image_url($hero_id, 'full');
    $srcset   = wp_get_attachment_image_srcset($hero_id, 'full');
    $sizes    = '(min-width: 1024px) 1200px, 100vw';
    if ($src_full && $srcset) {
        printf(
            "<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
            esc_url($src_full), esc_attr($srcset), esc_attr($sizes)
        );
    }
}, 6);

// 2.2) Inline critical CSS on front page if present
add_action('wp_head', function(){
    if (!is_front_page()) return;
    $path = get_stylesheet_directory() . '/css-output/critical-home.css';
    if (file_exists($path)) {
        $css = file_get_contents($path);
        if ($css !== false && $css !== '') {
            echo "<style id=\"zdk-critical-home\">{$css}</style>\n";
        }
    }
}, 7);

// 2.3) Force eager/high priority for the front-page and single-post featured image
add_filter('post_thumbnail_html', function(
  $html, $post_id, $post_thumbnail_id, $size, $attr
){
  $is_target = is_front_page() && (int) get_option('page_on_front') === (int) $post_id;
  $is_target = $is_target || (is_single() && in_the_loop());
  if (!$is_target) return $html;
  if (strpos($html, 'loading=') === false) {
    $html = str_replace('<img', '<img loading="eager"', $html);
  } else {
    $html = preg_replace('/loading=(["\'])([^"\']*)(["\'])/i', 'loading=$1eager$3', $html, 1);
  }
  if (strpos($html, 'fetchpriority=') === false) {
    $html = str_replace('<img', '<img fetchpriority="high"', $html);
  } else {
    $html = preg_replace('/fetchpriority=(["\'])([^"\']*)(["\'])/i', 'fetchpriority=$1high$3', $html, 1);
  }
  return $html;
}, 10, 5);

// ユーザープロフィールに英語の自己紹介欄を追加
add_action( 'show_user_profile', 'add_english_bio_field' );
add_action( 'edit_user_profile', 'add_english_bio_field' );

function add_english_bio_field( $user ) {
    ?>
    <h3>英語のプロフィール情報 / English Profile Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="description_en">英語のプロフィール<br>(English Biographical Info)</label></th>
            <td>
                <textarea name="description_en" id="description_en" rows="5" cols="30" class="regular-text"><?php echo esc_textarea( get_the_author_meta( 'description_en', $user->ID ) ); ?></textarea><br />
                <span class="description">英語での自己紹介文を入力してください。記事詳細ページの著書紹介欄に、日本語の下に表示されます。<br>Please enter your biographical info in English. It will be displayed below the Japanese bio on single post pages.</span>
            </td>
        </tr>
    </table>
    <?php
}

add_action( 'personal_options_update', 'save_english_bio_field' );
add_action( 'edit_user_profile_update', 'save_english_bio_field' );

function save_english_bio_field( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_user_meta( $user_id, 'description_en', isset($_POST['description_en']) ? $_POST['description_en'] : '' );
}

// ZIDOOKA!：xserverタグ付き投稿にA8バナー挿入
function zidooka_insert_xserver_banner($content) {

    if (!is_singular('post')) return $content; // 投稿のみ
    if (!has_tag('xserver')) return $content;  // タグ xserver の記事のみ
    
    // 管理画面とフィードは除外
    if (is_admin() || is_feed()) return $content;  

    // ここに挿入するアフィリエイトタグ
    $banner = '
    <div class="zidooka-xserver-ad" style="margin:24px 0; text-align:center;">
        <a href="https://px.a8.net/svt/ejp?a8mat=45K9KW+9QOC36+CO4+6K735" rel="nofollow">
        <img border="0" width="336" height="280" alt="" src="https://www25.a8.net/svt/bgt?aid=251208320589&wid=001&eno=01&mid=s00000001642001102000&mc=1"></a>
        <img border="0" width="1" height="1" src="https://www16.a8.net/0.gif?a8mat=45K9KW+9QOC36+CO4+6K735" alt="">
    </div>
    ';

    // 段落（<p>）ごとに分割
    $paras = explode("</p>", $content);
    $new_content = "";

    // 何段落目かカウントしながら組み直す
    foreach ($paras as $i => $para) {
        if (trim($para) == "") continue;

        $new_content .= $para . "</p>";

        // 3段落目の後に挿入
        if ($i == 2) {
            $new_content .= $banner;
        }
        // 5段落目の後に挿入
        if ($i == 4) {
            $new_content .= $banner;
        }
    }

    return $new_content;
}
add_filter('the_content', 'zidooka_insert_xserver_banner');


/**
 * Amazonリンク自動最適化（yamachi4u-22 専用）
 *
 * ・既に本文に存在していたAmazonリンクは絶対に変更しない
 * ・新しく追加されたAmazonリンクだけを対象にする
 * ・すでに tag= が付いているURLは上書きしない
 * ・/dp/ASIN の商品ページだけを、短いキレイなURL + tag=yamachi4u-22 に整形
 */
function yamachi4u_auto_amazon_links($data, $postarr) {

    // 必要なら「投稿だけに適用」などの絞り込みも可
    // if (isset($data['post_type']) && $data['post_type'] !== 'post') {
    //     return $data;
    // }

    // 旧バージョンの本文（既存リンク判定用）
    $old_content = '';
    if (!empty($postarr['ID'])) {
        $old_post = get_post($postarr['ID']);
        if ($old_post && isset($old_post->post_content)) {
            $old_content = $old_post->post_content;
        }
    }

    $content = $data['post_content'];

    // Amazon.co.jp のURLにマッチさせる
    $pattern = '/https?:\/\/(?:www\.)?amazon\.co\.jp\/[^\s"\']+/u';

    $tag_id = 'yamachi4u-22';

    $content = preg_replace_callback($pattern, function ($matches) use ($old_content, $tag_id) {

        $url = $matches[0];

        // 旧本文にすでに存在するURL → そのまま尊重して何もしない
        if ($old_content && strpos($old_content, $url) !== false) {
            return $url;
        }

        // すでに tag= が付いているURL → 上書きしない
        if (strpos($url, 'tag=') !== false) {
            return $url;
        }

        // /dp/ASIN（10桁）だけを対象にする
        if (!preg_match('/\/dp\/([A-Z0-9]{10})/i', $url, $m)) {
            return $url;
        }

        $asin = $m[1];

        // キレイな形のURLを組み立てる
        $clean_url = 'https://www.amazon.co.jp/dp/' . $asin . '/?tag=' . $tag_id;

        return $clean_url;

    }, $content);

    $data['post_content'] = $content;
    return $data;
}
add_filter('wp_insert_post_data', 'yamachi4u_auto_amazon_links', 10, 2);




add_action('transition_post_status', function ($new_status, $old_status, $post) {

  // publish になった瞬間のみ
  if ($old_status === 'publish' || $new_status !== 'publish') return;

  // 投稿タイプ制限
  if ($post->post_type !== 'post') return;

  // 自動保存・リビジョン除外
  if (wp_is_post_autosave($post->ID) || wp_is_post_revision($post->ID)) return;

  // 二重投稿防止
  if (get_post_meta($post->ID, '_x_posted', true)) return;

  // タイトル取得
  $title = trim(wp_strip_all_tags($post->post_title));
  if ($title === '') return;

  // 文字数制限（安全側）
  $title = mb_substr($title, 0, 100);
  $url   = get_permalink($post->ID);

  // 投稿文
  $text = "【新記事】\n{$title}\n\n{$url}";

  // GAS Web App URL（確定）
  $gas_url = 'https://script.google.com/macros/s/AKfycbwoI1ueaBa4CmzraXW_VLvUJd77winRoMI8HK4-Ck6p8NPbipRZIZygEig9HQQ6o1zsrA/exec';

  // GAS に POST
  $response = wp_remote_post(
    $gas_url,
    [
      'timeout' => 5,
      'headers' => [
        'Content-Type' => 'application/json'
      ],
      'body' => wp_json_encode([
        'post_id' => $post->ID,
        'text'    => $text
      ])
    ]
  );

  // 成功したらフラグ保存
  if (!is_wp_error($response)) {
    update_post_meta($post->ID, '_x_posted', 1);
  }

}, 10, 3);

/**
 * HCB Bridge: CLI → marked で language を埋め込んだコードを
 * 表示時に prism class でラップして HCB を起動させる
 */
add_filter('the_content', 'zidooka_hcb_bridge_with_language', 20);

function zidooka_hcb_bridge_with_language($content) {
  
  if (strpos($content, 'language-') === false) {
    return $content;
  }

  $content = preg_replace_callback(
    '/<pre([^>]*)>\s*<code class="language-([^"]+)">/i',
    function ($m) {
      $pre_attr = $m[1];
      $lang     = $m[2];

      // 言語マッピング（js ↔ javascript など正規化）
      $lang_map = [
        'javascript' => 'js',
        'js'         => 'js',
        'php'        => 'php',
        'html'       => 'html',
        'css'        => 'css',
        'bash'       => 'bash',
        'sh'         => 'bash',
        'json'       => 'json',
        'sql'        => 'sql',
      ];

      $hcb_lang = $lang_map[strtolower($lang)] ?? $lang;

      return sprintf(
        '<pre%s class="prism line-numbers language-%s" data-lang="%s"><code class="language-%s">',
        $pre_attr,
        esc_attr($hcb_lang),
        esc_attr(strtoupper($hcb_lang)),
        esc_attr($hcb_lang)
      );
    },
    $content
  );

  return $content;
}

/* サムネイルのデータ */

function setup_theme() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'setup_theme');
/**
 * 投稿タイトルから言語判定
 * ・日本語が1文字でも含まれていれば ja
 * ・それ以外で ASCII比率が高ければ en
 */
function zidooka_detect_lang_by_post($post) {
    $title = $post->post_title;

    // 日本語が含まれていれば日本語
    if (preg_match('/[ぁ-んァ-ン一-龠]/u', $title)) {
        return 'ja';
    }

    // ASCII比率で判定
    $ascii_count = preg_match_all('/[A-Za-z0-9\/\-\_\.\:\(\)\[\]\'" ]/', $title);
    $total_len   = mb_strlen($title);
    $ascii_ratio = $total_len > 0 ? ($ascii_count / $total_len) : 0;

    if ($ascii_ratio >= 0.7) {
        return 'en';
    }

    return 'ja';
}

/**
 * 本文の「最初の見出し（h2 / h3）以降」から抜粋を生成
 * → 書き出し被りを回避するため
 */
function zidooka_get_smart_excerpt($post_id, $length = 70) {

    $content = get_post_field('post_content', $post_id);
    if (!$content) {
        return '';
    }

    // 最初の h2 または h3 を探す
    if (preg_match('/<(h2|h3)[^>]*>.*?<\/\1>/is', $content, $m, PREG_OFFSET_CAPTURE)) {
        $start_pos = $m[0][1] + strlen($m[0][0]);
        $content = substr($content, $start_pos);
    }

    // HTML / ショートコード除去
    $content = wp_strip_all_tags(strip_shortcodes($content));

    // トリム
    return wp_trim_words($content, $length, '…');
}

/**
 * カテゴリ記事一覧ショートコード
 * ・カテゴリは1つ（日英混在）
 * ・出力側で日英を判定
 * ・Bootstrap card UI
 * ・カード全体クリック可能
 */
function zidooka_category_list_shortcode($atts) {

    $atts = shortcode_atts([
        'base_cat' => '',
        'lang' => 'ja',
        'heading_level' => '2',
        'posts_per_page' => 20,
    ], $atts);

    if (empty($atts['base_cat'])) {
        return '';
    }

    // 表示言語
    $lang = in_array($atts['lang'], ['ja', 'en'], true) ? $atts['lang'] : 'ja';

    // 見出しレベル
    $heading_level = in_array($atts['heading_level'], ['2', '3'], true)
        ? $atts['heading_level']
        : '2';
    $heading_tag = 'h' . $heading_level;

    // キャッシュキー
    $cache_key = 'zidooka_cat_list_v2_' . $atts['base_cat'] . '_' . $lang . '_h' . $heading_level;
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }

    $heading_text = [
        'ja' => '日本語の記事一覧',
        'en' => 'English Articles',
    ];

    // カテゴリは1つだけ取得
    $query = new WP_Query([
        'category_name'  => $atts['base_cat'],
        'posts_per_page' => intval($atts['posts_per_page']),
        'no_found_rows'  => true,
    ]);

    if (!$query->have_posts()) {
        return '';
    }

    ob_start();

    echo '<div class="zidooka-cat-list">';
    echo '<' . $heading_tag . ' class="mb-3">' . esc_html($heading_text[$lang]) . '</' . $heading_tag . '>';

    $count = 0;

    while ($query->have_posts()) {
        $query->the_post();

        // 言語判定
        $post_lang = zidooka_detect_lang_by_post(get_post());
        if ($post_lang !== $lang) {
            continue;
        }

        $count++;

        // スマート抜粋（2番目の見出し以降）
        $excerpt = zidooka_get_smart_excerpt(get_the_ID(), 70);

        echo '<div class="card mb-3 zidooka-cat-item position-relative">';
        echo '  <div class="card-body">';

        echo '    <' . $heading_tag . ' class="card-title h5 mb-2">';
        echo        esc_html(get_the_title());
        echo '    </' . $heading_tag . '>';

        echo '    <p class="card-text small text-muted mb-2">';
        echo        esc_html($excerpt);
        echo '    </p>';

        // カード全体クリック
        echo '    <a href="' . esc_url(get_permalink()) . '" class="stretched-link" aria-label="' . esc_attr(get_the_title()) . '"></a>';

        echo '  </div>';
        echo '</div>';
    }

    if ($count === 0) {
        ob_end_clean();
        wp_reset_postdata();
        return '';
    }

    echo '</div>';

    wp_reset_postdata();

    $output = ob_get_clean();

    // キャッシュ（12時間）
    set_transient($cache_key, $output, 12 * HOUR_IN_SECONDS);

    return $output;
}
add_shortcode('zidooka_cat_list', 'zidooka_category_list_shortcode');

// 2.4) Home meta description fallback (only if no major SEO plugin)
add_action('wp_head', function(){
    if (!is_front_page()) return;
    if (function_exists('aioseo') || function_exists('wpseo_head') || defined('RANK_MATH_VERSION')) return;
    $desc = 'AI活用と業務自動化、ノーコード/ローコードの実験記録と実務ノウハウを発信。設定・運用のつまずきを最短で解決し、成果に直結する手順と判断基準をまとめます。';
    echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";
}, 7);
// 2.5) Preconnect hints for external hosts
add_action('wp_head', function(){
    $hosts = [
      'https://www.googletagmanager.com',
      'https://pagead2.googlesyndication.com',
      'https://us.i.posthog.com',
    ];
    foreach ($hosts as $h) {
      echo '<link rel="preconnect" href="' . esc_url($h) . '" crossorigin />' . "\n";
    }
}, 3);
// 2.6) Comments: make name/email optional and remove website field
// Do not require name and email
add_filter('pre_option_require_name_email', function($value){ return '0'; });
// Remove the website (URL) field from the front-end form
add_filter('comment_form_default_fields', function($fields){
    if (isset($fields['url'])) unset($fields['url']);
    return $fields;
});// Ensure website field is removed at fields stage as well
add_filter('comment_form_fields', function($fields){
    if (isset($fields['url'])) unset($fields['url']);
    return $fields;
}, 99);
// 2.7) Normalize content URLs: convert ../wp-content/... to /wp-content/...
add_filter('the_content', function($content){
    // Fix src/href attributes that mistakenly start with ../wp-content
    $pattern = '/\b(src|href)=(\"|\')\.\.\/wp-content\//i';
    $replace = '$1=$2/wp-content/';
    return preg_replace($pattern, $replace, $content);
}, 20);

/**
 * Zenn-like Related Posts
 *
 * @param bool $is_english_only
 * @return array WP_Post[]
 */
function zenn_get_related_posts($is_english_only = false) {
    if (!is_single()) return [];
    
    global $post;
    
    // Get categories
    $categories = get_the_category();
    if (empty($categories)) return [];
    
    $cat_ids = wp_list_pluck($categories, 'term_id');
    
    $args = [
        'category__in' => $cat_ids,
        'post__not_in' => [$post->ID],
        'posts_per_page' => 10,
        'orderby' => 'date', 
        'order' => 'DESC',
        'no_found_rows' => true,
        'has_password' => false,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
    ];
    
    $query = new WP_Query($args);
    $related = [];
    
    if ($query->have_posts()) {
        foreach ($query->posts as $p) {
            // Filter by language
            if (function_exists('zidooka_detect_lang_by_post')) {
                $p_lang = zidooka_detect_lang_by_post($p);
                $p_is_english = ($p_lang === 'en');
                
                if ($p_is_english !== $is_english_only) {
                    continue;
                }
            }
            $related[] = $p;
            if (count($related) >= 5) break; 
        }
    }
    
    return $related;
}

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
