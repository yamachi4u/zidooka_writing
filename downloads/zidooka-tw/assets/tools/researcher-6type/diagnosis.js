(function () {
    const RESULTS = {
        truth_seeker: {
            name: "真理探究型（アルキメデス・タイプ）",
            catch: "「なぜ？」を突き詰め、世界の仕組みを解き明かす",
            feature: "役に立つかどうかよりも、知的好奇心と論理的な美しさを重視します。",
            strength: "誰も疑わなかった前提を覆し、パラダイムシフトを起こす力。",
            fields: "数学、理論物理学、基礎生物学など。",
            tags: ["#根本原理", "#論理", "#知的好奇心"],
            icon: "🧠",
            image: "truth-seeker.png"
        },
        social_implementer: {
            name: "社会実装型（エジソン・タイプ）",
            catch: "成果を社会に届け、課題を現実に解決する",
            feature: "実用性、効率、コストパフォーマンスを重視し、技術の社会還元をゴールにします。",
            strength: "プロトタイピングの速さと、社会のニーズを察知する嗅覚。",
            fields: "工学全般、応用化学、新薬開発など。",
            tags: ["#実装", "#実用性", "#スピード"],
            icon: "⚙️",
            image: "social-implementer.png"
        },
        data_craftsman: {
            name: "データ職人型（職人・タイプ）",
            catch: "精度と再現性を磨き、データで真実を掘る",
            feature: "「神は細部に宿る」を地で行き、ノイズのない美しいデータこそが真実を語ると信じています。",
            strength: "圧倒的な精度の実験スキルと、些細な変化を見逃さない観察眼。",
            fields: "分析化学、天文学、考古学など。",
            tags: ["#再現性", "#精密測定", "#観察眼"],
            icon: "🔬",
            image: "data-craftsman.png"
        },
        interdisciplinary_hub: {
            name: "領域横断型（ハブ・タイプ）",
            catch: "分野の壁を越え、新しい視点を編み上げる",
            feature: "専門外の人とも積極的に交流し、情報の翻訳や橋渡しを得意とします。",
            strength: "既存の分野では行き詰まっていた問題に、外部の知見で風穴を開ける力。",
            fields: "生体医工学、情報学、環境科学など。",
            tags: ["#橋渡し", "#翻訳", "#越境"],
            icon: "🌉",
            image: "interdisciplinary-hub.png"
        },
        field_adventurer: {
            name: "冒険家・フィールド型（インディ・ジョーンズ・タイプ）",
            catch: "一次情報を足で掴み、現場で研究を前進させる",
            feature: "研究室にこもるよりも、現場に出てナマの情報を掴むことに命をかけるタイプです。",
            strength: "予測不能な事態への対応力と、現場での強いリーダーシップ。",
            fields: "地質学、人類学、海洋学、生態学など。",
            tags: ["#一次情報", "#行動力", "#フィールド"],
            icon: "🧭",
            image: "field-adventurer.png"
        },
        meta_researcher: {
            name: "メタ研究・思想型（哲学者・タイプ）",
            catch: "研究の意味と倫理を俯瞰し、未来の方向を定める",
            feature: "「その研究をすることは人間にとって幸せか？」という根源的な問いを投げかけます。",
            strength: "科学の暴走を防ぎ、正しい方向へ導くためのフレームワーク作り。",
            fields: "科学哲学、科学技術社会論（STS）、生物倫理学など。",
            tags: ["#倫理", "#俯瞰", "#フレームワーク"],
            icon: "📚",
            image: "meta-researcher.png"
        }
    };

    const TYPE_ORDER = Object.keys(RESULTS);

    const QUESTIONS = [
        {
            text: "Q1. 新しい研究テーマを決めるとき、最も心が動くのは？",
            answers: [
                { text: "世界の根本原理を説明できる可能性", score: { truth_seeker: 2, meta_researcher: 1 } },
                { text: "現場の課題をすぐ解決できる可能性", score: { social_implementer: 2, field_adventurer: 1 } },
                { text: "誰も取れていない高品質データを取れる可能性", score: { data_craftsman: 2 } },
                { text: "異分野をつなぎ新手法を作れる可能性", score: { interdisciplinary_hub: 2, truth_seeker: 1 } }
            ]
        },
        {
            text: "Q2. プロジェクト初期に最初に着手することは？",
            answers: [
                { text: "前提を疑い、問いを定義し直す", score: { truth_seeker: 2, meta_researcher: 1 } },
                { text: "PoC設計と実装ロードマップを引く", score: { social_implementer: 2 } },
                { text: "測定条件と再現手順を固める", score: { data_craftsman: 2 } },
                { text: "必要な異分野メンバーを集める", score: { interdisciplinary_hub: 2 } }
            ]
        },
        {
            text: "Q3. 予想外の結果が出たとき、最初にする行動は？",
            answers: [
                { text: "理論モデルを更新し説明可能性を探る", score: { truth_seeker: 2 } },
                { text: "現場に行き、一次情報を取り直す", score: { field_adventurer: 2, data_craftsman: 1 } },
                { text: "社会実装にどう影響するか先に判断する", score: { social_implementer: 2 } },
                { text: "倫理・社会影響まで含めて再評価する", score: { meta_researcher: 2 } }
            ]
        },
        {
            text: "Q4. チームで自然に担う役割は？",
            answers: [
                { text: "実験精度を守る品質管理担当", score: { data_craftsman: 2 } },
                { text: "分野間コミュニケーションの翻訳者", score: { interdisciplinary_hub: 2 } },
                { text: "現地調査の先頭に立つリーダー", score: { field_adventurer: 2 } },
                { text: "研究の前提と目的を問い直す壁打ち役", score: { meta_researcher: 2, truth_seeker: 1 } }
            ]
        },
        {
            text: "Q5. 良い研究だと判断する基準は？",
            answers: [
                { text: "理論が美しく一貫していること", score: { truth_seeker: 2 } },
                { text: "社会導入の道筋が明確なこと", score: { social_implementer: 2 } },
                { text: "再現実験で同じ結果が出ること", score: { data_craftsman: 2 } },
                { text: "人間社会にとって妥当であること", score: { meta_researcher: 2 } }
            ]
        },
        {
            text: "Q6. もっとも集中できる作業環境は？",
            answers: [
                { text: "ホワイトボードと論文に囲まれた机", score: { truth_seeker: 2 } },
                { text: "試作機とユーザー検証が回る開発現場", score: { social_implementer: 2 } },
                { text: "計測機器が整った実験室", score: { data_craftsman: 2 } },
                { text: "予測不能な屋外フィールド", score: { field_adventurer: 2 } }
            ]
        },
        {
            text: "Q7. 情報収集で最も重視するものは？",
            answers: [
                { text: "専門外も含めた横断的な文献探索", score: { interdisciplinary_hub: 2 } },
                { text: "現地で得た一次観測データ", score: { field_adventurer: 2 } },
                { text: "研究制度・倫理・歴史の背景分析", score: { meta_researcher: 2 } },
                { text: "反証可能性の高い検証計画", score: { truth_seeker: 2, data_craftsman: 1 } }
            ]
        },
        {
            text: "Q8. この研究が成功したときの理想像は？",
            answers: [
                { text: "新しい理論が学問の地図を書き換える", score: { truth_seeker: 2 } },
                { text: "プロダクトや制度として社会に実装される", score: { social_implementer: 2 } },
                { text: "高再現のデータ基盤として長く使われる", score: { data_craftsman: 2 } },
                { text: "複数分野の標準手法として定着する", score: { interdisciplinary_hub: 2 } },
                { text: "現場の知見として次世代へ継承される", score: { field_adventurer: 2 } },
                { text: "倫理と科学の両立モデルとして評価される", score: { meta_researcher: 2 } }
            ]
        }
    ];

    const state = {
        currentQuestion: 0,
        scores: createInitialScores(),
        answerHistory: []
    };

    const dom = {};

    document.addEventListener("DOMContentLoaded", () => {
        dom.start = document.getElementById("r6d-start");
        dom.quiz = document.getElementById("r6d-quiz");
        dom.loading = document.getElementById("r6d-loading");
        dom.result = document.getElementById("r6d-result");
        dom.startBtn = document.getElementById("r6d-start-btn");
        dom.restartBtn = document.getElementById("r6d-restart-btn");
        dom.typePreview = document.getElementById("r6d-type-preview");
        dom.current = document.getElementById("r6d-current");
        dom.total = document.getElementById("r6d-total");
        dom.progressBar = document.getElementById("r6d-progress-bar");
        dom.progressTrack = document.querySelector(".zdk-r6d-progress-track");
        dom.question = document.getElementById("r6d-question");
        dom.answers = document.getElementById("r6d-answers");
        dom.resultName = document.getElementById("r6d-result-name");
        dom.resultCatch = document.getElementById("r6d-result-catch");
        dom.resultIcon = document.getElementById("r6d-result-icon");
        dom.tags = document.getElementById("r6d-tags");
        dom.feature = document.getElementById("r6d-feature");
        dom.strength = document.getElementById("r6d-strength");
        dom.fields = document.getElementById("r6d-fields");
        dom.figure = document.getElementById("r6d-result-figure");
        dom.image = document.getElementById("r6d-result-image");

        if (!dom.start || !dom.quiz || !dom.loading || !dom.result || !dom.startBtn || !dom.restartBtn) {
            return;
        }

        dom.total.textContent = String(QUESTIONS.length);
        renderTypePreview();
        dom.startBtn.addEventListener("click", startQuiz);
        dom.restartBtn.addEventListener("click", restartQuiz);
    });

    function createInitialScores() {
        return TYPE_ORDER.reduce((acc, key) => {
            acc[key] = 0;
            return acc;
        }, {});
    }

    function resetState() {
        state.currentQuestion = 0;
        state.scores = createInitialScores();
        state.answerHistory = [];
    }

    function startQuiz() {
        resetState();
        showScreen("quiz");
        renderQuestion();
    }

    function restartQuiz() {
        showScreen("start");
        resetState();
    }

    function showScreen(screenName) {
        const map = {
            start: dom.start,
            quiz: dom.quiz,
            loading: dom.loading,
            result: dom.result
        };

        Object.entries(map).forEach(([key, element]) => {
            if (!element) {
                return;
            }
            element.classList.toggle("zdk-r6d-hidden", key !== screenName);
        });
    }

    function renderQuestion() {
        const question = QUESTIONS[state.currentQuestion];
        if (!question) {
            return;
        }

        dom.question.textContent = question.text;
        dom.answers.innerHTML = "";
        dom.current.textContent = String(state.currentQuestion + 1);

        const progress = ((state.currentQuestion + 1) / QUESTIONS.length) * 100;
        dom.progressBar.style.width = `${progress}%`;
        if (dom.progressTrack) {
            dom.progressTrack.setAttribute("aria-valuenow", String(Math.round(progress)));
        }

        question.answers.forEach((answer) => {
            const button = document.createElement("button");
            button.type = "button";
            button.className = "zdk-r6d-answer-btn";
            button.textContent = answer.text;
            button.addEventListener("click", () => onSelectAnswer(answer.score));
            dom.answers.appendChild(button);
        });
    }

    function onSelectAnswer(scoreMap) {
        Object.entries(scoreMap).forEach(([type, value]) => {
            if (Object.prototype.hasOwnProperty.call(state.scores, type)) {
                state.scores[type] += value;
            }
        });
        state.answerHistory.push(getPrimaryType(scoreMap));

        state.currentQuestion += 1;
        if (state.currentQuestion < QUESTIONS.length) {
            renderQuestion();
            return;
        }

        showScreen("loading");
        setTimeout(showResult, 800);
    }

    function getPrimaryType(scoreMap) {
        let bestType = TYPE_ORDER[0];
        let bestScore = Number.NEGATIVE_INFINITY;
        TYPE_ORDER.forEach((type) => {
            const score = scoreMap[type] || 0;
            if (score > bestScore) {
                bestType = type;
                bestScore = score;
            }
        });
        return bestType;
    }

    function resolveResultType() {
        const maxScore = Math.max(...Object.values(state.scores));
        const candidates = TYPE_ORDER.filter((type) => state.scores[type] === maxScore);
        if (candidates.length === 1) {
            return candidates[0];
        }

        for (let i = state.answerHistory.length - 1; i >= 0; i -= 1) {
            const latest = state.answerHistory[i];
            if (candidates.includes(latest)) {
                return latest;
            }
        }

        return candidates[0];
    }

    function showResult() {
        const type = resolveResultType();
        const data = RESULTS[type];

        dom.resultName.textContent = data.name;
        dom.resultCatch.textContent = data.catch;
        dom.resultIcon.textContent = data.icon;
        dom.feature.textContent = data.feature;
        dom.strength.textContent = data.strength;
        dom.fields.textContent = data.fields;

        dom.tags.innerHTML = "";
        data.tags.forEach((tag) => {
            const span = document.createElement("span");
            span.className = "zdk-r6d-tag";
            span.textContent = tag;
            dom.tags.appendChild(span);
        });

        renderResultImage(data);
        showScreen("result");
    }

    function renderResultImage(data) {
        const base = typeof window.ZDK_R6D_IMAGE_BASE === "string" ? window.ZDK_R6D_IMAGE_BASE : "";
        const imageSrc = base && data.image ? `${base}/${data.image}` : "";
        if (!imageSrc) {
            dom.figure.classList.add("zdk-r6d-hidden");
            return;
        }

        dom.image.alt = `${data.name}のイメージ`;
        dom.image.src = imageSrc;
        dom.image.onerror = () => {
            dom.figure.classList.add("zdk-r6d-hidden");
        };
        dom.image.onload = () => {
            dom.figure.classList.remove("zdk-r6d-hidden");
        };
    }

    function renderTypePreview() {
        if (!dom.typePreview) {
            return;
        }
        const base = typeof window.ZDK_R6D_IMAGE_BASE === "string" ? window.ZDK_R6D_IMAGE_BASE : "";
        dom.typePreview.innerHTML = "";

        TYPE_ORDER.forEach((type) => {
            const data = RESULTS[type];
            const card = document.createElement("article");
            card.className = "zdk-r6d-type-card";

            const img = document.createElement("img");
            img.className = "zdk-r6d-type-thumb";
            img.alt = data.name;
            img.loading = "lazy";
            img.decoding = "async";
            img.src = base && data.image ? `${base}/${data.image}` : "";
            img.onerror = () => {
                img.style.display = "none";
                card.style.background = "linear-gradient(145deg, rgba(255,255,255,0.72), rgba(210,232,255,0.55))";
            };

            const label = document.createElement("p");
            label.className = "zdk-r6d-type-label";
            label.textContent = `${data.icon} ${data.name.replace(/（.*?）/g, "")}`;

            card.appendChild(img);
            card.appendChild(label);
            dom.typePreview.appendChild(card);
        });
    }
})();
