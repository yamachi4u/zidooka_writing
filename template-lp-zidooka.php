<?php
/**
 * Template Name: LP - ZIDOOKA
 * Description: ZIDOOKA! ランディングページテンプレート
 */

get_header();
?>

<style>
:root {
  --primary: #2563eb;
  --primary-dark: #1d4ed8;
  --secondary: #16a34a;
  --secondary-dark: #15803d;
  --dark: #1f2937;
  --light: #ffffff;
  --light-gray: #f3f4f6;
  --text: #374151;
  --border-radius: 8px;
  --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Base styles */
body {
  font-family: 'Noto Sans JP', 'Poppins', sans-serif;
  color: var(--text);
  scroll-behavior: smooth;
  margin: 0;
  padding: 0;
  background-color: var(--light);
  line-height: 1.6;
}

h1, h2, h3, h4, h5, h6 {
  font-weight: 700;
}

/* Navbar styling */
.navbar {
  background: #ffffff !important;
  padding: 15px 0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid #e5e7eb;
}
.navbar.scrolled {
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.navbar-brand {
  font-weight: 600;
  font-size: 1.4rem;
  color: var(--primary) !important;
}
.nav-link {
  font-weight: 400;
  margin: 0 8px;
  color: var(--text) !important;
  padding: 8px 12px;
  border-radius: 4px;
  transition: background-color 0.2s;
}
.nav-link:hover {
  background-color: var(--light-gray);
  color: var(--primary) !important;
}

/* Hero section styling */
.hero-section {
  position: relative;
  min-height: 75vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: var(--text);
  background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 50%, #fef3c7 100%);
  padding: 100px 15px 80px;
  overflow: hidden;
}
.hero-section::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
  border-radius: 50%;
  z-index: 0;
}
.hero-section::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -5%;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(22, 163, 74, 0.06) 0%, transparent 70%);
  border-radius: 50%;
  z-index: 0;
}
.hero-content {
  max-width: 800px;
  padding: 2rem;
  position: relative;
  z-index: 1;
}
.hero-section h1 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  line-height: 1.3;
  color: var(--dark);
}
.hero-section h1 .highlight {
  position: relative;
  display: inline-block;
}
.hero-section h1 .highlight::after {
  content: '';
  position: absolute;
  bottom: 5px;
  left: 0;
  width: 100%;
  height: 12px;
  background: linear-gradient(to right, rgba(37, 99, 235, 0.2), rgba(37, 99, 235, 0.3));
  z-index: -1;
  border-radius: 4px;
}
.hero-section p {
  font-size: 1.25rem;
  margin-bottom: 2.5rem;
  color: var(--text);
  line-height: 1.7;
}
.hero-badge {
  display: inline-block;
  background: white;
  border: 1px solid #e5e7eb;
  padding: 8px 20px;
  border-radius: 50px;
  font-size: 0.9rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.hero-badge i {
  color: var(--primary);
  margin-right: 8px;
}
.btn-cta {
  margin-top: 20px;
  padding: 14px 32px;
  font-size: 1rem;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  font-weight: 500;
  transition: all 0.2s ease;
}
.btn-primary {
  background-color: var(--primary);
  color: white;
  border: 1px solid var(--primary);
}
.btn-primary:hover {
  background-color: var(--primary-dark);
  border-color: var(--primary-dark);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
}
.btn-cta-secondary {
  background-color: white;
  color: var(--primary);
  border: 1px solid var(--primary);
  margin-left: 15px;
}
.btn-cta-secondary:hover {
  background-color: var(--light-gray);
  color: var(--primary-dark);
}

/* Card styling */
.card {
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: none;
  transition: box-shadow 0.2s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}
.card-body {
  flex: 1;
  display: flex;
  flex-direction: column;
}

/* Section styling */
section {
  padding: 80px 0;
}
section h2 {
  font-size: 2.2rem;
  margin-bottom: 3rem;
  font-weight: 600;
  color: var(--dark);
}

/* Stats section */
.stats-item {
  text-align: center;
  padding: 30px 20px;
  border-radius: var(--border-radius);
  background: white;
  border: 1px solid #e5e7eb;
  min-height: 180px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.stats-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 10px;
  line-height: 1;
}

/* Features section */
#features {
  background-color: white;
}
.feature-card {
  padding: 30px;
  border-radius: var(--border-radius);
  border: 1px solid #e5e7eb;
  height: 100%;
  background: white;
  display: flex;
  flex-direction: column;
  min-height: 280px;
}
.feature-card > div {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.feature-card h3 {
  margin-top: auto;
}
.feature-card p {
  flex: 1;
}
.feature-icon {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  background: #eff6ff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}
.feature-icon i {
  color: var(--primary);
}

/* Services section */
.service-card {
  padding: 40px 30px;
  text-align: center;
  border-radius: var(--border-radius);
  background: white;
  height: 100%;
  border: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 380px;
}
.service-icon {
  font-size: 3rem;
  color: var(--primary);
  margin-bottom: 20px;
}
.service-card h3 {
  margin-bottom: 20px;
}
.service-card p {
  flex: 1;
  margin-bottom: 20px;
}
.service-card .btn {
  margin-top: auto;
}

/* About section */
.about-icon-box {
  padding: 40px;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  text-align: center;
}
.about-list li {
  margin-bottom: 15px;
  display: flex;
  align-items: center;
}
.about-list i {
  color: var(--primary);
  margin-right: 10px;
}

/* Testimonials section */
.testimonial-card {
  padding: 30px;
  text-align: center;
  border-radius: var(--border-radius);
  background: white;
  height: 100%;
  border: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  min-height: 280px;
}
.testimonial-card p {
  flex: 1;
  margin-bottom: 20px;
}
.testimonial-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
}
.testimonial-icon i {
  color: var(--primary);
}

/* Accordion styling */
.accordion-item {
  border: 1px solid #e5e7eb;
  margin-bottom: 12px;
  border-radius: var(--border-radius) !important;
  overflow: hidden;
}
.accordion-button {
  border-radius: var(--border-radius);
  background-color: white;
  font-weight: 500;
  padding: 16px 20px;
}
.accordion-button:not(.collapsed) {
  color: var(--primary);
  background-color: #f9fafb;
}
.accordion-button:focus {
  box-shadow: none;
  border-color: #e5e7eb;
}
.accordion-body {
  padding: 16px 20px;
  background-color: white;
}

/* Contact section */
.contact-card {
  border-radius: var(--border-radius);
  border: 1px solid #e5e7eb;
  padding: 40px;
}
#contact {
  margin-bottom: 80px;
}

