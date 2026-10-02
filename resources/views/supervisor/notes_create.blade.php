<x-app-layout>
    <style>
        /* الربط المحلي */
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Regular.ttf') }}") format('truetype'); font-weight: 400; }
        * { font-family: 'Cairo', sans-serif !important; }

        /* الألوان الأساسية مع التدرجات */
        :root {
            --navy-deep: #0a1628;
            --navy-mid: #10233e;
            --navy-light: #1a3357;
            --navy-soft: #24426b;
            --gold-bright: #e8b84a;
            --gold-soft: #d4a13e;
            --gold-dark: #b8860b;
            --cream-pure: #fffdf7;
            --cream-warm: #fef8ec;
            --cream-soft: #faf3e0;
            --shadow-navy: rgba(10, 22, 40, 0.12);
        }

        body {
            background: linear-gradient(145deg, var(--cream-pure) 0%, var(--cream-soft) 100%);
            min-height: 100vh;
            position: relative;
        }

        /* نسيج خلفية ناعم */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle at 20% 35%, rgba(212, 161, 62, 0.04) 1.2px, transparent 1.2px);
            background-size: 35px 35px;
            pointer-events: none;
        }

        nav, header { display: none !important; }

        /* الحاوية الرئيسية */
        .navy-container {
            max-width: 1350px;
            margin: 45px auto;
            padding: 0 30px;
            position: relative;
            z-index: 2;
        }

        /* البطاقة الرئيسية */
        .navy-card {
            background: var(--cream-pure);
            border-radius: 44px;
            box-shadow: 0 25px 45px -15px var(--shadow-navy);
            transition: all 0.4s cubic-bezier(0.2, 0.95, 0.4, 1.1);
            border: 1px solid rgba(212, 161, 62, 0.2);
            overflow: hidden;
        }

        /* الهيدر - تدرج بلو نيفي */
        .navy-header {
            background: linear-gradient(115deg, var(--navy-deep) 0%, var(--navy-mid) 40%, var(--navy-light) 100%);
            padding: 30px 45px;
            position: relative;
            overflow: hidden;
        }

        .navy-header::after {
            content: '✦ ✦ ✦';
            position: absolute;
            bottom: -12px;
            left: 25px;
            font-size: 42px;
            color: rgba(232, 184, 74, 0.1);
            letter-spacing: 15px;
        }

        /* الشبكة 3 أعمدة */
        .navy-grid {
            display: grid;
            grid-template-columns: 280px 1fr 280px;
            gap: 0;
            background: transparent;
        }

        /* الأعمدة الجانبية */
        .navy-sidebar {
            padding: 32px 24px;
            background: linear-gradient(180deg, var(--cream-warm) 0%, var(--cream-pure) 100%);
        }

        .navy-sidebar-left {
            border-left: 1px solid rgba(212, 161, 62, 0.15);
        }

        .navy-sidebar-right {
            border-right: 1px solid rgba(212, 161, 62, 0.15);
        }

        /* المنتصف */
        .navy-center {
            padding: 38px 35px;
            background: var(--cream-pure);
            margin: 10px 0;
            border-radius: 28px;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.9), 0 5px 15px rgba(0,0,0,0.02);
        }

        /* الصورة الرمزية - دائرية بلون ذهبي */
        .navy-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(145deg, var(--gold-bright), var(--gold-dark));
            border-radius: 50%;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            box-shadow: 0 12px 22px -6px rgba(184, 134, 11, 0.35);
            transition: all 0.3s;
        }

        .navy-avatar:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 28px -8px rgba(184, 134, 11, 0.45);
        }

        /* إحصائيات بلو نيفي */
        .navy-stat {
            background: linear-gradient(95deg, rgba(16, 35, 62, 0.04) 0%, rgba(26, 51, 87, 0.02) 100%);
            border-radius: 24px;
            padding: 14px 18px;
            margin-bottom: 14px;
            transition: all 0.25s;
            border: 1px solid rgba(212, 161, 62, 0.1);
        }

        .navy-stat:hover {
            background: linear-gradient(95deg, rgba(212, 161, 62, 0.06) 0%, rgba(212, 161, 62, 0.02) 100%);
            transform: translateX(-4px);
            border-color: rgba(212, 161, 62, 0.25);
        }

        /* منطقة النص */
        .navy-textarea {
            width: 100%;
            background: var(--cream-warm);
            border: 2px solid rgba(212, 161, 62, 0.25);
            border-radius: 28px;
            padding: 22px 26px;
            font-size: 16px;
            line-height: 1.75;
            font-weight: 500;
            resize: vertical;
            transition: all 0.3s;
            color: var(--navy-mid);
        }

        .navy-textarea:focus {
            outline: none;
            border-color: var(--gold-bright);
            box-shadow: 0 0 0 4px rgba(232, 184, 74, 0.12);
            background: white;
        }

        /* زر الإرسال - تدرج بلو نيفي مع ذهبي */
        .navy-send {
            background: linear-gradient(135deg, var(--navy-mid) 0%, var(--navy-light) 100%);
            border: 1px solid rgba(232, 184, 74, 0.35);
            padding: 14px 34px;
            border-radius: 48px;
            color: var(--gold-bright);
            font-weight: 900;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.35s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .navy-send:hover {
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy-mid) 100%);
            color: var(--gold-soft);
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 12px 22px -10px rgba(26, 51, 87, 0.4);
            border-color: var(--gold-bright);
        }

        /* كروت الملاحظات */
        .navy-notes {
            margin-top: 35px;
            padding: 0 32px 40px 32px;
        }

        .navy-note-item {
            background: var(--cream-warm);
            border-radius: 24px;
            padding: 22px 28px;
            margin-bottom: 18px;
            transition: all 0.35s;
            border: 1px solid rgba(212, 161, 62, 0.18);
            position: relative;
        }

        .navy-note-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--gold-bright), var(--gold-dark));
            border-radius: 10px 0 0 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .navy-note-item:hover {
            transform: translateX(-6px);
            background: white;
            box-shadow: 0 12px 24px -12px var(--shadow-navy);
            border-color: rgba(232, 184, 74, 0.35);
        }

        .navy-note-item:hover::before {
            opacity: 1;
        }

        .navy-note-date {
            font-size: 11px;
            color: var(--gold-dark);
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            gap: 10px;
            align-items: center;
            letter-spacing: 0.3px;
        }

        .navy-note-text {
            color: var(--navy-mid);
            font-weight: 500;
            line-height: 1.7;
        }

        /* نصائح سريعة بتدرجات بلو */
        .navy-tip {
            background: linear-gradient(95deg, rgba(16, 35, 62, 0.04), rgba(16, 35, 62, 0.01));
            border-radius: 20px;
            padding: 12px 16px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: 700;
            color: var(--navy-light);
            transition: all 0.25s;
            border: 1px solid rgba(212, 161, 62, 0.12);
        }

        .navy-tip:hover {
            background: linear-gradient(95deg, rgba(212, 161, 62, 0.08), rgba(212, 161, 62, 0.02));
            transform: translateX(-3px);
            border-color: rgba(212, 161, 62, 0.3);
        }

        /* الفوتر - تدرج بلو */
        .navy-footer {
            background: linear-gradient(115deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
            padding: 20px 40px;
            text-align: center;
        }

        .navy-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: 2px solid var(--gold-bright);
            padding: 12px 38px;
            border-radius: 60px;
            color: var(--gold-bright);
            text-decoration: none;
            font-weight: 900;
            transition: all 0.35s;
        }

        .navy-back-btn:hover {
            background: var(--gold-bright);
            color: var(--navy-deep);
            transform: translateX(-6px);
            gap: 16px;
            border-color: var(--gold-bright);
        }

        /* زر الخروج */
        .navy-logout {
            background: rgba(232, 184, 74, 0.12);
            border: 1px solid rgba(232, 184, 74, 0.3);
            padding: 8px 22px;
            border-radius: 40px;
            color: var(--gold-bright);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s;
        }

        .navy-logout:hover {
            background: rgba(232, 184, 74, 0.25);
            transform: scale(1.02);
            border-color: var(--gold-bright);
        }

        /* سكرول */
        .navy-scroll {
            max-height: 380px;
            overflow-y: auto;
            padding-left: 6px;
        }

        .navy-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .navy-scroll::-webkit-scrollbar-track {
            background: var(--cream-soft);
            border-radius: 10px;
        }

        .navy-scroll::-webkit-scrollbar-thumb {
            background: var(--gold-soft);
            border-radius: 10px;
        }
    </style>

    <div class="navy-container" dir="rtl">

        <!-- الهيدر بتدرج بلو نيفي -->
        <div class="navy-card">
            <div class="navy-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <span style="background: rgba(232, 184, 74, 0.18); padding: 4px 18px; border-radius: 40px; font-size: 11px; font-weight: 900; color: var(--gold-bright);">
                                📄 مشروع #{{ $projectId }}
                            </span>
                        </div>
                        <h1 style="color: white; margin: 0; font-size: 34px; font-weight: 900; letter-spacing: -0.5px;">
                        إضافة <span style="color: var(--gold-bright);">ملاحظات</span>
                        </h1>
                        <p style="color: rgba(232, 184, 74, 0.65); margin: 6px 0 0 0; font-size: 14px;">تواصل مباشر مع فريق العمل</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="navy-logout">
                            🚪 خروج
                        </button>
                    </form>
                </div>
            </div>

            <!-- الشبكة 3 أعمدة -->
            <div class="navy-grid">

                <!-- العمود الأيمن -->
               <div class="navy-sidebar navy-sidebar-left">
    <div style="text-align: center;">
        {{-- ❌ تم حذف الدائرة (navy-avatar) --}}
        <h3 style="margin: 14px 0 4px; color: var(--navy-mid); font-weight: 900;">المشرف</h3>
        <p style="font-size: 12px; color: var(--gold-dark); font-weight: 700;">{{ auth()->user()->name ?? 'Senior Supervisor' }}</p>
    </div>
    <div style="margin-top: 28px;">

    </div>
