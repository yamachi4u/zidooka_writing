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

// DE-ENQUEUE PARENT THEME BOOTSTRAP JS BUNDLE
add_action( 'wp_print_scripts', function(){
    wp_dequeue_script( 'bootstrap5' );
    //wp_dequeue_script( 'dark-mode-switch' );  //optionally
}, 100 );

// ENQUEUE THE BOOTSTRAP JS BUNDLE (AND EVENTUALLY MORE LIBS) FROM THE CHILD THEME DIRECTORY
add_action( 'wp_enqueue_scripts', function() {
    //enqueue js in footer, defer
    wp_enqueue_script( 'bootstrap5-childtheme', get_stylesheet_directory_uri() . "/js/bootstrap.bundle.min.js", array(), null, array('strategy' => 'defer', 'in_footer' => true)  );
    
    // Enqueue custom styles
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array(), '1.0.0' );
    
    //optional: example of how to globally load js files eg  lottie player
    //wp_enqueue_script( 'lottie-player', 'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', array(), null, array('strategy' => 'defer', 'in_footer' => true)  );
}, 101);

// HACK HERE: ENQUEUE YOUR CUSTOM JS FILES, IF NEEDED
add_action( 'wp_enqueue_scripts', function() {	   
    
    //UNCOMMENT next row to include the js/custom.js file globally
    //wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array(/* 'jquery' */), null, array('strategy' => 'defer', 'in_footer' => true) ); 

    //UNCOMMENT next 3 rows to load the js file only on one page
    //if (is_page('mypageslug')) {
    //    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/js/custom.js', array(/* 'jquery' */), null, array('strategy' => 'defer', 'in_footer' => true) ); 
    //}  

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
    $img = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'full') : null;
    if ($img) {
        echo '<meta property="og:image" content="' . esc_url($img[0]) . '" />' . "\n";
        echo '<meta property="og:image:width" content="' . intval($img[1]) . '" />' . "\n";
        echo '<meta property="og:image:height" content="' . intval($img[2]) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($img[0]) . '" />' . "\n";
    }

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

// 2.3) Force eager/high priority for the front-page featured image
// 2.3) Force eager/high priority for the front-page featured image
add_filter('post_thumbnail_html', function(
  $html, $post_id, $post_thumbnail_id, $size, $attr
){
  if (!is_front_page()) return $html;
  // only target the front page's own thumbnail
  if ((int) get_option('page_on_front') !== (int) $post_id) return $html;
  // inject loading and fetchpriority attributes if missing
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
}, 10, 5);add_action('wp_footer', function(){ 
  return;
    // 現在のURLが/lpを含む場合はバナーを表示しない
    if (strpos($_SERVER['REQUEST_URI'], '/lp') !== false) {
        return;
    }
    ?>

    <!-- PC向け：右側固定バナー(デザイン改善版) -->
    <div class="my-square-banner-pc d-none d-lg-flex flex-column align-items-center justify-content-center">
      <button type="button" class="my-banner-close" onclick="this.parentNode.style.display='none'" aria-label="閉じる">
        <i class="fas fa-times"></i>
      </button>
      <div class="my-banner-content">
        <p class="my-banner-title">
            時間を創出する<br>業務自動化の専門家
            <span class="d-block small mt-1" style="font-size: 0.6em; font-weight: normal; opacity: 0.9;">Time Creation &<br>Automation Expert</span>
        </p>
        <p class="my-banner-subtitle">
            あなたのビジネスに革新を
            <span class="d-block small" style="font-size: 0.7em; opacity: 0.9;">Innovate Your Business</span>
        </p>
        <a href="/lp" target="_blank" rel="noopener" class="my-banner-link text-center">
          <div><i class="fas fa-paper-plane me-1"></i>無料相談はこちら</div>
          <div class="small" style="font-size: 0.7em; font-weight: normal;">Free Consultation</div>
        </a>
      </div>
    </div>
    
    <div class="my-floating-banner-sp d-lg-none" id="spBanner">
      <button type="button" class="my-banner-close" onclick="hideBanner()" aria-label="閉じる">
        <i class="fas fa-times"></i>
      </button>
      <a href="/lp" class="sp-banner-link d-flex align-items-center justify-content-center">
        <i class="fas fa-rocket me-2"></i>
        <div class="text-start lh-sm">
            <div>業務自動化で時間を創出しませんか？</div>
            <div style="font-size: 0.65em; font-weight: normal;">Create time with automation?</div>
        </div>
      </a> 
    </div>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
      /* PC向け：右側固定バナー (デザイン改善版) */
      .my-square-banner-pc {
        position: fixed;
        top: 30%;
        right: 20px;
        width: 240px;
        height: auto;
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        color: #fff;
        text-align: center;
        border-radius: 12px;
        z-index: 9999;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
      }
      
      .my-square-banner-pc:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
      }
      
      .my-square-banner-pc::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0.6;
        z-index: -1;
      }
      
      .my-square-banner-pc .my-banner-close {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        font-size: 14px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
      }
      
      .my-square-banner-pc .my-banner-close:hover {
        background: rgba(255,255,255,0.4);
      }
      
      /* SP向け：画面下フロートバナー (修正版) */
      .my-floating-banner-sp {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: linear-gradient(135deg, #3a7bd5, #00d2ff);
        color: #fff;
        text-align: center;
        padding: 12px 0;
        z-index: 9999;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.15);
        transform: translateY(0);
        transition: transform 0.3s ease;
        visibility: visible;
        opacity: 1;
      }
      
      .my-floating-banner-sp .my-banner-close {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        font-size: 12px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
      }
      
      .my-floating-banner-sp .my-banner-close:hover {
        background: rgba(255,255,255,0.4);
      }
      
      .my-floating-banner-sp.sp-hidden {
        transform: translateY(100%);
      }
      
      /* バナーコンテンツ共通 */
      .my-banner-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
      
      .my-banner-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 5px;
        line-height: 1.4;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
      }
      
      .my-banner-subtitle {
        font-size: 0.9rem;
        margin-bottom: 12px;
        opacity: 0.9;
      }
      
      .my-banner-link {
        display: inline-block;
        background-color: #fff;
        color: #3a7bd5;
        padding: 10px 16px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: bold;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      
      .my-banner-link:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      }
      
      .sp-banner-link {
        color: #fff;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: bold;
        display: block;
        padding: 0 40px 0 10px;
      }
      
      /* スクロールインジケーター */
      .scroll-indicator {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 30px;
        opacity: 0.7;
        animation: bounce 1.5s infinite;
      }
      
      @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
          transform: translateY(0);
        }
        40% {
          transform: translateY(-10px);
        }
        60% {
          transform: translateY(-5px);
        }
      }
      
      /* レスポンシブ調整 */
      @media (max-width: 768px) {
        .my-floating-banner-sp {
          padding: 10px 0;
        }
        
        .sp-banner-link {
          font-size: 0.9rem;
        }
      }
    </style>
    <script>
      // バナー閉じる機能を関数化
      function hideBanner() {
        var spBanner = document.getElementById('spBanner');
        if (spBanner) {
          spBanner.style.transform = 'translateY(100%)';
          // 一定時間後に非表示にする（transition効果が終わってから）
          setTimeout(function() {
            spBanner.style.display = 'none';
          }, 300);
          // 24時間（86400000ミリ秒）非表示にする
          sessionStorage.setItem('bannerClosed', Date.now());
        }
      }
      
      // 初回表示時にアニメーション
      document.addEventListener('DOMContentLoaded', function() {
        // PCバナーアニメーション
        var pcBanner = document.querySelector('.my-square-banner-pc');
        if (pcBanner) {
          pcBanner.style.transform = 'translateX(100%)';
          setTimeout(function() {
            pcBanner.style.transition = 'transform 0.5s ease';
            pcBanner.style.transform = 'translateX(0)';
          }, 1000);
        }
        
        // SPバナー初期化
        var spBanner = document.getElementById('spBanner');
        
        // 前回閉じてから24時間以内なら表示しない
        var lastClosed = sessionStorage.getItem('bannerClosed');
        if (lastClosed && Date.now() - lastClosed < 86400000) {
          if (pcBanner) pcBanner.style.display = 'none';
          if (spBanner) spBanner.style.display = 'none';
        }
        
        // スクロール検出とバナー表示制御（緩和版）
        var lastScrollTop = 0;
        var scrollThreshold = 200; // スクロールしきい値
        var scrollTimer = null;
        var isScrolling = false;
        
        window.addEventListener('scroll', function() {
          var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
          isScrolling = true;
          
          // スクロールが停止したら3秒後にSPバナーを表示する
          clearTimeout(scrollTimer);
          scrollTimer = setTimeout(function() {
            isScrolling = false;
            if (spBanner) {
              spBanner.classList.remove('sp-hidden');
            }
          }, 1000);
          
          // PCバナーのスクロール時の動作（少し上下に動く）
          if (pcBanner) {
            var shift = Math.min(5, Math.max(-5, (scrollTop - lastScrollTop) / 2));
            pcBanner.style.transform = 'translateY(' + shift + 'px)';
          }
          
          // SPバナーのスクロール動作（下スクロール時は徐々に隠す）
          if (spBanner && spBanner.style.display !== 'none') {
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
              // 大幅な下スクロール時のみ隠す
              if (scrollTop - lastScrollTop > 30) {
                spBanner.classList.add('sp-hidden');
              }
            } else if (scrollTop < lastScrollTop) {
              // 上スクロール時は表示する
              spBanner.classList.remove('sp-hidden');
            }
          }
          
          lastScrollTop = scrollTop;
        });
      });
    </script>
    <?php
}, 103);

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
    update_user_meta( $user_id, 'description_en', $_POST['description_en'] );
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

