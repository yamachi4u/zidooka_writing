</main>
<?php /* ???????????Bootstrap???? */ ?>
<footer class="bg-light border-top mt-5">
  <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
      <small class="text-muted mb-0">
        &copy; <?php echo esc_html( date_i18n('Y') ); ?> 
        <?php echo esc_html( get_bloginfo('name') ); ?>
      </small>

      <nav class="nav">
        <?php
        $is_english_content = false;
        if ( is_single() ) {
            $post_title = get_the_title();
            // Check if title contains Japanese characters (Hiragana, Katakana, Kanji). If not, assume English.
            if ( !preg_match('/[ぁ-んァ-ヶ一-龠]/u', $post_title) ) {
                $is_english_content = true;
            }
        }
        ?>
        <?php if ( $is_english_content ) : ?>
            <a class="nav-link px-2 text-muted" href="https://www.zidooka.com/%e3%83%97%e3%83%a9%e3%82%a4%e3%83%90%e3%82%b7%e3%83%bc%e3%83%9d%e3%83%aa%e3%82%b7%e3%83%bc">Privacy Policy</a>
            <a class="nav-link px-2 text-muted" href="https://www.zidooka.com/jigyo">Contact & Company Info</a>
            <a class="nav-link px-2 text-muted" href="/page/2/">View All Articles</a>
        <?php else : ?>
            <a class="nav-link px-2 text-muted" href="https://www.zidooka.com/%e3%83%97%e3%83%a9%e3%82%a4%e3%83%90%e3%82%b7%e3%83%bc%e3%83%9d%e3%83%aa%e3%82%b7%e3%83%bc">プライバシーポリシー / Privacy Policy</a>
            <a class="nav-link px-2 text-muted" href="https://www.zidooka.com/jigyo">お問合せ／事業概要・連絡先 / Contact & Company Info</a>
            <a class="nav-link px-2 text-muted" href="/page/2/">全記事一覧を見る</a>
        <?php endif; ?>
      </nav>
    </div>
  </div>
</footer>
<?php /* ?????? */ ?>
	<?php 

    // Custom filter to check if footer elements should be displayed. To disable, use: add_filter('picostrap_enable_footer_elements', '__return_false');
    if (apply_filters('picostrap_enable_footer_elements', true)):
    
        //check if LC option is set to "Handle Footer"   
        if (!function_exists("lc_custom_footer")) {
            
            //use the built-in theme footer elements 
            get_template_part( 'partials/footer', 'elements' );
            
        } else {
            //use the LiveCanvas Custom Footer
            lc_custom_footer(); 
        }
        
    endif;
    ?>

	<?php wp_footer(); ?>

	</body>
</html>

