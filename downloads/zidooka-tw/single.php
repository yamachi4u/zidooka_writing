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
    <main class="zenn-main-column px-4 sm:px-6 lg:px-0">
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
                'sidebar_about_title' => 'About Zidooka',
                'sidebar_about_body' => '“Zidooka” means automation in Japanese. We share practical automation solutions and real-world workflows. If you need help, we also accept consulting or implementation work.',
                'sidebar_reco_title' => 'Recommended',
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
                'sidebar_about_title' => 'Zidookaについて',
                'sidebar_about_body' => 'Zidookaは実務で使える自動化ソリューションや運用の工夫を実務者目線で共有するウェブサイトです。必要であれば、下記のフォーム・メールから個別相談・受託も可能です。フリーランスエンジニアとしても活動しております。',
                'sidebar_reco_title' => 'おすすめの記事',
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
                <header class="zenn-article-header mb-6">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight tracking-tight mb-4"><?php the_title(); ?></h1>

                    <div class="text-sm text-slate-500 leading-relaxed mb-4">
                        <?php echo esc_html($ui_text['cta_small_text']); ?>
                    </div>
                    
                    <?php if ($experience_note = get_post_meta(get_the_ID(), 'experience_note', true)) : ?>
                        <div class="bg-slate-50 rounded-xl p-4 mb-4">
                            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2"><?php echo esc_html($ui_text['context_label']); ?></div>
                            <div class="text-sm text-slate-700 leading-relaxed">
                                <?php echo nl2br(esc_html($experience_note)); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="zenn-article-meta mb-4">
                        <!-- Author Line -->
                        <div class="font-semibold text-base text-slate-800 mb-2">
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="no-underline hover:underline">
                                <?php the_author(); ?>
                            </a>
                        </div>

                        <!-- Date & Actions Row -->
                        <div class="flex flex-wrap items-center gap-3 pb-4 border-b border-slate-200">
                            
                            <!-- Date -->
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="text-sm text-slate-500">
                                <?php echo esc_html(get_the_date('Y.m.d')); ?>
                            </time>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <button type="button" class="zenn-like-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors" data-post-id="<?php the_ID(); ?>">
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    </svg>
                                    <span class="zenn-like-count"><?php echo (int)get_post_meta(get_the_ID(), '_post_like_count', true); ?></span>
                                </button>
                                <button class="zenn-share-btn inline-flex items-center justify-center w-8 h-8 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6l-2-2-2 2M10 4v8M4 10v2a2 2 0 002 2h4a2 2 0 002-2v-2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TOC inserted here (Full Width) -->
                    <div class="zenn-toc-wrapper my-5">
                        <div class="zenn-toc-placeholder w-full" aria-hidden="true"></div>
                    </div>
                    
                    <!-- Tags -->
                    <?php if (has_tag()) : ?>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php
                            $tags = get_the_tags();
                            if ($tags) {
                                foreach ($tags as $tag) {
                                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="inline-flex items-center px-3 py-1 text-sm rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors no-underline">#' . $tag->name . '</a>';
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
                <footer class="mt-6 pt-6 border-t border-slate-200">
                    <div class="space-y-5">
                        <!-- Like Section -->
                        <div class="flex justify-center">
                            <button type="button" class="zenn-like-btn-large inline-flex items-center gap-2 px-5 py-2.5 text-base font-medium rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors" data-post-id="<?php the_ID(); ?>">
                                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                                </svg>
                                <span class="zenn-like-count-large"><?php echo (int)get_post_meta(get_the_ID(), '_post_like_count', true); ?></span>
                            </button>
                        </div>
                        
                        <!-- Share Section -->
                        <div class="flex flex-wrap justify-center gap-2">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink() . '?utm_source=twitter&utm_medium=social&utm_campaign=zidooka_share'); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors no-underline" target="_blank" rel="noopener">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                                <span class="hidden sm:inline">Twitter</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink() . '?utm_source=facebook&utm_medium=social&utm_campaign=zidooka_share'); ?>" 
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors no-underline" target="_blank" rel="noopener">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="hidden sm:inline">Facebook</span>
                            </a>
                            <button class="inline-flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" onclick="copyToClipboard('<?php echo get_permalink() . '?utm_source=copy&utm_medium=social&utm_campaign=zidooka_share'; ?>')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                                </svg>
                                <span class="hidden sm:inline"><?php echo esc_html($ui_text['copy_link']); ?></span>
                            </button>
                        </div>

                        <!-- Buy Me a Coffee -->
                        <div class="text-center">
                            <a href="https://buymeacoffee.com/zidooka" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 transition-colors no-underline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 8h1a4 4 0 110 8h-1M3 8h14v9a4 4 0 01-4 4H7a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                                </svg>
                                <?php echo $is_english_only ? 'If helpful, Buy Me a Coffee' : '役に立ったら Buy Me a Coffee'; ?>
                            </a>
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

    <?php $post_id = get_queried_object_id(); ?>
    <aside class="zenn-left-column hidden lg:block" aria-label="Article actions">
        <div class="zenn-left-sticky">
            <div class="flex flex-col items-center gap-3">
                <button type="button" class="zenn-like-btn inline-flex flex-col items-center justify-center w-11 h-11 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-700" data-post-id="<?php echo esc_attr($post_id); ?>" aria-label="<?php echo esc_attr($ui_text['helpful']); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 14.5l-1.5-1.37C3.6 10.36 1 7.28 1 5.5 1 3.5 2.5 2 4.5 2c1.54 0 3.04 1.33 3.5 2.36C8.46 3.33 9.96 2 11.5 2 13.5 2 15 3.5 15 5.5c0 1.78-2.6 4.86-5.5 7.63L8 14.5z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                    <span class="zenn-like-count text-xs font-semibold"><?php echo (int)get_post_meta($post_id, '_post_like_count', true); ?></span>
                </button>
                <a class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600" href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink($post_id) . '?utm_source=twitter&utm_medium=social&utm_campaign=zidooka_share'); ?>&text=<?php echo urlencode(get_the_title($post_id)); ?>" target="_blank" rel="noopener" aria-label="Share on Twitter">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
                <a class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink($post_id) . '?utm_source=facebook&utm_medium=social&utm_campaign=zidooka_share'); ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <button class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-slate-600" onclick="copyToClipboard('<?php echo get_permalink($post_id) . '?utm_source=copy&utm_medium=social&utm_campaign=zidooka_share'; ?>')" aria-label="<?php echo esc_attr($ui_text['copy_link']); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path>
                    </svg>
                </button>
                <a class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 bg-white hover:bg-slate-50 transition-colors text-indigo-500" href="https://buymeacoffee.com/zidooka" target="_blank" rel="noopener" aria-label="Buy Me a Coffee">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 8h1a4 4 0 110 8h-1M3 8h14v9a4 4 0 01-4 4H7a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <aside class="zenn-sidebar-column hidden lg:block">
        <div class="sticky top-6">
            <div class="space-y-5 max-h-[calc(100vh-48px)] overflow-y-auto">
            <div class="bg-slate-50 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-800 mb-2 pb-2 border-b border-slate-200"><?php echo esc_html($ui_text['sidebar_about_title']); ?></h4>
                <p class="text-sm text-slate-600 leading-relaxed mb-3"><?php echo esc_html($ui_text['sidebar_about_body']); ?></p>
                <div class="space-y-2">
                    <a class="block w-full text-center px-3 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors no-underline" href="<?php echo esc_url($form_url); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html($ui_text['bio_btn_form']); ?>
                    </a>
                    <a class="block w-full text-center px-3 py-2 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors no-underline" href="mailto:main@zidooka.com">
                        <?php echo esc_html($ui_text['bio_btn_mail']); ?>
                    </a>
                </div>
            </div>

            <?php if (!empty($related_posts)) : ?>
                <?php $sidebar_rec = $related_posts[0]; ?>
                <div class="bg-white rounded-xl p-4 border border-slate-200">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2"><?php echo esc_html($ui_text['sidebar_reco_title']); ?></h4>
                    <a class="block no-underline group" href="<?php echo get_permalink($sidebar_rec->ID); ?>">
                        <span class="block text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors mb-1"><?php echo get_the_title($sidebar_rec->ID); ?></span>
                        <span class="text-xs text-slate-500"><?php echo get_the_date('Y.m.d', $sidebar_rec->ID); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Buy Me a Coffee -->
            <div class="pt-3 border-t border-slate-200">
                <a href="https://buymeacoffee.com/zidooka" target="_blank" rel="noopener" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-indigo-600 transition-colors no-underline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 8h1a4 4 0 110 8h-1M3 8h14v9a4 4 0 01-4 4H7a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                    </svg>
                    Buy Me a Coffee
                </a>
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
/* Core Layout - Desktop */
@media (min-width: 1024px) {
    .zenn-flex-wrapper {
        display: flex;
        justify-content: center;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    .zenn-main-column {
        flex: 1 1 0;
        max-width: 760px;
        min-width: 0;
    }
    .zenn-left-column { order: 1; width: 56px; flex-shrink: 0; }
    .zenn-main-column { order: 2; }
    .zenn-sidebar-column { order: 3; width: 280px; flex: 0 0 280px; }
    .zenn-left-sticky { position: sticky; top: 96px; }
}

/* TOC Styles */
.zenn-toc {
    background: #f8fafc;
    border-radius: 12px;
    padding: 14px 18px;
}
.zenn-toc-title {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 10px;
}
.zenn-toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.zenn-toc-item {
    margin: 0;
    line-height: 1.5;
}
.zenn-toc-h2 {
    font-weight: 600;
    padding: 5px 0;
}
.zenn-toc-h3 {
    padding: 3px 0 3px 14px;
    font-size: 0.85rem;
    color: #64748b;
}
.zenn-toc-link {
    color: #334155;
    text-decoration: none;
    display: block;
    transition: color 0.15s;
}
.zenn-toc-link:hover {
    color: #4f46e5;
}

/* Content Typography */
.zenn-content {
    font-size: 1rem;
    line-height: 1.9;
    color: #1f2937;
}
@media (min-width: 640px) {
    .zenn-content {
        font-size: 1.05rem;
        line-height: 2;
    }
}
.zenn-content h2 {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.4;
    margin: 2rem 0 0.75rem;
    padding-bottom: 0.4rem;
    border-bottom: 1px solid #e2e8f0;
}
@media (min-width: 640px) {
    .zenn-content h2 {
        font-size: 1.4rem;
        margin: 2.5rem 0 1rem;
    }
}
.zenn-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.5;
    margin: 1.25rem 0 0.5rem;
}
.zenn-content p {
    margin: 0 0 1.1rem;
}
.zenn-content a {
    color: #4f46e5;
    text-decoration: underline;
    text-underline-offset: 2px;
}
.zenn-content ul,
.zenn-content ol {
    padding-left: 1.25rem;
    margin: 0 0 1.1rem;
}
.zenn-content ul { list-style: disc; }
.zenn-content ol { list-style: decimal; }
.zenn-content li + li { margin-top: 0.4rem; }
.zenn-content blockquote {
    background: #f8fafc;
    border-left: 3px solid #cbd5e1;
    padding: 0.75rem 1rem;
    margin: 1rem 0;
    color: #475569;
}
.zenn-content code {
    background: #f1f5f9;
    border-radius: 4px;
    padding: 2px 5px;
    font-size: 0.85em;
}
.zenn-content pre {
    background: #1e293b;
    color: #e2e8f0;
    border-radius: 10px;
    padding: 0.875rem;
    overflow-x: auto;
    margin: 1rem 0;
    font-size: 0.8rem;
}
.zenn-content pre code {
    background: transparent;
    padding: 0;
    color: inherit;
}
.zenn-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    font-size: 0.85rem;
}
.zenn-content th,
.zenn-content td {
    border: 1px solid #e2e8f0;
    padding: 0.5rem 0.75rem;
    text-align: left;
}
.zenn-content th {
    background: #f8fafc;
    font-weight: 600;
}
.zenn-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Like button active state */
.zenn-like-btn.liked svg path,
.zenn-like-btn-large.liked svg path {
    fill: #ef4444;
    stroke: #ef4444;
}

/* Image Modal */
.zenn-image-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.9);
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.zenn-image-modal.is-visible {
    display: flex;
}
.zenn-image-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 1.5rem;
    cursor: pointer;
}
.zenn-image-modal-img {
    max-width: 90vw;
    max-height: 85vh;
    object-fit: contain;
    transition: transform 0.1s ease-out;
}
.zenn-image-modal-caption {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    font-size: 0.875rem;
    text-align: center;
    max-width: 80%;
}
body.zenn-modal-open {
    overflow: hidden;
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
