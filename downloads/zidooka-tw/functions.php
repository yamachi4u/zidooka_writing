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

if (!defined('ZIDOOKA_TW_TYPOGRAPHY_CLASSES')) {
    define('ZIDOOKA_TW_TYPOGRAPHY_CLASSES', 'prose');
}
if (!defined('ZDK_OGP_FALLBACK_IMAGE')) {
    define('ZDK_OGP_FALLBACK_IMAGE', 'https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png');
}

function zidooka_ga4_id() {
    $ga4_id = defined('GA_MEASUREMENT_ID') ? constant('GA_MEASUREMENT_ID') : '';
    $ga4_id = apply_filters('zidooka_ga4_id', $ga4_id);
    return $ga4_id ? $ga4_id : 'G-VNF3D5QY6E';
}

// Cache-Control for public pages
add_action('send_headers', function(){
    if (is_admin() || is_user_logged_in()) return;
    header('Cache-Control: public, max-age=3600, s-maxage=86400');
});

// WebSite schema with SearchAction
add_action('wp_head', function(){
    $url = home_url('/');
    $name = get_bloginfo('name');
    echo '<script type="application/ld+json">' . json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $name,
        'url' => $url,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => $url . '?s={search_term_string}'
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}, 3);

// Block REST API user enumeration
add_filter('rest_endpoints', function($endpoints){
    if (isset($endpoints['/wp/v2/users'])) unset($endpoints['/wp/v2/users']);
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    return $endpoints;
});

