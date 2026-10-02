const Tesseract = require('tesseract.js');
const axios = require('axios');
const fs = require('fs');
const path = require('path');

// مجلد الصور (تأكد أن اسمه archive_images وموجود بجانب هذا الملف)
const directoryPath = path.join(__dirname, 'archive_images');

async function processImages() {
    // التأكد من وجود المجلد
    if (!fs.existsSync(directoryPath)) {
        console.error("❌ المجلد archive_images غير موجود!");
        return;
    }

    const files = fs.readdirSync(directoryPath);

    for (const file of files) {
        if (file.toLowerCase().endsWith('.jpg') || file.toLowerCase().endsWith('.png')) {
            console.log(`\n--- جاري معالجة: ${file} ---`);

            try {
                // 1. استخراج النص باستخدام Tesseract
                const { data: { text } } = await Tesseract.recognize(
                    path.join(directoryPath, file),
                    'ara' // معالجة اللغة العربية
                );

                console.log(`📝 تم قراءة النص، جاري الإرسال إلى لارفيل...`);

                // 2. إرسال النص إلى رابط الـ API في لارفيل
                // ملاحظة: الرابط الكامل يجب أن يحتوي على /api/store-archive
                const response = await axios.post('http://127.0.0.1:8000/api/store-archive', {
                    raw_text: text,
                    file_name: file
                });

                console.log(`✅ نجاح: تم تخزين المشروع في قاعدة البيانات (ID: ${response.data.data.id})`);

            } catch (error) {
                if (error.response) {
                    // خطأ من طرف لارفيل (مثلاً السيرفر رد بـ 500 أو 404)
                    console.error(`❌ خطأ من لارفيل (Status: ${error.response.status}):`, error.response.data.message || "خطأ غير معروف");
                } else {
                    // خطأ في الاتصال (مثلاً artisan serve مو شغال)
                    console.error(`❌ خطأ في الاتصال بالسيرفر: تأكد من تشغيل php artisan serve`);
                }
            }
        }
    }
    console.log('\n--- ✅ انتهت معالجة كافة الصور ---');
}

processImages();
