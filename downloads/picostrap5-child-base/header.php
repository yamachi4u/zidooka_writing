<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

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
