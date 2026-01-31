<?php
/**
 * Zenn-like Single Post Template
 * 
 * @package ZennLike
 */

/**
 * Determine if the post title is English-only
 * 
 * @param string $title Post title
 * @return bool True if no Japanese characters detected
 */
function zenn_is_english_only($title) {
    return !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $title);
}

/**
 * Get UI text array based on language
 * 
 * @param bool $is_english_only Language flag
 * @return array UI text labels
 */
function zenn_get_ui_text($is_english_only) {
    if ($is_english_only) {
        return [
            'home' => 'Home',
            'context_label' => 'Context',
            'cta_small_text' => '* If you need help with the content of this article for work or development, individual support is available.',
            'page_label' => 'Page',
            'helpful' => 'Helpful',
            'rate_insight' => '* Rate as expert insight',
            'copy_link' => 'Copy Link',
            'copy_success' => 'Link copied!',
            'copy_fail' => 'Failed to copy: ',
            'prev_direction' => 'Prev',
            'next_direction' => 'Next',
            'thank_you' => 'Thank you for reading',
            'next_read_label' => 'Next Recommended Read',
            'related_posts_title' => 'Related Posts',
            'latest_suffix' => ' Latest',
            'sidebar_related_title' => 'Related Posts',
            'popular_title' => 'Popular',
            'toc_title' => 'Table of Contents',
            'no_toc' => 'No table of contents',
            'sidebar_about_title' => 'About Zidooka',
            'sidebar_about_body' => '“Zidooka” means automation in Japanese. We share practical automation solutions and real-world workflows. If you need help, we also accept consulting or implementation work.',
            'sidebar_reco_title' => 'Recommended',
            'bio_title' => 'Need help with the content of this article?',
            'bio_desc' => 'I provide individual technical support related to the issues described in this article, as a freelance developer. If the problem is blocking your work or internal tasks, feel free to reach out.',
            'bio_price' => 'Support starts from $30 USD',
            'bio_price_note' => '(Estimate provided in advance)',
            'bio_btn_form' => 'Consult about this article',
            'bio_btn_mail' => 'Consult via Email',
            'ai_policy_title' => 'Policy on AI Usage',
            'ai_policy_text' => 'Some articles on this site are written with the assistance of AI. However, we do not rely entirely on AI for writing; it is used strictly as a support tool.',
        ];
    } else {
        return [
            'home' => 'ホーム',
            'context_label' => 'この記事が生まれた背景',
            'cta_small_text' => 'この記事の内容について、業務や開発でお困りの場合は個別に対応できます。',
            'page_label' => 'ページ',
            'helpful' => '役に立った',
            'rate_insight' => '専門知見として評価する',
            'copy_link' => 'リンクをコピー',
            'copy_success' => 'リンクをクリップボードにコピーしました',
            'copy_fail' => 'コピーに失敗しました: ',
            'prev_direction' => '前の記事',
            'next_direction' => '次の記事',
            'thank_you' => '最後までお読みいただきありがとうございました',
            'next_read_label' => '次に読まれている関連記事',
            'related_posts_title' => '関連記事',
            'latest_suffix' => 'の最新記事',
            'sidebar_related_title' => '関連する記事',
            'popular_title' => 'よく読まれている記事',
            'toc_title' => '目次',
            'no_toc' => '目次はありません',
            'sidebar_about_title' => 'Zidookaについて',
            'sidebar_about_body' => 'Zidookaは実務で使える自動化ソリューションや運用の工夫を実務者目線で共有するウェブサイトです。必要であれば、下記のフォーム・メールから個別相談・受託も可能です。フリーランスエンジニアとしても活動しております。',
            'sidebar_reco_title' => 'おすすめの記事',
            'bio_title' => 'この記事の内容について、対応できます',
            'bio_desc' => 'この記事に関連する技術トラブルや開発上の問題について個別対応を行っています。',
            'bio_price' => '個別対応は3,000円〜',
            'bio_price_note' => '内容・工数により事前にお見積りします',
            'bio_btn_form' => 'この記事について相談する',
            'bio_btn_mail' => 'メールで相談する',
            'ai_policy_title' => 'AI活用に関するポリシー',
            'ai_policy_text' => '当サイトでは、記事の執筆補助にAIを活用する場合がありますが、全面的な委任は行いません。',
        ];
    }
}

/**
 * Generate form URL with post information
 * 
 * @param bool $is_english_only Language flag
 * @return string Form URL with parameters
 */