/* Footer */
footer {
  background: var(--dark);
  color: white;
  padding: 30px 0;
  margin-top: 60px;
  position: relative;
  z-index: 1;
}

/* Scroll to top button */
#scrollTopBtn {
  display: none;
  position: fixed;
  bottom: 30px;
  right: 30px;
  z-index: 998;
  border: none;
  outline: none;
  background-color: var(--primary);
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 50%;
  font-size: 18px;
  width: 50px;
  height: 50px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}
#scrollTopBtn:hover {
  background-color: var(--primary-dark);
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

/* Profile section */
.profile-section {
  background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
  border-top: 1px solid #e5e7eb;
  border-bottom: 1px solid #e5e7eb;
}
.profile-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  padding: 40px;
  max-width: 600px;
  margin: 0 auto;
}
.profile-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
}
.profile-card li {
  padding: 12px 0;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  align-items: center;
}
.profile-card li:last-child {
  border-bottom: none;
}
.profile-card li i {
  color: var(--primary);
  margin-right: 12px;
  width: 20px;
  text-align: center;
}

/* SP向けフローティングバナー */
.floating-banner {
  display: none;
}

@media (max-width: 768px) {
  .floating-banner {
    display: block;
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: var(--primary);
    color: #fff;
    text-align: center;
    padding: 14px 0;
    z-index: 100;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
  }
  .floating-banner a {
    color: #fff;
    font-weight: 500;
    text-decoration: none;
    font-size: 1rem;
    display: block;
  }
  section {
    padding: 60px 0;
  }
  .hero-section {
    min-height: 65vh;
    padding: 100px 15px 50px;
  }
  .hero-section::before,
  .hero-section::after {
    width: 300px;
    height: 300px;
  }
  .hero-section h1 {
    font-size: 2rem;
  }
  .hero-section p {
    font-size: 1.1rem;
  }
  .hero-badge {
    font-size: 0.85rem;
    padding: 6px 16px;
  }
  .btn-cta-secondary {
    margin-left: 0;
    margin-top: 10px;
  }
}
</style>

