<?php
/**
 * Zenn-like Single Post Template
 * 
 * @package ZennLike
 */

get_header(); ?>

<!-- wp:social-links --><ul class="wp-block-social-links"><!-- wp:social-link {"url":"https://gravatar.com/testnewstsukuba","service":"gravatar","rel":"me"} /--></ul><!-- /wp:social-links -->

<!-- スタイルは style.css に移動しました -->

<div class="zenn-flex-wrapper">
    <main class="zenn-main-column">
        <div class="zenn-container">
            <div class="zenn-wrapper">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php
            // Determine if the title is English-only (no Japanese characters)
            $is_english_only = !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', get_the_title());

            // Form URL Generation
            $form_base_url = $is_english_only 
                ? 'https://docs.google.com/forms/d/e/1FAIpQLSclriT5KimZS5Qltib6-UbtCEHCRgJpODKkd--mSZJdIYYutg/viewform'
                : 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform';
            
            $form_params = [
                'usp' => 'pp_url',
                'entry.2087005549' => "Titole: " . get_the_title() . "\nURL: " . get_permalink()
            ];
            $form_url = $form_base_url . '?' . http_build_query($form_params);

            // UI Text Definitions
            $ui_text = $is_english_only ? [
                'home' => 'Home',
                'context_label' => 'Context:',
                'cta_small_text' => '* If you need help with the content of this article for work or development, individual support is available.',
                'page_label' => 'Page:',
                'helpful' => 'Helpful',
                'rate_insight' => '* Rate as expert insight',
                'copy_link' => 'Copy Link',
                'copy_success' => 'Link copied!',
                'copy_fail' => 'Failed to copy: ',
                'prev_direction' => '← Prev',
                'next_direction' => 'Next →',
                'thank_you' => '── Thank you for reading ──',
                'next_read_label' => '▶ Next Recommended Read:',
                'related_posts_title' => 'Related Posts',
                'latest_suffix' => ' Latest',
                'sidebar_related_title' => 'Related Posts',
                'popular_title' => 'Popular',
                'toc_title' => 'Table of Contents',
                'no_toc' => 'No table of contents',
                'bio_title' => 'Need help with the content of this article?',
                'bio_desc' => 'I provide individual technical support related to the issues described in this article, as a freelance developer.<br>If the problem is blocking your work or internal tasks, feel free to reach out.',
                'bio_price' => 'Support starts from $30 USD',
                'bio_price_note' => '(Estimate provided in advance)',
                'bio_btn_form' => 'Consult about this article',
                'bio_btn_mail' => 'Consult via Email',
                'ai_policy_title' => 'Policy on AI Usage',
                'ai_policy_text' => 'Some articles on this site are written with the assistance of AI. However, we do not rely entirely on AI for writing; it is used strictly as a support tool. We believe that if using AI improves productivity and helps convey the message more effectively, it should be utilized.',
            ] : [
                'home' => 'ホーム',
                'context_label' => 'この記事が生まれた背景',
                'cta_small_text' => '※ この記事の内容について、業務・開発上お困りの場合は個別に対応できます（5,000円〜）。',
                'page_label' => 'ページ',
                'helpful' => '役に立った',
                'rate_insight' => '※ 専門的な知見として評価する',
                'copy_link' => 'リンクをコピー',
                'copy_success' => 'リンクをクリップボードにコピーしました！',
                'copy_fail' => 'コピーに失敗しました: ',
                'prev_direction' => '← 前の記事',
                'next_direction' => '次の記事 →',
                'thank_you' => '── 最後まで読んでいただきありがとうございます ──',
                'next_read_label' => '▶ 次に読まれている関連記事',
                'related_posts_title' => '関連記事',
                'latest_suffix' => 'の最新記事',
                'sidebar_related_title' => '関連する記事',
                'popular_title' => 'よく読まれている記事',
                'toc_title' => '目次',
                'no_toc' => '目次はありません',
                'bio_title' => 'この記事の内容について、対応できます',
                'bio_desc' => 'ZIDOOKA!では、この記事で扱っている内容に関連する技術トラブルや開発上の問題について、フリーランスとして個別対応を行っています。<br>「調べても解決しない」「業務で詰まっている」「社内で対応が難しい」といった場合にご相談ください。',
                'bio_price' => '個別対応：5,000円〜',
                'bio_price_note' => '（内容・工数により事前にお見積りします）',
                'bio_btn_form' => 'この記事の内容について相談する',
                'bio_btn_mail' => 'メールで相談する',
                'ai_policy_title' => 'AI活用に関するポリシー',
                'ai_policy_text' => '当サイトでは、記事の執筆支援にAIを活用する場合があります。ただし、AIに記事作成を全面的に委任することはなく、あくまでライティングの補助として利用しています。「AIを活用することで生産性が向上し、より伝わりやすいコンテンツになるならば積極的に利用すべき」という方針のもと運営しています。',
            ];
            ?>
            
            <!-- JSON-LD Structured Data -->
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "Article",
              "headline": <?php echo json_encode(get_the_title()); ?>,
              "image": [
                "<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : ''; ?>"
               ],
              "datePublished": "<?php echo get_the_date('c'); ?>",
              "dateModified": "<?php echo get_the_modified_date('c'); ?>",
              "author": [{
                  "@type": "Person",
                  "name": "Zidooka yamaguchi",
                  "url": "<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                }],
              "publisher": {
                "@type": "Organization",
                "name": "ZIDOOKA!",
                "logo": {
                  "@type": "ImageObject",
                  "url": "https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png"
                }
              },
              "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": "<?php the_permalink(); ?>"
              }
            }
            </script>
                        <!-- Breadcrumb JSON-LD (safe generation via PHP array + json_encode) -->
                        <?php
                        $breadcrumb = array();
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
                        ?>
                        <script type="application/ld+json">
                        <?php echo json_encode(array(
                                '@context' => 'https://schema.org',
                                '@type' => 'BreadcrumbList',
                                'itemListElement' => $breadcrumb
                        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
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
                                <?php echo nl2br(esc_html($experience_note)); ?>
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
                            </div>
                            
                            <!-- Actions -->
                            <div class="zenn-article-actions" style="margin: 0; display: flex; gap: 8px;">
                                <div class="zenn-like-button">
                                    <button class="zenn-like-btn" data-post-id="<?php the_ID(); ?>" style="padding: 4px 10px; font-size: 0.85rem; height: 32px; display: flex; align-items: center;">
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
                            <button class="zenn-like-btn-large" data-post-id="<?php the_ID(); ?>">
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

            <!-- Author Bio -->
            <section class="zenn-author-section">
                <div class="zenn-author-card">
                    <div class="zenn-author-info-large">
                        <h3 class="zenn-author-name-large">
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                <?php the_author(); ?>
                            </a>
                        </h3>
                        <div class="zenn-author-bio">
                            <div class="bio-content text-muted small mt-2" style="font-size: 0.95rem; line-height: 1.7;">
                                <p class="mb-2" style="font-weight: 700; color: #334155;"><?php echo esc_html($ui_text['bio_title']); ?></p>
                                <p class="mb-3">
                                    <?php echo $ui_text['bio_desc']; ?>
                                </p>
                                
                                <div class="bio-price mb-3" style="font-size: 0.9rem; color: #334155;">
                                    <strong><?php echo esc_html($ui_text['bio_price']); ?></strong>
                                    <span style="font-size: 0.85rem; color: #64748b;"><?php echo esc_html($ui_text['bio_price_note']); ?></span>
                                </div>

                                <div class="bio-actions" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <a href="<?php echo esc_url($form_url); ?>" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; justify-content: center; background: #2563eb; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; flex: 1; min-width: 200px; text-align: center;">
                                        <?php echo esc_html($ui_text['bio_btn_form']); ?>
                                    </a>
                                    <a href="mailto:main@zidooka.com" style="display: inline-flex; align-items: center; justify-content: center; background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; flex: 1; min-width: 160px; text-align: center;">
                                        <?php echo esc_html($ui_text['bio_btn_mail']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Navigation -->
            <nav class="zenn-post-navigation">
                <?php
                // Smart Navigation: Prioritize same language
                $get_smart_adjacent_post = function($previous) use ($is_english_only) {
                    $current_date = get_post_field('post_date', get_the_ID());
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 10, // Look ahead/behind 10 posts
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
                    
                    // 1. Try to find same language match
                    foreach ($candidates as $p) {
                        $is_p_english = !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $p->post_title);
                        if ($is_p_english === $is_english_only) {
                            return $p;
                        }
                    }
                    
                    // 2. Fallback to immediate neighbor
                    return $candidates[0];
                };

                $prev_post = $get_smart_adjacent_post(true);
                $next_post = $get_smart_adjacent_post(false);
                ?>
                
                <?php if ($prev_post) : ?>
                    <div class="zenn-nav-prev">
                        <a href="<?php echo get_permalink($prev_post->ID); ?>" class="zenn-nav-link">
                            <div class="zenn-nav-direction"><?php echo esc_html($ui_text['prev_direction']); ?></div>
                            <div class="zenn-nav-title"><?php echo get_the_title($prev_post->ID); ?></div>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($next_post) : ?>
                    <div class="zenn-nav-next">
                        <a href="<?php echo get_permalink($next_post->ID); ?>" class="zenn-nav-link">
                            <div class="zenn-nav-direction"><?php echo esc_html($ui_text['next_direction']); ?></div>
                            <div class="zenn-nav-title"><?php echo get_the_title($next_post->ID); ?></div>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>

            <!-- CTA Section & Next Read -->
            <div class="zenn-separator-heading">
                <?php echo esc_html($ui_text['thank_you']); ?>
            </div>

            <!-- CTA Section Removed as per spec -->

            <?php 
            // Related Posts Logic (Correctly placed)
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

            // Fetch candidates (fetch more to allow language filtering)
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
                // Check if candidate is English only
                $is_p_english = !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}]/u', $p->post_title);
                
                if ($is_english_only === $is_p_english) {
                    $same_lang_posts[] = $p;
                } else {
                    $other_lang_posts[] = $p;
                }
            }

            // Prioritize same language, then fallback
            $related_posts = array_merge($same_lang_posts, $other_lang_posts);
            
            // Limit to 4
            $related_posts = array_slice($related_posts, 0, 4);
            
            if ($related_posts) : 
                $next_read = $related_posts[0]; 
            ?>
                <div class="zenn-next-read">
                    <div class="zenn-next-read-label"><?php echo esc_html($ui_text['next_read_label']); ?></div>
                    <a href="<?php echo get_permalink($next_read->ID); ?>" class="zenn-next-read-link">
                        <?php echo get_the_title($next_read->ID); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Comments -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <section class="zenn-comments-section">
                    <?php comments_template(); ?>
                </section>
            <?php endif; ?>

            <!-- Related Posts Grid -->
            <?php if ($related_posts) : ?>
                <section class="zenn-related-posts">
                    <h3 class="zenn-related-title"><?php echo esc_html($ui_text['related_posts_title']); ?></h3>
                    <div class="zenn-related-grid">
                        <?php foreach ($related_posts as $post) : setup_postdata($post); ?>
                            <article class="zenn-related-item">
                                <a href="<?php the_permalink(); ?>" class="zenn-related-link">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="zenn-related-thumbnail">
                                            <?php the_post_thumbnail('medium', array('class' => 'zenn-related-image')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="zenn-related-content">
                                        <h4 class="zenn-related-post-title"><?php the_title(); ?></h4>
                                        <div class="zenn-related-meta">
                                            <time datetime="<?php echo get_the_date('c'); ?>">
                                                <?php echo get_the_date('Y.m.d'); ?>
                                            </time>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- AI Policy Disclaimer -->
            <div class="zenn-ai-policy" style="margin-top: 60px; padding-top: 20px; border-top: 1px solid #eee; font-size: 0.75rem; color: #999; line-height: 1.5;">
                <p style="margin-bottom: 5px;"><strong><?php echo esc_html($ui_text['ai_policy_title']); ?></strong></p>
                <p style="margin: 0;">
                    <?php echo esc_html($ui_text['ai_policy_text']); ?>
                </p>
            </div>

        <?php endwhile; endif; ?>
            </div> <!-- .zenn-wrapper -->
        </div> <!-- .zenn-container -->
    </main> <!-- .zenn-main-column -->

    <aside class="zenn-sidebar-column">
        <div class="zenn-sidebar-sticky">
            <div class="zenn-sidebar-inner">
            <!-- Category Latest -->
            <?php
            $cats = get_the_category(get_the_ID());
            if (!empty($cats)) {
                $cat = $cats[0];
                $cat_posts = get_posts(array(
                    'category' => $cat->term_id,
                    'numberposts' => 5,
                    'post__not_in' => array(get_the_ID())
                ));
                if ($cat_posts) {
                    echo '<div class="zenn-sidebar-widget widget-category">';
                    echo '<h4 class="zenn-sidebar-title">' . esc_html($cat->name) . esc_html($ui_text['latest_suffix']) . '</h4>';
                    echo '<ul class="zenn-sidebar-list">';
                    foreach ($cat_posts as $p) {
                        echo '<li><a href="' . get_permalink($p->ID) . '">' . get_the_title($p->ID) . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
            }
            ?>
            <!-- Tag Match -->
            <?php
            // Re-fetch tags for sidebar to ensure availability outside the loop
            $sidebar_tags = get_the_tags(get_the_ID());
            if (!empty($sidebar_tags)) {
                $sidebar_tag_ids = array();
                foreach ($sidebar_tags as $t) $sidebar_tag_ids[] = $t->term_id;

                $tag_posts = get_posts(array(
                    'tag__in' => $sidebar_tag_ids,
                    'numberposts' => 5,
                    'post__not_in' => array(get_the_ID())
                ));
                if ($tag_posts) {
                    echo '<div class="zenn-sidebar-widget widget-tag">';
                    echo '<h4 class="zenn-sidebar-title">' . esc_html($ui_text['sidebar_related_title']) . '</h4>';
                    echo '<ul class="zenn-sidebar-list">';
                    foreach ($tag_posts as $p) {
                        echo '<li><a href="' . get_permalink($p->ID) . '">' . get_the_title($p->ID) . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }
            }
            ?>
            
            <!-- CTA Widget Removed as per spec -->

            <!-- Popular/Random Posts -->
            <div class="zenn-sidebar-widget widget-popular">
                <h4 class="zenn-sidebar-title"><?php echo esc_html($ui_text['popular_title']); ?></h4>
                <ul class="zenn-sidebar-list">
                    <?php
                    $popular_posts = get_posts(array(
                        'numberposts' => 3,
                        'orderby' => 'rand',
                        'post__not_in' => array(get_the_ID())
                    ));
                    foreach ($popular_posts as $p) {
                        echo '<li><a href="' . get_permalink($p->ID) . '">' . get_the_title($p->ID) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
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
}

/* Mobile Default (Inline TOC visible, Sidebar TOC hidden) */
.zenn-toc-placeholder { display: block; }
.widget-toc { display: none; }

/* Desktop Layout (PC) */
@media (min-width: 992px) {
    .zenn-flex-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    .zenn-main-column {
        flex: 1;
        max-width: 760px; /* Main content width */
        min-width: 0; /* Prevent flex overflow */
    }
    .zenn-sidebar-column {
        width: 300px;
        flex-shrink: 0;
    }
    .zenn-sidebar-sticky {
        position: sticky;
        top: 24px;
    }
    .zenn-sidebar-inner {
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        scrollbar-width: thin;
    }
    
    /* Switch TOC visibility on Desktop */
    /* .zenn-toc-placeholder { display: none; } */ /* Keep inline TOC visible */
    .widget-toc { display: none; } /* Hide sidebar TOC */
}

/* TOC Common Styles */
.zenn-toc {
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 12px 16px;
    font-size: 1rem;
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
}
.zenn-sidebar-list a:hover {
    text-decoration: underline;
    color: #0d6efd;
}
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
    
    // Check local storage for liked posts
    const likedPosts = JSON.parse(localStorage.getItem('liked_posts') || '[]');
    
    likeButtons.forEach(button => {
        const postId = button.dataset.postId;
        
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