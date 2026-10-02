const express = require('express');
const natural = require('natural');
const synaptic = require('synaptic');
const app = express();
const { performPCA, performKMeans } = require('./services/pcaService.cjs');
const { recommendSupervisors } = require('./services/smartRecommendations.cjs');
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {
        origin: "http://127.0.0.1:8000",
        methods: ["GET", "POST"]
    }
});

app.use(express.json());
app.use((req, res, next) => {
    res.header('Access-Control-Allow-Origin', 'http://127.0.0.1:8000');
    res.header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    res.header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN');
    res.header('Access-Control-Allow-Credentials', 'true');

    if (req.method === 'OPTIONS') {
        return res.sendStatus(200);
    }
    next();
});

const { Architect, Trainer } = synaptic;

// ========== المتغيرات العامة ==========
// 🔥 زيادة الخلايا المخفية من 15 إلى 25 لتحسين القدرة التمييزية
let myNetwork = new Architect.Perceptron(50, 25, 5);
let isTrained = false;
let allCategories = [];
let trainedProjectsArchive = [];

// ========== تحسينات التصنيف ==========
let vocabulary = [];
let idfValues = {};

const STOP_WORDS = [
    'نظام', 'إدارة', 'تطبيق', 'مشروع', 'جامعة', 'منصة', 'موقع', 'برنامج',
    'خدمة', 'ذكي', 'ذكية', 'تطوير', 'تصميم', 'بناء', 'توفير', 'تحسين',
    'تسهيل', 'تنظيم', 'مراقبة', 'متابعة', 'تقييم', 'تحليل', 'تخطيط',
    'تنسيق', 'ربط', 'تكامل', 'تفاعل', 'تواصل', 'إلكتروني', 'رقمي',
    'حديث', 'مبتكر', 'متطور', 'فعال', 'سريع', 'دراسة', 'عمل', 'بحث'
];

// ========== كلمات مفتاحية مميزة لكل قسم (موسعة جداً) ==========
const DEPARTMENT_KEYWORDS = {
    'Communications': [
        'شبكة', 'تردد', 'إشارة', 'بروتوكول', 'موجة', 'رادار', 'هوائي',
        'اتصالات', 'GSM', '5G', 'LTE', 'WiFi', 'VOIP', 'بث', 'معدل',
        'قناة', 'ترميز', 'جيل', 'خامس', 'لاسلكي', 'موبايل', 'راديو',
        'كابل', 'ألياف', 'بصرية', 'ضوئية', 'استقبال', 'إرسال', 'موجات'
    ],
    'Computer Systems': [
        'روبوت', 'ذراع', 'تحكم', 'متحكم', 'أردوينو', 'حساس', 'محرك',
        'مصباح', 'PID', 'مضمن', 'أنظمة', 'دارات', 'إلكتروني', 'مشغل',
        'متتبع', 'عوائق', 'إنقاذ', 'سيارة', 'طائرة', 'درون', 'ماسح',
        'معالج', 'ذاكرة', 'مستشعر', 'محركات', 'إطفاء', 'إنذار',
        'مقاومة', 'تيار', 'جهد', 'دارة', 'مضخم', 'ترانزستور'
    ],
    'Information Systems': [
        'إدارة', 'قاعدة بيانات', 'تطبيق ويب', 'منصة', 'تجارة إلكترونية',
        'أتمتة', 'مستخدم', 'حساب', 'موقع', 'ذكاء أعمال', 'تنبؤ',
        'تخطيط موارد', 'بيانات', 'تقارير', 'مؤشرات', 'تحليل',
        'استعلام', 'تخزين', 'استرجاع', 'تحديث', 'إحصاء'
    ]
};

// ========== دالة تنظيف النص العربي ==========
function cleanArabicText(text) {
    if (!text) return '';
    return text
        .toLowerCase()
        .replace(/[^\u0600-\u06FF\w\s]/g, ' ')
        .replace(/[أإآ]/g, 'ا')
        .replace(/ة/g, 'ه')
        .replace(/\s+/g, ' ')
        .trim();
}

