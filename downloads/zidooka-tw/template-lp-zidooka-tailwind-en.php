<?php
/**
 * Template Name: LP - ZIDOOKA (EN)
 * Description: ZIDOOKA! landing page template (Tailwind CSS)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Time by Automating Work - ZIDOOKA!</title>
  <meta name="description" content="Automate operations with Google Apps Script and macros. Flexible, affordable freelance support. Fast delivery.">

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
    
    /* Single-color highlight text */
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
        display: block;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #2563eb;
        color: white;
        padding: 16px;
        z-index: 100;
        text-align: center;
        font-weight: 600;
      }
    }
    
    .highlight-row {
      background-color: rgba(37, 99, 235, 0.08);
      border-left: 4px solid #2563eb;
    }
    
    .nav-hidden { transform: translateY(-100%); }
    .nav-visible { transform: translateY(0); }
  </style>
</head>
<body class="font-sans text-slate-800 antialiased bg-slate-50">

  <!-- Mobile floating CTA -->
  <a href="#contact" class="floating-cta">
    <i class="fas fa-rocket mr-2"></i>Get a Free Consultation Now
  </a>

  <!-- Simple header -->
  <header id="mainNav" class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 nav-hidden">
    <div class="glass mx-4 mt-4 rounded-2xl px-6 py-4 shadow-lg">
      <div class="flex items-center justify-between max-w-7xl mx-auto">
        <a href="#" class="text-2xl font-black text-primary-700">ZIDOOKA!</a>
        <nav class="hidden md:flex items-center gap-8">
          <a href="#services" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">Services</a>
          <a href="#features" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">Features</a>
          <a href="#about" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">About</a>
          <a href="#comparison" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition">Comparison</a>
          <a href="#contact" class="bg-primary-600 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-primary-700 transition">
            Free Consultation
          </a>
        </nav>
        <button id="mobileMenuBtn" class="md:hidden text-slate-600 text-2xl">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile menu -->
  <div id="mobileMenu" class="fixed inset-0 z-40 bg-white hidden">
    <div class="p-6">
      <div class="flex justify-between items-center mb-8">
        <span class="text-2xl font-black text-primary-700">ZIDOOKA!</span>
        <button id="closeMobileMenu" class="text-2xl text-slate-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <nav class="flex flex-col gap-4">
        <a href="#services" class="text-lg font-medium py-3 border-b border-slate-100">Services</a>
        <a href="#features" class="text-lg font-medium py-3 border-b border-slate-100">Features</a>
        <a href="#about" class="text-lg font-medium py-3 border-b border-slate-100">About</a>
        <a href="#comparison" class="text-lg font-medium py-3 border-b border-slate-100">Comparison</a>
        <a href="#contact" class="bg-primary-600 text-white text-center py-4 rounded-xl font-semibold mt-4">Get a Free Consultation</a>
      </nav>
    </div>
  </div>

  <!-- Hero section -->
  <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-primary-950">
    <!-- Decorative circles (single color) -->
    <div class="blob bg-primary-600 w-96 h-96 top-20 -left-20 animate-float"></div>
    <div class="blob bg-primary-700 w-80 h-80 bottom-20 -right-20 animate-float-delayed"></div>
    <div class="blob bg-primary-500 w-64 h-64 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
    
    <!-- Grid pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Cpath%20d%3D%22M36%2034v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6%2034v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6%204V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
      <!-- Badge -->
      <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8 animate-fade-in-up">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
        </span>
        <span class="text-white/90 text-sm font-medium">Freelance: flexible and cost-effective</span>
      </div>
      
      <!-- Main title -->
      <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight animate-fade-in-up" style="animation-delay: 0.1s;">
        Create time<br>
        <span class="text-accent-500">with automation</span>
      </h1>
      
      <!-- Subtitle -->
      <p class="text-xl md:text-2xl text-white/70 mb-12 max-w-2xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
        Automate repetitive tasks and focus on what matters most.<br class="md:hidden">
      </p>
      
      <!-- CTA buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up" style="animation-delay: 0.3s;">
        <a href="#contact" class="group relative inline-flex items-center gap-2 bg-accent-500 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-accent-600 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <span>Get a Free Consultation Now</span>
          <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>
        <a href="#services" class="inline-flex items-center gap-2 text-white/80 hover:text-white px-8 py-4 rounded-full font-medium text-lg border border-white/20 hover:bg-white/10 transition-all duration-300">
          <i class="fas fa-play-circle"></i>
          <span>See Services</span>
        </a>
      </div>
      
      <!-- Scroll indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/40 animate-bounce">
        <i class="fas fa-chevron-down text-2xl"></i>
      </div>
    </div>
  </section>

  <!-- Track record section -->
  <section class="py-20 bg-white relative">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-3 gap-8 -mt-32 relative z-10">
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">50+</div>
          <div class="text-slate-600 font-medium">Track Record</div>
          <div class="text-xs text-slate-400 mt-2">Figures are from CrowdWorks.</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">100%</div>
          <div class="text-slate-600 font-medium">Contract Completion Rate</div>
          <div class="text-xs text-slate-400 mt-2">Figures are from CrowdWorks.</div>
        </div>
        <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="text-5xl font-black text-primary-600 mb-2">4.9</div>
          <div class="text-slate-600 font-medium">Average Rating (out of 5)</div>
          <div class="text-xs text-slate-400 mt-2">Figures are from CrowdWorks (as of Mar 2025).</div>
        </div>
      </div>
    </div>
  </section>

  <!-- About section -->
  <section id="about" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div class="order-2 lg:order-1">
          <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">About Us</span>
          <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-tight">
            Automation<br><span class="text-primary-600">Specialist</span>
          </h2>
          <p class="text-lg text-slate-600 mb-6 leading-relaxed">
            I reduce your operational workload by automating manual tasks with Google Apps Script and macros, so you can spend more time on high-value work.
          </p>
          
          <div class="space-y-4 mb-8">
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Google Apps Script Development</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Excel Macro Development & Refactoring</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Business Automation Consulting</span>
            </div>
            <div class="flex items-center gap-4 p-4 bg-white rounded-xl shadow-sm border border-slate-100">
              <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-primary-600 text-xl"></i>
              </div>
              <span class="font-medium text-slate-700">Spreadsheet Automation</span>
            </div>
          </div>
          
          <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg">
            Get a Free Consultation
            <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <div class="order-1 lg:order-2">
          <div class="relative">
            <div class="absolute -inset-4 bg-primary-200 rounded-3xl opacity-50 blur-2xl"></div>
            <div class="relative bg-white rounded-2xl p-10 shadow-2xl border border-slate-100">
              <div class="w-20 h-20 bg-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                <i class="fas fa-laptop-code text-4xl text-white"></i>
              </div>
              <h3 class="text-2xl font-bold text-slate-900 mb-3">Simple & Fast</h3>
              <p class="text-slate-600 leading-relaxed">
                I build only what you need for fast delivery, without unnecessary cost.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Portfolio highlights section -->
  <section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Portfolio</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">Results & Trust</h2>
      </div>
      
      <div class="grid md:grid-cols-2 gap-8">
        <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover-lift">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-building text-primary-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Clients & Outcomes</h3>
          </div>
          <div class="space-y-4">
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">Rental Business</div>
              <div class="text-sm text-slate-600">Built a reservation system with Google Apps Script.</div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">Software Company</div>
              <div class="text-sm text-slate-600">Built a Google API + Zoom integration system.</div>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border-l-4 border-primary-500">
              <div class="font-bold text-slate-900 mb-1">And more</div>
              <div class="text-sm text-slate-600">Excel automation and data analysis pipelines.</div>
            </div>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100 hover-lift">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-code text-primary-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Example Projects</h3>
          </div>
          <div class="space-y-4">
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-primary-600 text-sm"></i>
              </div>
              <span class="text-slate-700">E‑commerce product data automation</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-primary-600 text-sm"></i>
              </div>
              <span class="text-slate-700">News scraping tool</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-primary-600 text-sm"></i>
              </div>
              <span class="text-slate-700">WordPress auto‑posting (ChatGPT integrated)</span>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-xl shadow-sm">
              <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fas fa-check text-primary-600 text-sm"></i>
              </div>
              <span class="text-slate-700">Barcode generation & management</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg">
          Get a Free Consultation Now
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Comparison section -->
  <section id="comparison" class="py-24 bg-primary-50 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
      <div class="text-center mb-16">
        <span class="inline-block text-accent-500 font-bold text-sm tracking-wider uppercase mb-4">Comparison</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">Comparison with Agencies</h2>
        <p class="text-slate-600 text-lg">High value made possible by a lean freelance setup.</p>
      </div>
      
      <div class="max-w-4xl mx-auto mb-16">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                  <th class="px-6 py-5 text-left text-slate-600 font-medium">Item</th>
                  <th class="px-6 py-5 text-center">
                    <div class="inline-block bg-primary-600 text-white px-4 py-2 rounded-lg font-bold">
                      Zidooka
                    </div>
                  </th>
                  <th class="px-6 py-5 text-center text-slate-600 font-medium">Agency</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">Setup Fee</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">$0</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">from $300</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold text-slate-900">Monthly Retainer</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">$0</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">from $100</td>
                </tr>
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">Hourly / Task Rate</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">from $50</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">from $100</td>
                </tr>
                <tr>
                  <td class="px-6 py-5 font-semibold text-slate-900">Delivery</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-3xl font-black text-accent-500">as fast as 1 day</span>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">from 1 week</td>
                </tr>
                <tr class="bg-primary-50/50">
                  <td class="px-6 py-5 font-semibold text-slate-900">Middleman Cost</td>
                  <td class="px-6 py-5 text-center">
                    <span class="text-xl font-bold text-accent-500">None</span>
                    <div class="text-sm text-slate-500">Direct support</div>
                  </td>
                  <td class="px-6 py-5 text-center text-slate-500">
                    <span class="text-xl font-bold text-slate-500">Yes</span>
                    <div class="text-sm text-slate-400">Agency markup</div>
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
          <h3 class="text-xl font-bold text-slate-900 mb-3">Cost Advantage</h3>
          <p class="text-slate-600 leading-relaxed">
            Lean overhead and direct delivery reduce costs. You get only what you need.
          </p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
            <i class="fas fa-bolt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Fast Turnaround</h3>
          <p class="text-slate-600 leading-relaxed">
            Single‑owner delivery enables fast decisions and quick turnaround, sometimes in 1 day.
          </p>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
            <i class="fas fa-shield-alt text-white text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Consistent Quality</h3>
          <p class="text-slate-600 leading-relaxed">
            One person handles scoping, development, testing, and support. No handoff loss.
          </p>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-accent-500 text-white px-8 py-4 rounded-full font-bold hover:bg-accent-600 transition shadow-lg">
          Get a Free Consultation Now
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Services section -->
  <section id="services" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Services</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">Services</h2>
        <p class="text-slate-600 text-lg max-w-2xl mx-auto">
          I propose the most effective approach for your needs and context.<br>
          Simple requests can be delivered within a day.
        </p>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-robot text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">Workflow Automation</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            Automate repetitive tasks with Google Apps Script to reduce manual workload.
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            Talk to me <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-code text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">Macro Development & Refactor</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            Improve efficiency by building or refactoring macros for Excel and spreadsheets.
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            Request a free estimate <i class="fas fa-arrow-right"></i>
          </a>
        </div>
        
        <div class="group bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 hover-lift">
          <div class="w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
            <i class="fas fa-comments text-white text-3xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-4">Consulting</h3>
          <p class="text-slate-600 mb-6 leading-relaxed">
            We review your process together and propose concrete solutions.
          </p>
          <a href="#contact" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:gap-3 transition-all">
            Start with a consult <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Features section -->
  <section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Why Choose Us</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">Why Clients Choose Me</h2>
      </div>
      
      <div class="grid md:grid-cols-2 gap-6">
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
            <i class="fas fa-clock text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Flexible Response Time</h3>
            <p class="text-slate-600">Freelance flexibility means fast response for urgent requests.</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
            <i class="fas fa-coins text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Affordable Pricing</h3>
            <p class="text-slate-600">Low overhead enables strong value for money.</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
            <i class="fas fa-bolt text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Fast Delivery</h3>
            <p class="text-slate-600">Lean setup enables quick delivery, sometimes within a day.</p>
          </div>
        </div>
        
        <div class="flex gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 hover-lift">
          <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
            <i class="fas fa-user-shield text-white text-2xl"></i>
          </div>
          <div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Reliable Support</h3>
            <p class="text-slate-600">Flexible follow‑up and ongoing support after launch.</p>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg">
          Get a Free Consultation Now
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Testimonials section -->
  <section id="testimonials" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Testimonials</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">Testimonials</h2>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-primary-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "Quick and polite support—results exceeded expectations!"
          </p>
          <div class="font-bold text-slate-900">Website build / web development</div>
          <div class="text-sm text-slate-400 mt-1">Feb 1, 2025</div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-primary-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "Clear requirements and smooth execution."
          </p>
          <div class="font-bold text-slate-900">Excel macro build</div>
          <div class="text-sm text-slate-400 mt-1">Jun 24, 2024</div>
        </div>
        
        <div class="bg-white rounded-2xl p-8 shadow-lg shadow-slate-200/50 border border-slate-100 text-center hover-lift">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-user text-primary-600 text-2xl"></i>
          </div>
          <p class="text-slate-700 mb-6 italic leading-relaxed">
            "Great delivery plus helpful feature suggestions."
          </p>
          <div class="font-bold text-slate-900">Google Apps Script</div>
          <div class="text-sm text-slate-400 mt-1">May 7, 2024</div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg">
          Get a Free Consultation Now
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- FAQ section -->
  <section id="qa" class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">FAQ</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">FAQ</h2>
      </div>
      
      <div class="space-y-4">
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq1')">
            <span class="font-bold text-slate-900 text-lg">How long does delivery take?</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq1"></i>
          </button>
          <div id="faq1" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              Depends on scope, but simple automation can be delivered in as little as one day.
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq2')">
            <span class="font-bold text-slate-900 text-lg">What is the typical budget?</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq2"></i>
          </button>
          <div id="faq2" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              Simple scripts start around $50. Complex systems vary widely based on scope.
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq3')">
            <span class="font-bold text-slate-900 text-lg">How can we communicate?</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq3"></i>
          </button>
          <div id="faq3" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              Email (<strong>main@zidooka.com</strong>), Zoom, and Chatwork are available. CrowdWorks is also welcome.
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq4')">
            <span class="font-bold text-slate-900 text-lg">Can I request additional features later?</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq4"></i>
          </button>
          <div id="faq4" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              Yes, I can handle enhancements and refinements after launch.
            </p>
          </div>
        </div>
        
        <div class="bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
          <button class="w-full px-8 py-6 text-left flex justify-between items-center hover:bg-slate-100 transition" onclick="toggleFaq('faq5')">
            <span class="font-bold text-slate-900 text-lg">How does payment work?</span>
            <i class="fas fa-chevron-down text-primary-600 transition-transform" id="icon-faq5"></i>
          </button>
          <div id="faq5" class="px-8 pb-6 hidden">
            <p class="text-slate-600 leading-relaxed">
              Invoices are issued monthly. If preferred, you can place orders via <a href="https://crowdworks.jp/public/employers/1459615" target="_blank" rel="noopener noreferrer" class="text-accent-500 hover:underline">CrowdWorks</a>.
            </p>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#contact" class="inline-flex items-center gap-2 bg-accent-500 text-white px-8 py-4 rounded-full font-bold hover:bg-accent-600 transition shadow-lg">
          Contact me now
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Profile section -->
  <section id="profile" class="py-24 bg-slate-50">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-primary-600 font-bold text-sm tracking-wider uppercase mb-4">Profile</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900">Profile</h2>
      </div>
      
      <div class="bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100">
        <div class="space-y-4">
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-map-marker-alt text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">Zidooka! (Sole proprietor, Kyoto, Japan — Kazunori Yamaguchi)</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-user text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">Male, 27</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-briefcase text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">Freelance</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-code text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">10 years in programming/engineering</span>
          </div>
          <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <i class="fas fa-laptop-code text-primary-600 text-xl"></i>
            </div>
            <span class="text-slate-700">Websites, business automation</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact section -->
  <section id="contact" class="py-24 bg-white relative">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center mb-12">
        <span class="inline-block text-accent-500 font-bold text-sm tracking-wider uppercase mb-4">Contact</span>
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">Contact</h2>
        <p class="text-slate-600 text-lg mb-4">
          Email me at <strong class="text-slate-900">main@zidooka.com</strong>.
        </p>
        <p class="text-slate-500">
          <a href="https://crowdworks.jp/public/employers/1459615" target="_blank" rel="noopener noreferrer" class="text-accent-500 hover:text-accent-600 underline">
            CrowdWorks
          </a>
          Other platforms are also welcome.
        </p>
      </div>
      
      <div class="bg-slate-50 rounded-3xl p-8 md:p-12 border border-slate-100">
        <div class="text-center mb-8">
          <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-clipboard-list text-4xl text-primary-600"></i>
          </div>
          <h3 class="text-2xl font-bold text-slate-900 mb-3">Free Consultation Form</h3>
          <p class="text-slate-600 mb-2">
            Sharing your budget and timeline helps us move faster.
          </p>
          <p class="text-sm text-slate-500">
            If your request is still vague, feel free to describe it.<br>
            I aim to reply within 24 hours (up to 3 business days).
          </p>
        </div>
        
        <div class="flex flex-col gap-4">
          <a href="https://docs.google.com/forms/d/e/1FAIpQLSdsaBbQn208NuejNs3UPCx_AXsP0cImtvLStGAhQ2Ob92e23Q/viewform?usp=dialog" 
              target="_blank" 
              rel="noopener noreferrer"
              data-ga-event="lp_form_open"
              data-ga-location="contact_section"
              data-ga-label="google_form"
              class="group inline-flex items-center justify-center gap-3 bg-accent-500 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-accent-600 transition-all duration-300 hover:-translate-y-1 shadow-lg">
            <i class="fab fa-google-drive text-xl"></i>
            <span>Open the free consultation form (Google Form)</span>
            <i class="fas fa-external-link-alt group-hover:translate-x-1 transition-transform"></i>
          </a>
          
          <div class="text-center">
            <p class="text-slate-500 text-sm mb-3">Need to talk sooner?</p>
            <a href="mailto:main@zidooka.com" 
               data-ga-event="lp_mail_click"
               data-ga-location="contact_section"
               data-ga-label="mailto"
               class="inline-flex items-center gap-2 border-2 border-slate-300 text-slate-700 px-6 py-3 rounded-full font-semibold hover:bg-slate-100 transition">
              <i class="fas fa-envelope"></i>
              Email directly
            </a>
          </div>
        </div>
      </div>
      
      <!-- Extra CTA -->
      <div class="mt-12 text-center">
        <div class="bg-primary-50 rounded-2xl p-8 border border-primary-100">
          <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-paper-plane text-primary-600 text-2xl"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-900 mb-3">Feel free to reach out</h3>
          <p class="text-slate-600 mb-6">
            Small questions and early‑stage ideas are welcome.<br>
            Please contact me anytime.
          </p>
          <a href="mailto:main@zidooka.com" 
             data-ga-event="lp_mail_click"
             data-ga-location="contact_section_secondary"
             data-ga-label="mailto"
             class="inline-flex items-center gap-2 bg-primary-600 text-white px-8 py-4 rounded-full font-bold hover:bg-primary-700 transition shadow-lg">
            <i class="fas fa-envelope"></i>
            Contact by email
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-slate-100 text-slate-500 py-12 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-2xl font-black text-primary-600">ZIDOOKA!</div>
        <div class="text-sm">
          &copy; <?php echo date('Y'); ?> All rights reserved.
        </div>
        <div class="flex gap-4">
          <a href="#services" class="hover:text-primary-600 transition">Services</a>
          <a href="#features" class="hover:text-primary-600 transition">Features</a>
          <a href="#contact" class="hover:text-primary-600 transition">Contact</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to top button -->
  <button id="scrollTopBtn" class="fixed bottom-8 right-8 bg-primary-600 text-white w-14 h-14 rounded-full shadow-2xl hover:bg-primary-700 transition hidden z-50 flex items-center justify-center hover:scale-110" title="Back to top">
    <i class="fas fa-arrow-up text-xl"></i>
  </button>

  <script>
  // Navigation display control
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

  // Auto-tag #contact anchors to capture CTA intent
  document.querySelectorAll('a[href="#contact"]').forEach((a, idx) => {
    if (a.dataset.gaEvent) return;
    a.dataset.gaEvent = 'lp_cta_click';
    a.dataset.gaLocation = a.classList.contains('floating-cta') ? 'floating_cta' : 'contact_anchor';
    a.dataset.gaLabel = (a.textContent || '').trim().slice(0, 80) || `contact_anchor_${idx + 1}`;
  });

  // Mobile menu
  document.getElementById('mobileMenuBtn').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.remove('hidden');
  });
  
  document.getElementById('closeMobileMenu').addEventListener('click', () => {
    document.getElementById('mobileMenu').classList.add('hidden');
  });
  
  // FAQ toggle
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
  
  // Scroll-to-top button
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