<!-- スマホ向けフローティングバナー -->
<div class="floating-banner">
  <a href="#contact">
    <i class="fas fa-envelope me-2"></i>今すぐ無料相談してみませんか？<br> お気軽にお問い合わせください
  </a>
</div>

<!-- ナビゲーションバー -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo home_url('/'); ?>">ZIDOOKA!</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="#services">サービス</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">特徴</a></li>
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#qa">Q&A</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">お問い合わせ</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ヒーローセクション -->
<header class="hero-section">
  <div class="hero-content">
    <div class="hero-badge">
      <i class="fas fa-check-circle"></i>
      フリーランスだから柔軟で安い！
    </div>
    <h1>業務効率化で<span class="highlight" style="color: var(--primary);">時間</span>を「作る」</h1>
    <p>
      繰り返し作業を自動化して、本当に大切な業務に集中しませんか？
    </p>
    <div class="d-flex flex-wrap justify-content-center">
      <a href="#contact" class="btn btn-cta btn-primary">今すぐ無料相談をする</a>
      <a href="#services" class="btn btn-cta btn-cta-secondary">サービスを確認する</a>
    </div>
  </div>
</header>

<!-- Aboutセクション -->
<section id="about" class="py-5 bg-light-gray">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 order-lg-1 order-2">
        <h2 class="text-start mb-4">About Zidooka!</h2>
        <p class="lead fw-bold mb-4">
          業務効率化のスペシャリストとして、お客様の作業負荷を徹底的に削減します。
        </p>
        <p>
          Google Apps Scriptやマクロを活用して面倒な手作業を自動化し、大切な時間をより価値のある業務にシフトしていただけます。
        </p>
        <div class="mt-4">
          <h4 class="h5 mb-3">得意分野</h4>
          <ul class="list-unstyled about-list">
            <li><i class="fas fa-check-circle text-primary me-2"></i>Google Apps Script 開発</li>
            <li><i class="fas fa-check-circle text-primary me-2"></i>Excel マクロ開発・改修</li>
            <li><i class="fas fa-check-circle text-primary me-2"></i>業務効率化コンサルティング</li>
            <li><i class="fas fa-check-circle text-primary me-2"></i>スプレッドシート自動化</li>
          </ul>
        </div>
        <a href="#contact" class="btn btn-primary mt-4">無料相談する</a>
      </div>
      <div class="col-lg-6 order-lg-2 order-1 mb-5 mb-lg-0">
        <div class="about-icon-box">
          <i class="fas fa-laptop-code fa-6x text-primary mb-4"></i>
          <h3 class="h4">シンプル & スピーディー</h3>
          <p>
            必要な機能のみ実装し、無駄を省いた迅速な開発を実現します。
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 実績セクション -->
<section class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">実績と信頼</h2>
    <div class="row g-4 mb-5">
      <div class="col-md-4">
        <div class="stats-item">
          <div class="stats-number">50+</div>
          <p class="lead mb-0">取引実績</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-item">
          <div class="stats-number">100%</div>
          <p class="lead mb-0">契約完了率</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-item">
          <div class="stats-number">4.9</div>
          <p class="lead mb-0">平均評価（5点満点）<br> ※クラウドソーシングサイトの評価=25年3月時点</p>
        </div>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-building text-primary me-2"></i>
              企業・団体様の実績
            </h5>
            <ul class="list-unstyled">
              <li class="mb-3 p-3 bg-light rounded">
                <strong>薬剤師会様</strong><br>
                Google Apps Scriptによる機器貸出予約システムを開発
              </li>
              <li class="mb-3 p-3 bg-light rounded">
                <strong>システム開発企業様</strong><br>
                Google API / ZOOM連携システムを構築
              </li>
              <li class="mb-3 p-3 bg-light rounded">
                <strong>その他多数</strong><br>
                Excel業務効率化やデータ分析基盤の構築
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card h-100">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-code text-primary me-2"></i>
              開発実績例
            </h5>
            <ul class="list-unstyled">
              <li class="mb-3 p-3 bg-light rounded">
                <i class="fas fa-check text-primary me-2"></i>
                Amazon商品データ取得自動化システム
              </li>
              <li class="mb-3 p-3 bg-light rounded">
                <i class="fas fa-check text-primary me-2"></i>
                ニュースサイトスクレイピングツール
              </li>
              <li class="mb-3 p-3 bg-light rounded">
                <i class="fas fa-check text-primary me-2"></i>
                WordPress自動投稿システム（ChatGPT連携）
              </li>
              <li class="mb-3 p-3 bg-light rounded">
                <i class="fas fa-check text-primary me-2"></i>
                バーコード生成・管理システム
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- CTAセクション -->
    <div class="text-center mt-5">
      <a href="#contact" class="btn btn-primary btn-lg">今すぐ無料相談をする</a>
    </div>
  </div>
