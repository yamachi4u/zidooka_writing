<?php
/**
 * Template Name: LP - ZIDOOKA Modern
 * Description: ZIDOOKA! ランディングページ - Tailwindモダンデザイン版
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>業務効率化で時間を「作る」 - ZIDOOKA!</title>
  <meta name="description" content="Google Apps Scriptとマクロで業務を自動化。フリーランスだから柔軟で安い。最短1日で納品可能。">
  
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
          },
          secondary: {
            50: '#f0fdf4',
            500: '#22c55e',
            600: '#16a34a',
          },
          accent: '#f59e0b',
        },
        fontFamily: {
          sans: ['Noto Sans JP', 'system-ui', 'sans-serif'],
        },
        animation: {
          'float': 'float 6s ease-in-out infinite',
          'float-delayed': 'float 6s ease-in-out 3s infinite',
          'fade-in-up': 'fadeInUp 0.8s ease-out',
          'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
    
    /* グラデーションテキスト */
    .gradient-text {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #7c3aed 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* ガラスモーフィズム */
    .glass {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .glass-dark {
      background: rgba(30, 41, 59, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* 装飾的な背景要素 */
    .blob {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.4;
      z-index: 0;
    }
    
    /* カードホバーエフェクト */
    .hover-lift {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.2);
    }
    
    /* グローエフェクト */
    .glow {
      box-shadow: 0 0 40px rgba(37, 99, 235, 0.3);
    }
    
    /* フローティングバナー */
    .floating-cta {
      display: none;
    }
    @media (max-width: 768px) {
      .floating-cta {
        display: block;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(90deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 16px;
        z-index: 100;
        text-align: center;
        font-weight: 600;
        box-shadow: 0 -4px 20px rgba(37, 99, 235, 0.4);
      }
    }
    
    /* 比較テーブルのハイライト */
    .highlight-row {
      background: linear-gradient(90deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
      border-left: 4px solid #2563eb;
    }
    
    /* チェックマークアニメーション */
    .check-icon {
      animation: checkPop 0.5s ease-out;
    }
    @keyframes checkPop {
      0% { transform: scale(0); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }
    
    /* ナビゲーション */
    .nav-hidden { transform: translateY(-100%); }
    .nav-visible { transform: translateY(0); }
  </style>
</head>
<body class="font-sans text-slate-800 antialiased bg-slate-50">

  <!-- モバイルフローティングCTA -->
  <a href="#contact" class="floating-cta">
    <i class="fas fa-rocket mr-2"></i>今すぐ無料相談する
  </a>

  <!-- シンプルヘッダー -->
  <header id="mainNav" class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 nav-hidden">
    <div class="glass mx-4 mt-4 rounded-2xl px-6 py-4">
      <div class="flex items-center justify-between max-w-7xl mx-auto">
        <a href="#" class="text-2xl font-black gradient-text">ZIDOOKA!</a>
        <nav class="hidden md:flex items-center gap-8">
          <a href="#services" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">サービス</a>
          <a href="#features" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">特徴</a>
          <a href="#about" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">About</a>
          <a href="#comparison" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">比較</a>
          <a href="#contact" class="bg-primary-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
            無料相談
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
        <span class="text-2xl font-black gradient-text">ZIDOOKA!</span>
        <button id="closeMobileMenu" class="text-2xl text-slate-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="flex flex-col gap-4">
        <a href="#services" class="text-lg font-medium py-3 border-b border-slate-100">サービス</a>
        <a href="#features" class="text-lg font-medium py-3 border-b border-slate-100">特徴</a>
        <a href="#about" class="text-lg font-medium py-3 border-b border-slate-100">About</a>
        <a href="#comparison" class="text-lg font-medium py-3 border-b border-slate-100">比較</a>
        <a href="#contact" class="bg-primary-600 text-white text-center py-4 rounded-xl font-semibold mt-4">無料相談する</a>
      </nav>
    </div>
  </div>

  <!-- ヒーローセクション -->
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900">
    <!-- 背景エフェクト -->
    <div class="blob bg-primary-500 w-96 h-96 top-20 -left-20 animate-float"></div>
    <div class="blob bg-purple-500 w-80 h-80 bottom-20 -right-20 animate-float-delayed"></div>
    <div class="blob bg-cyan-400 w-64 h-64 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-20"></div>
    
    <!-- グリッドパターン -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
      <!-- バッジ -->
      <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8 animate-fade-in-up">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        <span class="text-white/90 text-sm font-medium">フリーランスだから柔軟で安い</span>
      </div>
      
      <!-- メインタイトル -->
      <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.1s;">
        業務効率化で<br>
        <span class="bg-gradient-to-r from-cyan-400 via-primary-400 to-purple-400 bg-clip-text text-transparent">時間</span>を「作る」
      </h1>
      
      <!-- サブタイトル -->
      <p class="text-xl md:text-2xl text-white/70 mb-12 max-w-2xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
        繰り返し作業を自動化して、<br class="md:hidden">本当に大切な業務に集中しませんか？
      </p>
      
      <!-- CTAボタン -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up" style="animation-delay: 0.3s;">
        <a href="#contact" class="group relative inline-flex items-center gap-2 bg-white text-primary-700 px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-white/20 transition-all duration-300 hover:-translate-y-1">
          <span>今すぐ無料相談をする</span>
          <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>
        <a href="#services" class="inline-flex items-center gap-2 text-white/80 hover:text-white px-8 py-4 rounded-full font-medium text-lg border border-white/20 hover:bg-white/10 transition-all duration-300">
          <i class="fas fa-play-circle"></i>
          <span>サービスを確認する</span>
        </a>
      </div>
      
      <!-- スクロールインジケーター -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
      </div>
    </div>
  </section>

  <!-- 実績セクション -->
  <section class="py-20 bg-white relative">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-3 gap-8 -mt-32 relative z-10">
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black gradient-text mb-2">50+</div>
          <div class="text-slate-600 font-medium">取引実績</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black gradient-text mb-2">100%</div>
          <div class="text-slate-600 font-medium">契約完了率</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black gradient-text mb-2">4.9</div>
          <div class="text-slate-600 font-medium">平均評価（5点満点）</div>
          <div class="text-xs text-slate-400 mt-1">※25年3月時点</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Aboutセクション -->
  <section id="about" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div class="order-2 lg:order-1">
          <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">About Us</span>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-tight">
            業務効率化の<br><span class="gradient-text">スペシャリスト</span>
          </h2>
          <p class="text-lg text-slate-600 mb-6 leading-relaxed">
            お客様の作業負荷を徹底的に削減します。Google Apps Scriptやマクロを活用して面倒な手作業を自動化し、大切な時間をより価値のある業務にシフトしていただけます。
          </p>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Google Apps Script 開発</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Excel マクロ開発・改修</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">業務効率化コンサルティング</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">スプレッドシート自動化</span>
            </div>
          </div>
          
          <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30 hover:shadow-xl hover:shadow-primary-600/40 hover:-translate-y-0.5">
            無料相談する
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <div class="order-1 lg:order-2">
          <div class="relative">
            <div class="absolute -inset-4 bg-gradient-to-r from-primary-500 to-purple-500 rounded-3xl opacity-20 blur-2xl"></div>
            <div class="relative bg-white rounded-2xl p-10 shadow-2xl border border-slate-100">
              <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary-500/30">
                <i class="fas fa-laptop-code text-4xl text-white"></i>
              </div>
              <h3 class="text-2xl font-bold text-slate-900 mb-3">シンプル & スピーディー</h3>
              <p class="text-slate-600 leading-relaxed">
                必要な機能のみ実装し、無駄を省いた迅速な開発を実現します。余計なコストをかけず、必要なものを必要な時に。
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 実績詳細セクション -->
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Portfolio</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">実績と信頼</h2>
      </div>
      
      <div class="grid md:grid-cols-2 gap-8">
        <!-- 企業実績 -->
        <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-8 border border-slate-100 hover-lift">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-building text-primary-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">企業・団体様の実績</h3>
          </div>
          <div class="space-y-4">
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">薬剤師会様</div>
              <div class="text-sm text-slate-600">Google Apps Scriptによる機器貸出予約システムを開発</div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">システム開発企業様</div>
              <div class="text-sm text-slate-600">Google API / ZOOM連携システムを構築</div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">その他多数</div>
              <div class="text-sm text-slate-600">Excel業務効率化やデータ分析基盤の構築</div>
            </div>
          </div>
        </div>
        
        <!-- 開発実績 -->
        <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl p-8 border border-slate-100 hover-lift">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-code text-secondary-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">開発実績例</h3>
          </div>
          <div class="space-y-4">
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-green-600 text-sm"></i>
              </div>
              <span class="text-slate-700">Amazon商品データ取得自動化システム</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-green-600 text-sm"></i>
              </div>
              <span class="text-slate-700">ニュースサイトスクレイピングツール</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-green-600 text-sm"></i>
              </div>
              <span class="text-slate-700">WordPress自動投稿システム（ChatGPT連携）</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-green-600 text-sm"></i>
              </div>
              <span class="text-slate-700">バーコード生成・管理システム</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
          今すぐ無料相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- 比較セクション -->
  <section id="comparison" class="py-24 bg-slate-900 text-white relative overflow-hidden">
    <!-- 背景エフェクト -->
    <div class="absolute top-0 left-0 w-full h-full">
      <div class="absolute top-20 right-20 w-64 h-64 bg-primary-500 rounded-full filter blur-3xl opacity-20"></div>
      <div class="absolute bottom-20 left-20 w-64 h-64 bg-purple-500 rounded-full filter blur-3xl opacity-20"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-400 font-bold text-sm tracking-wider uppercase mb-4">Comparison</span>
        <h2 class="text-4xl md:text-5xl font-black mb-4">他社との比較</h2>
        <p class="text-slate-400 text-lg">フリーランスだから実現できる圧倒的な価値</p>
      </div>
      
      <!-- 比較テーブル -->
      <div class="max-w-4xl mx-auto mb-16">
        <div class="glass-dark rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-white/10">
                  <th class="px-6 py-5 text-left text-slate-400 font-medium">比較項目</th>
                  <th class="px-6 py-5 text-center">
                    <div class="inline-block bg-gradient-to-r from-primary-500 to-primary-600 text-white px-4 py-2 rounded-lg font-bold">
                      Zidooka
                    </div>
                  </th>
                  <th class="px-6 py-5 text-center text-slate-400 font-medium">開発会社</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/10">
                <tr class="highlight-row">
                  <td class="px-6 py-5 font-semibold">初期費用</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-primary-400">0円</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-400">3万円〜</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold">月額固定費</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-primary-400">0円</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-400">1万円〜</td>
                </tr>
                <tr class="highlight-row">
                  <td class="px-6 py-5 font-semibold">開発単価</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-primary-400">5,000円〜</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-400">1万円〜</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold">納期</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-primary-400">最短1日</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-400">1週間〜</td>
                </tr>
                <tr class="highlight-row">
                  <td class="px-6 py-5 font-semibold">外注コスト</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-xl font-bold text-primary-400">なし</span>
                    <div class="text-sm text-slate-400">直接対応</div>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-400">
                    <span class="text-xl font-bold text-slate-400">あり</span>
                    <div class="text-sm text-slate-500">中間マージン発生</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <!-- 強みカード -->
      <div class="grid md:grid-cols-3 gap-6">
        <div class="glass-dark rounded-2xl p-8 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-orange-500/20">
            <i class="fas fa-coins text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">圧倒的なコスト優位性</h3>
          <p class="text-slate-400 leading-relaxed">
            固定費ゼロの事業構造と外注なしの直接対応で、中間マージンを削減。必要な機能だけを実装。
          </p>
        </div>
        
        <div class="glass-dark rounded-2xl p-8 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20">
            <i class="fas fa-bolt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">驚異的なスピード感</h3>
          <p class="text-slate-400 leading-relaxed">
            一人で対応するため意思決定が速く、承認プロセスなしで即断即決。最短1日での納品が可能。
          </p>
        </div>
        
        <div class="glass-dark rounded-2xl p-8 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-green-500/20">
            <i class="fas fa-shield-alt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">一貫した品質</h3>
          <p class="text-slate-400 leading-relaxed">
            要件定義から開発、テスト、運用サポートまで一貫して同一人物が担当。コミュニケーションロスなし。
          </p>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-white text-primary-700 px-8 py-4 rounded-full font-bold hover:shadow-2xl transition">
          今すぐ無料相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- サービスセクション -->
  <section id="services" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Services</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">提供サービス</h2>
        <p class="text-slate-600 text-lg max-w-2xl mx-auto">
          課題に寄り添い、状況や要望に合わせた最適な提案を行います。<br>
          簡単な内容なら1日での納品も可能です。
        </p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <!-- サービス1 -->
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-robot text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">業務自動化</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            Google Apps Scriptを使用して繰り返し作業を自動化し、手作業の負担を軽減します。
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            相談する <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <!-- サービス2 -->
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-code text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">マクロ開発・改修</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            既存マクロの改修や新規開発により業務効率を向上し、Excelやスプレッドシートの操作を簡易化します。
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            無料見積もりを依頼する <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <!-- サービス3 -->
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-cyan-500/30 group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-comments text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">コンサルティング</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            業務効率化のための最適な手段を共に検討し、具体的な解決策を提案します。
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            まずは相談する <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- 特徴セクション -->
  <section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Why Choose Us</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">選ばれる理由</h2>
      </div>
      
      <div class="grid md:grid-cols-2 gap-6">
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-primary-500/20">
            <i class="fas fa-clock text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">柔軟な対応時間</h3>
            <p class="text-slate-600">フリーランスだからこそ実現できる柔軟なスケジュールで、急ぎの案件にも迅速に対応します。</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/20">
            <i class="fas fa-coins text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">リーズナブルな料金</h3>
            <p class="text-slate-600">低固定費を活かし、コストパフォーマンスの高いサービスを提供します。</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-orange-500/20">
            <i class="fas fa-bolt text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">スピード対応</h3>
            <p class="text-slate-600">小規模だからこそフットワークが軽く、簡単な案件なら最短1日での納品が可能です。</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-500/20">
            <i class="fas fa-user-shield text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">安心のサポート</h3>
            <p class="text-slate-600">導入後のフォローアップや追加要望にも柔軟に対応し、長期的に安心して運用できます。</p>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
          今すぐ無料相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- お客様の声セクション -->
  <section id="testimonials" class="py-24 bg-gradient-to-br from-slate-50 to-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Testimonials</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">お客様の声</h2>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-primary-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "迅速丁寧な対応ありがとうございました。期待以上の成果でした！"
          </p>
          <div class="font-bold text-slate-900">サイト構築・ウェブ開発</div>
          <div class="text-sm text-slate-400 mt-1">2025年02月01日</div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-purple-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "要望説明が明確で、作業がとてもスムーズに進みました。"
          </p>
          <div class="font-bold text-slate-900">Excelマクロ構築</div>
          <div class="text-sm text-slate-400 mt-1">2024年06月24日</div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-green-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "細かい調整にも迅速に対応いただき、大変助かりました。"
          </p>
          <div class="font-bold text-slate-900">Google Apps Script開発</div>
          <div class="text-sm text-slate-400 mt-1">2024年05月15日</div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
          今すぐ無料相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Q&Aセクション -->
  <section id="qa" class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">FAQ</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">よくあるご質問</h2>
      </div>
      
      <div class="space-y-4">
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq1')">
            <span class="font-bold text-slate-900 text-lg">どのような業務を自動化できますか？</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq1"></i>
          </button>
          <div id="faq1" class="px-8 pb-6 hidden">
              <p class="text-slate-600 leading-relaxed">
              案件の内容と規模によりますが、簡単な自動化であれば最短1日で納品可能です。
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq2')">
            <span class="font-bold text-slate-900 text-lg">予算の目安はどのくらいですか？</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq2"></i>
          </button>
          <div id="faq2" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              簡単なスクリプトなら5,000円程度から対応しており、複雑なシステムの場合は数万円～数十万円まで幅広く承ります。
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq3')">
            <span class="font-bold text-slate-900 text-lg">連絡手段はどのようになりますか？</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq3"></i>
          </button>
          <div id="faq3" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              メール（<strong>main@zidooka.com</strong>）やZoom、Chatworkなどに対応します。クラウドワークス経由での連絡・契約も歓迎です。
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq4')">
            <span class="font-bold text-slate-900 text-lg">追加で機能を依頼したい場合はどうすればいいですか？</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq4"></i>
          </button>
          <div id="faq4" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              運用中の追加要望や改修にも柔軟に対応しますので、お気軽にご相談ください。
            </p>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-600/30">
          今すぐ無料相談をする
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- プロフィールセクション -->
  <section id="profile" class="py-24 bg-gradient-to-br from-slate-50 to-white">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Profile</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">プロフィール</h2>
      </div>
      
      <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-map-marker-alt text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">Zidooka!（個人事業主、京都市在住、氏名：山口和紀）</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-user text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">男性 27歳</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-briefcase text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">フリーランス</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-code text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">プログラミング／エンジニアリング歴 10年</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-laptop-code text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">HP制作、業務自動化</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- お問い合わせセクション -->
  <section id="contact" class="py-24 bg-gradient-to-br from-slate-900 via-primary-900 to-slate-900 text-white relative overflow-hidden">
    <!-- 背景エフェクト -->
    <div class="absolute top-0 left-0 w-full h-full">
      <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary-500 rounded-full filter blur-3xl opacity-20"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full filter blur-3xl opacity-20"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10">
      <div class="text-center mb-12">
        <span class="inline-block text-primary-400 font-bold text-sm tracking-wider uppercase mb-4">Contact</span>
        <h2 class="text-4xl md:text-5xl font-black mb-4">お問い合わせ</h2>
        <p class="text-slate-300 text-lg mb-4">
          メールでのご連絡は <strong class="text-white">main@zidooka.com</strong> までお願いいたします。
        </p>
        <p class="text-slate-400">
          <a href="https://crowdworks.jp/public/employers/1459615" target="_blank" rel="noopener noreferrer" class="text-primary-400 hover:text-primary-300 underline">
            クラウドワークス
          </a>
          等の他プラットフォームでの連絡も歓迎です。
        </p>
      </div>
      
      <div class="glass-dark rounded-3xl p-8 md:p-12">
        <div class="text-center mb-8">
          <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clipboard-list text-4xl text-primary-400"></i>
          </div>
          <h3 class="text-2xl font-bold mb-3">無料相談フォーム</h3>
          <p class="text-slate-300 mb-2">
            予算や納期のイメージを記載いただくとスムーズです。
          </p>
          <p class="text-sm text-slate-400">
            依頼内容が漠然としている場合もお気軽にご入力ください。<br>
            24時間以内に返事をするようにします（3営業日ほどかかる場合があります）。
          </p>
        </div>
        
        <div class="flex flex-col gap-4">
          <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform?usp=dialog" 
             target="_blank" 
             rel="noopener noreferrer"
             class="group inline-flex items-center justify-center gap-3 bg-white text-primary-700 px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:shadow-white/20 transition-all duration-300 hover:-translate-y-1">
            <i class="fab fa-google-drive text-xl"></i>
            <span>無料相談フォームを開く（Googleフォームが開きます）</span>
            <i class="fas fa-external-link-alt group-hover:translate-x-1 transition-transform"></i>
          </a>
          
          <div class="text-center">
            <p class="text-slate-400 text-sm mb-3">すぐに相談したい場合はこちらから</p>
            <a href="mailto:main@zidooka.com" 
               class="inline-flex items-center gap-2 border-2 border-white/30 text-white px-6 py-3 rounded-full font-semibold hover:bg-white/10 transition">
              <i class="fas fa-envelope"></i>
              メールで直接連絡する
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- フッター -->
  <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-2xl font-black gradient-text">ZIDOOKA!</div>
        <div class="text-sm">
          &copy; <?php echo date('Y'); ?> ZIDOOKA! All Rights Reserved.
        </div>
        <div class="flex gap-4">
          <a href="#services" class="hover:text-white transition">サービス</a>
          <a href="#features" class="hover:text-white transition">特徴</a>
          <a href="#contact" class="hover:text-white transition">お問い合わせ</a>
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
  
  // モバイルメニュー
  document.getElementById('mobileMenuBtn').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.remove('hidden');
  });
  
  document.getElementById('closeMobileMenu').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.add('hidden');
  });
  
  // FAQトグル
  function toggleFaq(id) {
    const content = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    
    if (content.classList.contains('hidden')) {
      content.classList.remove('hidden');
      icon.style.transform = 'rotate(180deg)';
    } else {
      content.classList.add('hidden');
      icon.style.transform = 'rotate(0deg)';
    }
  }
  
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
