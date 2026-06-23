<?php
/**
 * Template Name: LP - GAS業務発注
 * Description: 「GAS 業務 発注」検索を意識したGoogle Apps Script発注向けLP（Tailwind CSS版）
 */
$zdk_current_url = function_exists('get_permalink') ? get_permalink() : '';

// Disable AdSense only on this LP template.
add_filter('zidooka_adsense_client', function ($client) {
  if (is_page_template('template-lp-gas-business-order.php')) {
    return '';
  }
  return $client;
}, 999);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAS 業務 発注ならZIDOOKA | Google Apps Script外注</title>
  <meta name="description" content="GAS 業務 発注に特化した外注LP。Google Apps Scriptの要件整理、見積、実装、運用保守まで一気通貫で対応します。">
  <meta name="keywords" content="GAS 業務 発注, Google Apps Script 外注, GAS 開発 依頼, 業務自動化">
  <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
  <meta property="og:type" content="website">
  <meta property="og:title" content="GAS 業務 発注ならZIDOOKA | Google Apps Script外注">
  <meta property="og:description" content="GAS業務発注に特化。要件整理から納品・運用まで一気通貫で対応。">
  <meta property="og:locale" content="ja_JP">
  <?php if (!empty($zdk_current_url)) : ?>
  <link rel="canonical" href="<?php echo esc_url($zdk_current_url); ?>">
  <meta property="og:url" content="<?php echo esc_url($zdk_current_url); ?>">
  <?php endif; ?>

  <?php $ga4_id = function_exists('zidooka_ga4_id') ? zidooka_ga4_id() : ''; ?>
  <?php if (!empty($ga4_id)) : ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga4_id); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js($ga4_id); ?>');
  </script>
  <?php endif; ?>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "要件が未整理でも発注できますか？",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "はい、可能です。業務ヒアリングから開始し、要件定義まで整理してから見積を確定します。"
        }
      },
      {
        "@type": "Question",
        "name": "既存GASの改修だけでも依頼できますか？",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "はい。既存コードの調査、改善方針の提案、段階的な改修に対応しています。"
        }
      },
      {
        "@type": "Question",
        "name": "どのような納品物がありますか？",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "GASコード、導入手順、運用手順、設定値一覧、保守時の注意点をまとめて納品します。"
        }
      }
    ]
  }
  </script>

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "ZIDOOKA",
    "url": "<?php echo esc_url(!empty($zdk_current_url) ? $zdk_current_url : home_url('/')); ?>",
    "description": "GAS業務発注に特化したGoogle Apps Script開発サービス。要件整理から納品・運用まで一気通貫で対応。",
    "serviceType": "Google Apps Script 業務自動化開発",
    "areaServed": "JP"
  }
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: {
            50: '#eff6ff',
            100: '#dbeafe',
            200: '#bfdbfe',
            300: '#93c5fd',
            400: '#60a5fa',
            500: '#3b82f6',
            600: '#2563eb',
            700: '#1d4ed8',
            800: '#1e40af',
            900: '#1e3a8a',
            950: '#172554'
          },
          accent: {
            400: '#fbbf24',
            500: '#f59e0b',
            600: '#d97706'
          },
          success: {
            500: '#10b981',
            600: '#059669'
          }
        },
        fontFamily: {
          sans: ['Noto Sans JP', 'Poppins', 'system-ui', 'sans-serif']
        },
        boxShadow: {
          'brand': '0 16px 40px -16px rgba(37, 99, 235, 0.35)'
        }
      }
    }
  }
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&family=Poppins:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <?php wp_head(); ?>

  <style>
    html { scroll-behavior: smooth; }
    .glass {
      background: rgba(255, 255, 255, 0.82);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(148, 163, 184, 0.25);
    }
    .hero-grid {
      background-image:
        radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.18), transparent 40%),
        radial-gradient(circle at 80% 30%, rgba(16, 185, 129, 0.12), transparent 45%),
        linear-gradient(180deg, #0f172a 0%, #172554 45%, #1e3a8a 100%);
    }
    .grid-overlay {
      background-image: linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .hover-lift {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hover-lift:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 44px -24px rgba(15, 23, 42, 0.32);
    }
    .nav-hidden { transform: translateY(-100%); }
    .nav-visible { transform: translateY(0); }
    .floating-cta { display: none; }
    .check-list li {
      position: relative;
      padding-left: 1.6rem;
    }
    .check-list li::before {
      content: "";
      width: 0.7rem;
      height: 0.7rem;
      border-radius: 9999px;
      background: #10b981;
      position: absolute;
      left: 0;
      top: 0.55rem;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .zdk-cf7 .wpcf7-form {
      display: grid;
      gap: 0.85rem;
    }
    .zdk-cf7 input[type="text"],
    .zdk-cf7 input[type="email"],
    .zdk-cf7 input[type="tel"],
    .zdk-cf7 textarea {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 0.8rem;
      padding: 0.75rem 0.9rem;
      font-size: 0.95rem;
      line-height: 1.5;
      color: #0f172a;
      background: #ffffff;
    }
    .zdk-cf7 textarea {
      min-height: 8rem;
      resize: vertical;
    }
    .zdk-cf7 input[type="submit"] {
      border: 0;
      border-radius: 9999px;
      background: #2563eb;
      color: #fff;
      font-weight: 700;
      padding: 0.85rem 1.2rem;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .zdk-cf7 input[type="submit"]:hover {
      background: #1d4ed8;
    }
    .zdk-cf7 .wpcf7-spinner {
      margin: 0.4rem 0 0;
    }
    .zdk-cf7 .wpcf7-not-valid-tip {
      color: #dc2626;
      font-size: 0.82rem;
    }
    .zdk-cf7 .wpcf7-response-output {
      margin: 0.5rem 0 0;
      border-radius: 0.75rem;
      font-size: 0.9rem;
      padding: 0.75rem 0.9rem;
    }
    @media (max-width: 768px) {
      .floating-cta {
        display: block;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 70;
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        text-align: center;
        font-weight: 700;
        padding: 14px 16px;
        box-shadow: 0 -4px 24px rgba(37, 99, 235, 0.45);
      }
      .with-floating-space { padding-bottom: 84px; }
    }
  </style>
</head>
<body <?php body_class('font-sans text-slate-800 antialiased bg-slate-50 with-floating-space'); ?>>
<?php wp_body_open(); ?>

  <a href="#contact" class="floating-cta" data-ga-event="lp_cta_click" data-ga-location="floating_cta" data-ga-label="mobile_contact">
    <i class="fas fa-paper-plane mr-2"></i>無料で相談する（最短当日返信）
  </a>

  <header id="mainNav" class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 nav-hidden">
    <div class="max-w-7xl mx-auto px-4 pt-4">
      <div class="glass rounded-2xl px-5 py-3.5 shadow-lg">
        <div class="flex items-center justify-between gap-4">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="text-xl md:text-2xl font-black text-primary-700 tracking-wide">ZIDOOKA!</a>
          <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-600">
            <a href="#services" class="hover:text-primary-700 transition">対応範囲</a>
            <a href="#workflow" class="hover:text-primary-700 transition">発注フロー</a>
            <a href="#pricing" class="hover:text-primary-700 transition">料金目安</a>
            <a href="#faq" class="hover:text-primary-700 transition">FAQ</a>
            <a href="#contact" class="bg-primary-600 text-white px-5 py-2.5 rounded-full hover:bg-primary-700 transition" data-ga-event="lp_cta_click" data-ga-location="header" data-ga-label="header_contact">
              無料相談
            </a>
          </nav>
          <button id="mobileMenuBtn" class="lg:hidden text-slate-700 text-2xl" aria-label="メニューを開く">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </header>

  <div id="mobileMenu" class="fixed inset-0 z-50 bg-slate-950/90 hidden">
    <div class="absolute top-0 right-0 h-full w-72 bg-white p-6 shadow-2xl">
      <div class="flex items-center justify-between mb-8">
        <span class="text-xl font-black text-primary-700">MENU</span>
        <button id="closeMobileMenu" class="text-2xl text-slate-600" aria-label="メニューを閉じる">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="flex flex-col gap-2 text-slate-700">
        <a href="#services" class="py-3 border-b border-slate-100">対応範囲</a>
        <a href="#workflow" class="py-3 border-b border-slate-100">発注フロー</a>
        <a href="#pricing" class="py-3 border-b border-slate-100">料金目安</a>
        <a href="#faq" class="py-3 border-b border-slate-100">FAQ</a>
        <a href="#contact" class="mt-5 text-center bg-primary-600 text-white rounded-xl py-3 font-semibold" data-ga-event="lp_cta_click" data-ga-location="mobile_menu" data-ga-label="mobile_menu_contact">無料相談する</a>
      </nav>
    </div>
  </div>

  <section class="hero-grid relative overflow-hidden pt-36 pb-24 lg:pt-40 lg:pb-32">
    <div class="grid-overlay absolute inset-0 opacity-35"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-white/90 mb-6">
            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-success-500"></span>
            GAS業務発注専用ランディングページ
          </div>
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
            Google Apps Scriptの発注を、<br>
            <span class="text-accent-400">要件整理から運用</span>まで。
          </h1>
          <p class="text-lg md:text-xl text-white/80 leading-relaxed mb-8 max-w-2xl">
            「何を自動化すべきか分からない」段階から相談できます。業務ヒアリング、仕様化、実装、テスト、引き継ぎまで一気通貫で対応します。
          </p>
          <p class="text-sm md:text-base text-white/70 mb-8 max-w-2xl">
            「GAS 業務 発注」で情報収集中の方に向けて、判断しやすいように対応範囲・料金目安・進行手順をこのページで明確に公開しています。
          </p>
          <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a href="#contact" class="inline-flex items-center justify-center gap-2 bg-accent-500 text-white px-7 py-4 rounded-full font-bold hover:bg-accent-600 transition shadow-brand" data-ga-event="lp_cta_click" data-ga-location="hero" data-ga-label="hero_contact_primary">
              無料で相談する
              <i class="fas fa-arrow-right"></i>
            </a>
            <a href="#workflow" class="inline-flex items-center justify-center gap-2 border border-white/35 text-white px-7 py-4 rounded-full font-semibold hover:bg-white/10 transition" data-ga-event="lp_cta_click" data-ga-location="hero" data-ga-label="hero_flow_secondary">
              発注の流れを見る
              <i class="fas fa-route"></i>
            </a>
          </div>
          <ul class="grid sm:grid-cols-3 gap-3 text-sm text-white/85">
            <li class="rounded-lg border border-white/15 bg-white/10 px-4 py-3">最短2営業日で初版提出</li>
            <li class="rounded-lg border border-white/15 bg-white/10 px-4 py-3">小規模案件は5万円から</li>
            <li class="rounded-lg border border-white/15 bg-white/10 px-4 py-3">請求書払い・CW契約対応</li>
          </ul>
        </div>
        <div>
          <div class="bg-white rounded-3xl p-7 md:p-9 shadow-2xl border border-slate-100">
            <h2 class="text-2xl font-black text-slate-900 mb-5">発注前によくある悩み</h2>
            <ul class="space-y-4 check-list text-slate-700">
              <li>GASで実現できる範囲が分からず、要件が固まらない。</li>
              <li>既存スプレッドシート運用を止めずに自動化したい。</li>
              <li>納品後に保守できるよう、引き継ぎ資料が欲しい。</li>
              <li>権限設定やセキュリティ面で不安がある。</li>
            </ul>
            <div class="mt-7 rounded-2xl bg-primary-50 border border-primary-100 p-5">
              <p class="text-primary-900 font-semibold leading-relaxed">
                ZIDOOKAでは「技術的にできるか」だけでなく、運用フローに乗る形で設計します。
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 bg-white border-b border-slate-100">
    <div class="max-w-5xl mx-auto px-6">
      <div class="rounded-3xl border border-primary-100 bg-primary-50 p-7 md:p-9">
        <h2 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">「GAS 業務 発注」で探している方へ</h2>
        <p class="text-slate-700 leading-relaxed mb-4">
          GASの外注で失敗しやすいのは、実装前に「誰が・いつ・どのデータを使うか」が整理されないケースです。ZIDOOKAでは、コード実装だけではなく業務フローに沿った要件定義を重視します。
        </p>
        <p class="text-slate-700 leading-relaxed">
          そのため、単なる開発委託ではなく、GAS業務発注の伴走パートナーとして、導入後に運用が回る状態までを成果物として提供します。
        </p>
      </div>
    </div>
  </section>

  <section class="py-16 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-5">
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 text-center hover-lift">
          <div class="text-3xl font-black text-primary-600 mb-1">50+</div>
          <p class="text-sm text-slate-600">取引実績</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 text-center hover-lift">
          <div class="text-3xl font-black text-primary-600 mb-1">100%</div>
          <p class="text-sm text-slate-600">契約完了率</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 text-center hover-lift">
          <div class="text-3xl font-black text-primary-600 mb-1">4.9 / 5</div>
          <p class="text-sm text-slate-600">平均評価</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 text-center hover-lift">
          <div class="text-3xl font-black text-primary-600 mb-1">10年+</div>
          <p class="text-sm text-slate-600">開発経験</p>
        </div>
      </div>
      <p class="text-xs text-slate-400 mt-4 text-right">※実績値はクラウドソーシング等での公開情報を基準に記載</p>
    </div>
  </section>

  <section id="services" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="max-w-3xl mb-14">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Services</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">GAS発注で任せられる範囲</h2>
        <p class="text-lg text-slate-600">単発のスクリプト実装だけでなく、要件整理や運用設計まで対応します。発注者側の工数が増えない進め方を重視します。</p>
      </div>

      <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
        <article class="bg-white rounded-2xl border border-slate-100 p-6 hover-lift">
          <div class="w-14 h-14 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center mb-5">
            <i class="fas fa-table text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">スプレッドシート業務自動化</h3>
          <ul class="space-y-2 text-sm text-slate-600">
            <li>・入力チェックと自動整形</li>
            <li>・定期レポート生成と配信</li>
            <li>・複数シート間の集計自動化</li>
          </ul>
        </article>

        <article class="bg-white rounded-2xl border border-slate-100 p-6 hover-lift">
          <div class="w-14 h-14 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center mb-5">
            <i class="fas fa-plug text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Googleサービス連携</h3>
          <ul class="space-y-2 text-sm text-slate-600">
            <li>・Gmail自動通知 / 返信</li>
            <li>・Driveファイル整理</li>
            <li>・Calendar / Form / Slack連携</li>
          </ul>
        </article>

        <article class="bg-white rounded-2xl border border-slate-100 p-6 hover-lift">
          <div class="w-14 h-14 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center mb-5">
            <i class="fas fa-window-maximize text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">GAS Webアプリ開発</h3>
          <ul class="space-y-2 text-sm text-slate-600">
            <li>・社内申請フォームの構築</li>
            <li>・権限付きの業務画面作成</li>
            <li>・実行ログと監視設計</li>
          </ul>
        </article>

        <article class="bg-white rounded-2xl border border-slate-100 p-6 hover-lift">
          <div class="w-14 h-14 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center mb-5">
            <i class="fas fa-life-ring text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">保守・改修・引き継ぎ</h3>
          <ul class="space-y-2 text-sm text-slate-600">
            <li>・既存GASの不具合調査</li>
            <li>・リファクタリングと速度改善</li>
            <li>・運用手順書の納品</li>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <section id="workflow" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Workflow</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">GAS発注の進め方</h2>
        <p class="text-lg text-slate-600">「相談だけ」から開始できます。ヒアリング時点で仕様が未確定でも問題ありません。</p>
      </div>

      <div class="grid lg:grid-cols-5 gap-5">
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 hover-lift">
          <div class="text-xs font-black tracking-widest text-primary-600 mb-3">STEP 01</div>
          <h3 class="font-bold text-slate-900 mb-2">課題ヒアリング</h3>
          <p class="text-sm text-slate-600">現行フロー、手作業時間、ボトルネックを整理します。</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 hover-lift">
          <div class="text-xs font-black tracking-widest text-primary-600 mb-3">STEP 02</div>
          <h3 class="font-bold text-slate-900 mb-2">要件・見積提示</h3>
          <p class="text-sm text-slate-600">実装範囲、納期、費用を明確化して共有します。</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 hover-lift">
          <div class="text-xs font-black tracking-widest text-primary-600 mb-3">STEP 03</div>
          <h3 class="font-bold text-slate-900 mb-2">実装・中間確認</h3>
          <p class="text-sm text-slate-600">途中版を確認いただき、仕様ズレを防ぎます。</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 hover-lift">
          <div class="text-xs font-black tracking-widest text-primary-600 mb-3">STEP 04</div>
          <h3 class="font-bold text-slate-900 mb-2">納品・運用説明</h3>
          <p class="text-sm text-slate-600">コードだけでなく設定手順と利用手順も引き継ぎます。</p>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-6 hover-lift">
          <div class="text-xs font-black tracking-widest text-primary-600 mb-3">STEP 05</div>
          <h3 class="font-bold text-slate-900 mb-2">保守・追加開発</h3>
          <p class="text-sm text-slate-600">運用後の改善要望や障害対応にも継続対応します。</p>
        </div>
      </div>

      <div class="mt-12 text-center">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-brand" data-ga-event="lp_cta_click" data-ga-location="workflow" data-ga-label="workflow_contact">
          発注相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <section id="pricing" class="py-24 bg-slate-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-35" style="background: radial-gradient(circle at 20% 20%, rgba(59,130,246,.45), transparent 45%), radial-gradient(circle at 85% 10%, rgba(245,158,11,.3), transparent 35%);"></div>
    <div class="relative max-w-7xl mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-14">
        <span class="inline-block text-accent-400 font-bold text-sm tracking-wider uppercase mb-4">Pricing</span>
        <h2 class="text-4xl md:text-5xl font-black mb-4">料金目安</h2>
        <p class="text-white/75 text-lg">要件次第で変動しますが、発注時の判断に使える目安を公開しています。</p>
      </div>

      <div class="grid lg:grid-cols-3 gap-6">
        <article class="rounded-2xl bg-white/8 border border-white/15 p-7 backdrop-blur hover-lift">
          <h3 class="text-2xl font-bold mb-2">ライト</h3>
          <p class="text-white/70 text-sm mb-6">単機能の自動化・改修</p>
          <div class="text-4xl font-black text-accent-400 mb-5">5万〜15万円</div>
          <ul class="space-y-2 text-sm text-white/85">
            <li>・既存GASの修正</li>
            <li>・定期実行ジョブ1〜2本</li>
            <li>・簡易マニュアル付き</li>
          </ul>
        </article>

        <article class="rounded-2xl bg-primary-600 border border-primary-400 p-7 shadow-brand hover-lift relative">
          <span class="absolute -top-3 right-5 bg-accent-500 text-slate-900 text-xs font-black px-3 py-1 rounded-full">人気</span>
          <h3 class="text-2xl font-bold mb-2">スタンダード</h3>
          <p class="text-primary-100 text-sm mb-6">複数フローをまとめて自動化</p>
          <div class="text-4xl font-black text-white mb-5">15万〜40万円</div>
          <ul class="space-y-2 text-sm text-white/90">
            <li>・フォーム / メール / シート連携</li>
            <li>・運用設計とテスト支援</li>
            <li>・納品後2週間サポート</li>
          </ul>
        </article>

        <article class="rounded-2xl bg-white/8 border border-white/15 p-7 backdrop-blur hover-lift">
          <h3 class="text-2xl font-bold mb-2">アドバンス</h3>
          <p class="text-white/70 text-sm mb-6">Webアプリ化・横断連携</p>
          <div class="text-4xl font-black text-accent-400 mb-5">40万円〜</div>
          <ul class="space-y-2 text-sm text-white/85">
            <li>・Apps Script Webアプリ</li>
            <li>・外部API / 認証連携</li>
            <li>・継続保守プラン提案</li>
          </ul>
        </article>
      </div>

      <p class="text-xs text-white/65 mt-7">※最終見積はヒアリング後に提示します。NDA締結、請求書払い、クラウドワークス契約にも対応します。</p>
    </div>
  </section>

  <section id="assurance" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-3xl border border-slate-100 p-8 hover-lift">
          <h2 class="text-3xl font-black text-slate-900 mb-5">発注時の安心ポイント</h2>
          <ul class="space-y-3 text-slate-700">
            <li><i class="fas fa-shield-alt text-success-600 mr-2"></i>アカウント権限を最小化した設計で実装します。</li>
            <li><i class="fas fa-file-contract text-success-600 mr-2"></i>NDA・秘密保持契約に対応できます。</li>
            <li><i class="fas fa-list-check text-success-600 mr-2"></i>納品物（コード、設定手順、運用手順）を明確化します。</li>
            <li><i class="fas fa-rotate text-success-600 mr-2"></i>将来の改修を見据えた構成で作成します。</li>
          </ul>
        </div>
        <div class="bg-white rounded-3xl border border-slate-100 p-8 hover-lift">
          <h3 class="text-3xl font-black text-slate-900 mb-5">導入事例（GAS中心）</h3>
          <div class="space-y-4 text-sm text-slate-700">
            <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
              <p class="font-bold text-slate-900 mb-1">見積作成フロー自動化</p>
              <p>フォーム入力を元に見積書PDFを自動作成し、承認依頼メールまで自動送信。</p>
            </div>
            <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
              <p class="font-bold text-slate-900 mb-1">社内申請のWebアプリ化</p>
              <p>スプレッドシート運用をGAS Webアプリに置き換え、誤入力と属人化を削減。</p>
            </div>
            <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
              <p class="font-bold text-slate-900 mb-1">定期レポート配信</p>
              <p>毎朝のKPI集計を自動化し、Slack通知と履歴保存まで一括実行。</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="faq" class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">FAQ</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">よくある質問</h2>
      </div>

      <div class="space-y-4" id="faqList">
        <article class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
          <button class="w-full text-left px-6 py-5 flex justify-between items-center" data-faq="faq1">
            <span class="font-bold text-slate-900">要件が未整理でも発注できますか？</span>
            <i id="icon-faq1" class="fas fa-chevron-down text-primary-600 transition-transform"></i>
          </button>
          <div id="faq1" class="px-6 pb-5 hidden text-slate-600">はい、可能です。業務ヒアリングから入り、要件定義書レベルまで整理してから見積を確定します。</div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
          <button class="w-full text-left px-6 py-5 flex justify-between items-center" data-faq="faq2">
            <span class="font-bold text-slate-900">既存GASの改修だけでも依頼できますか？</span>
            <i id="icon-faq2" class="fas fa-chevron-down text-primary-600 transition-transform"></i>
          </button>
          <div id="faq2" class="px-6 pb-5 hidden text-slate-600">はい。既存コードの調査、改善方針の提案、段階的な改修に対応しています。</div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
          <button class="w-full text-left px-6 py-5 flex justify-between items-center" data-faq="faq3">
            <span class="font-bold text-slate-900">セキュリティ面はどう担保しますか？</span>
            <i id="icon-faq3" class="fas fa-chevron-down text-primary-600 transition-transform"></i>
          </button>
          <div id="faq3" class="px-6 pb-5 hidden text-slate-600">対象データと権限範囲を先に定義し、最小権限で実装します。必要に応じてNDAも締結します。</div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
          <button class="w-full text-left px-6 py-5 flex justify-between items-center" data-faq="faq4">
            <span class="font-bold text-slate-900">どのような納品物がありますか？</span>
            <i id="icon-faq4" class="fas fa-chevron-down text-primary-600 transition-transform"></i>
          </button>
          <div id="faq4" class="px-6 pb-5 hidden text-slate-600">GASコード、導入手順、運用手順、設定値一覧、保守時の注意点をセットで納品します。</div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
          <button class="w-full text-left px-6 py-5 flex justify-between items-center" data-faq="faq5">
            <span class="font-bold text-slate-900">支払い方法を教えてください。</span>
            <i id="icon-faq5" class="fas fa-chevron-down text-primary-600 transition-transform"></i>
          </button>
          <div id="faq5" class="px-6 pb-5 hidden text-slate-600">請求書払いに対応しています。詳細はメールまたは問い合わせフォーム（CF7）からご相談ください。</div>
        </article>
      </div>
    </div>
  </section>

  <section id="contact" class="py-24 bg-slate-950 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-30" style="background: radial-gradient(circle at 20% 20%, rgba(59,130,246,.45), transparent 45%), radial-gradient(circle at 85% 80%, rgba(16,185,129,.35), transparent 35%);"></div>
    <div class="relative max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-accent-400 font-bold text-sm tracking-wider uppercase mb-4">Contact</span>
        <h2 class="text-4xl md:text-5xl font-black mb-4">GAS発注の相談はこちら</h2>
        <p class="text-white/80 text-lg leading-relaxed">
          要件が固まっていない段階でも問題ありません。まずは現状の業務フローを共有してください。
        </p>
      </div>

      <div class="rounded-3xl bg-white/10 border border-white/20 backdrop-blur p-7 md:p-10">
        <div class="rounded-2xl bg-white/10 border border-white/15 p-5 mb-7">
          <h3 class="font-bold text-lg mb-2">メールで相談する</h3>
          <p class="text-sm text-white/75 mb-4">短い相談でも歓迎です。通常24時間以内に一次返信します。</p>
          <a href="mailto:main@zidooka.com"
             data-ga-event="lp_mail_click"
             data-ga-location="contact"
             data-ga-label="mailto_primary"
             class="inline-flex items-center gap-2 bg-white text-primary-700 px-5 py-3 rounded-full font-bold hover:bg-slate-100 transition">
            <i class="fas fa-envelope"></i>
            main@zidooka.com
          </a>
        </div>

        <div class="rounded-2xl bg-white p-5 md:p-6 text-slate-800">
          <h3 class="font-bold text-xl mb-2">問い合わせフォーム（CF7）</h3>
          <p class="text-sm text-slate-600 mb-5">GAS業務発注の概要、希望納期、現状の課題を記載してください。</p>
          <div class="zdk-cf7">
            <?php echo do_shortcode('[contact-form-7 id="e1f232c" title="問い合わせ"]'); ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-slate-100 text-slate-500 py-10 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="text-xl font-black text-primary-700">ZIDOOKA!</div>
      <div class="text-sm">&copy; <?php echo date('Y'); ?> All rights reserved.</div>
      <div class="flex items-center gap-4 text-sm">
        <a href="#services" class="hover:text-primary-700">対応範囲</a>
        <a href="#workflow" class="hover:text-primary-700">発注フロー</a>
        <a href="#contact" class="hover:text-primary-700">お問い合わせ</a>
      </div>
    </div>
  </footer>

  <button id="scrollTopBtn" class="fixed bottom-8 right-8 z-40 hidden w-12 h-12 rounded-full bg-primary-600 text-white shadow-lg hover:bg-primary-700 transition" aria-label="トップへ戻る">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
  (function () {
    const nav = document.getElementById('mainNav');
    const mobileMenu = document.getElementById('mobileMenu');
    const openBtn = document.getElementById('mobileMenuBtn');
    const closeBtn = document.getElementById('closeMobileMenu');
    const scrollTopBtn = document.getElementById('scrollTopBtn');

    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 80) {
        nav.classList.remove('nav-hidden');
        nav.classList.add('nav-visible');
      } else {
        nav.classList.add('nav-hidden');
        nav.classList.remove('nav-visible');
      }

      if (window.pageYOffset > 500) {
        scrollTopBtn.classList.remove('hidden');
      } else {
        scrollTopBtn.classList.add('hidden');
      }
    });

    if (openBtn && closeBtn && mobileMenu) {
      openBtn.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
      closeBtn.addEventListener('click', () => mobileMenu.classList.add('hidden'));
      mobileMenu.querySelectorAll('a[href^="#"]').forEach((a) => {
        a.addEventListener('click', () => mobileMenu.classList.add('hidden'));
      });
    }

    if (scrollTopBtn) {
      scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    document.querySelectorAll('[data-faq]').forEach((button) => {
      button.addEventListener('click', () => {
        const id = button.getAttribute('data-faq');
        const body = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (!body || !icon) return;

        const isHidden = body.classList.contains('hidden');
        if (isHidden) {
          body.classList.remove('hidden');
          icon.style.transform = 'rotate(180deg)';
        } else {
          body.classList.add('hidden');
          icon.style.transform = 'rotate(0deg)';
        }
      });
    });

    document.addEventListener('click', (event) => {
      const el = event.target.closest('[data-ga-event]');
      if (!el || typeof window.gtag !== 'function') return;

      window.gtag('event', el.dataset.gaEvent, {
        event_category: 'engagement',
        event_label: el.dataset.gaLabel || '',
        link_url: el.getAttribute('href') || '',
        location: el.dataset.gaLocation || ''
      });
    });

    document.querySelectorAll('a[href="#contact"]').forEach((a, index) => {
      if (a.dataset.gaEvent) return;
      a.dataset.gaEvent = 'lp_cta_click';
      a.dataset.gaLocation = 'contact_anchor';
      a.dataset.gaLabel = (a.textContent || '').trim().slice(0, 80) || ('contact_anchor_' + (index + 1));
    });
  })();
  </script>

  <?php wp_footer(); ?>
</body>
</html>
