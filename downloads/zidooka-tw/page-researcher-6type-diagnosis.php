<?php
/**
 * Template Name: Researcher 6 Type Diagnosis
 * Description: ポートフォリオ用「研究者6タイプ診断」ページ（独立表示）。
 */

if (!defined('ABSPATH')) {
    exit;
}

$asset_base = trailingslashit(get_stylesheet_directory_uri()) . 'assets/tools/researcher-6type';
$ga4_id = '';
if (defined('GA_MEASUREMENT_ID')) {
    $ga4_id = constant('GA_MEASUREMENT_ID');
}
$ga4_id = apply_filters('zidooka_ga4_id', $ga4_id);
if (!$ga4_id) {
    $ga4_id = 'G-VNF3D5QY6E';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>研究者6タイプ診断 | Zidooka Portfolio Sample</title>
    <meta name="description" content="研究者6タイプ診断。8つの質問で研究スタイルを可視化する、Zidookaのポートフォリオ用サンプル。">
    <?php if (!empty($ga4_id)) : ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', <?php echo wp_json_encode($ga4_id); ?>);
    </script>
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo esc_url($asset_base . '/diagnosis.css?v=20260219c'); ?>">
</head>
<body class="zdk-r6d-body">
<div class="zdk-r6d-page">
    <script>
        window.ZDK_R6D_IMAGE_BASE = <?php echo wp_json_encode($asset_base . '/images'); ?>;
    </script>

    <main class="zdk-r6d-app" aria-label="研究者6タイプ診断">
        <section id="r6d-start" class="zdk-r6d-screen">
            <p class="zdk-r6d-kicker">Zidooka Portfolio Sample</p>
            <h1 class="zdk-r6d-title">研究者6タイプ診断</h1>
            <p class="zdk-r6d-lead">
                8つの質問で、あなたの研究スタイルを可視化します。<br>
                組織づくり・採用・共同研究の会話起点に使える診断です。
            </p>

            <div id="r6d-type-preview" class="zdk-r6d-type-preview"></div>

            <button id="r6d-start-btn" class="zdk-r6d-primary-btn" type="button">診断をはじめる</button>
            <p class="zdk-r6d-note">所要時間: 約1分</p>
        </section>

        <section id="r6d-quiz" class="zdk-r6d-screen zdk-r6d-hidden" aria-live="polite">
            <header class="zdk-r6d-quiz-header">
                <p class="zdk-r6d-progress-text"><span id="r6d-current">1</span> / <span id="r6d-total">8</span></p>
                <div class="zdk-r6d-progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div id="r6d-progress-bar" class="zdk-r6d-progress-bar"></div>
                </div>
            </header>

            <h2 id="r6d-question" class="zdk-r6d-question"></h2>
            <div id="r6d-answers" class="zdk-r6d-answers"></div>
        </section>

        <section id="r6d-loading" class="zdk-r6d-screen zdk-r6d-hidden" aria-live="polite">
            <div class="zdk-r6d-spinner" aria-hidden="true"></div>
            <p>分析中...</p>
        </section>

        <section id="r6d-result" class="zdk-r6d-screen zdk-r6d-hidden">
            <p class="zdk-r6d-result-kicker">Your Researcher Type</p>
            <h2 id="r6d-result-name" class="zdk-r6d-result-name"></h2>
            <p id="r6d-result-catch" class="zdk-r6d-result-catch"></p>

            <figure id="r6d-result-figure" class="zdk-r6d-result-figure zdk-r6d-hidden">
                <img id="r6d-result-image" src="" alt="" loading="lazy">
            </figure>

            <div class="zdk-r6d-result-meta">
                <span id="r6d-result-icon" class="zdk-r6d-result-icon" aria-hidden="true"></span>
                <div id="r6d-tags" class="zdk-r6d-tags"></div>
            </div>

            <dl class="zdk-r6d-result-list">
                <dt>特徴</dt>
                <dd id="r6d-feature"></dd>
                <dt>強み</dt>
                <dd id="r6d-strength"></dd>
                <dt>主な分野</dt>
                <dd id="r6d-fields"></dd>
            </dl>

            <button id="r6d-restart-btn" class="zdk-r6d-secondary-btn" type="button">もう一度診断する</button>
        </section>
    </main>
</div>
<script src="<?php echo esc_url($asset_base . '/diagnosis.js?v=20260219b'); ?>"></script>
</body>
</html>
