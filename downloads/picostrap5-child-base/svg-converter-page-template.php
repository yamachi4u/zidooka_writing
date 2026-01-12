<?php
/**
 * Template Name: SVG変換サービス
 * 
 * SVGのコードをプレビューしたり、PNG/JPG形式で保存できるサービスのページテンプレート
 */

get_header(); // ヘッダーを読み込み

// SVG変換サービスのスクリプトを読み込む
$script_path = dirname(__FILE__) . '/script.js';
$script_exists = file_exists($script_path);

?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <?php
            // 固定ページのコンテンツ（説明文など）を表示
            while (have_posts()) :
                the_post();
            ?>
                <div class="page-header mb-4">
                    <h1><?php the_title(); ?></h1>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php
            endwhile;
            ?>

            <!-- SVG変換サービス -->
            <div class="svg-converter-container">
                <style>
                    /* SVG変換サービス専用のスタイル */
                    .svg-converter-container textarea {
                        font-family: monospace;
                        resize: vertical;
                    }
                    
                    .svg-converter-container #preview-container {
                        min-height: 300px;
                        border: 1px dashed #ddd;
                        padding: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background-color: rgba(255, 255, 255, 0.5);
                        overflow: auto;
                    }
                    
                    .svg-converter-container .setting-item {
                        display: flex;
                        align-items: center;
                        margin-bottom: 0.5rem;
                    }
                    
                    .svg-converter-container .setting-item label {
                        min-width: 80px;
                        margin-right: 10px;
                    }
                    
                    .svg-converter-container #quality-value {
                        font-weight: bold;
                        margin-left: 10px;
                    }
                    
                    .svg-converter-container .btn-danger {
                        background-color: #dc3545;
                        border-color: #dc3545;
                    }
                    
                    .svg-converter-container .btn-danger:hover {
                        background-color: #bb2d3b;
                        border-color: #b02a37;
                    }
                </style>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">SVG変換サービス</h2>
                        <p class="mb-0 small">SVGコードをプレビューしたり、PNG/JPG形式で保存できます</p>
                    </div>
                    
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- 入力セクション -->
                            <div class="col-lg-6">
                                <h3 class="h5 mb-3 border-bottom pb-2">SVGコード</h3>
                                <textarea id="svg-code" class="form-control mb-3" rows="8" 
                                    placeholder="ここにSVGコードを入力してください...例: <svg width='100' height='100'><circle cx='50' cy='50' r='40' fill='red'/></svg>"></textarea>
                                
                                <div class="mb-3">
                                    <button id="preview-btn" class="btn btn-primary">プレビュー</button>
                                    <button id="clear-btn" class="btn btn-danger">クリア</button>
                                    <label class="btn btn-outline-secondary ms-2">
                                        <i class="fas fa-upload"></i> SVGをアップロード
                                        <input type="file" id="upload-svg" accept=".svg" style="display:none">
                                    </label>
                                </div>
                                
                                <div class="sample-buttons">
                                    <h4 class="h6 mb-2">サンプルを試す:</h4>
                                    <button id="sample-circle" class="btn btn-sm btn-outline-secondary">円</button>
                                    <button id="sample-rect" class="btn btn-sm btn-outline-secondary">四角形</button>
                                    <button id="sample-path" class="btn btn-sm btn-outline-secondary">パス</button>
                                </div>
                            </div>
                            
                            <!-- プレビューセクション -->
                            <div class="col-lg-6">
                                <h3 class="h5 mb-3 border-bottom pb-2">プレビュー</h3>
                                <div id="preview-container" class="mb-3"></div>
                                
                                <div class="export-options mb-3">
                                    <h4 class="h6 mb-2">エクスポート:</h4>
                                    <div class="mb-2">
                                        <button id="download-svg" class="btn btn-sm btn-success">SVGとしてダウンロード</button>
                                        <button id="download-png" class="btn btn-sm btn-success">PNGとしてダウンロード</button>
                                        <button id="download-jpg" class="btn btn-sm btn-success">JPGとしてダウンロード</button>
                                        <button id="copy-svg" class="btn btn-sm btn-info">SVGコードをコピー</button>
                                    </div>
                                    <div class="mb-2 input-group input-group-sm">
                                        <span class="input-group-text">ファイル名</span>
                                        <input type="text" id="file-name" class="form-control" value="image" placeholder="ファイル名を入力">
                                    </div>
                                </div>
                                
                                <div class="settings">
                                    <h4 class="h6 mb-2">設定:</h4>
                                    <div class="setting-item">
                                        <label for="bg-color">背景色:</label>
                                        <input type="color" id="bg-color" class="form-control form-control-color" value="#ffffff">
                                    </div>
                                    <div class="setting-item">
                                        <label for="image-quality">JPG品質:</label>
                                        <input type="range" id="image-quality" class="form-range" style="width:100px" min="0" max="1" step="0.1" value="0.8">
                                        <span id="quality-value">0.8</span>
                                    </div>
                                    <div class="setting-item">
                                        <input type="checkbox" id="show-grid" class="form-check-input">
                                        <label for="show-grid" class="form-check-label">背景グリッド表示</label>
                                    </div>
                                    <div class="setting-item">
                                        <label for="export-scale">エクスポートスケール:</label>
                                        <select id="export-scale" class="form-select form-select-sm" style="width:100px">
                                            <option value="1">1x (元のサイズ)</option>
                                            <option value="2">2x</option>
                                            <option value="3">3x</option>
                                            <option value="0.5">0.5x (半分)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center text-muted small">
                    SVGのコードからファイルへ変換ができるというサービスです。<br>
                        クライアントベースで動作します。データはサーバーに送信されません。<br>本ページの使用にあたって生じたいかなる問題・支障について、一切の責任を負いかねます。
                    </div>
                </div>
				