function zenn_get_form_url($is_english_only, $post_id = null) {
    $form_base_url = $is_english_only 
        ? 'https://docs.google.com/forms/d/e/1FAIpQLSclriT5KimZS5Qltib6-UbtCEHCRgJpODKkd--mSZJdIYYutg/viewform'
        : 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform';
    
    $title = $post_id ? get_the_title($post_id) : get_the_title();
    $link = $post_id ? get_permalink($post_id) : get_permalink();
    $form_params = [
        'usp' => 'pp_url',
        'entry.2087005549' => "Titole: " . $title . "\nURL: " . $link
    ];
    return $form_base_url . '?' . http_build_query($form_params);
}

/**
 * Generate BlogPosting JSON-LD structured data
 * 
 * @return array Schema.org BlogPosting object
 */
function zenn_get_blogposting_schema() {
    $primary_cat = get_the_category();
    $primary_cat_name = $primary_cat ? $primary_cat[0]->name : '';
    $thumb_id = get_post_thumbnail_id();
    $img = $thumb_id ? wp_get_attachment_image_src($thumb_id, 'full') : null;
    $img_obj = $img ? [
      '@type' => 'ImageObject',
      'url' => $img[0],
      'width' => $img[1],
      'height' => $img[2],
    ] : [
      '@type' => 'ImageObject',
      'url' => 'https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png',
    ];
    $desc = get_the_excerpt();
    $is_english_only = zenn_is_english_only(get_the_title());
    
    return [
      '@context' => 'https://schema.org',
      '@type' => 'BlogPosting',
      'headline' => get_the_title(),
      'description' => wp_strip_all_tags($desc),
      'image' => $img ? $img_obj : [],
      'datePublished' => get_the_date('c'),
      'dateModified' => get_the_modified_date('c'),
      'author' => [
        '@type' => 'Person',
        'name' => get_the_author(),
        'url' => get_author_posts_url(get_the_author_meta('ID')),
      ],
      'publisher' => [
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'logo' => [
          '@type' => 'ImageObject',
          'url' => 'https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png',
        ],
      ],
      'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => get_permalink(),
      ],
      'articleSection' => $primary_cat_name,
      'wordCount' => str_word_count(wp_strip_all_tags(get_post_field('post_content', get_the_ID()))),
      'inLanguage' => $is_english_only ? 'en' : 'ja',
      'isAccessibleForFree' => true,
      'url' => get_permalink(),
    ];
}

/**
 * Generate BreadcrumbList JSON-LD structured data
 * 
 * @return array Schema.org BreadcrumbList object
 */
function zenn_get_breadcrumb_schema() {
    $breadcrumb = array();
    $is_english_only = zenn_is_english_only(get_the_title());
    $ui_text = zenn_get_ui_text($is_english_only);
    
    $breadcrumb[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => $ui_text['home'],
        'item' => esc_url(home_url('/'))
    );

    $bcats = get_the_category(get_the_ID());
    if (!empty($bcats)) {
        $breadcrumb[] = array(
            '@type' => 'ListItem',
            'position' => count($breadcrumb) + 1,
            'name' => $bcats[0]->name,
            'item' => esc_url(get_category_link($bcats[0]->term_id))
        );
    }

    $breadcrumb[] = array(
            '@type' => 'ListItem',
            'position' => count($breadcrumb) + 1,
            'name' => get_the_title(),
            'item' => get_permalink()
    );
    
    return array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumb
    );
}

/**
 * Get smart adjacent post (prioritize same language)
 * 
 * @param bool $previous Get previous or next post
 * @param bool $is_english_only Language flag
 * @return WP_Post|null Adjacent post or null
 */
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
    
    // Try to find same language match
    foreach ($candidates as $p) {
        $is_p_english = zenn_is_english_only($p->post_title);
        if ($is_p_english === $is_english_only) {
            return $p;
        }
    }
    
    // Fallback to immediate neighbor
    return $candidates[0];
}

/**
 * Get related posts filtered by language
 * 
 * @param bool $is_english_only Language flag
 * @return array Array of related posts (max 4)
 */