// Fix WordPress default sender email/name
add_filter('wp_mail_from', function(){ return 'main@zidooka.com'; });
add_filter('wp_mail_from_name', function(){ return 'ZIDOOKA'; });

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

    wp_enqueue_style('zdk-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    $dm_css = get_stylesheet_directory() . '/assets/dark-mode.css';
    if (file_exists($dm_css)) {
        wp_enqueue_style('zdk-dark-mode', get_stylesheet_directory_uri() . '/assets/dark-mode.css', array(), filemtime($dm_css));
    }
    $dm_js = get_stylesheet_directory() . '/assets/dark-mode.js';
    if (file_exists($dm_js)) {
        wp_enqueue_script('zdk-dark-mode', get_stylesheet_directory_uri() . '/assets/dark-mode.js', array(), filemtime($dm_js), true);
    }
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
    $ga4_id = zidooka_ga4_id();
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

// JS error tracking via PostHog
add_action('wp_head', function(){
    $key = '';
    if (defined('POSTHOG_KEY')) { $key = constant('POSTHOG_KEY'); }
    $key = apply_filters('zidooka_posthog_key', $key);
    if (!$key) return;
    ?>
<script>
(function(){var q=[];window.addEventListener('error',function(e){var m=e.message||'',s=e.filename||'',l=e.lineno||0,c=e.colno||0;q.push({message:m,source:s,lineno:l,colno:c,path:location.pathname,ts:Date.now()});if(q.length>10)q.shift()});var flush=function(){if(!q.length||typeof posthog==='undefined'||!posthog.capture)return;var batch=q.splice(0);try{posthog.capture('$exception',{errors:batch,_batch:true})}catch(e){}};setInterval(flush,15000);window.addEventListener('beforeunload',flush)})();
</script>
    <?php
}, 28);

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

// PostHog experiments JS + experiment CSS
add_action( 'wp_enqueue_scripts', function() {
    if (!is_singular('post') && !is_page()) return;

    $key = '';
    if (defined('POSTHOG_KEY')) { $key = constant('POSTHOG_KEY'); }
    $key = apply_filters('zidooka_posthog_key', $key);
    if (!$key) return;

    wp_enqueue_script(
        'zdk-posthog-experiments',
        get_stylesheet_directory_uri() . '/assets/posthog-experiments.js',
        array(),
        file_exists(get_stylesheet_directory() . '/assets/posthog-experiments.js') ? filemtime(get_stylesheet_directory() . '/assets/posthog-experiments.js') : null,
        array('strategy' => 'defer', 'in_footer' => true)
    );

    // A/B experiment CSS (loaded only when PostHog is configured)
    $exp_css = '
        .exp-line-loose { line-height: 1.9; }
        .exp-related-grid4 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .exp-ad-early .zidooka-xserver-ad:first-of-type { display: none; }
    ';
    wp_add_inline_style('theme-style', $exp_css);

    // Single post CSS/JS (extracted from inline)
    if (is_singular('post')) {
        $css_path = get_stylesheet_directory() . '/assets/single.css';
        if (file_exists($css_path)) {
            wp_enqueue_style('zdk-single', get_stylesheet_directory_uri() . '/assets/single.css', array(), filemtime($css_path));
        }
        $js_path = get_stylesheet_directory() . '/assets/single.js';
        if (file_exists($js_path)) {
            wp_enqueue_script('zdk-single', get_stylesheet_directory_uri() . '/assets/single.js', array(), filemtime($js_path), true);
            wp_localize_script('zdk-single', 'zdkUiText', array(
                'tocTitle' => __('目次', 'zidooka-tw'),
                'copySuccess' => __('コピーしました', 'zidooka-tw'),
                'copyFail' => __('コピーに失敗しました', 'zidooka-tw'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('simple-like-nonce'),
            ));
        }
    }

    if (is_front_page()) {
        $fp_css = get_stylesheet_directory() . '/assets/front-page.css';
        if (file_exists($fp_css)) {
            wp_enqueue_style('zdk-front-page', get_stylesheet_directory_uri() . '/assets/front-page.css', array(), filemtime($fp_css));
        }
    }
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

// --- Theme helper fallbacks (avoid fatal on search/archive templates) ---
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
        $post = get_post($post_id);
        return $post ? (zidooka_detect_lang_by_post($post) === 'en') : false;
    }
}

require_once get_stylesheet_directory() . '/inc/cta.php';


// Site UX: search tracking, keyboard shortcut, back-to-top
add_action('wp_footer', function(){
    ?>
<script>
(function(){
  document.addEventListener('submit',function(e){
    var f=e.target;if(!f||f.tagName!=='FORM')return;
    var s=f.querySelector('input[type=search],input[name=s]');
    if(!s)return;var q=s.value.trim();if(!q)return;
    try{if(typeof gtag==='function')gtag('event','zdk_search',{search_term:q})}catch(er){}
  },{passive:true});
  document.addEventListener('keydown',function(e){
    if(e.key!=='/'||e.target!==document.body||e.ctrlKey||e.metaKey||e.altKey)return;
    var s=document.querySelector('input[type=search],input[name=s]');
    if(s){e.preventDefault();s.focus();s.select()}
  });
  var btn=document.createElement('button');
  btn.innerHTML='\u2191';
  btn.setAttribute('aria-label','Back to top');
  btn.style.cssText='position:fixed;bottom:24px;right:24px;z-index:999;width:44px;height:44px;border-radius:50%;border:1px solid #d1d5db;background:#fff;color:#4f46e5;font-size:20px;cursor:pointer;opacity:0;transition:opacity 0.25s;box-shadow:0 2px 8px rgba(0,0,0,.1);display:flex;align-items:center;justify-content:center';
  btn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'})});
  var t=false;
  window.addEventListener('scroll',function(){if(t)return;t=true;requestAnimationFrame(function(){t=false;btn.style.opacity=window.scrollY>400?'1':'0'})},{passive:true});
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',function(){document.body.appendChild(btn)});
  else document.body.appendChild(btn);
})();
</script>
    <?php
}, 100);

// Simple Like System
add_action('wp_ajax_nopriv_process_simple_like', 'process_simple_like');
add_action('wp_ajax_process_simple_like', 'process_simple_like');

function process_simple_like() {
    // Rate limit: max 10 likes per IP per hour
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $rate_key = 'zdk_like_rate_' . md5($ip);
    $attempts = intval(get_transient($rate_key));
    if ($attempts >= 10) {
        wp_send_json_error('Rate limit exceeded');
    }
    set_transient($rate_key, $attempts + 1, HOUR_IN_SECONDS);

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
        $og_img = ZDK_OGP_FALLBACK_IMAGE;
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

    // hreflang for bilingual posts
    $is_en = function_exists('zidooka_is_english_post') ? zidooka_is_english_post($post_id) : false;
    $slug = get_post_field('post_name', $post_id);
    $counterpart_slug = $is_en ? preg_replace('/-en$/', '', $slug) : ($slug . '-en');
    if ($counterpart_slug !== $slug) {
        $counterpart = get_page_by_path($counterpart_slug, OBJECT, 'post');
        if ($counterpart && $counterpart->post_status === 'publish') {
            $en_url = $is_en ? $url : get_permalink($counterpart);
            $ja_url = $is_en ? get_permalink($counterpart) : $url;
            echo '<link rel="alternate" hreflang="ja" href="' . esc_url($ja_url) . '" />' . "\n";
            echo '<link rel="alternate" hreflang="en" href="' . esc_url($en_url) . '" />' . "\n";
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($ja_url) . '" />' . "\n";
        }
    }

    // Multipage rel prev/next for paginated posts
    global $page, $numpages;
    if ($numpages > 1) {
        if ($page > 1) echo '<link rel="prev" href="' . esc_url(get_pagenum_link($page - 1)) . '" />' . "\n";
        if ($page < $numpages) echo '<link rel="next" href="' . esc_url(get_pagenum_link($page + 1)) . '" />' . "\n";
    }
}, 5);

// 1.6) FAQ schema for error/QA articles
add_action('wp_head', function(){
    if (!is_singular('post')) return;
    $post_id = get_queried_object_id();
    $content = get_post_field('post_content', $post_id);
    if (!$content) return;

    // Detect Q&A pattern: headings with 原因/対処/解決/方法/FAQ
    preg_match_all('/<h[23][^>]*>(.*?(原因|対処|解決方法|やり方|手順|方法|とは|意味|エラー|FAQ).*?)<\/h[23]>/iu', $content, $matches, PREG_SET_ORDER);
    if (count($matches) < 3) return; // require at least 3 matching headings

    $qa = array();
    foreach ($matches as $m) {
        $q = wp_strip_all_tags($m[1]);
        // Extract next paragraph after this heading
        $pattern = '/' . preg_quote($m[0], '/') . '\s*<(p|div|ul|ol)[^>]*>(.*?)<\/\1>/is';
        if (preg_match($pattern, $content, $am)) {
            $answer = wp_strip_all_tags($am[2]);
            $answer = mb_substr($answer, 0, 300);
            if (mb_strlen($answer) < 20) continue;
            $qa[] = array('q' => $q, 'a' => $answer);
        }
    }
    if (count($qa) < 3) return;

    $faqItems = array();
    foreach ($qa as $pair) {
        $faqItems[] = array(
            '@type' => 'Question',
            'name' => $pair['q'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $pair['a'],
            ),
        );
    }

    echo '<script type="application/ld+json">' . json_encode(array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqItems,
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 10);

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
    if (preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FFF}]/u', $title)) {
        return 'ja';
    }
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

// RSS feed: add featured image
add_action('rss2_item', function(){
    if (!has_post_thumbnail()) return;
    $img = get_the_post_thumbnail(null, 'medium_large', ['style' => 'max-width:100%;height:auto;margin-bottom:8px;']);
    echo $img;
});

add_action('transition_post_status', function ($new_status, $old_status, $post) {
    if ($post->post_type !== 'post') return;
    if ($new_status !== 'publish' && $old_status !== 'publish') return;
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_zidooka_cat_list_v2_%' OR option_name LIKE '_transient_timeout_zidooka_cat_list_v2_%'");
}, 10, 3);

// Language detection for posts
function zenn_is_english_only($title) {
    return !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FFF}]/u', $title);
}

