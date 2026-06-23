<?php
/*
Template Name: Manga AI Generator (English)
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manga AI Generator (Gemini)</title>
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
        <h1 class="text-3xl font-bold text-gray-900">Manga AI Generator (Gemini)</h1>
        <div class="flex space-x-4">
            <button id="manualBtn" class="text-sm text-gray-600 hover:text-gray-900">
                Usage / Disclaimer
            </button>
            <button id="apiKeyBtn" class="text-sm text-indigo-600 hover:text-indigo-900">
                API Key Settings
            </button>
        </div>
    </div>

    <!-- Manual Modal -->
    <div id="manualModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Usage / Disclaimer</h3>
                <div class="mt-2 px-4 py-3 text-sm text-gray-600 space-y-4 max-h-[70vh] overflow-y-auto">
                    
                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">1. How to get an API Key</h4>
                        <ol class="list-decimal list-inside space-y-1 ml-2">
                            <li>Access <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-indigo-600 hover:underline">Google AI Studio</a>.</li>
                            <li>Log in with your Google account.</li>
                            <li>Click the "Create API key" button.</li>
                            <li>Copy the created key (string starting with AIzaSy...).</li>
                            <li>Click "API Key Settings" at the top right of this tool, paste the key, and save.</li>
                        </ol>
                    </section>

                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">2. Security and Privacy</h4>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li><strong>Client-Side Processing:</strong> This tool communicates directly from your browser to Google's servers.</li>
                            <li><strong>No Server Involvement:</strong> The API key you enter and the generated image data are never sent to or saved on the web server (WordPress, etc.) hosting this tool.</li>
                            <li><strong>Data Storage:</strong> The API key is saved in your browser's `LocalStorage`. This area exists only within your device.</li>
                        </ul>
                    </section>

                    <section>
                        <h4 class="font-bold text-gray-800 mb-2">3. Disclaimer</h4>
                        <div class="bg-gray-100 p-3 rounded border border-gray-200">
                            <p>This tool is an experimental application using the Google Gemini API.</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>The developer and provider assume no responsibility for the quality or content of the generated images.</li>
                                <li>API usage fees (if exceeding the free tier) are the responsibility of the API key owner.</li>
                                <li>Please use in accordance with Google's terms of service and policies.</li>
                                <li>The provider cannot be held responsible for any damages caused by the use of this tool.</li>
                            </ul>
                        </div>
                    </section>

                </div>
                <div class="items-center px-4 py-3 text-center">
                    <button id="closeManualModal" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- API Key Modal -->
    <div id="apiKeyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">API Key Settings</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">
                        Please enter your Gemini API Key.<br>
                        The key is saved in your browser (LocalStorage) and<br>
                        <strong>is NOT sent to the server.</strong>
                    </p>
                    <input type="password" id="apiKeyInput" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="AIzaSy...">
                </div>
                <div class="items-center px-4 py-3">
                    <button id="saveApiKey" class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        Save
                    </button>
                    <button id="closeModal" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Generator UI -->
    <div class="bg-white shadow sm:rounded-lg p-6 mb-8">
        
        <!-- 1. Preset Selector -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">1. Select Style (Preset)</label>
            <select id="presetSelector" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                <option value="shonen">Shonen Manga (Battle/Action)</option>
                <option value="shoujo">Shoujo Manga (Romance/Emotional)</option>
                <option value="koma4">4-Panel Manga (Comedy/Slice of Life)</option>
                <option value="gekiga">Gekiga (Serious/Hardboiled)</option>
                <option value="sns">SNS Viral Manga (Empathy/Essay)</option>
                <option value="webtoon">Webtoon (Vertical Scroll/Full Color)</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Selecting a style will automatically switch the options below to recommended settings.</p>
        </div>

        <!-- 2. Character Description -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">2. Character & Situation Description</label>
            <textarea id="characterInput" rows="3" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white" placeholder="e.g. Black-haired high school student, wearing a uniform, holding a sword, shouting a special move"></textarea>
        </div>

        <!-- 3. Detailed Options -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">3. Atmosphere & Composition (Broad)</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-md border border-gray-200">
                
                <!-- Atmosphere -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Story Atmosphere</label>
                    <select id="optAtmosphere" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">None (Auto)</option>
                        <option value="serious">Serious / Heavy (Tense)</option>
                        <option value="comedy">Comedy / Bright (Fun)</option>
                        <option value="emotional">Emotional (Drama)</option>
                        <option value="horror">Horror / Suspense (Scary)</option>
                        <option value="peaceful">Peaceful / Daily Life (Relaxing)</option>
                    </select>
                </div>

                <!-- Pacing -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pacing & Tempo</label>
                    <select id="optPacing" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">None (Auto)</option>
                        <option value="dynamic">Dynamic / Action-heavy (Speedy)</option>
                        <option value="static">Static / Conversation-centric (Psychological)</option>
                        <option value="impact">Shocking Development (Climax)</option>
                        <option value="slow">Slow / Emotional (Lingering)</option>
                    </select>
                </div>

                <!-- Density -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Screen Density / Detail</label>
                    <select id="optDensity" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">None (Auto)</option>
                        <option value="simple">Simple (Readability / White Space)</option>
                        <option value="standard">Standard Balance</option>
                        <option value="high">High Density (Detailed Backgrounds)</option>
                    </select>
                </div>

                <!-- Art Style -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Art Style</label>
                    <select id="optStyle" class="w-full p-2 border border-gray-300 rounded text-sm">
                        <option value="default">None (Auto)</option>
                        <option value="bold">Bold / Rough (Shonen Manga)</option>
                        <option value="delicate">Delicate / Beautiful (Shoujo Manga)</option>
                        <option value="pop">Pop / Deformed (Friendly)</option>
                        <option value="realistic">Realistic / Gekiga (Photorealistic)</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Advanced: Generated Prompt Preview -->
        <div class="mb-6">
            <details>
                <summary class="text-sm text-indigo-600 cursor-pointer hover:text-indigo-800 select-none">Advanced: Check Generated Prompt</summary>
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Generated Prompt (Auto)</label>
                    <textarea id="finalPrompt" rows="4" class="w-full p-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 text-xs font-mono" readonly></textarea>
                    <p class="text-xs text-gray-500 mt-1">* Automatically updated when options above are changed.</p>
                </div>
            </details>
        </div>

        <!-- Settings -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aspect Ratio</label>
                <select id="aspectRatio" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                    <option value="1:1">1:1 (Square)</option>
                    <option value="3:4">3:4 (Portrait - Smartphone/Book)</option>
                    <option value="4:3">4:3 (Landscape - PC/Video)</option>
                    <option value="16:9">16:9 (Wide - Thumbnail)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image Size</label>
                <select id="imageSize" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 bg-white">
                    <option value="1K">1K (Standard)</option>
                    <option value="2K">2K (High Quality)</option>
                </select>
            </div>
        </div>

        <!-- Generate Button -->
        <button id="generateBtn" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-indigo-400">
            <span id="btnText">Generate Manga Panel</span>
            <div id="btnLoader" class="loader ml-2 hidden"></div>
        </button>
    </div>

    <!-- Results -->
    <div id="resultArea" class="hidden">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Generation Result</h2>
        <div id="imageContainer" class="bg-white p-4 rounded-lg shadow flex justify-center mb-4">
            <!-- Image will be inserted here -->
        </div>
        <button id="downloadBtn" class="w-full flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Download as JPEG
        </button>
    </div>

    <!-- Footer Disclaimer -->
    <div class="mt-12 text-xs text-gray-500 space-y-2 border-t border-gray-200 pt-6">
        <p>*1. By clicking the Generate button, you are deemed to have agreed to the Terms of Use and Disclaimer.</p>
        <p>*2. Contact: <a href="mailto:main@zidooka.com" class="text-indigo-600 hover:underline">main@zidooka.com</a></p>
        <p>*3. A Gemini API Key is required for use. Please search for "how to get gemini api key" to obtain one, and register it from the "API Key Settings" menu at the top right.</p>
        <p class="text-gray-600">
            <strong>*About Data Transmission:</strong><br>
            Data entered into this tool (API key and prompts) is NOT sent to or stored by the tool operator (server administrator).<br>
            However, please note that data is <strong>sent directly from your browser to Google's (Gemini) servers</strong> to perform AI generation. Please be careful when entering confidential or personal information.
        </p>
    </div>

</div>

<script>
// 1. Presets Configuration
const PRESETS = {
    shonen: {
        name: "Shonen Manga",
        baseTemplate: `Shonen manga style, manga page layout, panel layout, multiple panels,
dynamic action, bold lines, speed lines, impactful composition,
high contrast black and white, muscle balance, motion blur, intense expressions`,
        defaults: {
            atmosphere: "default",
            pacing: "dynamic",
            density: "default",
            style: "bold"
        }
    },
    shoujo: {
        name: "Shoujo Manga",
        baseTemplate: `Shoujo manga style, manga page layout, panel layout, multiple panels,
delicate and beautiful lines, gentle eyes, sparkling effects, thin outlines,
romantic lighting, whitish tone, floral background, emotional expressions, soft atmosphere`,
        defaults: {
            atmosphere: "emotional",
            pacing: "default",
            density: "default",
            style: "delicate"
        }
    },
    koma4: {
        name: "4-Panel Manga",
        baseTemplate: `4-panel manga style, 4 panels, vertical panel layout,
thick borders, simple background, minimalist lines, comical expressions,
chibi characters allowed, clean paneling, easy to read composition`,
        defaults: {
            atmosphere: "comedy",
            pacing: "static",
            density: "simple",
            style: "pop"
        }
    },
    gekiga: {
        name: "Gekiga",
        baseTemplate: `Gekiga style, manga page layout, panel layout, multiple panels,
realistic depiction, deep shadows, detailed drawing, serious atmosphere,
brush pen touch, rough lines, heavy storytelling, for adults`,
        defaults: {
            atmosphere: "serious",
            pacing: "default",
            density: "high",
            style: "realistic"
        }
    },
    sns: {
        name: "SNS Viral Manga",
        baseTemplate: `SNS essay manga style, manga page layout, panel layout, multiple panels,
simple and friendly, relatable expressions, plenty of white space, emphasis on readability,
digital drawing, modern art style`,
        defaults: {
            atmosphere: "peaceful",
            pacing: "static",
            density: "simple",
            style: "pop"
        }
    },
    webtoon: {
        name: "Webtoon Style",
        baseTemplate: `Webtoon style, vertical scroll manga, vertical panel layout,
full color (or high definition monochrome), anime coloring, vivid lighting,
cinematic direction, composition easy to read on smartphone`,
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
        serious: "Serious atmosphere, tense development, heavy story",
        comedy: "Comedy touch, bright atmosphere, funny development, fun",
        emotional: "Touching atmosphere, emotional, heartwarming, dramatic",
        horror: "Horror, horror taste, eerie atmosphere, suspense",
        peaceful: "Peaceful daily life, calm atmosphere, relaxing"
    },
    pacing: {
        default: null,
        static: "Conversation-centric, static composition, focus on character expressions, calm development",
        dynamic: "Intense action, speedy development, dynamic movement, battle scenes",
        impact: "Shocking development, impactful large panels, climax, dramatic",
        slow: "Slow tempo, emotional direction, lingering feeling, slow pace"
    },
    density: {
        default: null,
        simple: "Simple screen composition, use of white space, readability first, clean background",
        standard: "Standard detail, balanced screen",
        high: "Intense detail, high density screen, detailed background, overwhelming amount of information"
    },
    style: {
        default: null,
        delicate: "Delicate lines, beautiful touch, fine tone processing, shoujo manga style",
        bold: "Powerful lines, rough touch, high contrast, shonen manga style",
        pop: "Pop art style, deformed expression, rounded lines, friendly",
        realistic: "Realistic depiction, gekiga touch, photorealistic, emphasis on shading"
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
        
        const charDesc = characterInput.value.trim() || "Character";
        
        // Helper to get option text only if not default
        const getOpt = (cat, val) => OPTION_LABELS[cat][val];

        const atmosphere = getOpt('atmosphere', optAtmosphere.value);
        const pacing = getOpt('pacing', optPacing.value);
        const density = getOpt('density', optDensity.value);
        const style = getOpt('style', optStyle.value);

        // Build option lines conditionally
        let optionsBlock = "";
        if (atmosphere) optionsBlock += `Atmosphere: ${atmosphere}\n`;
        if (pacing) optionsBlock += `Pacing/Tempo: ${pacing}\n`;
        if (density) optionsBlock += `Density: ${density}\n`;
        if (style) optionsBlock += `Style: ${style}\n`;

        // Construct the prompt
        const prompt = `
${preset.baseTemplate}

[Character & Situation]
${charDesc}

[Atmosphere & Composition]
${optionsBlock}
[Quality Assurance]
Manga panel layout, Multiple panels,
No distorted faces, No abnormal fingers, No artifacts, 
Natural body balance, Commercial manga quality,
High resolution, Denoising, Clear lines
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
            alert('API Key saved');
        } else {
            alert('Please enter an API Key');
        }
    });

    // Generate
    generateBtn.addEventListener('click', async () => {
        const apiKey = getApiKey();
        if (!apiKey) {
            alert('API Key is not set. Please set it from "API Key Settings" at the top right.');
            apiKeyModal.classList.remove('hidden');
            return;
        }

        const prompt = finalPrompt.value; // Use the generated prompt
        const aspectRatio = document.getElementById('aspectRatio').value;
        const imageSize = document.getElementById('imageSize').value;

        if (!prompt) {
            alert('Prompt is empty.');
            return;
        }

        // UI Loading State
        generateBtn.disabled = true;
        btnText.textContent = 'Generating...';
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
                throw new Error('Image was not generated. Please change the prompt and try again.');
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
            alert('Error occurred: ' + error.message);
        } finally {
            generateBtn.disabled = false;
            btnText.textContent = 'Generate Manga Panel';
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