function zenn_get_related_posts($is_english_only) {
    $current_id = get_the_ID();
    $cats = get_the_category();
    $cat_ids = array();
    if ($cats) {
        foreach($cats as $c) $cat_ids[] = $c->term_id;
    }
    
    $tags = get_the_tags();
    $tag_ids = array();
    if ($tags) {
        foreach($tags as $t) $tag_ids[] = $t->term_id;
    }

    $candidates = array();
    
    // 1. Try tags first
    if (!empty($tag_ids)) {
        $candidates = get_posts(array(
            'post_type' => 'post',
            'numberposts' => 20,
            'post__not_in' => array($current_id),
            'tag__in' => $tag_ids,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
    }
    
    // 2. Fill with category if needed
    if (count($candidates) < 10 && !empty($cat_ids)) {
        $exclude_ids = array($current_id);
        foreach($candidates as $p) $exclude_ids[] = $p->ID;
        
        $more_candidates = get_posts(array(
            'post_type' => 'post',
            'numberposts' => 20,
            'post__not_in' => $exclude_ids,
            'category__in' => $cat_ids,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        $candidates = array_merge($candidates, $more_candidates);
    }

    // Filter by Language
    $same_lang_posts = [];
    $other_lang_posts = [];

    foreach ($candidates as $p) {
        $is_p_english = zenn_is_english_only($p->post_title);
        
        if ($is_english_only === $is_p_english) {
            $same_lang_posts[] = $p;
        } else {
            $other_lang_posts[] = $p;
        }
    }

    $related_posts = array_merge($same_lang_posts, $other_lang_posts);
    return array_slice($related_posts, 0, 4);
}

get_header(); ?>

<!-- wp:social-links --><ul class="wp-block-social-links"><!-- wp:social-link {"url":"https://gravatar.com/testnewstsukuba","service":"gravatar","rel":"me"} /--></ul><!-- /wp:social-links -->

<!-- スタイルは style.css に移動しました -->

<?php
$post_id = get_queried_object_id();
$is_english_only = zenn_is_english_only(get_the_title($post_id));
$ui_text = zenn_get_ui_text($is_english_only);
$form_url = zenn_get_form_url($is_english_only, $post_id);
?>

<div class="zenn-flex-wrapper">
    <aside class="zenn-left-column" aria-label="Article actions">
        <div class="zenn-left-sticky">
            <div class="zenn-left-actions">
                <button type="button" class="zenn-like-btn zenn-like-btn-compact" data-post-id="<?php echo esc_attr($post_id); ?>" aria-label="<?php echo esc_attr($ui_text['helpful']); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                    <span class="zenn-like-count"><?php echo (int)get_post_meta($post_id, '_post_like_count', true); ?></span>
                </button>
                <a class="zenn-share-icon zenn-share-twitter" href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink($post_id) . '?utm_source=twitter&utm_medium=social&utm_campaign=zidooka_share'); ?>&text=<?php echo urlencode(get_the_title($post_id)); ?>" target="_blank" rel="noopener" aria-label="Share on Twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a class="zenn-share-icon zenn-share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink($post_id) . '?utm_source=facebook&utm_medium=social&utm_campaign=zidooka_share'); ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <button class="zenn-share-icon zenn-share-copy" onclick="copyToClipboard('<?php echo get_permalink($post_id) . '?utm_source=copy&utm_medium=social&utm_campaign=zidooka_share'; ?>')" aria-label="<?php echo esc_attr($ui_text['copy_link']); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                    </svg>
                </button>
            </div>
        </div>
    </aside>
    <main class="zenn-main-column">
        <div class="zenn-container">
            <div class="zenn-wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php
            $is_english_only = zenn_is_english_only(get_the_title());
            $ui_text = zenn_get_ui_text($is_english_only);
            $form_url = zenn_get_form_url($is_english_only);
            ?>
            
            <!-- JSON-LD Structured Data -->
            <script type="application/ld+json">
            <?php
            $schema = zenn_get_blogposting_schema();
            echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            ?>
            </script>
                        <!-- Breadcrumb JSON-LD -->
                        <script type="application/ld+json">
                        <?php echo json_encode(zenn_get_breadcrumb_schema(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
                        </script>

            <!-- Article Header -->
            <article class="zenn-article">
                <header class="zenn-article-header">
                    <h1 class="zenn-article-title"><?php the_title(); ?></h1>

                    <div class="zenn-title-cta" style="margin-top: 10px; margin-bottom: 10px; font-size: 0.85rem; color: #475569;">
                        <span style="color: inherit; text-decoration: underline; text-decoration-color: #cbd5e1; cursor: default;">
                            <?php echo esc_html($ui_text['cta_small_text']); ?>
                        </span>
                    </div>
                    
                    <?php if ($experience_note = get_post_meta(get_the_ID(), 'experience_note', true)) : ?>
                        <div class="zenn-experience-note">
                            <div class="zenn-experience-title"><?php echo esc_html($ui_text['context_label']); ?></div>
                            <div class="zenn-experience-content">
                                <?php
                                    // Normalize user-entered linebreak tokens like '/n' or '\n' to real newlines
                                    $normalized_note = preg_replace('/\s*\\n\s*/', "\n", str_replace('/n', "\n", (string)$experience_note));
                                    echo nl2br(esc_html($normalized_note));
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="zenn-article-meta">
                        <!-- Author Line -->
                        <div class="zenn-author-name" style="margin-bottom: 10px; font-weight: bold; font-size: 1.1rem;">
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" style="text-decoration: none; color: inherit;">
                                <?php the_author(); ?>
                            </a>
                        </div>

                        <!-- Date & Actions & Support Line -->
                        <div class="zenn-meta-info-row" style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                            
                            <!-- Date -->
                            <div class="zenn-publish-date" style="color: #666; font-size: 0.9rem;">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date('Y.m.d')); ?>
                                </time>
                                <?php if ( get_the_modified_time('Ymd') !== get_the_time('Ymd') ) : ?>
                                    <span class="ms-2" aria-label="Updated">
                                        （更新: <?php echo esc_html(get_the_modified_date('Y.m.d')); ?>）
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Actions -->
                            <div class="zenn-article-actions" style="margin: 0; display: flex; gap: 8px;">
                                <div class="zenn-like-button">
                                    <button type="button" class="zenn-like-btn" data-post-id="<?php the_ID(); ?>" style="padding: 4px 10px; font-size: 0.85rem; height: 32px; display: flex; align-items: center;">
                                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 4px;">
                                            <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                        </svg>
                                        <span class="zenn-like-count"><?php echo (int)get_post_meta(get_the_ID(), '_post_like_count', true); ?></span>
                                    </button>
                                </div>
                                <div class="zenn-share-button">
                                    <button class="zenn-share-btn" style="padding: 4px 8px; height: 32px; display: flex; align-items: center;">
                                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 6l-2-2-2 2M10 4v8M4 10v2a2 2 0 002 2h4a2 2 0 002-2v-2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Support Link (Pushed to right) -->
                            <!-- Removed as per CTA spec -->
                        </div>
                    </div>
                    
                    <!-- TOC inserted here (Full Width) -->
                    <div class="zenn-toc-wrapper" style="margin: 12px 0 32px;">
                        <div class="zenn-toc-placeholder" aria-hidden="true" style="width: 100%;"></div>
                    </div>
                    
                    <!-- Tags -->
                    <?php if (has_tag()) : ?>
                        <div class="zenn-tags">
                            <?php
                            $tags = get_the_tags();
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="zenn-tag">#' . $tag->name . '</a>';
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Article Content -->
                <div class="zenn-article-content">
                    <?php 
                    // Featured Image
                    if (has_post_thumbnail()) : ?>
                        <div class="zenn-featured-image">
                            <?php the_post_thumbnail('large', array('class' => 'zenn-thumbnail')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="zenn-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Pagination for multi-page posts -->
                    <?php
                    $args = array(
                        'before' => '<div class="zenn-page-links"><span class="zenn-page-links-title">' . esc_html($ui_text['page_label']) . '</span>',
                        'after' => '</div>',
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                        'next_or_number' => 'number',
                        'separator' => ' ',
                    );
                    wp_link_pages($args);
                    ?>
                </div>

                <!-- Article Footer -->
                <footer class="zenn-article-footer">
                    <div class="zenn-article-actions-footer">
                        <div class="zenn-like-section">
                            <button type="button" class="zenn-like-btn-large" data-post-id="<?php the_ID(); ?>">
                                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <?php echo esc_html($ui_text['helpful']); ?> <span class="zenn-like-count-large ms-2"><?php echo (int)get_post_meta(get_the_ID(), '_post_like_count', true); ?></span>
                            </button>
                            <div class="mt-2 text-muted small" style="font-size: 0.8rem;">
                                <?php echo esc_html($ui_text['rate_insight']); ?>
                            </div>
                        </div>
                        
                        <div class="zenn-share-section">
                            <div class="zenn-share-buttons">
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink() . '?utm_source=twitter&utm_medium=social&utm_campaign=zidooka_share'); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                   class="zenn-share-twitter" target="_blank" rel="noopener">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink() . '?utm_source=facebook&utm_medium=social&utm_campaign=zidooka_share'); ?>" 
                                   class="zenn-share-facebook" target="_blank" rel="noopener">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </a>
                                <button class="zenn-share-copy" onclick="copyToClipboard('<?php echo get_permalink() . '?utm_source=copy&utm_medium=social&utm_campaign=zidooka_share'; ?>')">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                                    </svg>
                                    <?php echo esc_html($ui_text['copy_link']); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </footer>
            </article>

            <?php
            $related_posts = zenn_get_related_posts($is_english_only);
            ?>
            <!-- Comments -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <section class="zenn-comments-section">
                    <?php comments_template(); ?>
                </section>
            <?php endif; ?>

        <?php endwhile; endif; ?>
            </div> <!-- .zenn-wrapper -->
        </div> <!-- .zenn-container -->
    </main> <!-- .zenn-main-column -->

    <aside class="zenn-sidebar-column">
        <div class="zenn-sidebar-sticky">
            <div class="zenn-sidebar-inner">
            <div class="zenn-sidebar-widget widget-about">
                <h4 class="zenn-sidebar-title"><?php echo esc_html($ui_text['sidebar_about_title']); ?></h4>
                <p class="zenn-sidebar-text"><?php echo esc_html($ui_text['sidebar_about_body']); ?></p>
                <div class="zenn-sidebar-actions">
                    <a class="zenn-sidebar-btn zenn-sidebar-btn-primary" href="<?php echo esc_url($form_url); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html($ui_text['bio_btn_form']); ?>
                    </a>
                    <a class="zenn-sidebar-btn" href="mailto:main@zidooka.com">
                        <?php echo esc_html($ui_text['bio_btn_mail']); ?>
                    </a>
                </div>
            </div>

            <?php if (!empty($related_posts)) : ?>
                <?php $sidebar_rec = $related_posts[0]; ?>
                <div class="zenn-sidebar-widget widget-reco">
                    <h4 class="zenn-sidebar-title"><?php echo esc_html($ui_text['sidebar_reco_title']); ?></h4>
                    <a class="zenn-sidebar-reco-link" href="<?php echo get_permalink($sidebar_rec->ID); ?>">
                        <span class="zenn-sidebar-reco-title"><?php echo get_the_title($sidebar_rec->ID); ?></span>
                        <span class="zenn-sidebar-reco-date"><?php echo get_the_date('Y.m.d', $sidebar_rec->ID); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </aside>

    <!-- Image Modal -->
    <div id="zenn-image-modal" class="zenn-image-modal" aria-hidden="true" role="dialog" aria-modal="true">
        <button type="button" class="zenn-image-modal-close" aria-label="閉じる / Close">×</button>
        <div class="zenn-image-modal-body">
            <img class="zenn-image-modal-img" alt="" />
            <div class="zenn-image-modal-caption"></div>
        </div>
    </div>
</div> <!-- .zenn-flex-wrapper -->

<!-- スタイルは style.css に移動しました -->

<style>
/* Layout Fixes & TOC Styles */
.zenn-flex-wrapper {
    width: 100%;
    box-sizing: border-box;
    display: block;
}

/* Mobile Default (Inline TOC visible, Sidebar TOC hidden) */
.zenn-toc-placeholder { display: block; }
.widget-toc { display: none; }

/* Left Column Actions */
.zenn-left-column {
    width: 64px;
    flex-shrink: 0;
}
.zenn-left-sticky {
    position: sticky;
    top: 96px;
}
.zenn-left-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}
.zenn-like-btn-compact,
.zenn-share-icon {
    width: 40px;
    height: 40px;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    background: #fff;
    text-decoration: none;
    gap: 6px;
    padding: 0;
}
.zenn-like-btn-compact .zenn-like-count {
    font-size: 0.75rem;
    font-weight: 600;
}
.zenn-share-icon:hover,
.zenn-like-btn-compact:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}

/* Mobile/Tablet Stack */
@media (max-width: 991.98px) {
    .zenn-left-column {
        width: 100%;
        margin-bottom: 16px;
    }
    .zenn-left-sticky {
        position: static;
    }
    .zenn-left-actions {
        flex-direction: row;
        justify-content: flex-start;
    }
    .zenn-main-column,
    .zenn-sidebar-column {
        width: 100%;
        max-width: 100%;
    }
    .zenn-sidebar-column {
        margin-top: 32px;
    }
}

/* Desktop Layout (PC) */
@media (min-width: 992px) {
    .zenn-flex-wrapper {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center;
        align-items: stretch !important;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    .zenn-main-column {
        flex: 1 1 0 !important;
        max-width: 760px; /* Main content width */
        min-width: 0; /* Prevent flex overflow */
        width: auto !important;
    }
    .zenn-sidebar-column {
        width: 300px !important;
        max-width: 300px;
        flex: 0 0 300px !important;
    }
    .zenn-left-column { order: 1; }
    .zenn-main-column { order: 2; }
    .zenn-sidebar-column { order: 3; }
    .zenn-sidebar-sticky {
        position: sticky;
        top: 96px;
    }
    .zenn-sidebar-inner {
        max-height: none;
        overflow: visible;
        scrollbar-width: thin;
    }
    
    /* Switch TOC visibility on Desktop */
    /* .zenn-toc-placeholder { display: none; } */ /* Keep inline TOC visible */
    .widget-toc { display: none; } /* Hide sidebar TOC */
}

/* TOC Common Styles */
.zenn-toc {
    background: #f8f9fa;
    border: none;
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 0.85rem;
    margin-bottom: 2rem;
}
.zenn-toc-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 8px;
}
.zenn-toc-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
}
.zenn-toc-item {
    margin: 2px 0;
    line-height: 1.4;
}
.zenn-toc-h2 {
    font-weight: 600;
    margin-top: 4px;
}
.zenn-toc-h3 {
    margin-left: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
}
.zenn-toc-link {
    color: #495057;
    text-decoration: none;
    display: block;
    padding: 2px 0;
    transition: color 0.2s;
}
.zenn-toc-link:hover {
    text-decoration: underline;
    color: #0d6efd;
}

