const express = require('express');
const natural = require('natural');
const app = express();
app.use(express.json());

const classifier = new natural.BayesClassifier();

// تدريب مكثف أكثر ليغطي كلماتك
classifier.addDocument('موقع ويب متجر صيدلية عيادة مستشفى إدارة', 'Web Development');
classifier.addDocument('تطبيق موبايل أندرويد أيفون توصيل شات', 'Mobile Development');
classifier.addDocument('ذكاء اصطناعي خوارزمية بيانات تحليل صور', 'AI & Data Science');
classifier.addDocument('نظام إدارة قواعد بيانات محاسبة أرشيف', 'Information Systems');

console.log('جاري إعادة التدريب...');
classifier.train();
console.log('تم التدريب بنجاح!');

app.post('/analyze', (req, res) => {
    const text = req.body.text;

    // إذا النص موجود، نصنفه، إذا لا، نعطي نتيجة افتراضية
    let result = "غير محدد";
    if (text) {
        result = classifier.classify(text);
    }

    console.log(`النص المستلم: "${text}" -> التصنيف: ${result}`);
    res.json({ category: result });
});

app.listen(3000, () => {
    console.log('AI Server is running on http://localhost:3000');
});
