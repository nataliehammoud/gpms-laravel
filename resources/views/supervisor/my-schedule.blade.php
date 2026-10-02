<x-app-layout>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy-deep: #0f1f2d;
            --navy-soft: #1a3345;
            --gold-bright: #e8b84a;
            --gold-dark: #c9972c;
            --cream-pure: #fffdf8;
            --cream-warm: #fef7ed;
            --shadow-soft: 0 20px 35px -12px rgba(0,0,0,0.08);
            --shadow-gold: 0 18px 30px -12px rgba(232, 184, 74, 0.2);
        }

        body {
            background: linear-gradient(145deg, var(--cream-pure) 0%, var(--cream-warm) 100%);
            font-family: 'Cairo', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* خلفية أكاديمية ناعمة */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 25% 40%, rgba(232, 184, 74, 0.04) 1.2px, transparent 1.2px);
            background-size: 50px 50px;
            pointer-events: none;
        }

        /* الحاوية الرئيسية */
        .discussions-dashboard {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 35px;
            position: relative;
            z-index: 2;
        }

        /* زر الرجوع - أكاديمي رسمي */
        .back-nav {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--navy-deep);
            padding: 10px 28px;
            border-radius: 40px;
            text-decoration: none;
            color: var(--gold-bright);
            font-weight: 600;
            margin-bottom: 35px;
            transition: all 0.3s ease;
            font-size: 14px;
            border: 1px solid rgba(232, 184, 74, 0.3);
        }

        .back-nav:hover {
            background: var(--gold-bright);
            color: var(--navy-deep);
            transform: translateX(-4px);
        }

        /* الهيدر الرئيسي - أكاديمي */
        .hero-section {
            text-align: center;
            margin-bottom: 45px;
            border-bottom: 2px solid rgba(232, 184, 74, 0.2);
            padding-bottom: 20px;
        }

        .hero-section h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy-deep);
            margin: 0;
        }

        .hero-section h1 span {
            color: var(--gold-bright);
        }

        .hero-section p {
            color: #6b7a8a;
            font-weight: 500;
            margin-top: 8px;
            font-size: 0.85rem;
        }

        /* إحصائيات سريعة */
        .stats-strip {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .stat-pill {
            background: white;
            padding: 8px 24px;
            border-radius: 40px;
            border: 1px solid rgba(232, 184, 74, 0.25);
            font-weight: 600;
            color: var(--navy-soft);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            box-shadow: var(--shadow-soft);
        }

        /* شبكة البطاقات - أكاديمية */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 28px;
        }

        .discussion-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(232, 184, 74, 0.15);
            box-shadow: var(--shadow-soft);
        }

        .discussion-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold-bright);
            box-shadow: var(--shadow-gold);
        }

        /* رأس البطاقة */
        .card-ribbon {
            background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-soft) 100%);
            padding: 18px 22px;
        }

        .project-title {
            color: white;
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.4;
            padding-right: 12px;
            border-right: 3px solid var(--gold-bright);
        }

        /* جسم البطاقة */
        .card-body {
            padding: 22px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f0ece4;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 11px;
            color: #8b9aab;
            font-weight: 600;
            min-width: 60px;
        }

        .info-value {
            font-weight: 600;
            color: var(--navy-deep);
            font-size: 0.85rem;
        }

        .room-chip {
            background: rgba(232, 184, 74, 0.1);
            padding: 4px 14px;
            border-radius: 20px;
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gold-dark);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8f5e9;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: #2e7d64;
            margin-top: 12px;
        }

        /* حالة فارغة */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: white;
            border-radius: 24px;
            border: 1px solid rgba(232, 184, 74, 0.2);
        }

        .empty-state h3 {
            color: var(--navy-deep);
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #9b8a70;
            font-size: 0.85rem;
        }

        /* تذييل أكاديمي */
        .footer-note {
            margin-top: 45px;
            text-align: center;
            padding: 18px;
            color: #9b8a70;
            font-size: 11px;
            border-top: 1px solid rgba(232, 184, 74, 0.15);
        }

        @media (max-width: 850px) {
            .discussions-dashboard { padding: 0 20px; margin: 25px auto; }
            .cards-grid { grid-template-columns: 1fr; }
            .hero-section h1 { font-size: 1.5rem; }
        }
    </style>

    <div class="discussions-dashboard" dir="rtl">

        <!-- زر العودة -->
        <a href="{{ route('supervisor.super') }}" class="back-nav">
            <span>←</span> العودة للوحة المشرف
        </a>

        <!-- الهيدر -->
        <div class="hero-section">
            <h1>جدول <span>المناقشات</span></h1>
            <p>مواعيد مناقشات المشاريع المعتمدة</p>
        </div>

        <!-- إحصائيات -->
        <div class="stats-strip">
            <div class="stat-pill"> إجمالي المشاريع: {{ $projects->count() }}</div>
            <div class="stat-pill"> تحت الإشراف: {{ $projects->count() }}</div>
            <div class="stat-pill"> {{ now()->format('F Y') }}</div>
        </div>

        <!-- شبكة البطاقات -->
        <div class="cards-grid">
            @forelse($projects as $idx => $project)
            <div class="discussion-card">
                <div class="card-ribbon">
                    <div class="project-title">{{ $project->title }}</div>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">الطالب</div>
                        <div class="info-value">{{ $project->student->user->name ?? 'غير محدد' }}</div>
                    </div>

                    @if($project->discussion_time)
                    <div class="info-row">
                        <div class="info-label">التاريخ</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($project->discussion_time)->format('Y/m/d') }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">الوقت</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($project->discussion_time)->format('h:i A') }}</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">القاعة</div>
                        <div class="info-value">
                            <span class="room-chip">{{ $project->discussion_room ?? 'لم تحدد' }}</span>
                        </div>
                    </div>

                    <div class="status-badge">
                        ✓ تمت الجدولة رسمياً
                    </div>
                    @else
                    <div class="info-row">
                        <div class="info-label">حالة الموعد</div>
                        <div class="info-value" style="color: #c9972c;">بانتظار التحديد</div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1/-1;">
                <h3>لا توجد مشاريع حالياً</h3>
                <p>لم يتم تعيين أي مشاريع تحت إشرافك بعد</p>
            </div>
            @endforelse
        </div>

        <!-- تذييل -->
        <div class="footer-note">
            <span>نظام إدارة المناقشات الأكاديمية</span>
        </div>
    </div>
</x-app-layout>