/* Sidebar Widget Styles */
.zenn-sidebar-widget {
    margin-bottom: 2rem;
}
.zenn-sidebar-title {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 10px;
    border-bottom: 2px solid #eee;
    padding-bottom: 5px;
}
.zenn-sidebar-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.zenn-sidebar-list li {
    margin-bottom: 8px;
    font-size: 0.95rem;
}
.zenn-sidebar-list a {
    text-decoration: none;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.zenn-sidebar-list a:hover {
    text-decoration: underline;
    color: #0d6efd;
}
.zenn-sidebar-text {
    font-size: 0.95rem;
    color: #334155;
    line-height: 1.7;
    margin: 0 0 12px;
}
.zenn-sidebar-actions {
    display: grid;
    gap: 8px;
}
.zenn-sidebar-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 10px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #334155;
    text-decoration: none;
    background: #f8fafc;
}
.zenn-sidebar-btn-primary {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
.zenn-sidebar-reco-link {
    display: block;
    text-decoration: none;
    color: #0f172a;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px;
    background: #fff;
}
.zenn-sidebar-reco-title {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
}
.zenn-sidebar-reco-date {
    font-size: 0.8rem;
    color: #64748b;
}
.zenn-sidebar-reco-link:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}

/* Article Typography (Tailwind-like) */
.zenn-article {
    color: #0f172a;
}
.zenn-article-header {
    margin-bottom: 24px;
}
.zenn-article-title {
    font-size: clamp(1.9rem, 1.4rem + 1.8vw, 2.6rem);
    line-height: 1.2;
    letter-spacing: -0.02em;
    margin-bottom: 12px;
}
.zenn-article-content {
    background: #ffffff;
    border: none;
    border-radius: 14px;
    padding: 32px;
}
.zenn-content {
    max-width: 680px;
    margin: 0 auto;
    font-size: 1.1rem;
    line-height: 2;
    color: #1f2937;
}
.zenn-content h2 {
    font-size: 1.5rem;
    line-height: 1.4;
    margin: 40px 0 12px;
    padding-top: 0;
    border-top: none;
}
.zenn-content h3 {
    font-size: 1.2rem;
    line-height: 1.5;
    margin: 24px 0 8px;
}
.zenn-content p {
    margin: 0 0 22px;
}
.zenn-content a {
    color: #0f172a;
    text-decoration: underline;
    text-decoration-thickness: 2px;
    text-underline-offset: 3px;
}
.zenn-content ul,
.zenn-content ol {
    padding-left: 1.2rem;
    margin: 0 0 18px;
}
.zenn-content ul { list-style: disc; }
.zenn-content ol { list-style: decimal; }
.zenn-content li + li {
    margin-top: 6px;
}
.zenn-content blockquote {
    border-left: none;
    background: #f8fafc;
    padding: 14px 18px;
    margin: 20px 0;
    color: #0f172a;
}
.zenn-content code {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 2px 6px;
    font-size: 0.95em;
}
.zenn-content pre {
    background: #0f172a;
    color: #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    overflow: auto;
    margin: 20px 0;
}
.zenn-content pre code {
    background: transparent;
    border: none;
    padding: 0;
    color: inherit;
}
.zenn-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 0.95rem;
}
.zenn-content th,
.zenn-content td {
    border: 1px solid #e5e7eb;
    padding: 10px 12px;
    text-align: left;
}
.zenn-content th {
    background: #f8fafc;
    font-weight: 600;
}
.zenn-content img {
    max-width: 100%;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}
