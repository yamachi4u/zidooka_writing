<?php
/**
 * Template Name: Tailwind Home
 * Description: Tailwind-first front page layout.
 */

get_header();
?>

<div class="bg-slate-50">
    <div class="mx-auto max-w-6xl px-6 py-12">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
            <main>
                <h1 class="mb-6 text-2xl font-semibold tracking-tight text-slate-900">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </h1>

                <section class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Start Here</p>
                            <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-900">よく読まれている入口</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                                ChatGPT、Copilot、GAS、Access Denied 系の入口を先に置いています。検索から来た人が次に見たいページへ移りやすい導線です。
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                        <a href="/archives/3716" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Hub</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">AIエラー解決まとめ</div>
                        </a>
                        <a href="/archives/1965" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">ChatGPT</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">ChatGPT エラー解決集</div>
                        </a>
                        <a href="/archives/2672" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Copilot</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">Copilot エラー総合</div>
                        </a>
                        <a href="/archives/2877" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">GAS</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">Rhino 廃止 / V8 移行ガイド</div>
                        </a>
                        <a href="/archives/105" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Popular</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">PrinceXML エラー対処</div>
                        </a>
                        <a href="/archives/2590" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-400 hover:bg-white">
                            <div class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Access Denied</div>
                            <div class="mt-2 text-sm font-semibold leading-snug text-slate-900">errors.edgesuite.net の解説</div>
                        </a>
                    </div>
                </section>

                <form role="search" method="get" class="mb-10" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm">
                        <input
                            type="search"
                            class="flex-1 bg-transparent text-sm text-slate-700 placeholder-slate-400 focus:outline-none"
                            placeholder="キーワードで記事を検索（例：エラー名、ツール名…）"
                            value="<?php echo esc_attr(get_search_query()); ?>"
                            name="s"
                        />
                        <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold tracking-widest text-white">
                            検索
                        </button>
                    </div>
                </form>

                <?php
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
                    <div class="grid gap-6 md:grid-cols-2">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md'); ?>>
                                <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                                    <?php
                                    if (function_exists('the_optimal_thumbnail')) {
                                        the_optimal_thumbnail(get_the_ID(), 'medium_large', ['class' => 'h-44 w-full object-cover transition duration-300 group-hover:scale-105']);
                                    } elseif (has_post_thumbnail()) {
                                        the_post_thumbnail('medium_large', ['class' => 'h-44 w-full object-cover transition duration-300 group-hover:scale-105']);
                                    } else {
                                        echo '<img src="https://www.zidooka.com/wp-content/uploads/2024/05/Slide-16_9-1.png" class="h-44 w-full object-cover" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                    }
                                    ?>
                                </a>
                                <div class="grid gap-3 p-5">
                                    <?php if (has_category()) : ?>
                                        <div class="flex flex-wrap gap-2 text-[0.65rem] uppercase tracking-[0.2em] text-slate-500">
                                            <?php
                                            $categories = get_the_category();
                                            if (!empty($categories)) {
                                                foreach ($categories as $category) {
                                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="rounded-full border border-slate-200 px-2 py-1 text-slate-600 hover:border-slate-400">' . esc_html($category->name) . '</a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>

                                    <h2 class="text-lg font-semibold leading-snug text-slate-900">
                                        <?php if (get_post_meta(get_the_ID(), 'is_resolved', true) === 'true') : ?>
                                            <span class="mr-2 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-widest text-emerald-700">解決済み</span>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>" class="hover:text-slate-700"><?php the_title(); ?></a>
                                    </h2>

                                    <div class="text-xs text-slate-500">
                                        <?php echo esc_html(get_the_date('Y.m.d')); ?>
                                    </div>

                                    <div class="text-sm leading-relaxed text-slate-600">
                                        <?php
                                        if (function_exists('jp_excerpt')) {
                                            echo jp_excerpt(80);
                                        } else {
                                            echo wp_trim_words(get_the_excerpt(), 25, '...');
                                        }
                                        ?>
                                    </div>

                                    <div>
                                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-xs font-semibold uppercase tracking-widest text-slate-900">
                                            続きを読む →
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    $pagination_args = array(
                        'total'     => $query->max_num_pages,
                        'current'   => max(1, $paged),
                        'prev_text' => '«',
                        'next_text' => '»',
                        'type'      => 'list',
                    );
                    $pagination = paginate_links($pagination_args);
                    if ($pagination) :
                        $pagination = preg_replace('/class=\"page-numbers\"/', 'class="page-numbers inline-flex items-center justify-center min-w-[36px] h-9 rounded-full border border-slate-200 text-sm text-slate-600 hover:border-slate-400"', $pagination);
                        $pagination = preg_replace('/<ul class=[\"\']page-numbers[\"\']>/', '<ul class="page-numbers flex flex-wrap items-center justify-center gap-2">', $pagination);
                        $pagination = preg_replace('/class=\"page-numbers current\"/', 'class="page-numbers current inline-flex items-center justify-center min-w-[36px] h-9 rounded-full border border-slate-900 bg-slate-900 text-sm text-white"', $pagination);
                        ?>
                        <nav class="mt-10 space-y-4">
                            <div class="text-center">
                                <?php echo wp_kses_post($pagination); ?>
                            </div>
                            <a href="/page/2/" class="block w-full rounded-full border border-slate-200 px-4 py-3 text-center text-xs font-semibold uppercase tracking-widest text-slate-700 hover:border-slate-400">
                                全記事一覧を見る
                            </a>
                        </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-600">
                        記事がありません。
                    </div>
                <?php endif;
                wp_reset_postdata(); ?>
            </main>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">ZIDOOKA!</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        ZIDOOKA！は「自動化」ソリューションを皆様にお届けするウェブサイトです。最新の技術情報やトレンドをお届けします。
                    </p>
                    <div class="mt-4 space-y-2">
                        <a href="https://www.zidooka.com/jigyo" class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white">
                            お問い合わせ
                        </a>
                        <a href="mailto:main@zidooka.com" class="block text-center text-xs text-slate-500 hover:text-slate-700">
                            main@zidooka.com
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">検索</h3>
                    <form role="search" method="get" class="mt-3" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm">
                            <input
                                type="search"
                                class="flex-1 bg-transparent text-sm text-slate-700 placeholder-slate-400 focus:outline-none"
                                placeholder="キーワードで記事を検索"
                                value="<?php echo esc_attr(get_search_query()); ?>"
                                name="s"
                            />
                            <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold tracking-widest text-white">
                                検索
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">カテゴリー</h3>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <?php
                        $parent_categories = get_categories(array('parent' => 0));
                        foreach ($parent_categories as $parent) {
                            $child_categories = get_categories(array('parent' => $parent->term_id));
                            $has_children = !empty($child_categories);

                            echo '<li>';
                            if ($has_children) {
                                echo '<button class="flex w-full items-center justify-between text-left text-slate-700" onclick="toggleCategory(this)">';
                                echo '<span>' . esc_html($parent->name) . '</span>';
                                echo '<span class="text-xs text-slate-400">+</span>';
                                echo '</button>';
                                echo '<ul class="mt-2 hidden space-y-1 pl-4 text-xs text-slate-500 category-children">';
                                foreach ($child_categories as $child) {
                                    echo '<li><a href="' . esc_url(get_category_link($child->term_id)) . '" class="hover:text-slate-700">' . esc_html($child->name) . '</a></li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<a href="' . esc_url(get_category_link($parent->term_id)) . '" class="hover:text-slate-700">' . esc_html($parent->name) . '</a>';
                            }
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <script>
                function toggleCategory(element) {
                    const children = element.nextElementSibling;
                    if (!children) return;
                    children.classList.toggle('hidden');
                }
                </script>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">最近の投稿</h3>
                    <?php
                    $recent_posts_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 5,
                        'orderby'        => 'date',
                        'order'          => 'DESC'
                    );
                    $recent_posts = new WP_Query($recent_posts_args);
                    if ($recent_posts->have_posts()) : ?>
                        <div class="mt-4 space-y-4">
                            <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                <div class="flex items-start gap-3">
                                    <div class="h-14 w-14 overflow-hidden rounded-lg bg-slate-100">
                                        <?php
                                        if (function_exists('the_optimal_thumbnail')) {
                                            the_optimal_thumbnail(get_the_ID(), 'thumbnail', ['class' => 'h-full w-full object-cover']);
                                        } elseif (has_post_thumbnail()) {
                                            the_post_thumbnail('thumbnail', ['class' => 'h-full w-full object-cover']);
                                        } else {
                                            echo '<img src="../wp-content/uploads/2024/05/Slide-16_9-1.png" class="h-full w-full object-cover" alt="' . esc_attr(get_the_title()) . '" loading="lazy">';
                                        }
                                        ?>
                                    </div>
                                    <div>
                                        <a href="<?php the_permalink(); ?>" class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                                            <?php the_title(); ?>
                                        </a>
                                        <div class="mt-1 text-xs text-slate-400"><?php echo esc_html(get_the_date('Y.m.d')); ?></div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <p class="mt-3 text-sm text-slate-500">記事がありません。</p>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">タグ</h3>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs">
                        <?php
                        $tags = get_tags();
                        if ($tags) :
                            foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="rounded-full border border-slate-200 px-3 py-1 text-slate-600 hover:border-slate-400">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach;
                        else : ?>
                            <p class="text-sm text-slate-500">タグはありません。</p>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php
get_footer();
?>