// Smart adjacent post (prioritize same language)
function zenn_get_smart_adjacent_post($previous, $is_english_only) {
    $current_date = get_post_field('post_date', get_the_ID());
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => $previous ? 'DESC' : 'ASC',
        'date_query' => array(
            array(
                $previous ? 'before' : 'after' => $current_date,
                'inclusive' => false,
            ),
        ),
        'post__not_in' => array(get_the_ID()),
    );

    $candidates = get_posts($args);
    if (empty($candidates)) return null;

    foreach ($candidates as $p) {
        $is_p_english = zenn_is_english_only($p->post_title);
        if ($is_p_english === $is_english_only) {
            return $p;
        }
    }

    return $candidates[0];
}

// 2.4) Fallback meta description for front page, 404, search, archive
add_action('wp_head', function(){
    if (function_exists('aioseo') || function_exists('wpseo_head') || defined('RANK_MATH_VERSION')) return;

    if (is_front_page()) {
        $desc = 'AI活用と業務自動化、ノーコード/ローコードの実験記録と実務ノウハウを発信。設定・運用のつまずきを最短で解決し、成果に直結する手順と判断基準をまとめます。';
        echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";
    } elseif (is_404()) {
        echo '<meta name="description" content="お探しのページが見つかりませんでした。ZIDOOKAの記事一覧からお探しください。" />' . "\n";
        echo '<meta name="robots" content="noindex,follow" />' . "\n";
    } elseif (is_search()) {
        $q = get_search_query();
        echo '<meta name="description" content="' . esc_attr('「' . $q . '」の検索結果 – ZIDOOKA') . '" />' . "\n";
        echo '<meta name="robots" content="noindex,follow" />' . "\n";
    } elseif (is_archive()) {
        $desc = strip_tags(get_the_archive_title());
        echo '<meta name="description" content="' . esc_attr($desc . ' に関する記事一覧 – ZIDOOKA') . '" />' . "\n";
    }
}, 7);
// 2.5b) Noindex all feeds (reduces Bing crawl errors from thin XML pages)
add_filter('wp_robots', function($robots) {
    if (is_feed()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
});
// 2.5) Preconnect hints for external hosts
add_action('wp_head', function(){
    $hosts = [
      'https://cdn.tailwindcss.com',
      'https://www.googletagmanager.com',
      'https://pagead2.googlesyndication.com',
      'https://us.i.posthog.com',
    ];
    foreach ($hosts as $h) {
      echo '<link rel="preconnect" href="' . esc_url($h) . '" crossorigin />' . "\n";
      echo '<link rel="dns-prefetch" href="' . esc_url($h) . '" />' . "\n";
    }
}, 3);
// 2.6) Comments: make name/email optional and remove website field
// Do not require name and email
add_filter('pre_option_require_name_email', '__return_zero');
add_filter('comment_form_default_fields', function($fields){
    if (isset($fields['url'])) unset($fields['url']);
    return $fields;
});
add_filter('comment_form_fields', function($fields){
    if (isset($fields['url'])) unset($fields['url']);
    $fields['zdk_hp'] = '<p class="zdk-hp-wrap" style="position:absolute;left:-9999px"><label for="zdk_hp">Leave empty</label><input type="text" name="zdk_hp" id="zdk_hp" tabindex="-1" autocomplete="off"></p>';
    return $fields;
}, 99);
add_filter('preprocess_comment', function($data){
    if (!empty($_POST['zdk_hp'])) {
        wp_die('Spam detected.');
    }
    return $data;
});
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
    $cache_key = 'zdk_rel_' . $post->ID . '_' . ($is_english_only ? 'en' : 'ja');
    $cached = get_transient($cache_key);
    if (is_array($cached)) return $cached;

    $categories = get_the_category();
    $tags = get_the_tags();
    $cat_ids = $categories ? wp_list_pluck($categories, 'term_id') : [];
    $tag_ids = $tags ? wp_list_pluck($tags, 'term_id') : [];

    $collected = [];
    $scored = [];
    $seen = [$post->ID => true];

    // Phase 1: tag-matched candidates (high relevance, limit 8)
    if (!empty($tag_ids)) {
        $tag_query = new WP_Query([
            'tag__in' => $tag_ids,
            'post__not_in' => [$post->ID],
            'posts_per_page' => 8,
            'no_found_rows' => true,
            'has_password' => false,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ]);
        foreach ($tag_query->posts as $p) {
            if (isset($seen[$p->ID])) continue;
            $seen[$p->ID] = true;
            $collected[] = $p;
        }
    }

    // Phase 2: category-matched candidates (fallback if not enough from tags)
    $need = max(10 - count($collected), 0);
    if ($need > 0 && !empty($cat_ids)) {
        $cat_query = new WP_Query([
            'category__in' => $cat_ids,
            'post__not_in' => array_merge([$post->ID], array_keys($seen)),
            'posts_per_page' => $need,
            'no_found_rows' => true,
            'has_password' => false,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ]);
        foreach ($cat_query->posts as $p) {
            if (isset($seen[$p->ID])) continue;
            $seen[$p->ID] = true;
            $collected[] = $p;
        }
    }

    // Phase 3: language filter
    $filtered = [];
    foreach ($collected as $p) {
        if (function_exists('zidooka_detect_lang_by_post')) {
            $p_lang = zidooka_detect_lang_by_post($p);
            if (($p_lang === 'en') !== $is_english_only) continue;
        }
        $filtered[] = $p;
    }

    // Phase 4: score by multi-signal relevance
    $current_title = $post->post_title;
    $current_title_words = array_filter(explode(' ', mb_strtolower(wp_strip_all_tags($current_title))));
    $current_cats = [];
    foreach ($categories as $c) $current_cats[$c->term_id] = true;
    $current_tags = [];
    foreach ($tags as $t) $current_tags[$t->term_id] = true;

    foreach ($filtered as $p) {
        $score = 0;

        // Shared tags: +3 each
        $p_tags = wp_get_post_tags($p->ID, ['fields' => 'ids']);
        foreach ($p_tags as $tid) {
            if (isset($current_tags[$tid])) $score += 3;
        }

        // Shared categories: +1 each
        $p_cats = wp_get_post_categories($p->ID, ['fields' => 'ids']);
        foreach ($p_cats as $cid) {
            if (isset($current_cats[$cid])) $score += 1;
        }

        // Title word overlap: +2 per shared significant word
        $p_title = wp_strip_all_tags($p->post_title);
        $p_words = array_filter(explode(' ', mb_strtolower($p_title)));
        foreach ($current_title_words as $cw) {
            if (mb_strlen($cw) < 2) continue;
            foreach ($p_words as $pw) {
                if ($cw === $pw || (mb_strlen($cw) > 3 && mb_strpos($pw, $cw) !== false)) {
                    $score += 2;
                    break;
                }
            }
        }

        // Recency bonus: +0 to +2 (newer = better within 180 days)
        $age_days = (time() - strtotime($p->post_date)) / 86400;
        if ($age_days <= 30) $score += 2;
        elseif ($age_days <= 90) $score += 1;
        elseif ($age_days <= 180) $score += 0.5;

        $scored[] = ['post' => $p, 'score' => $score];
    }

    // Sort by score DESC, date DESC as tiebreaker
    usort($scored, function($a, $b) {
        $diff = $b['score'] - $a['score'];
        if ($diff != 0) return $diff > 0 ? 1 : -1;
        return strtotime($b['post']->post_date) - strtotime($a['post']->post_date);
    });

    $related = [];
    foreach ($scored as $s) {
        $related[] = $s['post'];
        if (count($related) >= 5) break;
    }

    // Fallback: if nothing found, return latest posts matching language
    if (empty($related)) {
        $fallback = new WP_Query([
            'post__not_in' => [$post->ID],
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
            'has_password' => false,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ]);
        foreach ($fallback->posts as $p) {
            if (function_exists('zidooka_detect_lang_by_post')) {
                $p_lang = zidooka_detect_lang_by_post($p);
                if (($p_lang === 'en') !== $is_english_only) continue;
            }
            $related[] = $p;
            if (count($related) >= 5) break;
        }
    }

    set_transient($cache_key, $related, 12 * HOUR_IN_SECONDS);
    return $related;
}

require_once get_stylesheet_directory() . '/inc/gas-dist.php';


require_once get_stylesheet_directory() . '/inc/template-functions.php';
require_once get_stylesheet_directory() . '/inc/template-tags.php';
