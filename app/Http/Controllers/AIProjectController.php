<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Tip;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Project;

class AIProjectController extends Controller
{
    public function handleStudentAction(Request $request)
    {
        try {
            $action = $request->input('action');
            $title = $request->input('title');

            if (!$title) {
                return response()->json([
                    'success' => false,
                    'error' => '⚠️ الرجاء إدخال عنوان المشروع'
                ]);
            }

            // 🎯 زر فحص التميز
            if ($action === 'check') {
                $archiveProjects = DB::table('archived_projects')
                    ->select('title', 'supervisor', 'year')
                    ->get()
                    ->toArray();

                if (empty($archiveProjects)) {
                    return response()->json([
                        'success' => true,
                        'result' => "✅ **لا توجد مشاريع في قاعدة البيانات للمقارنة**\n\n🎉 فكرتك جديدة ومميزة! يمكنك المتابعة بثقة."
                    ]);
                }

                try {
                    $urlCheck = 'http://127.0.0.1:3000/api/check-similarity';
                    $response = Http::timeout(10)->post($urlCheck, [
                        'title' => $title,
                        'archive_projects' => $archiveProjects
                    ]);

                    if ($response->successful()) {
                        $result = $response->json();

                        $score = isset($result['score']) ? floatval($result['score']) : 0;
                        $isDuplicate = isset($result['is_duplicate']) ? $result['is_duplicate'] : ($score > 0.75);
                        $matched = $result['matched_with'] ?? null;

                        if ($isDuplicate && $matched) {
                            $message = " نتيجة فحص التميز\n\n";
                            $message .= "نسبة التشابه:" . round($score * 100) . "%\n";
                            $message .= "مشروع مشابه: \"{$matched['title']}\"\n";
                            if (isset($matched['supervisor'])) $message .= "المشرف: {$matched['supervisor']}\n";
                            if (isset($matched['year'])) $message .= "السنة: {$matched['year']}\n\n";
                            $message .= "نصيحة: يفضل تعديل الفكرة لضمان التميز .";
                            return response()->json(['success' => true, 'result' => $message]);
                        } else {
                            $message = "نتيجة فحص التميز\n\n";
                            $message .= "نسبة التشابه: " . round($score * 100) . "%\n";
                            $message .= "فكرتك مميزة وجديدة!\n";
                            $message .= "لا يوجد مشاريع مشابهة في قاعدة البيانات.\n\n";
                            $message .= "يمكنك المتابعة في تسجيل مشروعك بثقة.";
                            return response()->json(['success' => true, 'result' => $message]);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'error' => '⚠️ سيرفر التحليل لا يستجيب. يرجى المحاولة لاحقاً.'
                        ]);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'error' => '❌ خطأ في الاتصال: ' . $e->getMessage()
                    ]);
                }
            }

            // 🎯 زر خطة العمل
            if ($action === 'suggest') {
                try {
                    $urlAnalyze = 'http://127.0.0.1:3000/api/analyze-project';
                    $response = Http::timeout(10)->post($urlAnalyze, ['text' => $title]);

                    if ($response->successful()) {
                        $result = $response->json();
                        $category = $result['category'] ?? 'مشروع تقني';
                        $confidence = isset($result['confidence']) ? round($result['confidence'] * 100) : 85;
                        $planSteps = $result['plan'] ?? [];

                        $confidenceEmoji = $confidence >= 70 ? '🟢' : ($confidence >= 40 ? '🟡' : '🔴');

                        $message = " تحليل الذكاء الاصطناعي\n\n";
                        $message .= " التصنيف المتوقع: {$category}\n";
                        $message .= "{$confidenceEmoji} نسبة الثقة: {$confidence}%\n\n";
                        $message .= " خطة العمل المقترحة:\n";
                        $message .= "─────────────────\n";

                        if (count($planSteps) > 0) {
                            foreach ($planSteps as $step) {
                                $message .= " {$step}\n";
                            }
                        } else {
                            $message .= " 1. دراسة وتحليل المتطلبات\n";
                            $message .= " 2. تصميم النظام وقاعدة البيانات\n";
                            $message .= " 3. تنفيذ البرمجة والتطوير\n";
                            $message .= " 4. اختبار النظام وتصحيح الأخطاء\n";
                            $message .= " 5. توثيق المشروع وإعداد التقارير\n";
                        }

                        $message .= "─────────────────\n\n";
                        $message .= " هذه الخطة مقترحة بناءً على مشاريع سابقة مشابهة.";

                        return response()->json(['success' => true, 'result' => $message]);
                    } else {
                        throw new \Exception('فشل الاتصال');
                    }
                } catch (\Exception $e) {
                    // رد افتراضي إذا فشل الاتصال
                    $message = " تحليل الذكاء الاصطناعي (وضع احتياطي)\n\n";
                    $message .= " التصنيف: مشروع تقني / برمجي\n";
                    $message .= " نسبة الثقة: 60%\n\n";
                    $message .= " خطة العمل المقترحة:\n";
                    $message .= "─────────────────\n";
                    $message .= " 1. دراسة وتحليل المتطلبات\n";
                    $message .= " 2. تصميم النظام وقاعدة البيانات\n";
                    $message .= " 3. تنفيذ البرمجة والتطوير\n";
                    $message .= " 4. اختبار النظام وتصحيح الأخطاء\n";
                    $message .= " 5. توثيق المشروع وإعداد التقارير\n";
                    $message .= "─────────────────\n\n";
                    $message .= " هذه خطة عامة لمشاريع التخرج التقنية.";

                    return response()->json(['success' => true, 'result' => $message]);
                }
            }

