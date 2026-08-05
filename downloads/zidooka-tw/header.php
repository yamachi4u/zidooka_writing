<?php
// Exit if accessed directly.
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#4f46e5" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
	<!-- wp_head -->
	<?php wp_head(); ?>
	<!-- /wp_head -->
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<a href="#theme-main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-md">コンテンツにスキップ</a>

	<?php 
    // Custom filter to check if header elements should be displayed. To disable, use: add_filter('picostrap_enable_header_elements', '__return_false');
    if (apply_filters('picostrap_enable_header_elements', true)) :
	?>
	<header class="zdk-site-header">
		<div class="zdk-header-inner">
			<a class="zdk-brand-link" href="<?php echo esc_url(home_url('/')); ?>">Zidooka</a>
		</div>
	</header>
	<script>(function(){var h=document.querySelector('.zdk-site-header');if(!h)return;var t=function(){h.classList.toggle('zdk-header-scrolled',window.scrollY>10)};t();window.addEventListener('scroll',t,{passive:true})})();</script>
	<?php endif; ?>
	<?php echo zidooka_render_ad('campaign_top'); ?>
	<div id="theme-main">
