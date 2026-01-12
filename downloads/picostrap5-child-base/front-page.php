<?php
/**
 * Template Name: Modern Bootstrap Template
 * Description: シンプルでモダンなBootstrapテンプレート (Modernized Version)
 */

// ヘッダーを読み込む
get_header();
?>

<!-- カスタムスタイルは style.css に移動しました -->

<!-- メインコンテンツエリア -->
<div class="container content-area">
    <div class="row g-4">
        <!-- メインコンテンツエリア -->
        <main class="col-lg-8">            <h1 class="h2 site-title mb-3"><?php echo esc_html(get_bloginfo('name')); ?></h1>
            <!-- 検索フォーム -->
            <div class="mb-5">
                <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                    <div class="input-group input-group-lg">
                        <input type="search" class="form-control" placeholder="キーワードで記事を検索（例：エラー名、ツール名…）" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- 記事リスト -->
            <?php
            // ページ番号の取得（フロントページ対応）
            $paged = (get_query_var('paged')) ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
            
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 10,
                'paged'          => $paged,
                'orderby'        => 'date',
                'order'          => 'DESC'
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) : ?>
                <div class="row g-4">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="col-md-6 d-flex">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card w-100'); ?>>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        if (function_exists('the_optimal_thumbnail')) {
                                            the_optimal_thumbnail(get_the_ID(), 'medium_large', ['class' => 'img-fluid']);
                                        } elseif (has_post_thumbnail()) {
                                            the_post_thumbnail('medium_large', ['class' => 'img-fluid']);
                                        } else {
                                            echo '<img src="https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png" class="img-fluid" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                        }
                                        ?>
                                    </a>
                                </div>
                                <div class="post-card-body">
                                    <header class="entry-header">
                                        <?php if (has_category()) : ?>
                                            <div class="categories mb-2">
                                                <?php 
                                                $categories = get_the_category();
                                                if (!empty($categories)) {
                                                    $output = '';
                                                    foreach ($categories as $category) {
                                                        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="cat-link cat-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                                                    }
                                                    echo $output;
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>

                                        <h2 class="entry-title">
                                            <?php if (get_post_meta(get_the_ID(), 'is_resolved', true) === 'true') : ?>
                                                <span class="resolved-badge">✔ 解決済み</span>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="entry-meta">
                                            <span class="posted-on">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                        </div>
                                    </header>
                                    <div class="entry-content">
                                        <?php 
                                        if (function_exists('jp_excerpt')) {
                                            echo jp_excerpt(80);
                                        } else {
                                            echo wp_trim_words(get_the_excerpt(), 25, '...');
                                        }
                                        ?>
                                    </div>
                                    <footer class="entry-footer mt-auto">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-read-more">
                                            続きを読む <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- ページネーション -->
                <nav class="pagination-wrapper mt-5">
                    <?php
                    $big = 999999999;
                    $pagination_args = array(
                        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format'    => '?paged=%#%',
                        'total'     => $query->max_num_pages,
                        'current'   => max(1, $paged),
                        'prev_text' => '«',
                        'next_text' => '»',
                        'type'      => 'list',
                        'class'     => 'pagination justify-content-center',
                    );
                    echo paginate_links($pagination_args);
                    ?>
                    <div class="text-center mt-3">
                        <a href="/page/2/" class="btn btn-outline-primary w-100">全記事一覧を見る</a>
                    </div>
                </nav>

            <?php else : ?>
                <div class="alert alert-info rounded-3">
                    <p class="mb-0">記事がありません。</p>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>
        </main>

        <!-- サイドバー -->
        <aside class="col-lg-4">
            <!-- サイト紹介 -->
<div class="sidebar about-site">
    <h3><i class="fas fa-info-circle"></i>ZIDOOKA!</h3>
    <p>ZIDOOKA！は「自動化」ソリューションを皆様にお届けするウェブサイトです。最新の技術情報やトレンドをお届けします。</p>
    
    <a href="https://www.zidooka.com/jigyo" class="btn btn-primary" style="margin-top:10px;">
        <i class="fas fa-envelope"></i> お問い合わせ
    </a>
<p><span>mail:<br><a href="mail:main@zidooka.com">main@zidooka.com</a></span></p>
</div>


            <!-- 検索ボックス -->
            <div class="sidebar">
                <h3><i class="fas fa-search"></i>検索</h3>
                <?php get_search_form(); ?>
            </div>

            <!-- カテゴリー -->
            <div class="sidebar">
                <h3><i class="fas fa-folder"></i>カテゴリー</h3>
                <ul class="list-unstyled mb-0">
                    <?php 
                    $parent_categories = get_categories(array('parent' => 0));
                    foreach($parent_categories as $parent) {
                        $child_categories = get_categories(array('parent' => $parent->term_id));
                        $has_children = !empty($child_categories);
                        
                        echo '<li class="mb-2">';
                        
                        if ($has_children) {
                            echo '<div class="category-parent d-flex align-items-center" onclick="toggleCategory(this)">';
                            echo '<i class="fas fa-chevron-right toggle-icon me-2 text-secondary" style="font-size: 0.7rem;"></i>';
                            echo '<a href="' . get_category_link($parent->term_id) . '" class="footer-link text-dark d-flex align-items-center flex-grow-1" onclick="event.stopPropagation()">';
                            echo '<i class="fas fa-folder me-2 text-primary" style="font-size: 0.8rem;"></i>';
                            echo $parent->name;
                            echo '<span class="ms-auto badge rounded-pill bg-light text-dark">' . $parent->count . '</span>';
                            echo '</a>';
                            echo '</div>';
                            
                            echo '<ul class="list-unstyled ms-4 mt-1 category-children">';
                            foreach($child_categories as $child) {
                                echo '<li class="mb-1"><a href="' . get_category_link($child->term_id) . '" class="footer-link text-dark d-flex align-items-center" style="font-size: 0.9rem;">';
                                echo '<i class="fas fa-chevron-right me-2 text-secondary" style="font-size: 0.6rem;"></i>';
                                echo $child->name;
                                echo '<span class="ms-auto badge rounded-pill bg-light text-dark" style="font-size: 0.7rem;">' . $child->count . '</span>';
                                echo '</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<a href="' . get_category_link($parent->term_id) . '" class="footer-link text-dark d-flex align-items-center">';
                            echo '<i class="fas fa-folder me-2 text-primary" style="font-size: 0.8rem;"></i>';
                            echo $parent->name;
                            echo '<span class="ms-auto badge rounded-pill bg-light text-dark">' . $parent->count . '</span>';
                            echo '</a>';
                        }
                        
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            
            <script>
            function toggleCategory(element) {
                element.classList.toggle('open');
                const children = element.nextElementSibling;
                if (children && children.classList.contains('category-children')) {
                    children.classList.toggle('open');
                }
            }
            </script>

            <!-- 最近の投稿 -->
            <div class="sidebar">
                <h3><i class="fas fa-clock"></i>最近の投稿</h3>
                <?php
                $recent_posts_args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 5,
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                );
                $recent_posts = new WP_Query($recent_posts_args);
                if ($recent_posts->have_posts()) : ?>
                    <div class="recent-posts">
                        <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                            <div class="recent-post-item d-flex align-items-center">
                                <div class="recent-post-thumbnail flex-shrink-0 me-3">
                                    <?php 
                                    if (function_exists('the_optimal_thumbnail')) {
                                        the_optimal_thumbnail(get_the_ID(), 'thumbnail', ['class' => 'img-fluid']);
                                    } elseif (has_post_thumbnail()) {
                                        the_post_thumbnail('thumbnail', ['class' => 'img-fluid']);
                                    } else {
                                        echo '<img src="../wp-content/uploads/2024/05/Slide-16_9-1.png" class="img-fluid" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                    }
                                    ?>
                                </div>
                                <div class="recent-post-content">
                                    <a href="<?php the_permalink(); ?>" class="recent-post-title text-decoration-none">
                                        <?php the_title(); ?>
                                    </a>
                                    <div class="text-muted" style="font-size: 0.8rem;"><?php echo get_the_date('Y.m.d'); ?></div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <p>記事がありません。</p>
                <?php endif;
                wp_reset_postdata(); ?>
            </div>

            <!-- タグクラウド -->
            <div class="sidebar">
                <h3><i class="fas fa-tags"></i>タグクラウド</h3>
                <div class="tag-cloud">
                    <?php
                    $tags = get_tags();
                    if ($tags) :
                        foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>">
                                <?php echo $tag->name; ?> <span class="badge bg-white text-primary"><?php echo $tag->count; ?></span>
                            </a>
                        <?php endforeach;
                    else : ?>
                        <p class="mb-0">タグはありません。</p>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php
// フッターを読み込む
get_footer();
?>