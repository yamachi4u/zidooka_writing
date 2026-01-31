<?php
/**
 * Category Archive (Tailwind)
 */

get_header();
?>

<div class="bg-slate-50">
    <div class="mx-auto max-w-6xl px-6 py-12">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
            <main>
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        <?php single_cat_title(); ?>
                    </h1>
                    <?php if (category_description()) : ?>
                        <p class="mt-2 text-sm text-slate-600"><?php echo wp_kses_post(category_description()); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (have_posts()) : ?>
                    <div class="grid gap-6 md:grid-cols-2">
                        <?php while (have_posts()) : the_post(); ?>
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
                                    <h2 class="text-lg font-semibold leading-snug text-slate-900">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-slate-700"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="text-xs text-slate-500">
                                        <?php echo esc_html(get_the_date('Y.m.d')); ?>
                                    </div>
                                    <div class="text-sm leading-relaxed text-slate-600">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
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
                    $pagination = paginate_links(array(
                        'prev_text' => '«',
                        'next_text' => '»',
                        'type'      => 'list',
                    ));
                    if ($pagination) :
                        $pagination = preg_replace('/class=\"page-numbers\"/', 'class="page-numbers inline-flex items-center justify-center min-w-[36px] h-9 rounded-full border border-slate-200 text-sm text-slate-600 hover:border-slate-400"', $pagination);
                        $pagination = preg_replace('/<ul class=[\"\']page-numbers[\"\']>/', '<ul class="page-numbers flex flex-wrap items-center justify-center gap-2">', $pagination);
                        $pagination = preg_replace('/class=\"page-numbers current\"/', 'class="page-numbers current inline-flex items-center justify-center min-w-[36px] h-9 rounded-full border border-slate-900 bg-slate-900 text-sm text-white"', $pagination);
                        ?>
                        <nav class="mt-10 text-center">
                            <?php echo wp_kses_post($pagination); ?>
                        </nav>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-slate-600">
                        記事がありません。
                    </div>
                <?php endif; ?>
            </main>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-widest text-slate-700">ZIDOOKA!</h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Zidookaは実務で使える自動化ソリューションや運用の工夫を実務者目線で共有するウェブサイトです。必要であれば、下記のフォーム・メールから個別相談・受託も可能です。フリーランスエンジニアとしても活動しております。
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
                    <div class="mt-3">
                        <?php get_search_form(); ?>
                    </div>
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