</section>

<!-- 他社との比較セクション -->
<section id="comparison" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">他社との比較</h2>
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="bg-light">
                  <tr>
                    <th class="text-center">比較項目</th>
                    <th class="text-center text-primary">Zidooka<br>(フリーランス)</th>
                    <th class="text-center">開発会社</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="fw-bold">初期費用</td>
                    <td class="text-center text-primary fw-bold">0円</td>
                    <td class="text-center">3万円〜</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">月額固定費</td>
                    <td class="text-center text-primary fw-bold">0円</td>
                    <td class="text-center">1万円〜</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">開発単価</td>
                    <td class="text-center text-primary fw-bold">5,000円〜</td>
                    <td class="text-center">1万円〜</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">納期</td>
                    <td class="text-center text-primary fw-bold">最短1日</td>
                    <td class="text-center">1週間〜</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">外注コスト</td>
                    <td class="text-center text-primary fw-bold">なし<br>(直接対応)</td>
                    <td class="text-center">あり<br>(中間マージン発生)</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-coins text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">圧倒的なコスト優位性</h3>
            <p>
              固定費ゼロの事業構造と外注なしの直接対応で、中間マージンを削減。必要な機能だけを実装することで無駄なコストを抑えています。
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-bolt text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">驚異的なスピード感</h3>
            <p>
              一人で対応するため意思決定が速く、承認プロセスなしで即断即決。柔軟なスケジュールで最短1日での納品が可能です。
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-check-circle text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">一貫した品質</h3>
            <p>
              要件定義から開発、テスト、運用サポートまで一貫して同一人物が担当。コミュニケーションロスがなく、責任の所在が明確です。
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- CTAセクション -->
    <div class="text-center mt-5">
      <a href="#contact" class="btn btn-primary btn-lg">今すぐ無料相談をする</a>
    </div>
  </div>
</section>

<!-- サービスセクション -->
<section id="services" class="py-5 bg-light-gray">
  <div class="container">
    <h2 class="text-center mb-5">提供サービス</h2>
    <p class="text-center lead mb-5">
      課題に寄り添い、状況や要望に合わせた最適な提案を行います。<br>
      簡単な内容なら1日での納品も可能です。
    </p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-robot"></i>
          </div>
          <h3 class="h4 mb-3">業務自動化</h3>
          <p>
            Google Apps Scriptを使用して繰り返し作業を自動化し、手作業の負担を軽減します。
          </p>
          <a href="#contact" class="btn btn-primary mt-4">相談する</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-code"></i>
          </div>
          <h3 class="h4 mb-3">マクロ開発・改修</h3>
          <p>
            既存マクロの改修や新規開発により業務効率を向上し、Excelやスプレッドシートの操作を簡易化します。
          </p>
          <a href="#contact" class="btn btn-primary mt-4">無料見積もりを依頼する</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="service-card">
          <div class="service-icon">
            <i class="fas fa-comments"></i>
          </div>
          <h3 class="h4 mb-3">コンサルティング</h3>
          <p>
            業務効率化のための最適な手段を共に検討し、具体的な解決策を提案します。
          </p>
          <a href="#contact" class="btn btn-primary mt-4">まずは相談する</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 特徴セクション -->
<section id="features" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">選ばれる理由</h2>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-clock text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">柔軟な対応時間</h3>
            <p>
              フリーランスだからこそ実現できる柔軟なスケジュールで、急ぎの案件にも迅速に対応します。
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-coins text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">リーズナブルな料金</h3>
            <p>
              低固定費を活かし、コストパフォーマンスの高いサービスを提供します。余計な費用をかけずに開発できます。
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-bolt text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">スピード対応</h3>
            <p>
              小規模だからこそフットワークが軽く、簡単な案件なら最短1日での納品が可能です。
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-user-shield text-primary fa-2x"></i>
          </div>
          <div>
            <h3 class="h5 mb-3">安心のサポート</h3>
            <p>
              導入後のフォローアップや追加要望にも柔軟に対応し、長期的に安心して運用できる体制を整えます。
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- CTAセクション -->
    <div class="text-center mt-5">
      <a href="#contact" class="btn btn-primary btn-lg">今すぐ無料相談をする</a>
    </div>
  </div>
