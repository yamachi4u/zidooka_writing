<?php
/*
Template Name: Manga AI Generator
*/
/**
 * Manga AI Generator for WordPress (Client-Side Only Version)
 * 
 * Usage:
 * 1. Upload this file to your server or include it in a WordPress plugin/theme.
 * 2. If using as a standalone file, access it directly.
 * 3. If embedding in WP, you can use the HTML/JS part.
 * 
 * Note: This version performs all API calls directly from the browser to Google's servers.
 * Your server (WordPress) is NOT involved in the API communication, ensuring the API key
 * never passes through your server logic.
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>漫画AIジェネレーター (Gemini)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles if needed */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-8 px-4 sm:px-6 lg:px-8">

<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">漫画AIジェネレーター (Gemini)</h1>
        <div class="flex space-x-4">
            <button id="manualBtn" class="text-sm text-gray-600 hover:text-gray-900">
                使い方・免責事項
            </button>
            <button id="apiKeyBtn" class="text-sm text-indigo-600 hover:text-indigo-900">
                APIキー設定
            </button>
        </div>
    </div>

    <!-- Manual Modal -->
    <div id="manualModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">使い方・免責事項</h3>
                <div class="mt-2 px-4 py-3 text-sm text-gray-600 space-y-4 max-h-[70vh] overflow-y-auto">
                    
                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">1. APIキーの取得方法</h4>
                        <ol class="list-decimal list-inside space-y-1 ml-2">
                            <li><a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-indigo-600 hover:underline">Google AI Studio</a> にアクセスします。</li>
                            <li>Googleアカウントでログインします。</li>
                            <li>「Create API key」ボタンをクリックします。</li>
                            <li>作成されたキー（AIzaSy...で始まる文字列）をコピーします。</li>
                            <li>本ツールの右上の「APIキー設定」をクリックし、キーを貼り付けて保存します。</li>
                        </ol>
                    </section>

                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">2. セキュリティとプライバシーについて</h4>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li><strong>クライアントサイド処理:</strong> 本ツールは、あなたのブラウザから直接Googleのサーバーへ通信を行います。</li>
                            <li><strong>サーバー非経由:</strong> 入力したAPIキーや生成された画像データが、本ツールを設置しているWebサーバー（WordPress等）に送信・保存されることは一切ありません。</li>
                            <li><strong>データ保存:</strong> APIキーはブラウザの `LocalStorage`（ローカルストレージ）に保存されます。これはあなたの端末内にのみ存在する領域です。</li>
                        </ul>
                    </section>

                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">3. 免責事項</h4>
                        <div class="bg-gray-100 p-3 rounded border border-gray-200">
                            <p>本ツールは、Google Gemini APIを利用した実験的なアプリケーションです。</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>生成される画像の品質や内容について、開発者および提供者は一切の責任を負いません。</li>
                                <li>APIの利用料金（無料枠を超えた場合など）は、APIキー所有者の負担となります。</li>
                                <li>Googleの利用規約やポリシーに従ってご利用ください。</li>
                                <li>本ツールの利用により生じた損害について、提供者は責任を負いかねます。</li>
                            </ul>
                        </div>
                    </section>

                </div>
                <div class="items-center px-4 py-3 text-center">
                    <button id="closeManualModal" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- API Key Modal -->
    <div id="apiKeyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">APIキー設定</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">
                        Gemini APIキーを入力してください。<br>
                        キーはブラウザ(LocalStorage)に保存され、<br>
                        <strong>サーバーには送信されません。</strong>
                    </p>
                    <input type="password" id="apiKeyInput" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="AIzaSy...">
                </div>
                <div class="items-center px-4 py-3">
                    <button id="saveApiKey" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        保存
                    </button>
                    <button id="closeModal" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Generator UI -->
    <div class="bg-white shadow sm:rounded-lg p-6 mb-8">
        
        <!-- 1. Preset Selector -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">1. スタイル選択 (プリセット)</label>
            <select id="presetSelector" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                <option value="shonen">少年漫画 (バトル/アクション)</option>
                <option value="shoujo">少女漫画 (恋愛/エモ)</option>
                <option value="koma4">4コマ漫画 (コメディ/日常)</option>
                <option value="gekiga">劇画 (シリアス/ハードボイルド)</option>
                <option value="sns">SNSバズ漫画 (共感/エッセイ)</option>
                <option value="webtoon">Webtoon風 (縦スクロール/フルカラー)</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">スタイルを選ぶと、下のオプションが自動で推奨設定に切り替わります。</p>
        </div>

        <!-- 2. Character Description -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">2. キャラクター・状況説明</label>
            <textarea id="characterInput" rows="3" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white" placeholder="例: 黒髪の高校生、制服を着ている、剣を持っている、必殺技を叫んでいる"></textarea>
        </div>

        <!-- 3. Detailed Options -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">3. 全体の雰囲気・演出 (ざっくり指定)</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                
                <!-- Atmosphere -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">ストーリーの雰囲気</label>
                    <select id="optAtmosphere" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">指定なし (おまかせ)</option>
                        <option value="serious">シリアス・重厚 (緊張感)</option>
                        <option value="comedy">コメディ・明るい (楽しい)</option>
                        <option value="emotional">エモーショナル (感動・ドラマ)</option>
                        <option value="horror">ホラー・サスペンス (恐怖)</option>
                        <option value="peaceful">ほのぼの・日常 (リラックス)</option>
                    </select>
                </div>

                <!-- Pacing -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">展開のテンポ・構成</label>
                    <select id="optPacing" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">指定なし (おまかせ)</option>
                        <option value="dynamic">動的・アクション多め (スピード感)</option>
                        <option value="static">静的・会話中心 (心理描写)</option>
                        <option value="impact">衝撃的な展開 (大ゴマ・決めシーン)</option>
                        <option value="slow">ゆったり・情緒的 (余韻)</option>
                    </select>
                </div>

                <!-- Density -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">画面の密度・描き込み</label>
                    <select id="optDensity" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">指定なし (おまかせ)</option>
                        <option value="simple">シンプル (読みやすさ重視・余白多め)</option>
                        <option value="standard">標準バランス</option>
                        <option value="high">高密度 (描き込み重視・背景詳細)</option>
                    </select>
                </div>

                <!-- Art Style -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">描画タッチ</label>
                    <select id="optStyle" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">指定なし (おまかせ)</option>
                        <option value="bold">力強い・荒々しい (少年漫画的)</option>
                        <option value="delicate">繊細・美麗 (少女漫画的)</option>
                        <option value="pop">ポップ・デフォルメ (親しみやすい)</option>
                        <option value="realistic">リアル・劇画調 (写実的)</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Advanced: Generated Prompt Preview -->
        <div class="mb-6">
            <details>
                <summary class="text-sm text-indigo-600 cursor-pointer hover:text-indigo-800 select-none">詳細設定・生成されるプロンプトを確認</summary>
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">生成プロンプト (自動生成)</label>
                    <textarea id="finalPrompt" rows="4" class="w-full p-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 text-xs font-mono" readonly></textarea>
                    <p class="text-xs text-gray-500 mt-1">※上のオプションを変更すると自動で更新されます。</p>
                </div>
            </details>
        </div>

        <!-- Settings -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">アスペクト比</label>
                <select id="aspectRatio" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                    <option value="1:1">1:1 (正方形)</option>
                    <option value="3:4">3:4 (縦長 - スマホ/単行本)</option>
                    <option value="4:3">4:3 (横長 - PC/動画)</option>
                    <option value="16:9">16:9 (ワイド - サムネ)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">画像サイズ</label>
                <select id="imageSize" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                    <option value="1K">1K (標準)</option>
                    <option value="2K">2K (高画質)</option>
                </select>
            </div>
        </div>

        <!-- Generate Button -->
        <button id="generateBtn" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-indigo-400">
            <span id="btnText">漫画コマを生成</span>
            <div id="btnLoader" class="loader ml-2 hidden"></div>
        </button>
    </div>

    <!-- Results -->
    <div id="resultArea" class="hidden">
        <h2 class="text-xl font-bold text-gray-900 mb-4">生成結果</h2>
        <div id="imageContainer" class="bg-white p-4 rounded-lg shadow flex justify-center mb-4">
            <!-- Image will be inserted here -->
        </div>
        <button id="downloadBtn" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            JPEGでダウンロード
        </button>
    </div>

    <!-- Footer Disclaimer -->
    <div class="mt-12 text-xs text-gray-500 space-y-2 border-t border-gray-200 pt-6">
        <p>※1. 生成ボタンをクリックした時点で、利用規約・免責事項に同意したものとみなします。</p>
        <p>※2. 問い合わせ先： <a href="mailto:main@zidooka.com" class="text-indigo-600 hover:underline">main@zidooka.com</a></p>
        <p>※3. 利用にはGemini API Keyの発行が必要です。「gemini api key 発行手順」などで検索して取得したキーを、右上の「APIキー設定」から登録してください。</p>
        <p class="text-gray-600">
            <strong>※データの送信先について：</strong><br>
            本ツールに入力されたデータ（APIキーやプロンプト）が、本ツールの運営者（サーバー管理者）に送信・保存されることはありません。<br>
            ただし、AI生成を行うために、お客様のブラウザから直接 <strong>Google (Gemini) のサーバーへは送信されます</strong>。機密情報や個人情報の入力にはご注意ください。
        </p>
    </div>