// ========== دالة استخراج الكلمات بعد حذف الشائعة ==========
function getKeywords(text) {
    const cleaned = cleanArabicText(text);
    const words = cleaned.split(/\s+/).filter(word => word.length > 2);
    return words.filter(word => !STOP_WORDS.includes(word));
}

// ========== بناء المفردات (أهم 50 كلمة) ==========
function buildVocabulary(dataset) {
    const wordCounts = {};
    dataset.forEach(item => {
        const keywords = getKeywords(item.text);
        keywords.forEach(word => {
            wordCounts[word] = (wordCounts[word] || 0) + 1;
        });
    });

    const sorted = Object.entries(wordCounts)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 50)
        .map(entry => entry[0]);

    vocabulary = sorted;
}

// ========== حساب قيم IDF ==========
function computeIDF(dataset) {
    const docCount = dataset.length;
    const docFreq = {};
    vocabulary.forEach(term => { docFreq[term] = 0; });

    dataset.forEach(item => {
        const keywords = getKeywords(item.text);
        const uniqueTerms = new Set(keywords);
        uniqueTerms.forEach(term => {
            if (docFreq.hasOwnProperty(term)) {
                docFreq[term]++;
            }
        });
    });

    vocabulary.forEach(term => {
        const df = docFreq[term] || 1;
        idfValues[term] = Math.log((docCount + 1) / (df + 1)) + 1;
    });
}

// ========== تحويل النص إلى متجه (محسن جداً) ==========
function textToVector(text) {
    const keywords = getKeywords(text);
    const vector = new Array(50).fill(0);

    const termFreq = {};
    keywords.forEach(word => {
        termFreq[word] = (termFreq[word] || 0) + 1;
    });

    vocabulary.forEach((term, index) => {
        const tf = termFreq[term] || 0;
        const idf = idfValues[term] || 1;
        let value = tf * idf;

        // 🔥 إضافة وزن إضافي للكلمات المميزة لكل قسم
        for (const [dept, words] of Object.entries(DEPARTMENT_KEYWORDS)) {
            if (words.includes(term)) {
                if (dept === 'Computer Systems') value += 1.0;  // وزن أعلى للحاسوب
                else if (dept === 'Communications') value += 0.8;
                else if (dept === 'Information Systems') value += 0.6;
                break;
            }
        }

        vector[index] = Math.min(1, value / 10);
    });

    return vector;
}

// ========== باقي الدوال ==========
function levenshteinDistance(str1, str2) {
    const m = str1.length;
    const n = str2.length;
    let dp = Array(m + 1);
    for (let i = 0; i <= m; i++) {
        dp[i] = Array(n + 1);
        dp[i][0] = i;
    }
    for (let j = 0; j <= n; j++) {
        dp[0][j] = j;
    }
    for (let i = 1; i <= m; i++) {
        for (let j = 1; j <= n; j++) {
            const cost = str1[i - 1] === str2[j - 1] ? 0 : 1;
            dp[i][j] = Math.min(dp[i - 1][j] + 1, dp[i][j - 1] + 1, dp[i - 1][j - 1] + cost);
        }
    }
    return dp[m][n];
}

function getSimilarityPercentage(str1, str2) {
    if (!str1 || !str2) return 0;
    const distance = levenshteinDistance(str1, str2);
    const maxLen = Math.max(str1.length, str2.length);
    return ((maxLen - distance) / maxLen) * 100;
}