<div class="container my-4">
  <div class="card border-0">
    <div class="card-body text-center">
      <h3 class="card-title mb-3">このページをシェアして広めてください！</h3>
      <div class="d-flex justify-content-center gap-3">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-facebook me-2"></i>Facebook
        </a>
        <a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="btn btn-outline-success">
          <i class="bi bi-chat-left-text me-2"></i>LINE
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="btn btn-outline-info">
          <i class="bi bi-linkedin me-2"></i>LinkedIn
        </a>
        <a href="https://x.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="btn btn-outline-secondary">
          <i class="bi bi-twitter me-2"></i>X
        </a>
      </div>
    </div>
  </div>
</div>



                <style>
                    /* SVG変換サービス用の追加スタイル */
                    .svg-converter-container .grid-bg {
                        background-image: linear-gradient(to right, rgba(0,0,0,.1) 1px, transparent 1px),
                                          linear-gradient(to bottom, rgba(0,0,0,.1) 1px, transparent 1px);
                        background-size: 10px 10px;
                    }
                    
                    /* コピー成功時のアニメーション */
                    @keyframes copy-success {
                        0% { background-color: #198754; }
                        100% { background-color: #0d6efd; }
                    }
                    .copy-success {
                        animation: copy-success 1.5s;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // 要素の取得
                        const svgCodeTextarea = document.getElementById('svg-code');
                        const previewBtn = document.getElementById('preview-btn');
                        const clearBtn = document.getElementById('clear-btn');
                        const previewContainer = document.getElementById('preview-container');
                        const downloadSvgBtn = document.getElementById('download-svg');
                        const downloadPngBtn = document.getElementById('download-png');
                        const downloadJpgBtn = document.getElementById('download-jpg');
                        const bgColorInput = document.getElementById('bg-color');
                        const imageQualityInput = document.getElementById('image-quality');
                        const qualityValueSpan = document.getElementById('quality-value');
                        const uploadSvgInput = document.getElementById('upload-svg');
                        const copySvgBtn = document.getElementById('copy-svg');
                        const fileNameInput = document.getElementById('file-name');
                        const showGridCheckbox = document.getElementById('show-grid');
                        const exportScaleSelect = document.getElementById('export-scale');
                        
                        // サンプルSVG
                        const sampleCircle = document.getElementById('sample-circle');
                        const sampleRect = document.getElementById('sample-rect');
                        const samplePath = document.getElementById('sample-path');
                        
                        // サンプルSVGの定義
                        const circleSvg = `<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="50" cy="50" r="40" fill="red" stroke="black" stroke-width="2" />
                    </svg>`;
                        
                        const rectSvg = `<svg width="150" height="100" xmlns="http://www.w3.org/2000/svg">
                      <rect x="25" y="25" width="100" height="50" fill="blue" stroke="black" stroke-width="2" rx="10" ry="10" />
                    </svg>`;
                        
                        const pathSvg = `<svg width="200" height="100" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10 80 C 40 10, 65 10, 95 80 S 150 150, 180 80" stroke="green" fill="transparent" stroke-width="3" />
                    </svg>`;
                        
                        // サンプルSVGをクリックした時の処理
                        sampleCircle.addEventListener('click', () => {
                            svgCodeTextarea.value = circleSvg;
                            updatePreview();
                        });
                        
                        sampleRect.addEventListener('click', () => {
                            svgCodeTextarea.value = rectSvg;
                            updatePreview();
                        });
                        
                        samplePath.addEventListener('click', () => {
                            svgCodeTextarea.value = pathSvg;
                            updatePreview();
                        });
                        
                        // 品質スライダーの値を表示
                        imageQualityInput.addEventListener('input', () => {
                            qualityValueSpan.textContent = imageQualityInput.value;
                        });
                        
                        // 背景色が変更された時にプレビューを更新
                        bgColorInput.addEventListener('input', updatePreview);
                        
                        // プレビューボタンのクリックイベント
                        previewBtn.addEventListener('click', updatePreview);
                        
                        // クリアボタンのクリックイベント
                        clearBtn.addEventListener('click', () => {
                            svgCodeTextarea.value = '';
                            previewContainer.innerHTML = '';
                        });
                        
                        // SVGダウンロードボタンのクリックイベント
                        downloadSvgBtn.addEventListener('click', downloadSvg);
                        
                        // PNGダウンロードボタンのクリックイベント
                        downloadPngBtn.addEventListener('click', () => downloadImage('png'));
                        
                        // JPGダウンロードボタンのクリックイベント
                        downloadJpgBtn.addEventListener('click', () => downloadImage('jpg'));
                        
                        // 入力が変更されたら自動的にプレビューを更新
                        svgCodeTextarea.addEventListener('input', debounce(updatePreview, 500));
                        
                        // SVGファイルアップロード処理
                        uploadSvgInput.addEventListener('change', function(event) {
                            const file = event.target.files[0];
                            if (file && file.type === 'image/svg+xml') {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    svgCodeTextarea.value = e.target.result;
                                    updatePreview();
                                };
                                reader.readAsText(file);
                            } else if (file) {
                                alert('SVGファイルを選択してください');
                            }
                        });
                        
                        // SVGコードをクリップボードにコピー
                        copySvgBtn.addEventListener('click', function() {
                            const svgCode = svgCodeTextarea.value.trim();
                            
                            if (!svgCode) {
                                alert('SVGコードを入力してください');
                                return;
                            }
                            
                            navigator.clipboard.writeText(svgCode).then(() => {
                                // コピー成功時のフィードバック
                                this.textContent = 'コピーしました！';
                                this.classList.add('copy-success');
                                
                                setTimeout(() => {
                                    this.textContent = 'SVGコードをコピー';
                                    this.classList.remove('copy-success');
                                }, 1500);
                            }).catch(err => {
                                alert('コピーに失敗しました: ' + err);
                            });
                        });
                        
                        // グリッド表示切り替え
                        showGridCheckbox.addEventListener('change', function() {
                            if (this.checked) {
                                previewContainer.classList.add('grid-bg');
                            } else {
                                previewContainer.classList.remove('grid-bg');
                            }
                        });
                        
                        // プレビューを更新する関数
                        function updatePreview() {
                            const svgCode = svgCodeTextarea.value.trim();
                            previewContainer.innerHTML = '';
                            
                            if (!svgCode) {
                                previewContainer.innerHTML = '<p class="text-muted">SVGコードを入力してください</p>';
                                return;
                            }
                            
                            try {
                                // 背景色を適用
                                const bgColor = bgColorInput.value;
                                previewContainer.style.backgroundColor = bgColor;
                                
                                // SVGコードを表示
                                previewContainer.innerHTML = svgCode;
                            } catch (error) {
                                previewContainer.innerHTML = `<p class="text-danger">エラー: ${error.message}</p>`;
                            }
                        }
                        
                        // SVGをダウンロードする関数
                        function downloadSvg() {
                            const svgCode = svgCodeTextarea.value.trim();
                            
                            if (!svgCode) {
                                alert('SVGコードを入力してください');
                                return;
                            }
                            
                            // Blobを作成
                            const blob = new Blob([svgCode], { type: 'image/svg+xml' });
                            const url = URL.createObjectURL(blob);
                            
                            // カスタムファイル名を使用
                            const fileName = fileNameInput.value || 'image';
                            
                            // ダウンロードリンクを作成
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${fileName}.svg`;
                            document.body.appendChild(a);
                            a.click();
                            
                            // クリーンアップ
                            setTimeout(() => {
                                document.body.removeChild(a);
                                URL.revokeObjectURL(url);
                            }, 100);
                        }
                        
                        // 画像をダウンロードする関数
                        function downloadImage(type) {
                            const svgCode = svgCodeTextarea.value.trim();
                            
                            if (!svgCode) {
                                alert('SVGコードを入力してください');
                                return;
                            }
                            
                            // SVG要素を取得
                            const svgElement = previewContainer.querySelector('svg');
                            
                            if (!svgElement) {
                                alert('有効なSVG要素が見つかりません');
                                return;
                            }
                            
                            // SVG要素のサイズを取得
                            const svgWidth = svgElement.width.baseVal.value || parseInt(svgElement.getAttribute('width')) || 300;
                            const svgHeight = svgElement.height.baseVal.value || parseInt(svgElement.getAttribute('height')) || 150;
                            
                            // エクスポートスケールを適用
                            const scale = parseFloat(exportScaleSelect.value);
                            
                            // キャンバスを作成
                            const canvas = document.createElement('canvas');
                            canvas.width = svgWidth * scale;
                            canvas.height = svgHeight * scale;
                            const ctx = canvas.getContext('2d');
                            
                            // 背景色を設定
                            ctx.fillStyle = bgColorInput.value;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            
                            // SVGを描画
                            const img = new Image();
                            const svgBlob = new Blob([svgCode], { type: 'image/svg+xml' });
                            const url = URL.createObjectURL(svgBlob);
                            
                            img.onload = function() {
                                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                                URL.revokeObjectURL(url);
                                
                                // 画像をダウンロード
                                let imageType = type === 'jpg' ? 'image/jpeg' : 'image/png';
                                let quality = type === 'jpg' ? parseFloat(imageQualityInput.value) : 1.0;
                                
                                const imageUrl = canvas.toDataURL(imageType, quality);
                                const a = document.createElement('a');
                                a.href = imageUrl;
                                
                                // カスタムファイル名を使用
                                const fileName = fileNameInput.value || 'image';
                                a.download = `${fileName}.${type}`;
                                
                                document.body.appendChild(a);
                                a.click();
                                
                                // クリーンアップ
                                setTimeout(() => {
                                    document.body.removeChild(a);
                                }, 100);
                            };
                            
                            img.src = url;
                        }
                        
                        // デバウンス関数（連続した処理の間引き）
                        function debounce(func, wait) {
                            let timeout;
                            return function(...args) {
                                clearTimeout(timeout);
                                timeout = setTimeout(() => func.apply(this, args), wait);
                            };
                        }
                        
                        // 初回ロード時にサンプルの円を表示
                        sampleCircle.click();
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<style>
.my-square-banner-pc{
display : none !important;
}
.page-header{
display : none !important;
}

</style>

<?php get_footer(); // フッターを読み込み ?>