</div>

<script>
// 1. Presets Configuration
const PRESETS = {
    shonen: {
        name: "少年漫画",
        baseTemplate: `少年漫画スタイル, 漫画のページレイアウト, コマ割り, 複数のコマ,
ダイナミックなアクション, 大胆な線画, 集中線, インパクトのある構図,
高コントラストの白黒, 筋肉のバランス, モーションブラー, 激しい表情`,
        defaults: {
            atmosphere: "default",
            pacing: "dynamic",
            density: "default",
            style: "bold"
        }
    },
    shoujo: {
        name: "少女漫画",
        baseTemplate: `少女漫画スタイル, 漫画のページレイアウト, コマ割り, 複数のコマ,
繊細で綺麗な線画, 優しい目, キラキラエフェクト, 細い輪郭線,
ロマンチックな照明, 白っぽいトーン, 花の背景, 感情的な表情, 柔らかい雰囲気`,
        defaults: {
            atmosphere: "emotional",
            pacing: "default",
            density: "default",
            style: "delicate"
        }
    },
    koma4: {
        name: "4コマ漫画",
        baseTemplate: `4コマ漫画スタイル, 4つのコマ, 縦に並んだコマ割り,
太い枠線, シンプルな背景, ミニマリストな線画, コミカルな表情,
デフォルメ(ちびキャラ)可, きれいなコマ割り, 読みやすい構図`,
        defaults: {
            atmosphere: "comedy",
            pacing: "static",
            density: "simple",
            style: "pop"
        }
    },
    gekiga: {
        name: "劇画",
        baseTemplate: `劇画スタイル, 漫画のページレイアウト, コマ割り, 複数のコマ,
リアルな描写, 濃い影, 緻密な書き込み, シリアスな雰囲気,
筆ペンタッチ, 荒々しい線, 重厚なストーリー性, 大人向け`,
        defaults: {
            atmosphere: "serious",
            pacing: "default",
            density: "high",
            style: "realistic"
        }
    },
    sns: {
        name: "SNSバズ漫画",
        baseTemplate: `SNSエッセイ漫画風, 漫画のページレイアウト, コマ割り, 複数のコマ,
シンプルで親しみやすい, 共感を呼ぶ表情, 余白多め, 読みやすさ重視,
デジタル作画, 今風の絵柄`,
        defaults: {
            atmosphere: "peaceful",
            pacing: "static",
            density: "simple",
            style: "pop"
        }
    },
    webtoon: {
        name: "Webtoon風",
        baseTemplate: `Webtoonスタイル, 縦スクロール漫画, 縦長のコマ割り,
フルカラー(または高精細モノクロ), アニメ塗り, 鮮やかな照明,
映画的な演出, スマホで読みやすい構図`,
        defaults: {
            atmosphere: "default",
            pacing: "dynamic",
            density: "default",
            style: "default"
        }
    }
};

