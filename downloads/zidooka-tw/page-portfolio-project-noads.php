<?php
/**
 * Template Name: Portfolio Project (No AdSense)
 * Description: ポートフォリオ実績ページ用テンプレート。通常ヘッダ/フッタを使い、AdSenseを除外。
 */

if (!defined('ABSPATH')) {
    exit;
}

$asset_base = trailingslashit(get_stylesheet_directory_uri()) . 'assets/tools/portfolio-template';
wp_enqueue_style(
    'zdk-portfolio-template',
    $asset_base . '/portfolio.css',
    array(),
    '2026.02.19'
);

// functions.php 側の AdSense クライアントを無効化
add_filter('zidooka_adsense_client', '__return_empty_string', 999);

// header.php 直書き分を含む AdSense script を除去しつつ通常ヘッダを使う
ob_start();
get_header();
$header_html = ob_get_clean();
$header_html = preg_replace(
    '#<script[^>]*pagead2\.googlesyndication\.com/pagead/js/adsbygoogle\.js[^>]*>\s*</script>\s*#is',
    '',
    $header_html
);
echo $header_html;

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $role = trim((string) get_post_meta(get_the_ID(), 'portfolio_role', true));
        $period = trim((string) get_post_meta(get_the_ID(), 'portfolio_period', true));
        $team = trim((string) get_post_meta(get_the_ID(), 'portfolio_team', true));
        $stack_raw = trim((string) get_post_meta(get_the_ID(), 'portfolio_stack', true));
        $project_url = trim((string) get_post_meta(get_the_ID(), 'portfolio_url', true));
        $repo_url = trim((string) get_post_meta(get_the_ID(), 'portfolio_repo_url', true));
        $problem = trim((string) get_post_meta(get_the_ID(), 'portfolio_problem', true));
        $solution = trim((string) get_post_meta(get_the_ID(), 'portfolio_solution', true));
        $result = trim((string) get_post_meta(get_the_ID(), 'portfolio_result', true));

        $stack_items = preg_split('/[,、]/u', $stack_raw);
        if (!is_array($stack_items)) {
            $stack_items = array();
        }
        $stack_items = array_values(array_filter(array_map('trim', $stack_items)));
        ?>
        <div class="zdk-pf-page">
            <article class="zdk-pf-shell">
                <header class="zdk-pf-hero">
                    <p class="zdk-pf-kicker">Portfolio</p>
                    <h1 class="zdk-pf-title"><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <p class="zdk-pf-lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>

                    <div class="zdk-pf-meta">
                        <?php if ($role !== '') : ?><span class="zdk-pf-chip">役割: <?php echo esc_html($role); ?></span><?php endif; ?>
                        <?php if ($period !== '') : ?><span class="zdk-pf-chip">期間: <?php echo esc_html($period); ?></span><?php endif; ?>
                        <?php if ($team !== '') : ?><span class="zdk-pf-chip">体制: <?php echo esc_html($team); ?></span><?php endif; ?>
                    </div>

                    <?php if (!empty($stack_items)) : ?>
                        <div class="zdk-pf-stack-wrap">
                            <?php foreach ($stack_items as $stack_item) : ?>
                                <span class="zdk-pf-stack"><?php echo esc_html($stack_item); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($project_url !== '' || $repo_url !== '') : ?>
                        <div class="zdk-pf-links">
                            <?php if ($project_url !== '') : ?>
                                <a class="zdk-pf-btn zdk-pf-btn-primary" href="<?php echo esc_url($project_url); ?>" target="_blank" rel="noopener">公開URL</a>
                            <?php endif; ?>
                            <?php if ($repo_url !== '') : ?>
                                <a class="zdk-pf-btn zdk-pf-btn-secondary" href="<?php echo esc_url($repo_url); ?>" target="_blank" rel="noopener">リポジトリ</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <figure class="zdk-pf-cover">
                        <?php the_post_thumbnail('large', array('loading' => 'eager', 'fetchpriority' => 'high')); ?>
                    </figure>
                <?php endif; ?>

                <section class="zdk-pf-grid">
                    <?php if ($problem !== '') : ?>
                        <section class="zdk-pf-card">
                            <h2>課題</h2>
                            <p><?php echo nl2br(esc_html($problem)); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if ($solution !== '') : ?>
                        <section class="zdk-pf-card">
                            <h2>対応</h2>
                            <p><?php echo nl2br(esc_html($solution)); ?></p>
                        </section>
                    <?php endif; ?>

                    <?php if ($result !== '') : ?>
                        <section class="zdk-pf-card">
                            <h2>成果</h2>
                            <p><?php echo nl2br(esc_html($result)); ?></p>
                        </section>
                    <?php endif; ?>
                </section>

                <section class="zdk-pf-content">
                    <?php the_content(); ?>
                </section>
            </article>
        </div>
        <?php
    endwhile;
endif;

ob_start();
get_footer();
$footer_html = ob_get_clean();
$footer_html = preg_replace(
    '#<script[^>]*pagead2\.googlesyndication\.com/pagead/js/adsbygoogle\.js[^>]*>\s*</script>\s*#is',
    '',
    $footer_html
);
echo $footer_html;