            return response()->json([
                'success' => false,
                'error' => '⚠️ إجراء غير معروف'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => '❌ خطأ في السيرفر: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * دالة تدريب الشبكة العصبونية
     */
    public function trainNetworkOffline()
    {
        try {
            $projects = DB::table('archived_projects')
                ->whereNotNull('title')
                ->get()
                ->map(function ($project) {
                    return [
                        'text' => $project->title . ' ' . ($project->description ?? ''),
                        'title' => $project->title,
                        'year' => $project->year ?? date('Y'),
                        'category' => $project->department ?? 'مشروع تقني / برمجى'
                    ];
                })
                ->toArray();

            if (empty($projects)) {
                return response()->json([
                    'success' => false,
                    'error' => '⚠️ لا توجد بيانات للتدريب! أضف مشاريع إلى الأرشيف أولاً.'
                ]);
            }

            $ip = '127.0.0.1';
            $port = '3000';
            $urlTrain = 'http://' . $ip . ':' . $port . '/api/train';

            $response = Http::timeout(60)->post($urlTrain, [
                'projects' => $projects
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => '❌ فشل الاتصال بسيرفر التدريب: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSmartRecommendations(Request $request)
    {
        $title = $request->title;
        if (!$title) return response()->json(['recommendations' => []]);

        $tips = DB::table('tips')->get();
        $recommendations = [];
        $normalizedTitle = mb_strtolower($title);

        foreach ($tips as $tip) {
            $keywords = explode(',', $tip->keyword);
            foreach ($keywords as $keyword) {
                $keyword = mb_strtolower(trim($keyword));
                similar_text($normalizedTitle, $keyword, $percent);
                if ($percent > 80) {
                    $recommendations[] = $tip->advice;
                    break;
                }
            }
        }

        return response()->json(['recommendations' => $recommendations]);
    }

// ========== تحليل المشاعر (باستخدام قائمة كلمات موسعة جداً) ==========
public function analyzeSentiment(Request $request)
{
    try {
        $text = $request->input('text');

        if (empty($text)) {
            return response()->json([
                'success' => false,
                'message' => 'الرجاء إدخال نص للتحليل'
            ]);
        }

        // ✅ قائمة الكلمات الإيجابية (موسعة جداً)
        $positiveWords = [
            // الأساسيات
            'رائع', 'ممتاز', 'جميل', 'مفيد', 'مبتكر', 'متميز', 'فعال', 'ناجح',
            'مذهل', 'رائعة', 'ممتازة', 'جميلة', 'مفيدة', 'مبتكرة', 'متميزة',
            'فعالة', 'ناجحة', 'مذهلة', 'أحسنت', 'شكراً', 'يسعدني', 'سعيد',
            'مبهر', 'مبهرة', 'قوي', 'قوية', 'متطور', 'متطورة', 'حديث', 'حديثة',
            'ذكي', 'ذكية', 'عملي', 'عملية', 'سهل', 'سهلة', 'سريع', 'سريعة',

            // كلمات التقييم الأكاديمي
            'جيد', 'جيدة', 'جيداً', 'جيدةً', 'حسن', 'حسنة', 'لطيف', 'لطيفة',
            'مراجعة', 'مراجعات', 'تصحيح', 'تصحيحات', 'تحسين', 'تحسينات',
            'تطوير', 'تطويرات', 'دقيق', 'دقة', 'مضبوط', 'مقبول', 'مقبولة',
            'مرضٍ', 'مرضية', 'واضح', 'واضحة', 'منظم', 'منظمة', 'مرتب', 'مرتبة',
            'متكامل', 'متكاملة', 'مفصل', 'مفصلة', 'دقيق', 'دقيقة', 'صحيح', 'صحيحة',
            'سليم', 'سليمة', 'ممتاز', 'ممتازة', 'مشرف', 'مشرفة', 'مبدع', 'مبدعة',
            'متفوق', 'متفوقة', 'متمكن', 'متمكنة', 'مستوفٍ', 'مستوفية', 'كامل', 'كاملة',

            // كلمات التطوير
            'تطوير', 'تطويرية', 'تحسين', 'تحسينية', 'إضافة', 'إضافية', 'توسيع', 'توسعية',
            'تحديث', 'تحديثات', 'تعديل', 'تعديلات', 'إعادة', 'مراجعة', 'تنقيح', 'تنقيحات',

            // كلمات الدعم والتشجيع
            'أحسنت', 'ممتاز', 'رائع', 'جميل', 'مفيد', 'مبتكر', 'متميز', 'فعال',
            'ناجح', 'مذهل', 'مبهر', 'قوي', 'متطور', 'حديث', 'ذكي', 'عملي',
            'سهل', 'سريع', 'مشجع', 'داعم', 'مساعد', 'مفيد', 'فعال', 'ناجح',

            // كلمات الحماس
            'متحمس', 'متحمسة', 'حماس', 'حماسة', 'شغف', 'شغوف', 'مهتم', 'مهتمة',
            'متفانٍ', 'متفانية', 'مجتهد', 'مجتهدة', 'نشيط', 'نشيطة',
        ];

        // ✅ قائمة الكلمات السلبية (موسعة جداً)
        $negativeWords = [
            // الأساسيات
            'سيء', 'سيئة', 'ضعيف', 'ضعيفة', 'ممل', 'مملة', 'متأخر', 'متأخرة',
            'فاشل', 'فاشلة', 'صعب', 'صعبة', 'معقد', 'معقدة', 'مخيب', 'مخيبة',
            'مزعج', 'مزعجة', 'متعب', 'متعبة', 'فشل', 'فشلت', 'أخطاء', 'مشكلة',
            'مشاكل', 'نقص', 'ناقص', 'ناقصة', 'ضعف', 'غير', 'لا', 'ليس', 'ليست',

            // كلمات التقييم السلبي الأكاديمي
            'غير مقبول', 'غير مقبولة', 'غير مرضٍ', 'غير مرضية', 'غير واضح', 'غير واضحة',
            'غير منظم', 'غير منظمة', 'غير مرتب', 'غير مرتبة', 'غير متكامل', 'غير متكاملة',
            'غير مفصل', 'غير مفصلة', 'غير دقيق', 'غير دقيقة', 'غير صحيح', 'غير صحيحة',
            'غير سليم', 'غير سليمة', 'غير ممتاز', 'غير ممتازة', 'غير مشرف', 'غير مشرفة',
            'غير مبدع', 'غير مبدعة', 'غير متفوق', 'غير متفوقة', 'غير متمكن', 'غير متمكنة',
            'غير مستوفٍ', 'غير مستوفية', 'غير كامل', 'غير كاملة',

            // كلمات النقد
            'نقد', 'ناقد', 'ناقدة', 'انتقاد', 'انتقادات', 'معترض', 'معترضة',
            'اعتراض', 'اعتراضات', 'متحفظ', 'متحفظة', 'تحفظ', 'تحفظات',

            // كلمات المشاكل
            'مشكلة', 'مشاكل', 'عيب', 'عيوب', 'نقص', 'نواقص', 'خلل', 'أخطاء',
            'خطأ', 'أخطاء', 'تقصير', 'تقصيرات', 'تأخير', 'تأخيرات', 'إهمال',

            // كلمات الضعف
            'ضعيف', 'ضعيفة', 'ضعف', 'تراجع', 'تراجعات', 'انحدار', 'انحدارات',
            'تدهور', 'تدهورات', 'فوضى', 'فوضوية', 'ارتباك', 'مربك', 'مربكة',
        ];

        // تنظيف النص
        $cleanText = preg_replace('/[^\p{Arabic}\s]/u', '', $text);
        $words = preg_split('/\s+/', $cleanText, -1, PREG_SPLIT_NO_EMPTY);

        $positiveCount = 0;
        $negativeCount = 0;
        $totalWords = count($words);

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveCount++;
            } elseif (in_array($word, $negativeWords)) {
                $negativeCount++;
            }
        }

        // حساب النسبة
        if ($totalWords === 0) {
            return response()->json([
                'success' => true,
                'label' => 'NEUTRAL',
                'score' => 0.5,
                'emoji' => '😐',
                'description' => 'نص محايد (لا توجد كلمات)',
                'positive_count' => 0,
                'negative_count' => 0,
                'total_words' => 0
            ]);
        }

        // النسبة المئوية للكلمات الإيجابية
        $score = $positiveCount / $totalWords;

        // تحديد المشاعر
        $label = 'NEUTRAL';
        $emoji = '😐';
        $description = 'نص محايد';

        if ($positiveCount > $negativeCount && $score > 0.15) {
            $label = 'POSITIVE';
            $emoji = '😊';
            $description = 'نص إيجابي';
        } elseif ($positiveCount > $negativeCount && $score > 0.30) {
            $label = 'VERY_POSITIVE';
            $emoji = '🌟';
            $description = 'نص إيجابي جداً';
        } elseif ($negativeCount > $positiveCount && $negativeCount / $totalWords > 0.10) {
            $label = 'NEGATIVE';
            $emoji = '😞';
            $description = 'نص سلبي';
        } elseif ($negativeCount > $positiveCount && $negativeCount / $totalWords > 0.25) {
            $label = 'VERY_NEGATIVE';
            $emoji = '💔';
            $description = 'نص سلبي جداً';
        }

        return response()->json([
            'success' => true,
            'label' => $label,
            'score' => round($score, 2),
            'emoji' => $emoji,
            'description' => $description,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'total_words' => $totalWords,
            'text' => $text
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ تحليل المشاعر:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء تحليل المشاعر',
            'error' => $e->getMessage()
        ], 500);
    }
}
    // ========== التوصية الذكية بالمشرفين (مع تحسين المهلة) ==========
    public function recommendSupervisors(Request $request)
    {
        try {
            $request->validate([
                'project_title' => 'required|string|max:255',
                'project_description' => 'nullable|string',
            ]);

            // ✅ طباعة البيانات الواردة
            \Log::info('📥 البيانات المستلمة:', $request->all());

            // ✅ التحقق من تشغيل خادم Node.js أولاً
            try {
                $ping = Http::timeout(2)->get('http://127.0.0.1:3000');
                \Log::info('✅ خادم Node.js يعمل');
            } catch (\Exception $e) {
                \Log::error('❌ خادم Node.js لا يعمل: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ خادم الذكاء الاصطناعي غير متصل. تأكدي من تشغيله على المنفذ 3000.',
                    'error' => $e->getMessage()
                ]);
            }

            // جلب المشرفين
            $supervisors = User::where('role', 'supervisor')
                ->with('supervisor')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'specialization' => $user->supervisor->specialization ?? 'عام',
                    ];
                })->toArray();

            \Log::info('👥 عدد المشرفين:', ['count' => count($supervisors)]);

            if (empty($supervisors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يوجد مشرفون في النظام',
                ]);
            }

            // إرسال الطلب إلى خادم Node.js (مهلة أطول 120 ثانية)
            $payload = [
                'title' => $request->project_title,
                'description' => $request->project_description ?? '',
                'supervisors' => $supervisors,
            ];
            \Log::info('📤 البيانات المرسلة لـ Node.js:', $payload);

$response = Http::timeout(180)->post('http://127.0.0.1:3000/api/recommend-supervisors', $payload);
            \Log::info('📥 استجابة Node.js:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'فشل الاتصال بخادم الذكاء الاصطناعي',
                'details' => $response->body(),
            ], 500);
        } catch (\Exception $e) {
            \Log::error('❌ خطأ في التوصيات:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء التوصية',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