// Option Labels (for prompt construction)
const OPTION_LABELS = {
    atmosphere: {
        default: null,
        serious: "シリアスな雰囲気, 緊張感のある展開, 重厚なストーリー",
        comedy: "コメディタッチ, 明るい雰囲気, 笑える展開, 楽しい",
        emotional: "感動的な雰囲気, エモーショナル, 心温まる, ドラマチック",
        horror: "恐怖, ホラーテイスト, 不気味な雰囲気, サスペンス",
        peaceful: "ほのぼのとした日常, 穏やかな雰囲気, リラックス"
    },
    pacing: {
        default: null,
        static: "会話シーン中心, 静的な構図, キャラクターの表情重視, 落ち着いた展開",
        dynamic: "激しいアクション, スピード感のある展開, ダイナミックな動き, 戦闘シーン",
        impact: "衝撃的な展開, インパクトのある大ゴマ, クライマックス, 劇的",
        slow: "ゆったりとした間, 情緒的な演出, 余韻を感じさせる, スローテンポ"
    },
    density: {
        default: null,
        simple: "シンプルな画面構成, 余白を活かす, 読みやすさ最優先, すっきりとした背景",
        standard: "標準的な描き込み, バランスの取れた画面",
        high: "緻密な描き込み, 高密度な画面, 背景の細部まで描写, 圧倒的な情報量"
    },
    style: {
        default: null,
        delicate: "繊細な線画, 美麗なタッチ, 細かいトーン処理, 少女漫画的",
        bold: "力強い線画, 荒々しいタッチ, ハイコントラスト, 少年漫画的",
        pop: "ポップな絵柄, デフォルメ表現, 丸みのある線, 親しみやすい",
        realistic: "リアルな描写, 劇画タッチ, 写実的, 陰影の強調"
    }
};

