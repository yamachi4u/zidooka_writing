<?php
/**
 * Template Name: LP - ZIDOOKA
 * Description: ZIDOOKA! ランディングページテンプレート（Tailwind CSS版）
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>面倒な手作業を自動化。無料見積り | ZIDOOKA!</title>
  <meta name="description" content="毎日の面倒な手作業を自動化します。メールの転記、請求書作成、データ集計などに対応。個人で直接対応するため、最短1日納品・5,000円からご相談いただけます。">

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
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Tailwind Config -->
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
            950: '#172554',
          },
          accent: {
            400: '#fbbf24',
            500: '#f59e0b',
            600: '#d97706',
          },
        },
        fontFamily: {
          sans: ['Noto Sans JP', 'system-ui', 'sans-serif'],
        },
        animation: {
          'float': 'float 6s ease-in-out infinite',
          'float-delayed': 'float 6s ease-in-out 3s infinite',
          'fade-in-up': 'fadeInUp 0.8s ease-out',
        },
        keyframes: {
          float: {
            '0%, 100%': { transform: 'translateY(0px)' },
            '50%': { transform: 'translateY(-20px)' },
          },
          fadeInUp: {
            '0%': { opacity: '0', transform: 'translateY(30px)' },
            '100%': { opacity: '1', transform: 'translateY(0)' },
          },
        },
      }
    }
  }
  </script>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <style>
    html { scroll-behavior: smooth; }
    
    /* 単色の強調テキスト */
    .accent-text {
      color: #f59e0b;
    }
    
    .glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .glass-dark {
      background: rgba(30, 41, 59, 0.9);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.15;
      z-index: 0;
    }
    
    .hover-lift {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.15);
    }
    
    .floating-cta {
      display: none;
    }
    @media (max-width: 768px) {
      .floating-cta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        z-index: 100;
        border-top: 1px solid #cbd5e1;
        box-shadow: 0 -6px 24px rgba(15, 23, 42, 0.12);
      }
    }

    .floating-cta-link {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      min-height: 56px;
      font-weight: 700;
      text-decoration: none;
    }

    .floating-cta-mail {
      color: #1d4ed8;
      background: #ffffff;
      border-right: 1px solid #e2e8f0;
    }

    .floating-cta-quote {
      color: #ffffff;
      background: #f59e0b;
    }
    
    .highlight-row {
      background-color: rgba(37, 99, 235, 0.08);
      border-left: 4px solid #2563eb;
    }
    
    .nav-hidden { transform: translateY(-100%); }
    .nav-visible { transform: translateY(0); }

    /* Contact Form 7: ensure controls are visible and tappable */
    .wpcf7 {
      color: #0f172a;
    }
    .wpcf7 form p {
      margin: 0 0 14px 0;
    }
    .wpcf7 input[type="text"],
    .wpcf7 input[type="email"],
    .wpcf7 input[type="tel"],
    .wpcf7 input[type="url"],
    .wpcf7 input[type="number"],
    .wpcf7 textarea,
    .wpcf7 select {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      background: #ffffff;
      color: #0f172a;
      padding: 12px 14px;
      font-size: 16px;
      line-height: 1.5;
      outline: none;
      box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .wpcf7 textarea {
      min-height: 140px;
      resize: vertical;
    }
    .wpcf7 input:focus,
    .wpcf7 textarea:focus,
    .wpcf7 select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    }
    .wpcf7 input[type="submit"],
    .wpcf7 button,
    .wpcf7 .wpcf7-submit {
      appearance: none;
      border: none;
      border-radius: 9999px;
      background: #f59e0b;
      color: #ffffff;
      font-weight: 700;
      font-size: 16px;
      line-height: 1.2;
      padding: 14px 24px;
      cursor: pointer;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .wpcf7 input[type="submit"]:hover,
    .wpcf7 button:hover,
    .wpcf7 .wpcf7-submit:hover {
      background: #d97706;
      transform: translateY(-1px);
    }
    .wpcf7 .wpcf7-not-valid-tip {
      color: #dc2626;
      font-size: 13px;
      margin-top: 6px;
    }
    .wpcf7 .wpcf7-response-output {
      margin: 16px 0 0;
      border-radius: 12px;
      padding: 12px 14px;
      border: 1px solid #cbd5e1;
      font-size: 14px;
    }
  </style>
</head>
<body class="font-sans text-slate-800 antialiased bg-slate-50">

  <!-- モバイルフローティングCTA -->
  <div class="floating-cta">
    <a href="mailto:main@zidooka.com"
       data-ga-event="lp_quote_mail_click"
       data-ga-location="floating_cta"
       data-ga-label="mailto"
       class="floating-cta-link floating-cta-mail">
      <i class="fas fa-envelope"></i>メールで質問
    </a>
    <a href="#contact" class="floating-cta-link floating-cta-quote">
      <i class="fas fa-file-signature"></i>見積り依頼
    </a>
  </div>

  <!-- シンプルヘッダー -->
  <header id="mainNav" class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 nav-hidden">
    <div class="glass mx-4 mt-4 rounded-2xl px-6 py-4 shadow-lg">
      <div class="flex items-center justify-between max-w-7xl mx-auto">
        <a href="#" class="text-2xl font-black text-primary-700">ZIDOOKA!</a>
        <nav class="hidden md:flex items-center gap-8">
          <a href="#pain" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">課題</a>
          <a href="#proof" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">実績</a>
          <a href="#comparison" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">料金と納期</a>
          <a href="#contact" class="bg-primary-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-primary-700 transition">
            無料見積り
          </a>
        </nav>
        <button id="mobileMenuBtn" class="md:hidden text-slate-600 text-2xl">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- モバイルメニュー -->
  <div id="mobileMenu" class="fixed inset-0 z-40 bg-white hidden">
    <div class="p-6">
      <div class="flex justify-between items-center mb-8">
        <span class="text-2xl font-black text-primary-700">ZIDOOKA!</span>
        <button id="closeMobileMenu" class="text-2xl text-slate-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="flex flex-col gap-4">
        <a href="#pain" class="text-lg font-medium py-3 border-b border-slate-100">課題</a>
        <a href="#proof" class="text-lg font-medium py-3 border-b border-slate-100">実績</a>
        <a href="#comparison" class="text-lg font-medium py-3 border-b border-slate-100">料金と納期</a>
        <a href="#contact" class="bg-primary-600 text-white text-center py-4 rounded-xl font-semibold mt-4">無料見積りを依頼する</a>
      </nav>
    </div>
  </div>

  <!-- ヒーローセクション -->
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-primary-950">
    <!-- 装飾的な円（単色） -->
    <div class="blob bg-primary-600 w-96 h-96 top-20 -left-20 animate-float"></div>
    <div class="blob bg-primary-700 w-80 h-80 bottom-20 -right-20 animate-float-delayed"></div>
    <div class="blob bg-primary-500 w-64 h-64 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
    
    <!-- グリッドパターン -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
      <!-- バッジ -->
      <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8 animate-fade-in-up">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
        </span>
        <span class="text-white/90 text-sm font-medium">最短24時間で見積り回答</span>
      </div>
      
      <!-- メインタイトル -->
      <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.1s;">
        面倒な手作業を<br>
        <span class="text-accent-500">自動化で消す</span>
      </h1>
      
      <!-- サブタイトル -->
      <p class="text-xl md:text-2xl text-white/70 mb-12 max-w-3xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
        毎日の面倒な作業を自動化して、手間とミスを減らします。
        専門用語は不要です。やりたいことをそのままご相談ください。
      </p>
      
      <!-- CTAボタン -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up" style="animation-delay: 0.3s;">
        <a href="#contact" class="group relative inline-flex items-center gap-2 bg-accent-500 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-accent-600 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <div class="text-center leading-tight">
            <span class="block text-xs opacity-85 font-medium mb-1">最短24時間以内に一次回答</span>
            <span>その作業、自動化できるか無料で診断する</span>
          </div>
          <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>
        <a href="#comparison" class="inline-flex items-center gap-2 text-white/80 hover:text-white px-8 py-4 rounded-full font-medium text-lg border border-white/20 hover:bg-white/10 transition-all duration-300">
          <i class="fas fa-play-circle"></i>
          <span>実績と料金を見る</span>
        </a>
      </div>
      <p class="text-sm text-white/65 mt-2 animate-fade-in-up" style="animation-delay: 0.35s;">
        目安: 小規模案件 5,000円〜 / 最短1日納品
      </p>
      
      <!-- スクロールインジケーター -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
      </div>
    </div>
  </section>


  <!-- 悩み具体化セクション -->
  <section id="pain" class="py-16 bg-white border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-6 text-center">
      <p class="text-slate-600 font-bold mb-7">こんな作業、まだ手作業でやってませんか？</p>
      <div class="grid md:grid-cols-3 gap-4 text-left">
        <div class="flex items-start gap-3 p-5 bg-red-50 rounded-xl border border-red-100">
          <i class="fas fa-times-circle text-red-500 mt-1"></i>
          <span class="text-base font-bold text-slate-700 leading-snug">毎日届く注文メールを、表に1行ずつ転記している</span>
        </div>
        <div class="flex items-start gap-3 p-5 bg-red-50 rounded-xl border border-red-100">
          <i class="fas fa-times-circle text-red-500 mt-1"></i>
          <span class="text-base font-bold text-slate-700 leading-snug">請求書PDFを複数社へ手作業で作成・送信している</span>
        </div>
        <div class="flex items-start gap-3 p-5 bg-red-50 rounded-xl border border-red-100">
          <i class="fas fa-times-circle text-red-500 mt-1"></i>
          <span class="text-base font-bold text-slate-700 leading-snug">複数サイトの価格チェックを毎日目視で繰り返している</span>
        </div>
      </div>
      <div class="mt-8">
        <p class="text-2xl md:text-3xl font-black text-primary-700">
          その作業、設計次第で自動化できます
        </p>
      </div>
    </div>
  </section>

  <!-- 実績セクション -->
  <section id="proof" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-black text-slate-900">実績</h2>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">50+</div>
          <div class="text-slate-600 font-medium">取引実績</div>
          <div class="text-xs text-slate-400 mt-2">※クラウドワークス上の数値です</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">100%</div>
          <div class="text-slate-600 font-medium">契約完了率</div>
          <div class="text-xs text-slate-400 mt-2">※クラウドワークス上の数値です</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">4.9</div>
          <div class="text-slate-600 font-medium">平均評価（5点満点）</div>
          <div class="text-xs text-slate-400 mt-2">※クラウドワークス上の数値です（25年3月時点）</div>
        </div>
      </div>
    </div>
  </section>


  <!-- 比較セクション -->
  <section id="comparison" class="py-24 bg-primary-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="text-center mb-16">
        <span class="inline-block text-accent-500 font-bold text-sm tracking-wider uppercase mb-4">Comparison</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">料金と納期</h2>
        <p class="text-slate-600 text-lg">まずは「何に困っているか」だけ教えてください。</p>
      </div>
      
      <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                  <th class="px-6 py-5 text-left text-slate-600 font-medium">比較項目</th>
                  <th class="px-6 py-5 text-center">
                    <div class="inline-block bg-primary-600 text-white px-4 py-2 rounded-lg font-bold">
                      Zidooka
                    </div>
                  </th>
                  <th class="px-6 py-5 text-center text-slate-600 font-medium">開発会社</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">初期費用</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">0円</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">3万円〜</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold text-slate-900">月額固定費</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">0円</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">1万円〜</td>
                </tr>
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">開発単価</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">5,000円〜</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">1万円〜</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold text-slate-900">納期</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">最短1日</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">1週間〜</td>
                </tr>
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">外注コスト</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-xl font-bold text-accent-500">なし</span>
                    <div class="text-sm text-slate-500">直接対応</div>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">
                    <span class="text-xl font-bold text-slate-500">あり</span>
                    <div class="text-sm text-slate-400">中間マージン発生</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-accent-500 rounded-xl flex items-center justify-center mb-6 shadow-lg">
            <i class="fas fa-coins text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">固定費・外注ゼロ</h3>
          <p class="text-slate-600 leading-relaxed">
            個人で直接対応するため中間マージンがありません。必要な範囲だけ、無駄なく作成します。
          </p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
            <i class="fas fa-bolt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">相談から実装まで速い</h3>
          <p class="text-slate-600 leading-relaxed">
            担当者が最初から最後まで対応するので話が早いです。小規模な内容なら最短1日で納品できます。
          </p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
            <i class="fas fa-shield-alt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">担当者が変わらない</h3>
          <p class="text-slate-600 leading-relaxed">
            最初の相談から納品後の調整まで、同じ担当者が対応します。作って終わりにはしません。
          </p>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-accent-500 text-white px-8 py-4 rounded-full font-bold hover:bg-accent-600 transition shadow-lg">
          今すぐ無料見積りを依頼する
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>


  <!-- 無料見積りセクション -->
  <section id="contact" class="py-24 bg-white relative">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-accent-500 font-bold text-sm tracking-wider uppercase mb-4">Contact</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">無料見積り</h2>
        <p class="text-slate-600 text-lg mb-4">
          まずは<strong class="text-slate-900">ご相談</strong>から。予算と納期の目安をお伝えします。
        </p>
        <p class="text-slate-500">
          <a href="https://crowdworks.jp/public/employers/1459615" target="_blank" rel="noopener noreferrer" class="text-accent-500 hover:text-accent-600 underline">
            クラウドワークス
          </a>
          経由のご相談も歓迎です。メール直通は <strong>main@zidooka.com</strong> です。
        </p>
      </div>
      
      <div class="bg-slate-50 rounded-3xl p-8 md:p-12 border border-slate-100">
        <div class="text-center mb-8">
          <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clipboard-list text-4xl text-primary-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-3">無料見積りフォーム</h3>
          <p class="text-slate-600 mb-2">
            分かる範囲で大丈夫です。ふんわりしたご相談でも問題ありません。
          </p>
          <p class="text-sm text-slate-500">
            今の作業内容 / 自動化したいこと / 希望時期 の3つがあるとスムーズです。<br>
            24時間以内に返信します。
          </p>
        </div>
        
        <div class="flex flex-col gap-4">
          <div class="bg-white rounded-2xl p-6 border border-slate-200">
            <?php
            $lp_form_shortcode = apply_filters('zidooka_lp_quote_form_shortcode', '[contact-form-7 id="e1f232c" title="問い合わせ"]');
            $lp_form_html = do_shortcode($lp_form_shortcode);
            if (!empty(trim(wp_strip_all_tags($lp_form_html)))) :
            ?>
              <?php echo $lp_form_html; ?>
            <?php else : ?>
              <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform?usp=dialog"
                 target="_blank"
                 rel="noopener noreferrer"
                 data-ga-event="lp_quote_form_open"
                 data-ga-location="contact_section_fallback"
                 data-ga-label="google_form_fallback"
                 class="group inline-flex items-center justify-center gap-3 bg-accent-500 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-accent-600 transition-all duration-300 hover:-translate-y-1 shadow-lg w-full">
                <i class="fab fa-google-drive text-xl"></i>
                <span>無料見積りフォームを開く（外部）</span>
                <i class="fas fa-external-link-alt group-hover:translate-x-1 transition-transform"></i>
              </a>
            <?php endif; ?>
          </div>

          <div class="text-center">
            <p class="text-slate-500 text-sm mb-3">フォームが難しい場合はメールでどうぞ</p>
            <a href="mailto:main@zidooka.com" 
               data-ga-event="lp_quote_mail_click"
               data-ga-location="contact_section"
               data-ga-label="mailto"
               class="inline-flex items-center gap-2 border-2 border-slate-300 text-slate-700 px-6 py-3 rounded-full font-semibold hover:bg-slate-100 transition">
              <i class="fas fa-envelope"></i>
              メールで無料見積りを依頼する
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- フッター -->
  <footer class="bg-slate-100 text-slate-500 py-12 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-2xl font-black text-primary-600">ZIDOOKA!</div>
        <div class="text-sm">
          &copy; <?php echo date('Y'); ?> All rights reserved.
        </div>
        <div class="flex gap-4">
          <a href="#pain" class="hover:text-primary-600 transition">課題</a>
          <a href="#proof" class="hover:text-primary-600 transition">実績</a>
          <a href="#comparison" class="hover:text-primary-600 transition">料金と納期</a>
          <a href="#contact" class="hover:text-primary-600 transition">無料見積り</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- トップへ戻るボタン -->
  <button id="scrollTopBtn" class="fixed bottom-8 right-8 bg-primary-600 text-white w-14 h-14 rounded-full shadow-2xl hover:bg-primary-700 transition hidden z-50 flex items-center justify-center hover:scale-110" title="トップへ戻る">
    <i class="fas fa-arrow-up text-xl"></i>
  </button>

  <script>
  // ナビゲーション表示制御
  let lastScroll = 0;
  const nav = document.getElementById('mainNav');
  
  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
      nav.classList.remove('nav-hidden');
      nav.classList.add('nav-visible');
    } else {
      nav.classList.add('nav-hidden');
      nav.classList.remove('nav-visible');
    }
    
    lastScroll = currentScroll;
  });
  
  // GA4 click events for LP CTAs
  document.addEventListener('click', (event) => {
    const target = event.target.closest('[data-ga-event]');
    if (!target) return;
    if (typeof window.gtag !== 'function') return;

    const eventName = target.getAttribute('data-ga-event') || 'lp_click';
    const params = {
      event_category: 'engagement',
      event_label: target.getAttribute('data-ga-label') || '',
      link_url: target.getAttribute('href') || '',
      location: target.getAttribute('data-ga-location') || ''
    };
    window.gtag('event', eventName, params);
  });

  // Auto-tag #contact anchors to capture quote intent
  document.querySelectorAll('a[href="#contact"]').forEach((a, idx) => {
    if (a.dataset.gaEvent) return;
    a.dataset.gaEvent = 'lp_quote_cta_click';
    a.dataset.gaLocation = a.closest('.floating-cta') ? 'floating_cta' : 'contact_anchor';
    a.dataset.gaLabel = (a.textContent || '').trim().slice(0, 80) || `contact_anchor_${idx + 1}`;
  });

  // モバイルメニュー
  document.getElementById('mobileMenuBtn').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.remove('hidden');
  });
  
  document.getElementById('closeMobileMenu').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.add('hidden');
  });
  
  // スクロールトップボタン
  const scrollTopBtn = document.getElementById('scrollTopBtn');
  
  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 500) {
      scrollTopBtn.classList.remove('hidden');
    } else {
      scrollTopBtn.classList.add('hidden');
    }
  });
  
  scrollTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
  </script>

</body>
</html>