</div>
                <!-- المنتصف -->
                <div class="navy-center">
                    <div style="margin-bottom: 22px;">
                        <span style="background: linear-gradient(135deg, var(--gold-bright), var(--gold-dark)); padding: 4px 18px; border-radius: 40px; font-size: 11px; font-weight: 900; color: var(--navy-deep);">
                            ✎ ملاحظة جديدة
                        </span>
                    </div>

                    <form action="{{ route('supervisor.notes.store', $projectId) }}" method="POST">
                        @csrf
                        <textarea
                            name="content"
                            rows="5"
                            class="navy-textarea"
                            placeholder="اكتب توجيهاتك هنا... سيتم إرسالها مباشرة للطلاب"
                            required
                        ></textarea>

                        <div style="margin-top: 28px;">
                            <button type="submit" class="navy-send">
                             إرسال التوجيه
                                <span style="font-size: 15px;">←</span>
                            </button>
                        </div>
                    </form>

                    @if(session('success'))
                        <div style="margin-top: 20px; background: rgba(232, 184, 74, 0.08); border-radius: 20px; padding: 14px; text-align: center; font-weight: 700; color: var(--gold-dark); border-right: 3px solid var(--gold-bright);">
                            ✓ {{ session('success') }}
                        </div>
                    @endif
                </div>

                <!-- العمود الأيسر -->
                <div class="navy-sidebar navy-sidebar-right">
                    <h4 style="color: var(--navy-mid); margin: 0 0 18px 0; font-size: 15px; font-weight: 900;"> تذكيرات </h4>
                    <div class="navy-tip">
                         كن محدداً وواضحاً
                    </div>
                    <div class="navy-tip">
                         التوقيت يحسن الاستجابة
                    </div>
                    <div class="navy-tip">
                         استخدم أمثلة توضيحية
                    </div>
                    <div class="navy-tip">
                         تابع سجل الملاحظات
                    </div>
                    <div class="navy-tip">
                         التوجيهات المنتظمة = جودة أعلى
                    </div>
                    <div style="margin-top: 28px; text-align: center;">
                        <span style="font-size: 10px; color: var(--gold-soft); letter-spacing: 1px;">✦ نظام إدارة متقدم ✦</span>
                    </div>
                </div>
            </div>

            <!-- سجل الملاحظات -->
            <div class="navy-notes">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 22px;">
                    <span style="font-size: 24px; color: var(--gold-dark);">📋</span>
                    <h3 style="color: var(--navy-mid); margin: 0; font-weight: 900;">سجل التوجيهات السابقة</h3>
                    <div style="flex: 1; height: 2px; background: linear-gradient(90deg, var(--gold-bright), transparent);"></div>
                </div>

                @php
                    $previousNotes = \App\Models\ProjectNote::where('project_id', $projectId)->latest()->get();
                @endphp

                @if($previousNotes->isEmpty())
                    <div style="text-align: center; padding: 55px; background: linear-gradient(135deg, var(--cream-warm), var(--cream-pure)); border-radius: 32px; border: 1px solid rgba(212, 161, 62, 0.15);">
                        <span style="font-size: 48px; display: block; margin-bottom: 12px;">📭</span>
                        <p style="color: #b8aa8a; font-weight: 600;">لا توجد ملاحظات سابقة، أرسل أول توجيه الآن</p>
                    </div>
                @else
                    <div class="navy-scroll">
                        @foreach($previousNotes as $pNote)
                            <div class="navy-note-item">
                                <div class="navy-note-date">
                                    <span>✍️ المشرف</span>
                                    <span>•</span>
                                    <span> {{ $pNote->created_at->format('Y-m-d | h:i A') }}</span>
                                </div>
                                <div class="navy-note-text">
                                    {{ $pNote->content }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- الفوتر -->
            <div class="navy-footer">
                <a href="{{ route('supervisor.super') }}" class="navy-back-btn">
                    <span>←</span> العودة إلى لوحة المشاريع
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