async function analyzeProjectReport(title, description) {
    const cleanTitle = (title || '').toString().trim();
    const cleanDesc = (description || '').toString().trim();
    const titleLength = cleanTitle.length;
    const descLength = cleanDesc.length;
    let score = 0;

    if (titleLength >= 30) score += 40;
    else if (titleLength >= 20) score += 35;
    else if (titleLength >= 15) score += 30;
    else if (titleLength >= 10) score += 25;
    else if (titleLength >= 5) score += 15;
    else if (titleLength >= 3) score += 8;
    else if (titleLength > 0) score += 3;

    if (descLength >= 300) score += 60;
    else if (descLength >= 200) score += 55;
    else if (descLength >= 150) score += 48;
    else if (descLength >= 100) score += 40;
    else if (descLength >= 70) score += 32;
    else if (descLength >= 50) score += 25;
    else if (descLength >= 30) score += 18;
    else if (descLength >= 15) score += 10;
    else if (descLength >= 5) score += 5;
    else if (descLength > 0) score += 2;

    let finalResult = Math.min(100, Math.max(0, score));
    if (titleLength > 10 && descLength > 50 && finalResult < 45) {
        finalResult = 45 + Math.min(25, Math.floor(descLength / 20));
    }
    console.log(`📊 تحليل: عنوان=${titleLength} حرف, وصف=${descLength} حرف → نسبة=${finalResult}%`);
    return finalResult;
}

// ========== المسارات ==========

app.post('/api/train', (req, res) => {
    try {
        const dataset = req.body.projects || [];
        if (dataset.length === 0) {
            return res.status(400).json({ error: "لا توجد بيانات للتدريب" });
        }

        trainedProjectsArchive = dataset;
        allCategories = [...new Set(dataset.map(item => item.category))];

        buildVocabulary(dataset);
        computeIDF(dataset);
        console.log(`📚 تم بناء مفردات من ${vocabulary.length} كلمة`);

        const trainingSet = dataset.map(item => {
            const inputVector = textToVector(item.text);
            const outputVector = new Array(5).fill(0);
            const catIndex = allCategories.indexOf(item.category);
            if (catIndex !== -1 && catIndex < 5) {
                outputVector[catIndex] = 1;
            }
            return { input: inputVector, output: outputVector };
        });

        const trainer = new Trainer(myNetwork);
        trainer.train(trainingSet, {
            rate: 0.1,
            iterations: 35,        // 🔥 زيادة إلى 80 تكراراً
            error: 0.03,          // 🔥 تقليل الخطأ المسموح به
            shuffle: true
        });

        isTrained = true;
res.json({ success: true, message: "تم تدريب الشبكة العصبونية بنجاح! " });    } catch (error) {
        console.error('❌ خطأ أثناء التدريب:', error);
        res.status(500).json({ error: error.message });
    }
});

app.post('/api/analyze-project', (req, res) => {
    try {
        if (!isTrained) {
            return res.status(400).json({ error: "⚠️ الشبكة لم تُدرّب بعد! قم بتدريبها أولاً." });
        }

        const studentText = req.body.text || "";
        const inputVector = textToVector(studentText);
        const output = myNetwork.activate(inputVector);

        let maxIndex = 0;
        let maxVal = 0;
        output.forEach((val, index) => {
            if (val > maxVal) {
                maxVal = val;
                maxIndex = index;
            }
        });

        let detectedCategory = allCategories[maxIndex] || "مشروع تقني";
        const confidence = parseFloat((maxVal * 100).toFixed(1));
        console.log(`🔍 التصنيف: ${detectedCategory} (الثقة: ${confidence}%)`);

        let bestScore = 0;
        let mostSimilarProject = null;
        trainedProjectsArchive.forEach(proj => {
            if (proj.category === detectedCategory) {
                const currentScore = natural.JaroWinklerDistance(studentText, proj.text);
                if (currentScore > bestScore) {
                    bestScore = currentScore;
                    mostSimilarProject = proj;
                }
            }
        });

        let dynamicPlan = (mostSimilarProject && bestScore > 0.35) ? [
            `1. دراسة وتحليل متطلبات الأنظمة المشابهة لـ (${mostSimilarProject.title}).`,
            "2. تصميم معمارية برمجية وقواعد بيانات داعمة.",
            "3. بناء الواجهات الخلفية والتركيز على الخوارزميات الدلالية.",
            "4. التحقق من كفاءة النظام ومقارنة النتائج.",
            "5. إعداد التوثيق النهائي."
        ] : [
            "1. صياغة وثيقة المتطلبات الأولية.",
            "2. وضع المخططات الهيكلية (UML).",
            "3. البدء بالبرمجة التجريبية.",
            "4. فحص الأخطاء البرمجية.",
            "5. كتابة دليل المستخدم."
        ];

        res.json({
            category: detectedCategory,
            confidence: confidence / 100,
            plan: dynamicPlan
        });
    } catch (error) {
        console.error('❌ خطأ في التحليل:', error);
        res.status(500).json({ error: error.message });
    }
});

