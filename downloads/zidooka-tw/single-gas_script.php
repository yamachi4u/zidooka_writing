<?php
/**
 * Single template for GAS distribution posts.
 *
 * @package zidooka-tw
 */

get_header();
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php
      $post_id = get_the_ID();
      $version_key = defined('ZDK_GAS_META_VERSION') ? ZDK_GAS_META_VERSION : '_zdk_gas_version';
      $version = (string) get_post_meta($post_id, $version_key, true);
      $download_url = function_exists('zdk_gas_get_download_url') ? zdk_gas_get_download_url($post_id) : '';
      $dist_files = function_exists('zdk_gas_get_dist_files') ? zdk_gas_get_dist_files($post_id) : [];
      $has_dist = !empty($dist_files);
      $updated = get_the_modified_time('Y-m-d');
      $cats = get_the_category($post_id);
      $tags = get_the_tags($post_id);
      $archive_url = get_post_type_archive_link('gas_script');
      $form_base_url = 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform';
      $form_params = [
        'usp' => 'pp_url',
        'entry.2087005549' => "タイトル: " . get_the_title() . "\nURL: " . get_permalink(),
      ];
      $consult_url = $form_base_url . '?' . http_build_query($form_params);
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
      <header class="mb-6">
        <div class="text-xs text-slate-500">
          <a class="hover:underline" href="<?php echo esc_url(home_url('/')); ?>">ホーム</a>
          <span class="mx-1">/</span>
          <?php if ($archive_url) : ?>
            <a class="hover:underline" href="<?php echo esc_url($archive_url); ?>">GAS配布</a>
          <?php else : ?>
            <span>GAS配布</span>
          <?php endif; ?>
        </div>

        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 leading-snug mt-2"><?php the_title(); ?></h1>

        <?php if (has_excerpt()) : ?>
          <p class="text-base text-slate-600 leading-relaxed mt-3"><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>

        <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
          <span>更新: <?php echo esc_html($updated); ?></span>
          <?php if ($version !== '') : ?>
            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono">v<?php echo esc_html($version); ?></span>
          <?php endif; ?>
          <?php if ($cats) : foreach ($cats as $c) : ?>
            <a class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors no-underline" href="<?php echo esc_url(get_category_link($c->term_id)); ?>"><?php echo esc_html($c->name); ?></a>
          <?php endforeach; endif; ?>
          <?php if ($tags) : foreach ($tags as $t) : ?>
            <a class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors no-underline" href="<?php echo esc_url(get_tag_link($t->term_id)); ?>">#<?php echo esc_html($t->name); ?></a>
          <?php endforeach; endif; ?>
        </div>

        <div class="mt-5 flex flex-col sm:flex-row gap-3">
          <?php if ($download_url && $has_dist) : ?>
            <a href="<?php echo esc_url($download_url); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors no-underline">
              コードをダウンロード
            </a>
          <?php endif; ?>
          <a href="<?php echo esc_url($consult_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors no-underline">
            カスタマイズ相談（日本語）
          </a>
        </div>
      </header>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        <div class="zenn-content">
          <?php the_content(); ?>
        </div>
      </div>

      <?php if ($has_dist) : ?>
        <section class="mt-8">
          <h2 class="text-lg font-semibold text-slate-900 mb-3">配布コード</h2>
          <?php echo do_shortcode('[zdk_gas_code]'); ?>
          <div class="mt-4">
            <?php echo do_shortcode('[zdk_gas_download label="ダウンロード"]'); ?>
          </div>
          <p class="text-xs text-slate-500 mt-3">
            ※ サンプル提供です。業務導入向けに「権限設計」「ログ/監視」「エラー復旧」「運用ドキュメント」まで含めて整える場合は相談ください。
          </p>
        </section>
      <?php endif; ?>

      <?php
        // Category CTA (reuse site-wide map).
        $cta = function_exists('zidooka_get_cta_for_post') ? zidooka_get_cta_for_post($post_id, false) : null;
        if ($cta && is_array($cta)) :
          $heading = isset($cta['heading']) ? (string) $cta['heading'] : '';
          $sub = isset($cta['sub']) ? (string) $cta['sub'] : '';
          $note = isset($cta['note']) ? (string) $cta['note'] : '';
          $primary = isset($cta['primary']) && is_array($cta['primary']) ? $cta['primary'] : [];
          $secondary = isset($cta['secondary']) && is_array($cta['secondary']) ? $cta['secondary'] : [];
          $primary_url = isset($primary['url']) ? (string) $primary['url'] : '';
          $secondary_url = isset($secondary['url']) ? (string) $secondary['url'] : '';
          $primary_target = isset($primary['target']) ? (string) $primary['target'] : '';
          $secondary_target = isset($secondary['target']) ? (string) $secondary['target'] : '';
          $primary_rel = $primary_target === '_blank' ? 'noopener' : '';
          $secondary_rel = $secondary_target === '_blank' ? 'noopener' : '';
          $primary_label = isset($primary['label']) ? (string) $primary['label'] : '';
          $secondary_label = isset($secondary['label']) ? (string) $secondary['label'] : '';
      ?>
        <section class="mt-8">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
            <?php if ($heading !== '') : ?>
              <p class="text-base font-semibold text-slate-900"><?php echo esc_html($heading); ?></p>
            <?php endif; ?>
            <?php if ($sub !== '') : ?>
              <p class="text-sm text-slate-600 leading-relaxed mt-2"><?php echo esc_html($sub); ?></p>
            <?php endif; ?>
            <?php if ($note !== '') : ?>
              <p class="text-xs text-slate-500 mt-2"><?php echo esc_html($note); ?></p>
            <?php endif; ?>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
              <?php if ($primary_url !== '' && $primary_label !== '') : ?>
                <a href="<?php echo esc_url($primary_url); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors no-underline" <?php if ($primary_target !== '') echo 'target="' . esc_attr($primary_target) . '"'; ?> <?php if ($primary_rel !== '') echo 'rel="' . esc_attr($primary_rel) . '"'; ?>>
                  <?php echo esc_html($primary_label); ?>
                </a>
              <?php endif; ?>
              <?php if ($secondary_url !== '' && $secondary_label !== '') : ?>
                <a href="<?php echo esc_url($secondary_url); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors no-underline" <?php if ($secondary_target !== '') echo 'target="' . esc_attr($secondary_target) . '"'; ?> <?php if ($secondary_rel !== '') echo 'rel="' . esc_attr($secondary_rel) . '"'; ?>>
                  <?php echo esc_html($secondary_label); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </article>
  <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