.zenn-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}
.zenn-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #0f172a;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.85rem;
    text-decoration: none;
}
.zenn-tag:hover {
    border-color: #cbd5e1;
    background: #eef2ff;
}

/* Article Footer Cleanup */
.zenn-article-actions-footer {
    display: grid;
    gap: 16px;
    padding: 20px 0;
    border-top: none;
}
.zenn-like-section {
    display: grid;
    gap: 6px;
}
.zenn-share-section {
    border-top: none;
    padding-top: 0;
}
.zenn-share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.zenn-share-buttons a,
.zenn-share-buttons button {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.85rem;
    background: #fff;
}


/* Comments Cleanup */
.zenn-comments-section {
    border-top: none;
    padding-top: 24px;
}
.zenn-comments-section .comment-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
}
.zenn-comments-section .comment-body {
    border-bottom: 1px solid #e5e7eb;
    padding: 16px 0;
    margin: 0;
    background: transparent;
}
.zenn-comments-section .children {
    margin: 8px 0 0 16px;
    padding-left: 16px;
    border-left: 1px solid #e5e7eb;
}
.zenn-comments-section .comment-respond,
.zenn-comments-section .comment-form {
    padding: 0;
    margin: 0;
    border: none;
    background: transparent;
    box-shadow: none;
}
.zenn-comments-section .comment-form-comment textarea,
.zenn-comments-section .comment-form input[type="text"],
.zenn-comments-section .comment-form input[type="email"],
.zenn-comments-section .comment-form input[type="url"] {
    width: 100%;
    max-width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.95rem;
}
.zenn-comments-section .form-submit {
    margin-top: 12px;
}
@media (min-width: 992px) { body.admin-bar .zenn-sidebar-sticky { top: 128px; } }
</style>

