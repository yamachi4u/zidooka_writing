<?php
/**
 * Archive template for GAS distribution posts.
 *
 * @package zidooka-tw
 */

get_header();
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <?php
    $form_base_url = 'https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform';
    $form_params = [
      'usp' => 'pp_url',
      'entry.2087005549' => "タイトル: GAS配布一覧\nURL: " . (is_string(get_post_type_archive_link('gas_script')) ? get_post_type_archive_link('gas_script') : home_url('/')),
    ];
    $consult_url = $form_base_url . '?' . http_build_query($form_params);
  ?>

  <header class="mb-8">
    <div class="text-xs text-slate-500">
      <a class="hover:underline" href="<?php echo esc_url(home_url('/')); ?>">ホーム</a>
      <span class="mx-1">/</span>
      <span>GAS配布</span>
    </div>
    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 leading-snug mt-2">GASスクリプト配布</h1>
    <p class="text-sm text-slate-600 leading-relaxed mt-3">
      コピペで動くサンプルから、`appsscript.json` を含む複数ファイル構成まで配布します。業務導入・カスタマイズも対応できます。
    </p>
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
      <a href="<?php echo esc_url($consult_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors no-underline">
        相談する（日本語）
      </a>
      <a href="<?php echo esc_url(home_url('/lp2025')); ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-800 transition-colors no-underline">
        サービス詳細を見る
      </a>
    </div>
  </header>

  <?php if (have_posts()) : ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php while (have_posts()) : the_post(); ?>
        <?php
          $post_id = get_the_ID();
          $version_key = defined('ZDK_GAS_META_VERSION') ? ZDK_GAS_META_VERSION : '_zdk_gas_version';
          $bundle_key = defined('ZDK_GAS_META_BUNDLE') ? ZDK_GAS_META_BUNDLE : '_zdk_gas_bundle';
          $version = (string) get_post_meta($post_id, $version_key, true);
          $bundle = (string) get_post_meta($post_id, $bundle_key, true);
          $bundle_files = function_exists('zdk_gas_parse_bundle') ? zdk_gas_parse_bundle($bundle) : [];
          $is_zip = !empty($bundle_files);
          $file_count = $is_zip ? count($bundle_files) : 1;
          $download_url = function_exists('zdk_gas_get_download_url') ? zdk_gas_get_download_url($post_id) : '';
          $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 28, '...');
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('group rounded-2xl border border-slate-200 bg-white p-5 flex flex-col'); ?>>
          <div class="flex items-start justify-between gap-3">
            <h2 class="text-lg font-semibold text-slate-900 leading-snug">
              <a class="hover:underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <span class="shrink-0 px-2 py-0.5 rounded-full text-xs font-semibold <?php echo $is_zip ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-700'; ?>">
              <?php echo $is_zip ? 'ZIP' : '.gs'; ?>
            </span>
          </div>

          <div class="mt-2 text-xs text-slate-500 flex flex-wrap gap-2">
            <span>更新: <?php echo esc_html(get_the_modified_time('Y-m-d')); ?></span>
            <?php if ($version !== '') : ?>
              <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono">v<?php echo esc_html($version); ?></span>
            <?php endif; ?>
            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700"><?php echo esc_html($file_count); ?>ファイル</span>
          </div>

          <?php if ($excerpt) : ?>
            <p class="text-sm text-slate-600 leading-relaxed mt-3"><?php echo esc_html($excerpt); ?></p>
          <?php endif; ?>

          <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
            <?php if ($download_url) : ?>
              <a href="<?php echo esc_url($download_url); ?>" class="inline-flex items-center justify-center px-3 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors no-underline">
                ダウンロード
              </a>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center px-3 py-2 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition-colors no-underline">
              詳細を見る
            </a>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="mt-8">
      <?php the_posts_navigation(); ?>
    </div>
  <?php else : ?>
    <p class="text-slate-600">まだ配布スクリプトがありません。</p>
  <?php endif; ?>

  <section class="mt-10">
    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 sm:p-6">
      <p class="text-base font-semibold text-slate-900">業務導入向けに、落とし穴まで含めて整えられます</p>
      <p class="text-sm text-slate-600 leading-relaxed mt-2">
        「権限設計」「エラー復旧」「ログ/監視」「運用ドキュメント」「引き継ぎ」まで含めて、実運用に耐える形に仕上げます。
      </p>
      <div class="mt-4">
        <a href="<?php echo esc_url($consult_url); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-slate-900 text-white hover:bg-slate-800 transition-colors no-underline">
          相談する（日本語）
        </a>
      </div>
    </div>
  </section>
</div>

<?php get_footer(); ?>
