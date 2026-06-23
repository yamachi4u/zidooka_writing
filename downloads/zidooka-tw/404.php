<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package zidooka
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<div class="max-w-2xl mx-auto px-4 py-8">
				<header class="page-header text-center mb-8">
					<h1 class="page-title text-4xl font-bold text-gray-900 mb-2">404</h1>
					<p class="text-gray-600"><?php esc_html_e( 'This page could not be found. It might have been removed or renamed, or it may never have existed.', 'zidooka-tw' ); ?></p>
				</header>

				<div class="page-content mb-8">
					<?php get_search_form(); ?>
				</div>

				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<div class="bg-white rounded-xl border border-gray-200 p-5">
						<h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-3"><?php esc_html_e( 'Recent Posts', 'zidooka-tw' ); ?></h2>
						<ul class="space-y-2">
							<?php
							$recent = new WP_Query(['posts_per_page' => 5, 'no_found_rows' => true]);
							while ($recent->have_posts()) : $recent->the_post(); ?>
								<li><a href="<?php the_permalink(); ?>" class="text-sm text-gray-800 hover:text-indigo-600 transition-colors no-underline"><?php the_title(); ?></a></li>
							<?php endwhile; wp_reset_postdata(); ?>
						</ul>
					</div>
					<div class="bg-white rounded-xl border border-gray-200 p-5">
						<h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-3"><?php esc_html_e( 'Categories', 'zidooka-tw' ); ?></h2>
						<div class="flex flex-wrap gap-2">
							<?php
							$cats = get_categories(['orderby' => 'count', 'order' => 'DESC', 'number' => 10]);
							foreach ($cats as $cat) : ?>
								<a href="<?php echo get_category_link($cat->term_id); ?>" class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition-colors no-underline">
									<?php echo esc_html($cat->name); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
if (function_exists('gtag')) : ?>
<script>
gtag('event', 'zdk_404', {
  referrer: document.referrer || '(direct)',
  requested_url: location.pathname + location.search
});
</script>
<?php endif; ?>
<?php
get_footer();