<script>
// UI Text for JS
const uiTextTocTitle = "<?php echo esc_js($ui_text['toc_title']); ?>";

// TOC Generation & ScrollSpy
document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.zenn-content');
    // Targets: Inline (Mobile) and Sidebar (Desktop)
    const tocPlaceholder = document.querySelector('.zenn-toc-placeholder');
    const sidebarTocContainer = document.querySelector('.widget-toc-content');
    
    if (!content) return;
    
    const headings = content.querySelectorAll('h2, h3');
    
    // Debug log
    console.log('TOC Generation: Found ' + headings.length + ' headings');

    // Show TOC if there are at least 2 headings (H2 or H3)
    // Relaxed condition: 1 H2 + H3s is also a valid structure for TOC
    if (headings.length < 2) {
        console.log('TOC Generation: Skipped (fewer than 2 headings)');
        return;
    }
    
    // Function to build TOC HTML
    function buildToc(isSidebar) {
        const tocContainer = document.createElement('div');
        tocContainer.className = isSidebar ? 'zenn-sidebar-toc-list' : 'zenn-toc';
        
        if (!isSidebar) {
            const title = document.createElement('div');
            title.className = 'zenn-toc-title';
            title.textContent = uiTextTocTitle;
            tocContainer.appendChild(title);
        }
        
        const list = document.createElement('ul');
        list.className = 'zenn-toc-list';
        
        headings.forEach((heading, index) => {
            const id = 'toc-' + index;
            if (!heading.id) heading.id = id;
            
            const li = document.createElement('li');
            li.className = 'zenn-toc-item zenn-toc-' + heading.tagName.toLowerCase();
            
            const a = document.createElement('a');
            a.href = '#' + heading.id;
            a.textContent = heading.textContent;
            a.className = 'zenn-toc-link';
            a.dataset.target = id;
            
            a.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.getElementById(id);
                if(target) {
                    const offset = 80; // Header offset
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = target.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                    history.pushState(null, null, '#' + id);
                }
            });
            
            li.appendChild(a);
            list.appendChild(li);
        });
        
        tocContainer.appendChild(list);
        return tocContainer;
    }
    
    // 1. Populate Inline TOC (Mobile)
    if (tocPlaceholder) {
        tocPlaceholder.appendChild(buildToc(false));
        tocPlaceholder.removeAttribute('aria-hidden');
    }
});

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('<?php echo esc_js($ui_text['copy_success']); ?>');
    }, function(err) {
        console.error('<?php echo esc_js($ui_text['copy_fail']); ?>', err);
    });
}