// 2.3) Force eager/high priority for the front-page featured image
// 2.3) Force eager/high priority for the front-page featured image
add_filter('post_thumbnail_html', function(
  $html, $post_id, $post_thumbnail_id, $size, $attr
){
  if (!is_front_page()) return $html;
  // only target the front page's own thumbnail
  if ((int) get_option('page_on_front') !== (int) $post_id) return $html;
  // inject loading and fetchpriority attributes if missing
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
// 2.4) Home meta description fallback (only if no major SEO plugin)
add_action('wp_head', function(){
    if (!is_front_page()) return;
    if (function_exists('aioseo') || function_exists('wpseo_head') || defined('RANK_MATH_VERSION')) return;
    $desc = 'AI活用と業務自動化、ノーコード/ローコードの実験記録と実務ノウハウを発信。設定・運用のつまずきを最短で解決し、成果に直結する手順と判断基準をまとめます。';
    echo '<meta name="description" content="' . esc_attr($desc) . '" />' . "\n";
}, 7);
// 2.5) Preconnect hints for external hosts (front page only)
add_action('wp_head', function(){
    if (!is_front_page()) return;
    $hosts = [
      'https://www.googletagmanager.com',
      'https://pagead2.googlesyndication.com',
    ];
    foreach ($hosts as $h) {
      echo '<link rel="preconnect" href="' . esc_url($h) . '" crossorigin />' . "\n";
      echo '<link rel="dns-prefetch" href="' . esc_url($h) . '" />' . "\n";
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
// === LCP improvements for single posts ===
// Preload first content image or featured image in <head>
add_action('wp_head', function(){
  if (!is_single()) return;
  global $post; if (!$post) return;
  $content = get_post_field('post_content', $post->ID);
  $img_id = 0;
  if ($content) {
    if (preg_match('/wp-image-(\d+)/', $content, $m)) {
      $img_id = (int)$m[1];
    }
    if (!$img_id && preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $m2)) {
      $href = esc_url($m2[1]);
      printf("<link rel=\"preload\" as=\"image\" href=\"%s\">\n", $href);
    }
  }
  if ($img_id) {
    $src = wp_get_attachment_image_url($img_id, 'full');
    $srcset = wp_get_attachment_image_srcset($img_id, 'full');
    $sizes = '(min-width: 1024px) 1200px, 100vw';
    if ($src && $srcset) {
      printf("<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
        esc_url($src), esc_attr($srcset), esc_attr($sizes)
      );
    }
  } elseif (has_post_thumbnail($post)) {
    $thumb_id = get_post_thumbnail_id($post);
    $src = wp_get_attachment_image_url($thumb_id, 'full');
    $srcset = wp_get_attachment_image_srcset($thumb_id, 'full');
    $sizes = '(min-width: 1024px) 1200px, 100vw';
    if ($src && $srcset) {
      printf("<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
        esc_url($src), esc_attr($srcset), esc_attr($sizes)
      );
    }
  }
}, 4);

// Mark first content image eager/high priority
add_filter('the_content', function($content){
  if (!is_single() || empty($content)) return $content;
  $replaced = false;
  $content = preg_replace_callback('/<img[^>]*>/i', function($m) use (&$replaced){
    if ($replaced) return $m[0];
    $img = $m[0];
    if (stripos($img, 'loading=') !== false) {
      $img = preg_replace('/loading=(["\'])([^"\']*)(["\'])/i', 'loading=$1eager$3', $img, 1);
    } else {
      $img = preg_replace('/<img/i', '<img loading="eager"', $img, 1);
    }
    if (stripos($img, 'fetchpriority=') !== false) {
      $img = preg_replace('/fetchpriority=(["\'])([^"\']*)(["\'])/i', 'fetchpriority=$1high$3', $img, 1);
    } else {
      $img = preg_replace('/<img/i', '<img fetchpriority="high"', $img, 1);
    }
    if (stripos($img, 'decoding=') === false) {
      $img = preg_replace('/<img/i', '<img decoding="async"', $img, 1);
    }
    $replaced = true;
    return $img;
  }, $content, 1);
  return $content;
}, 12);

// Ensure featured image on single has eager/high priority (when used)
add_filter('post_thumbnail_html', function($html){
  if (!is_single()) return $html;
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
}, 11);

// === LCP improvements for single posts ===
add_action('wp_head', function(){
    if (!is_single()) return;
    $path = get_stylesheet_directory() . '/css-output/critical-single.css';
    if (file_exists($path)) {
        $css = file_get_contents($path);
        if ($css !== false && $css !== '') {
            echo "<style id=\"zdk-critical-single\">{$css}</style>\n";
        }
    }
}, 7);

add_action('wp_head', function(){
    if (!is_single()) return;
    global $post; if (!$post) return;
    $content = get_post_field('post_content', $post->ID) ?: '';
    $img_id = 0;
    if (preg_match('/wp-image-(\d+)/', $content, $m)) {
        $img_id = (int)$m[1];
    }
    $printed = false;
    if ($img_id) {
        $src = wp_get_attachment_image_url($img_id, 'full');
        $srcset = wp_get_attachment_image_srcset($img_id, 'full');
        $sizes = '(min-width: 1024px) 1200px, 100vw';
        if ($src && $srcset) {
            printf("<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
                esc_url($src), esc_attr($srcset), esc_attr($sizes)
            );
            $printed = true;
        }
    }
    if (!$printed) {
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $m2)) {
            $href = esc_url($m2[1]);
            printf("<link rel=\"preload\" as=\"image\" href=\"%s\">\n", $href);
            $printed = true;
        }
    }
    if (!$printed && has_post_thumbnail($post)) {
        $thumb_id = get_post_thumbnail_id($post);
        $src = wp_get_attachment_image_url($thumb_id, 'full');
        $srcset = wp_get_attachment_image_srcset($thumb_id, 'full');
        $sizes = '(min-width: 1024px) 1200px, 100vw';
        if ($src && $srcset) {
            printf("<link rel=\"preload\" as=\"image\" href=\"%s\" imagesrcset=\"%s\" imagesizes=\"%s\">\n",
                esc_url($src), esc_attr($srcset), esc_attr($sizes)
            );
            $printed = true;
        }
    }
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m3)) {
        $host = wp_parse_url($m3[1], PHP_URL_SCHEME) . '://' . wp_parse_url($m3[1], PHP_URL_HOST);
        if ($host) {
            printf("<link rel=\"preconnect\" href=\"%s\" crossorigin>\n", esc_url($host));
            printf("<link rel=\"dns-prefetch\" href=\"%s\">\n", esc_url($host));
        }
    }
}, 6);

add_filter('the_content', function($content){
    if (!is_single() || empty($content)) return $content;
    $done = false;
    $content = preg_replace_callback('/<img[^>]*>/i', function($m) use (&$done){
        if ($done) return $m[0];
        $img = $m[0];
        if (stripos($img, 'loading=') !== false) {
            $img = preg_replace('/loading=(["\'])([^"\']*)(["\'])/i', 'loading=$1eager$3', $img, 1);
        } else {
            $img = preg_replace('/<img/i', '<img loading="eager"', $img, 1);
        }
        if (stripos($img, 'fetchpriority=') !== false) {
            $img = preg_replace('/fetchpriority=(["\'])([^"\']*)(["\'])/i', 'fetchpriority=$1high$3', $img, 1);
        } else {
            $img = preg_replace('/<img/i', '<img fetchpriority="high"', $img, 1);
        }
        if (stripos($img, 'decoding=') === false) {
            $img = preg_replace('/<img/i', '<img decoding="async"', $img, 1);
        }
        $done = true;
        return $img;
    }, $content, 1);
    return $content;
}, 12);