// Storage Helpers
function setApiKey(key) {
    localStorage.setItem('gemini_api_key', key);
}

function getApiKey() {
    return localStorage.getItem('gemini_api_key');
}

// UI Logic
document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const apiKeyModal = document.getElementById('apiKeyModal');
    const apiKeyInput = document.getElementById('apiKeyInput');
    const apiKeyBtn = document.getElementById('apiKeyBtn');
    const saveApiKeyBtn = document.getElementById('saveApiKey');
    const closeModalBtn = document.getElementById('closeModal');
    
    const manualModal = document.getElementById('manualModal');
    const manualBtn = document.getElementById('manualBtn');
    const closeManualModalBtn = document.getElementById('closeManualModal');
    
    const presetSelector = document.getElementById('presetSelector');
    const characterInput = document.getElementById('characterInput');
    
    // New Options
    const optAtmosphere = document.getElementById('optAtmosphere');
    const optPacing = document.getElementById('optPacing');
    const optDensity = document.getElementById('optDensity');
    const optStyle = document.getElementById('optStyle');
    
    const finalPrompt = document.getElementById('finalPrompt');
    const generateBtn = document.getElementById('generateBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');
    const resultArea = document.getElementById('resultArea');
    const imageContainer = document.getElementById('imageContainer');

    // --- Logic Functions ---

    function updatePrompt() {
        const presetKey = presetSelector.value;
        const preset = PRESETS[presetKey];
        
        const charDesc = characterInput.value.trim() || "人物";
        
        // Helper to get option text only if not default
        const getOpt = (cat, val) => OPTION_LABELS[cat][val];

        const atmosphere = getOpt('atmosphere', optAtmosphere.value);
        const pacing = getOpt('pacing', optPacing.value);
        const density = getOpt('density', optDensity.value);
        const style = getOpt('style', optStyle.value);

        // Build option lines conditionally
        let optionsBlock = "";
        if (atmosphere) optionsBlock += `雰囲気: ${atmosphere}\n`;
        if (pacing) optionsBlock += `展開・テンポ: ${pacing}\n`;
        if (density) optionsBlock += `画面密度: ${density}\n`;
        if (style) optionsBlock += `描画スタイル: ${style}\n`;

        // Construct the prompt
        const prompt = `
${preset.baseTemplate}

【キャラクター・状況】
${charDesc}

【全体演出・構成】
${optionsBlock}
【品質保持】
漫画のコマ割り(Panel Layout), 複数のコマ(Multiple Panels),
顔崩れなし, 指の異常なし, アーティファクト禁止, 
自然な身体バランス, 商業漫画クオリティ,
高解像度, ノイズ除去, 鮮明な線画
`.trim();

        finalPrompt.value = prompt;
    }

    function applyPresetDefaults(presetKey) {
        const defaults = PRESETS[presetKey].defaults;
        if (defaults) {
            optAtmosphere.value = defaults.atmosphere;
            optPacing.value = defaults.pacing;
            optDensity.value = defaults.density;
            optStyle.value = defaults.style;
        }
        updatePrompt();
    }

    // --- Event Listeners ---

    // Preset Change
    presetSelector.addEventListener('change', (e) => {
        applyPresetDefaults(e.target.value);
    });

    // Input Changes
    [characterInput, optAtmosphere, optPacing, optDensity, optStyle].forEach(el => {
        el.addEventListener('input', updatePrompt);
    });

    // Initialize
    applyPresetDefaults('shonen'); // Default preset
    
    // Check API Key
    const currentKey = getApiKey();
    if (!currentKey) {
        apiKeyModal.classList.remove('hidden');
    } else {
        apiKeyInput.value = currentKey;
    }

    // Modal Handlers
    apiKeyBtn.addEventListener('click', () => {
        apiKeyInput.value = getApiKey() || '';
        apiKeyModal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        apiKeyModal.classList.add('hidden');
    });

    manualBtn.addEventListener('click', () => {
        manualModal.classList.remove('hidden');
    });

    closeManualModalBtn.addEventListener('click', () => {
        manualModal.classList.add('hidden');
    });

    saveApiKeyBtn.addEventListener('click', () => {
        const key = apiKeyInput.value.trim();
        if (key) {
            setApiKey(key);
            apiKeyModal.classList.add('hidden');
            alert('APIキーを保存しました');
        } else {
            alert('APIキーを入力してください');
        }
    });

    // Generate
    generateBtn.addEventListener('click', async () => {
        const apiKey = getApiKey();
        if (!apiKey) {
            alert('APIキーが設定されていません。右上の「APIキー設定」から設定してください。');
            apiKeyModal.classList.remove('hidden');
            return;
        }

        const prompt = finalPrompt.value; // Use the generated prompt
        const aspectRatio = document.getElementById('aspectRatio').value;
        const imageSize = document.getElementById('imageSize').value;

        if (!prompt) {
            alert('プロンプトが空です。');
            return;
        }

        // UI Loading State
        generateBtn.disabled = true;
        btnText.textContent = '生成中...';
        btnLoader.classList.remove('hidden');
        resultArea.classList.add('hidden');
        imageContainer.innerHTML = '';

        try {
            // Direct API Call to Gemini (Client-Side)
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-3-pro-image-preview:generateContent?key=${apiKey}`;
            
            const payload = {
                contents: [
                    {
                        parts: [{ text: prompt }]
                    }
                ],
                generationConfig: {
                    imageConfig: {
                        aspectRatio: aspectRatio,
                        imageSize: imageSize
                    }
                }
            };

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                const errorText = await response.text();
                let errorMsg = `API Error: ${response.status}`;
                try {
                    const errorJson = JSON.parse(errorText);
                    if (errorJson.error && errorJson.error.message) {
                        errorMsg = errorJson.error.message;
                    }
                } catch (e) {}
                throw new Error(errorMsg);
            }

            const data = await response.json();
            
            // Parse Response
            const candidate = data.candidates?.[0];
            const part = candidate?.content?.parts?.find(p => p.inlineData?.data);

            if (!part) {
                throw new Error('画像が生成されませんでした。プロンプトを変更して再試行してください。');
            }

            const base64 = part.inlineData.data;
            const mime = part.inlineData.mimeType || 'image/png';
            const dataUrl = `data:${mime};base64,${base64}`;

            const img = document.createElement('img');
            img.src = dataUrl;
            img.className = 'max-w-full h-auto rounded shadow-lg';
            imageContainer.appendChild(img);
            resultArea.classList.remove('hidden');

        } catch (error) {
            console.error(error);
            alert('エラーが発生しました: ' + error.message);
        } finally {
            generateBtn.disabled = false;
            btnText.textContent = '漫画コマを生成';
            btnLoader.classList.add('hidden');
        }
    });

    // Download Button
    const downloadBtn = document.getElementById('downloadBtn');
    downloadBtn.addEventListener('click', () => {
        const img = imageContainer.querySelector('img');
        if (!img) return;

        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d');
        
        // Fill white background just in case
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.drawImage(img, 0, 0);
        
        const jpegUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        const link = document.createElement('a');
        link.href = jpegUrl;
        link.download = 'manga_panel_' + new Date().getTime() + '.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>
</body>
</html>