// Like button functionality
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.zenn-like-btn, .zenn-like-btn-large');
    const ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
    const nonce = '<?php echo esc_js(wp_create_nonce('simple-like-nonce')); ?>';
    
    // Check local storage for liked posts (safe)
    let likedPosts = [];
    try {
        likedPosts = JSON.parse(localStorage.getItem('liked_posts') || '[]');
    } catch (e) {
        likedPosts = [];
    }
    
    likeButtons.forEach(button => {
        const postId = button.dataset.postId;
        if (!postId) return;
        
        // If already liked, add active class
        if (likedPosts.includes(postId)) {
            button.classList.add('liked');
            const svg = button.querySelector('svg path');
            if(svg) svg.setAttribute('fill', 'currentColor');
            button.disabled = true; // Disable button if already liked
        }

        button.addEventListener('click', function() {
            if (this.classList.contains('liked')) return;

            const self = this;
            const originalContent = self.innerHTML;
            
            // Optimistic UI update
            self.classList.add('liked');
            const svg = self.querySelector('svg path');
            if(svg) svg.setAttribute('fill', 'currentColor');
            
            // Update all counters for this post
            document.querySelectorAll(`.zenn-like-btn[data-post-id="${postId}"] .zenn-like-count, .zenn-like-btn-large[data-post-id="${postId}"] .zenn-like-count-large`).forEach(el => {
                el.textContent = parseInt(el.textContent || 0) + 1;
            });

            // Disable all buttons for this post
            document.querySelectorAll(`button[data-post-id="${postId}"]`).forEach(btn => {
                btn.disabled = true;
                btn.classList.add('liked');
                const s = btn.querySelector('svg path');
                if(s) s.setAttribute('fill', 'currentColor');
            });

            // Send AJAX request
            const formData = new FormData();
            formData.append('action', 'process_simple_like');
            formData.append('post_id', postId);
            formData.append('nonce', nonce);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Save to local storage
                    likedPosts.push(postId);
                    localStorage.setItem('liked_posts', JSON.stringify(likedPosts));
                } else {
                    console.error('Like failed:', data);
                    // Revert UI on failure (optional, but good for UX)
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});

// Image modal on tap/click with mouse-follow zoom
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('zenn-image-modal');
    const modalImg = modal ? modal.querySelector('.zenn-image-modal-img') : null;
    const modalCaption = modal ? modal.querySelector('.zenn-image-modal-caption') : null;
    const closeBtn = modal ? modal.querySelector('.zenn-image-modal-close') : null;

    if (!modal || !modalImg || !modalCaption || !closeBtn) return;

    const targetSelector = '.zenn-content img, .zenn-featured-image img';

    const openModal = (img) => {
        const src = img.currentSrc || img.src;
        if (!src) return;
        modalImg.src = src;
        const captionText = img.getAttribute('alt') || img.getAttribute('title') || '';
        modalCaption.textContent = captionText;
        modal.classList.add('is-visible');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('zenn-modal-open');
    };

    const closeModal = () => {
        modal.classList.remove('is-visible');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('zenn-modal-open');
        modalImg.src = '';
        modalCaption.textContent = '';
        modalImg.style.transform = 'scale(1)';
        modalImg.style.transformOrigin = 'center center';
    };

    const attachListeners = (img) => {
        if (img.dataset.modalBound) return;
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', () => openModal(img));
        img.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openModal(img);
            }
        });
        img.setAttribute('tabindex', '0');
        img.dataset.modalBound = 'true';
    };

    document.querySelectorAll(targetSelector).forEach(attachListeners);

    // Handle dynamically inserted content (e.g., Gutenberg blocks)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (!(node instanceof HTMLElement)) return;
                if (node.matches && node.matches('img') && node.closest('.zenn-content, .zenn-featured-image')) {
                    attachListeners(node);
                }
                node.querySelectorAll && node.querySelectorAll(targetSelector).forEach(attachListeners);
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });

    // Mouse-follow zoom on modal image
    modalImg.addEventListener('mousemove', (e) => {
        const rect = modalImg.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        modalImg.style.transformOrigin = `${x}% ${y}%`;
        modalImg.style.transform = 'scale(1.8)';
    });

    modalImg.addEventListener('mouseleave', () => {
        modalImg.style.transform = 'scale(1)';
        modalImg.style.transformOrigin = 'center center';
    });

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    window.addEventListener('keyup', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-visible')) {
            closeModal();
        }
    });
});
</script>

<?php get_footer(); ?>


