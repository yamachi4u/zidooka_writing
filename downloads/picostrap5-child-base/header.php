<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
<?php if ( ! has_tag( 'affiliate' ) ) : ?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5002038850592836"
     crossorigin="anonymous"></script>
<?php endif; ?>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://cdn.tailwindcss.com"></script>
	<?php
	$meta_desc = '';
	if (is_singular()) {
		$excerpt = trim(wp_strip_all_tags(get_the_excerpt()));
		if (!empty($excerpt)) {
			$meta_desc = $excerpt;
		}
	}
	if (empty($meta_desc) && (is_home() || is_front_page())) {
		$meta_desc = get_bloginfo('description');
	}
	if (!empty($meta_desc)) {
		echo '<meta name="description" content="' . esc_attr($meta_desc) . '">';
	}
	if (function_exists('wp_get_canonical_url')) {
		$canonical = wp_get_canonical_url();
		if (!empty($canonical)) {
			echo '<link rel="canonical" href="' . esc_url($canonical) . '">';
		}
	}
	if (function_exists('wp_get_document_title') && !current_theme_supports('title-tag')) {
		echo '<title>' . esc_html(wp_get_document_title()) . '</title>';
	}
	?>

	<!-- wp_head -->
	<?php wp_head(); ?>
	<!-- /wp_head -->
	<style>
	.zdk-site-header {
		border-bottom: 1px solid #e5e7eb;
		background: #ffffff;
	}
	.zdk-header-inner {
		max-width: 1200px;
		margin: 0 auto;
		padding: 22px 20px;
	}
	.zdk-brand-link {
		text-decoration: none;
		color: #0f172a;
		font-weight: 700;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		font-size: 1.05rem;
	}
	</style>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php 
    // Custom filter to check if header elements should be displayed. To disable, use: add_filter('picostrap_enable_header_elements', '__return_false');
    if (apply_filters('picostrap_enable_header_elements', true)) :
	?>
	<header class="zdk-site-header">
		<div class="zdk-header-inner">
			<a class="zdk-brand-link" href="<?php echo esc_url(home_url('/')); ?>">Zidooka</a>
		</div>
	</header>
	<?php endif; ?>

	<main id='theme-main'>