</section>

<!-- お客様の声セクション -->
<section id="testimonials" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">お客様の声</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-icon">
            <i class="fas fa-user-circle fa-3x text-primary"></i>
          </div>
          <p class="mb-4">
            "迅速丁寧な対応ありがとうございました。期待以上の成果でした！"
          </p>
          <h5 class="h6 fw-bold">サイト構築・ウェブ開発</h5>
          <small class="text-muted">2025年02月01日</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-icon">
            <i class="fas fa-user-circle fa-3x text-primary"></i>
          </div>
          <p class="mb-4">
            "要望説明が明確で、作業がとてもスムーズに進みました。"
          </p>
          <h5 class="h6 fw-bold">Excelマクロ構築</h5>
          <small class="text-muted">2024年06月24日</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-icon">
            <i class="fas fa-user-circle fa-3x text-primary"></i>
          </div>
          <p class="mb-4">
            "細かい調整にも迅速に対応いただき、大変助かりました。"
          </p>
          <h5 class="h6 fw-bold">Google Apps Script開発</h5>
          <small class="text-muted">2024年05月15日</small>
        </div>
      </div>
    </div>
    <!-- CTAセクション -->
    <div class="text-center mt-5">
      <a href="#contact" class="btn btn-primary btn-lg">今すぐ無料相談をする</a>
    </div>
  </div>
</section>

<!-- Q&Aセクション -->
<section id="qa" class="py-5 bg-light-gray">
  <div class="container">
    <h2 class="text-center mb-5">よくあるご質問</h2>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                どのような業務を自動化できますか？
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Google Apps Scriptを使用して、スプレッドシートの操作、メール送信、データ収集、API連携など、様々な業務を自動化できます。まずはご相談ください。
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                見積もりは無料ですか？
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                はい、初回のご相談と見積もりは無料です。お気軽にお問い合わせください。
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                納期はどのくらいかかりますか？
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                案件の規模によりますが、簡単な内容であれば最短1日での納品も可能です。詳細なスケジュールはご相談時にお伝えします。
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                導入後のサポートはありますか？
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                はい、導入後のフォローアップや追加要望にも柔軟に対応しております。長期的なお付き合いを大切にしています。
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- CTAセクション -->
    <div class="text-center mt-5">
      <a href="#contact" class="btn btn-primary btn-lg">今すぐ無料相談をする</a>
    </div>
  </div>
</section>

<!-- お問い合わせセクション -->
<section id="contact" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5">お問い合わせ</h2>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="contact-card">
          <p class="text-center lead mb-4">
            お気軽にお問い合わせください。<br>
            24時間以内にご返信いたします。
          </p>
          <?php
          // Contact Form 7ショートコードがあれば表示
          if (shortcode_exists('contact-form-7')) {
            echo do_shortcode('[contact-form-7 id="1" title="お問い合わせ"]');
          } else {
            // フォームがない場合の代替表示
          ?>
          <div class="text-center">
            <p>お問い合わせフォームを設置してください。</p>
            <p>Contact Form 7などのプラグインでショートコードを設定できます。</p>
            <a href="mailto:contact@zidooka.com" class="btn btn-primary btn-lg">
              <i class="fas fa-envelope me-2"></i>メールでお問い合わせ
            </a>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- トップへ戻るボタン -->
<button id="scrollTopBtn" title="トップへ戻る">
  <i class="fas fa-arrow-up"></i>
</button>

<script>
// スクロールトップボタン
document.addEventListener('DOMContentLoaded', function() {
  var scrollTopBtn = document.getElementById('scrollTopBtn');
  
  window.onscroll = function() {
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
      scrollTopBtn.style.display = 'block';
    } else {
      scrollTopBtn.style.display = 'none';
    }
  };
  
  scrollTopBtn.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
});
</script>

<?php get_footer(); ?>
