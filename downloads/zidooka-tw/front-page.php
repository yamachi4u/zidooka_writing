<?php
/**
 * Template Name: Modern Bootstrap Template
 * Description: シンプルでモダンなBootstrapテンプレート (Modernized Version)
 */

// ヘッダーを読み込む
get_header();
?>

<!-- Front-page component styles -->
<style>
/* ── Layout ── */
.content-area { padding-top: 2rem; padding-bottom: 2rem; }
.site-title { color: #0f172a; }

/* ── Post card overrides for front-page grid ── */
.post-card {
  border: 1px solid #e5e7eb;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
  transition: box-shadow 0.2s, transform 0.2s;
}
.post-card:hover {
  box-shadow: 0 6px 20px rgba(0,0,0,.1);
  transform: translateY(-2px);
}
.post-card .post-thumbnail img {
  aspect-ratio: 16/9;
  object-fit: cover;
  width: 100%;
}
.post-card .entry-title {
  font-size: 1rem;
  line-height: 1.4;
  font-weight: 600;
  max-width: none;
  margin-bottom: 0.25rem;
}
.post-card .entry-title a {
  color: #1e293b;
  text-decoration: none;
}
.post-card .entry-title a:hover { color: #4f46e5; }
.post-card .entry-meta {
  font-size: 0.8rem;
  color: #64748b;
  max-width: none;
  margin-bottom: 0.5rem;
}
.post-card .entry-content {
  font-size: 0.85rem;
  color: #475569;
  line-height: 1.5;
  max-width: none;
}
.post-card .entry-footer { max-width: none; margin-bottom: 0; }
.post-card .post-card-body {
  display: flex;
  flex-direction: column;
  height: 100%;
  padding: 1rem 1.1rem 1.15rem;
}
.post-card .post-card-body .entry-footer { margin-top: auto; }

/* ── Category badges ── */
.cat-link {
  display: inline-block;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.15em 0.6em;
  border-radius: 9999px;
  background: #eef2ff;
  color: #4338ca;
  text-decoration: none;
  margin-right: 0.25rem;
  margin-bottom: 0.25rem;
}
.cat-link:hover { background: #c7d2fe; }

/* ── Resolved badge ── */
.resolved-badge {
  display: inline-block;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.15em 0.5em;
  border-radius: 9999px;
  background: #dcfce7;
  color: #15803d;
  margin-right: 0.25rem;
}

/* ── Pagination ── */
.pagination-wrapper { margin-top: 2rem; }

/* ── Sidebar ── */
.sidebar {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 0.625rem;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
}
.sidebar h3 {
  font-size: 0.95rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.9rem;
}
.sidebar h3 i { margin-right: 0.4rem; color: #4f46e5; }

.sidebar-search-form {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}
.sidebar-search-form input[type="search"] {
  width: 100%;
  border: 1px solid #d1d5db;
  border-radius: 0.625rem;
  background: #fff;
  color: #111827;
  padding: 0.7rem 0.9rem;
  box-sizing: border-box;
}
.sidebar-search-form input[type="search"]:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.18);
}
.sidebar-search-form button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  border-radius: 0.625rem;
  background: #111827;
  color: #fff;
  padding: 0.7rem 1rem;
  border: 1px solid #111827;
  font-size: 0.9rem;
  font-weight: 600;
  transition: background-color 0.15s, border-color 0.15s;
}
.sidebar-search-form button:hover {
  background: #1f2937;
  border-color: #1f2937;
}

/* About-site */
.about-site p { font-size: 0.85rem; color: #475569; line-height: 1.6; margin-bottom: 0.5rem; }

/* Footer / sidebar links */
.footer-link {
  text-decoration: none;
  font-size: 0.9rem;
  padding: 0.25rem 0;
  transition: color 0.15s;
}
.footer-link:hover { color: #4f46e5 !important; }

/* ── Category tree toggle ── */
.category-children {
  display: none;
  margin-top: 0.25rem;
}
.category-children.open { display: block; }
.category-parent {
  cursor: pointer;
  padding: 0.2rem 0;
}
.toggle-icon {
  transition: transform 0.2s;
  display: inline-block;
}
.category-parent.open .toggle-icon {
  transform: rotate(90deg);
}

/* ── Recent posts ── */
.recent-post-title {
  font-size: 0.85rem;
  color: #1e293b;
  line-height: 1.35;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.recent-post-title:hover { color: #4f46e5; }

/* ── Tag cloud ── */
.tag-cloud {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}
.tag-cloud a {
  display: inline-flex;
  align-items: center;
  font-size: 0.78rem;
  padding: 0.25em 0.75em;
  border: 1px solid #e5e7eb;
  border-radius: 9999px;
  color: #475569;
  background: #f9fafb;
  text-decoration: none;
  transition: all 0.15s;
}
.tag-cloud a:hover {
  background: #eef2ff;
  border-color: #c7d2fe;
  color: #4338ca;
}
</style>

<!-- メインコンテンツエリア -->
<div class="max-w-7xl mx-auto px-4 content-area">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- メインコンテンツエリア -->
        <main class="lg:col-span-8">
            <!-- 検索フォーム -->
            <div class="mb-5">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="flex gap-2">
                        <input type="search" class="rounded-md border-2 border-gray-300 bg-white px-4 py-2 text-gray-900 w-full focus:border-indigo-500 focus:ring-2 focus:ring-indigo-300 focus:outline-none"
                               placeholder="キーワードで記事を検索（例：エラー名、ツール名…）" value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
                        <button type="submit" class="inline-flex items-center justify-center rounded-md px-5 py-2 text-white bg-indigo-600 hover:bg-indigo-700 font-medium text-sm whitespace-nowrap">
                            検索
                        </button>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="flex">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card w-100'); ?>>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        if (function_exists('the_optimal_thumbnail')) {
                                            the_optimal_thumbnail(get_the_ID(), 'medium_large', ['class' => 'w-full h-auto object-cover']);
                                        } elseif (has_post_thumbnail()) {
                                            the_post_thumbnail('medium_large', ['class' => 'w-full h-auto object-cover']);
                                        } else {
                                            echo '<img src="https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png" class="w-full h-auto object-cover" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
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
                                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800">
                                            続きを読む <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </footer>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- ページネーション -->
                <nav class="pagination-wrapper mt-6" aria-label="Pagination">
                    <?php
                    $big = 999999999;
                    $pagination_links = paginate_links(array(
                        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format'    => '',
                        'total'     => (int) $query->max_num_pages,
                        'current'   => max(1, (int) $paged),
                        'prev_text' => '前へ',
                        'next_text' => '次へ',
                        'type'      => 'array',
                    ));

                    if (!empty($pagination_links) && is_array($pagination_links)) {
                        echo '<ul class="flex flex-wrap items-center justify-center gap-2">';
                        foreach ($pagination_links as $link_html) {
                            // Normalize WP-generated classes into Tailwind-ish buttons.
                            $link_html = str_replace(
                                'page-numbers',
                                'page-numbers inline-flex min-w-[2.25rem] items-center justify-center rounded-md border border-gray-200 px-3 py-2 text-sm text-gray-800 hover:bg-gray-50',
                                $link_html
                            );
                            $link_html = str_replace(
                                'current',
                                'current border-gray-900 bg-gray-900 text-white hover:bg-gray-900',
                                $link_html
                            );
                            $link_html = str_replace(
                                'dots',
                                'dots border-transparent text-gray-500',
                                $link_html
                            );
                            echo '<li>' . $link_html . '</li>';
                        }
                        echo '</ul>';
                    }
                    ?>
                    <div class="text-center mt-3">
                        <a href="/page/2/" class="inline-flex items-center justify-center rounded-md border border-blue-600 text-blue-600 hover:bg-blue-50 w-full px-3 py-2 text-sm font-medium">全記事一覧を見る</a>
                    </div>
                </nav>

            <?php else : ?>
                <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 text-blue-900">
                    <p class="mb-0">記事がありません。</p>
                </div>
            <?php endif;
            wp_reset_postdata(); ?>
        </main>

        <!-- サイドバー -->
        <aside class="lg:col-span-4">
            <!-- サイト紹介 -->
<div class="sidebar about-site">
    <h3><i class="fas fa-info-circle"></i>このサイトについて</h3>
    <p>ZIDOOKA！は「自動化」ソリューションを皆様にお届けするウェブサイトです。最新の技術情報やトレンドをお届けします。</p>
    
    <a href="https://www.zidooka.com/jigyo" class="inline-flex items-center justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 mt-4">
        お問い合わせ
    </a>
<p><span>mail:<br><a href="mailto:main@zidooka.com">main@zidooka.com</a></span></p>
 </div>


            <!-- 検索ボックス -->
            <div class="sidebar">
                <h3><i class="fas fa-search"></i>検索</h3>
                <form role="search" method="get" class="sidebar-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" name="s" placeholder="キーワードで検索" value="<?php echo esc_attr(get_search_query()); ?>" />
                    <button type="submit">検索</button>
                </form>
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
                            echo '<div class="category-parent flex items-center" onclick="toggleCategory(this)">';
                            echo '<i class="fas fa-chevron-right toggle-icon mr-2 text-gray-500 text-[0.7rem]"></i>';
                            echo '<a href="' . get_category_link($parent->term_id) . '" class="footer-link flex items-center text-gray-900 grow" onclick="event.stopPropagation()">';
                            echo '<i class="fas fa-folder mr-2 text-blue-600 text-[0.8rem]"></i>';
                            echo $parent->name;
                            echo '<span class="ml-auto inline-flex items-center rounded-full bg-gray-100 text-gray-900 text-xs px-2 py-0.5">' . $parent->count . '</span>';
                            echo '</a>';
                            echo '</div>';
                            
                            echo '<ul class="list-none ml-4 mt-1 category-children">';
                            foreach($child_categories as $child) {
                                echo '<li class="mb-1"><a href="' . get_category_link($child->term_id) . '" class="footer-link flex items-center text-gray-900 text-[0.9rem]">';
                                echo '<i class="fas fa-chevron-right mr-2 text-gray-500 text-[0.6rem]"></i>';
                                echo $child->name;
                                echo '<span class="ml-auto inline-flex items-center rounded-full bg-gray-100 text-gray-900 text-[0.7rem] px-2 py-0.5">' . $child->count . '</span>';
                                echo '</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<a href="' . get_category_link($parent->term_id) . '" class="footer-link flex items-center text-gray-900">';
                            echo '<i class="fas fa-folder mr-2 text-blue-600 text-[0.8rem]"></i>';
                            echo $parent->name;
                            echo '<span class="ml-auto inline-flex items-center rounded-full bg-gray-100 text-gray-900 text-xs px-2 py-0.5">' . $parent->count . '</span>';
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
                            <div class="recent-post-item flex items-center gap-3 py-2">
                                <div class="recent-post-thumbnail shrink-0">
                                    <?php 
                                    if (function_exists('the_optimal_thumbnail')) {
                                        the_optimal_thumbnail(get_the_ID(), 'thumbnail', ['class' => 'h-12 w-12 rounded-md object-cover']);
                                    } elseif (has_post_thumbnail()) {
                                        the_post_thumbnail('thumbnail', ['class' => 'h-12 w-12 rounded-md object-cover']);
                                    } else {
                                        echo '<img src="https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png" class="h-12 w-12 rounded-md object-cover" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                    }
                                    ?>
                                </div>
                                <div class="recent-post-content">
                                    <a href="<?php the_permalink(); ?>" class="recent-post-title no-underline hover:underline">
                                        <?php the_title(); ?>
                                    </a>
                                    <div class="text-gray-500 text-xs"><?php echo get_the_date('Y.m.d'); ?></div>
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
                    $tags = get_tags([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 50,
                        'hide_empty' => true,
                    ]);
                    if ($tags) {
                        $tags = array_values(array_filter($tags, function ($tag) {
                            return (int) $tag->count >= 3;
                        }));
                        $tags = array_slice($tags, 0, 12);
                    }
                    if ($tags) :
                        foreach ($tags as $tag) : ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>">
                                <?php echo esc_html($tag->name); ?>
                                <span class="ml-1 inline-flex items-center rounded-full bg-white px-2 py-0.5 text-xs text-gray-700 border border-gray-200"><?php echo (int) $tag->count; ?></span>
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
