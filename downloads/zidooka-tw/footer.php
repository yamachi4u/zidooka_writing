<?php // Tailwind footer
?>
</div>
<footer class="bg-gray-50 border-t mt-12">
  <div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row items-center justify-between gap-3">
      <small class="text-gray-500">
        &copy; <?php echo esc_html( date_i18n('Y') ); ?> <?php echo esc_html( get_bloginfo('name') ); ?>
      </small>
      <nav class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
        <?php
        $is_english_content = false;
        if ( is_singular() ) {
            $is_english_content = function_exists('zenn_is_english_only') && zenn_is_english_only(get_the_title());
        } elseif ( is_category() || is_tag() ) {
            $term = get_queried_object();
            if ( $term && !empty($term->name) ) {
                $is_english_content = !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FFF}]/u', $term->name);
            }
        } elseif ( is_search() ) {
            $q = get_search_query();
            $is_english_content = $q ? !preg_match('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FFF}]/u', $q) : false;
        }
        ?>
        <?php if ( $is_english_content ) : ?>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="<?php echo esc_url( function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : '' ); ?>">Privacy Policy</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://www.zidooka.com/jigyo">Contact & Company Info</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://www.zidooka.com/">Zidooka Home</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://tools.zidooka.com">Tools</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://tools.zidooka.com/jp/calendar">Lucky-day Calendar</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="<?php echo esc_url( ( $p = (int) get_option('page_for_posts') ) ? get_permalink( $p ) : home_url('/') ); ?>">View All Articles</a>
        <?php else : ?>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="<?php echo esc_url( function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : '' ); ?>">プライバシーポリシー / Privacy Policy</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://www.zidooka.com/jigyo">お問い合わせ・事業/会社情報 / Contact & Company Info</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://www.zidooka.com/">Zidookaトップ</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://tools.zidooka.com">便利ツール / Tools</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="https://tools.zidooka.com/jp/calendar">吉日カレンダー</a>
          <a class="px-2 text-gray-500 hover:text-gray-700" href="<?php echo esc_url( ( $p = (int) get_option('page_for_posts') ) ? get_permalink( $p ) : home_url('/') ); ?>">全記事一覧を見る</a>
        <?php endif; ?>
      </nav>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