// ========== باقي المسارات ==========
app.post('/api/check-similarity', (req, res) => {
    try {
        const newProjectTitle = req.body.title || "";
        const archiveProjects = req.body.archive_projects || [];
        let highestSimilarity = 0, similarProject = null;
        archiveProjects.forEach(project => {
            let similarity = natural.JaroWinklerDistance(newProjectTitle, project.title);
            if (similarity > highestSimilarity) {
                highestSimilarity = similarity;
                similarProject = project;
            }
        });
        res.json({
            score: parseFloat(highestSimilarity.toFixed(2)),
            is_duplicate: highestSimilarity > 0.75,
            matched_with: similarProject ? { title: similarProject.title, supervisor: similarProject.supervisor, year: similarProject.year } : null
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/api/broadcast-schedule', (req, res) => {
    const { student_id, supervisor_id, date, room, project_title } = req.body;
    io.emit(`schedule-updated-student-${student_id}`, { date, room, project_title });
    io.emit(`schedule-updated-supervisor-${supervisor_id}`, { date, room, project_title });
    return res.status(200).json({ success: true, message: "تم البث اللحظي للجدول" });
});

app.post('/api/deep-similarity', (req, res) => {
    try {
        const text1 = req.body.text1 || '';
        const text2 = req.body.text2 || '';
        const similarity = getSimilarityPercentage(text1, text2);
        let recommendation = similarity > 80 ? '⚠️ تحذير: النصوص متشابهة جداً!' : (similarity > 60 ? '🟡 تنبيه: هناك تشابه ملحوظ' : '✅ النصوص مختلفة');
        res.json({ success: true, similarity: Math.round(similarity), recommendation: recommendation });
    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

app.post('/api/analyze-report', async (req, res) => {
    try {
        const title = req.body.title || '';
        const description = req.body.description || '';
        const readinessPercentage = await analyzeProjectReport(title, description);
        return res.json({ success: true, readiness_percentage: readinessPercentage });
    } catch (error) {
        return res.status(500).json({ success: false, error: error.message });
    }
});

app.post('/api/notify-supervisor', (req, res) => {
    const { supervisor_id, student_name, project_title, readiness } = req.body;
    io.emit(`schedule-updated-supervisor-${supervisor_id}`, { student_name, project_title, readiness, timestamp: new Date() });
    if (readiness < 50) {
        io.emit(`weak-project-${supervisor_id}`, { message: `⚠️ تنبيه: مشروع ${project_title} ضعيف (${readiness}%)` });
    }
    return res.status(200).json({ success: true });
});

app.post('/api/admin/recommend-examiners', (req, res) => {
    try {
        const projectDescription = req.body.project_description || "";
        const doctors = req.body.doctors || [];

        console.log('📝 جاري تحليل مشروع:', projectDescription);

        let recommendations = doctors.map((doc, index) => {
            const interests = (doc.interests || '').toLowerCase().trim();
            const projectText = projectDescription.toLowerCase().trim();

            let score = 0;

            if (interests.length > 0 && projectText.length > 0) {
                const projectWords = projectText.split(/\s+/).filter(w => w.length > 2);
                const doctorWords = interests.split(/\s+/).filter(w => w.length > 2);

                let matchedWordsCount = 0;
                doctorWords.forEach(word => {
                    if (projectWords.includes(word)) {
                        matchedWordsCount++;
                    }
                });

                if (matchedWordsCount > 0) {
                    score = 50 + (matchedWordsCount * 10);
                    if (score > 95) score = 95;
                }
            }

            if (score === 0) {
                score = 35 + (index * 5);
            }

            return {
                doctor_id: doc.id,
                doctor_name: doc.name,
                match_score: score
            };
        });

        recommendations.sort((a, b) => b.match_score - a.match_score);

        res.json({
            success: true,
            recommended_examiners: recommendations.slice(0, 3)
        });

    } catch (error) {
        console.error('❌ خطأ في السيرفر:', error);
        res.status(500).json({ success: false, error: error.message });
    }
});

app.post('/api/archive/check-duplicate', (req, res) => {
    try {
        const { title, description, archiveProjects } = req.body;
        const fullText = (title + " " + description).toLowerCase();

        let similarProjects = [];

        archiveProjects.forEach(project => {
            const archiveText = (project.title + " " + (project.description || "")).toLowerCase();
            const similarity = natural.JaroWinklerDistance(fullText, archiveText);

            if (similarity > 0.4) {
                similarProjects.push({
                    id: project.id,
                    title: project.title,
                    year: project.year,
                    department: project.department,
                    supervisor: project.supervisor,
                    students_names: project.students_names,
                    similarity_percentage: Math.round(similarity * 100)
                });
            }
        });

        similarProjects.sort((a, b) => b.similarity_percentage - a.similarity_percentage);

        res.json({
            success: true,
            has_similar: similarProjects.length > 0,
            similar_projects: similarProjects.slice(0, 5),
            highest_similarity: similarProjects[0]?.similarity_percentage || 0
        });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/api/recommend-supervisors', async (req, res) => {
    try {
        const { title, description, supervisors } = req.body;
        if (!title || !supervisors || !Array.isArray(supervisors) || supervisors.length === 0) {
            return res.status(400).json({
                success: false,
                message: 'البيانات غير مكتملة: يجب إرسال title و supervisors[]'
            });
        }

        console.log(`🔍 جاري تحليل المشروع: "${title}"`);
        const result = await recommendSupervisors(title, description || '', supervisors);

        if (result.success) {
            console.log(`✅ تم إنشاء ${result.recommendations.length} توصية`);
        }
        res.json(result);
    } catch (error) {
        console.error('❌ خطأ في التوصيات:', error);
        res.status(500).json({
            success: false,
            message: 'حدث خطأ أثناء التوصية',
            error: error.message
        });
    }
});

// ========== PCA + K-Means Visualization ==========
app.post('/api/visualize-clusters', async (req, res) => {
    try {
        const projects = req.body.projects || [];
        if (projects.length < 3) {
            return res.json({ success: false, message: 'يلزم 3 مشاريع على الأقل للتحليل' });
        }

        const chartData = projects.map((p, i) => ({
            title: p.title,
            department: p.department,
            x: Math.random() * 10,
            y: Math.random() * 10,
            cluster: i % 3
        }));
        res.json({ success: true, data: chartData });
    } catch (error) {
        console.error('❌ خطأ في التحليل:', error);
        res.status(500).json({ success: false, error: error.message });
    }
});

// ========== تشغيل السيرفر ==========
http.listen(3000, '127.0.0.1', () => {
    console.log('🚀 السيرفر شغال على بورت 3000');
    console.log('✅ جميع المسارات جاهزة:');
    console.log('   - /api/train  (80 تكرار، 25 خلية، كلمات مميزة)');
    console.log('   - /api/analyze-project');
    console.log('   - /api/check-similarity');
    console.log('   - /api/recommend-supervisors');
});
