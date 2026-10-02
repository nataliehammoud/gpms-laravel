<x-app-layout>
    <style>
        /* 1. الربط المحلي القوي (لم نلمسه) */
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        @font-face { font-family: 'Font Awesome 6 Free'; src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2'); font-weight: 900; }
        * { font-family: 'Cairo', sans-serif !important; }
        .fas, .fa-solid { font-family: 'Font Awesome 6 Free' !important; font-weight: 900 !important; }

        /* لوحة الألوان الفينتج الجديدة */
        :root {
            --vintage-navy: #1a2a3a;
            --vintage-gold: #d4af37;
            --vintage-cream: #fdf8e7;
            --vintage-dark-cream: #f5ecd7;
            --vintage-bronze: #8b7355;
            --vintage-shadow: rgba(26, 42, 58, 0.15);
        }

        body {
            background: linear-gradient(135deg, var(--vintage-cream) 0%, #f0e5d2 100%);
            overflow-x: hidden;
            position: relative;
        }

        /* إضافة نسيج فينتج خفيف */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" opacity="0.03"><path d="M70 30 L90 10 L110 30 L130 10 L150 30 L170 10 L190 30" stroke="%23d4af37" fill="none" stroke-width="1"/><circle cx="100" cy="100" r="20" stroke="%23d4af37" fill="none" stroke-width="1"/><path d="M30 70 L10 90 L30 110 L10 130 L30 150 L10 170 L30 190" stroke="%23d4af37" fill="none" stroke-width="1"/></svg>');
            background-repeat: repeat;
            pointer-events: none;
        }

        nav, header { display: none !important; }

        /* الحاوية الرئيسية - نظام شبكي فينتج */
        .vintage-dashboard {
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 25px;
        }

        /* الكارت الرئيسي القديم */
        .antique-card {
            background: var(--vintage-cream);
            border-radius: 25px;
            box-shadow: 15px 15px 30px var(--vintage-shadow), -5px -5px 12px rgba(255,255,255,0.7);
            margin-bottom: 35px;
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        /* هيدر بنمط صناعي قديم */
        .vintage-header {
            background: linear-gradient(135deg, var(--vintage-navy) 0%, #243b4a 100%);
            border-radius: 25px 25px 15px 15px;
            padding: 35px 45px;
            position: relative;
            overflow: hidden;
        }

        .vintage-header::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 140%;
            height: 200%;
            background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .golden-line {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--vintage-gold), var(--vintage-gold), transparent);
        }

        /* تنسيقات مبتكرة للبطاقات */
        .stages-vintage-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 30px;
            padding: 40px 35px;
        }

        /* البطاقة الفينتج */
        .vintage-stage-card {
            background: var(--vintage-cream);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.2, 0.64, 1);
            box-shadow: 8px 8px 16px var(--vintage-shadow), -4px -4px 8px rgba(255,255,240,0.8);
            border: 1px solid rgba(212, 175, 55, 0.2);
            position: relative;
        }

        .vintage-stage-card:hover {
            transform: translateY(-12px) rotate(0.5deg);
            box-shadow: 12px 18px 30px var(--vintage-shadow);
            border-color: var(--vintage-gold);
        }

        /* شريط ذهبي علوي متحرك */
        .vintage-stage-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--vintage-gold), #ffea9e, var(--vintage-gold));
            transform: scaleX(0);
            transition: transform 0.5s ease;
            transform-origin: left;
        }

        .vintage-stage-card:hover::before {
            transform: scaleX(1);
        }

        /* رأس البطاقة */
        .card-vintage-head {
            padding: 22px 25px 15px 25px;
            background: linear-gradient(135deg, rgba(26,42,58,0.03), rgba(212,175,55,0.05));
            border-bottom: 2px dotted rgba(212, 175, 55, 0.3);
        }

        .stage-title {
            font-size: 22px;
            font-weight: 900;
            color: var(--vintage-navy);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stage-title i {
            color: var(--vintage-gold);
            font-size: 26px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .vintage-date {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
            font-size: 13px;
            color: var(--vintage-bronze);
            font-weight: 700;
            padding: 8px 12px;
            background: rgba(212, 175, 55, 0.08);
            border-radius: 40px;
            width: fit-content;
        }

        /* جسم البطاقة */
        .card-vintage-body {
            padding: 22px 25px;
        }

        /* مؤشر الحالة بنمط عتيق */
        .vintage-status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(26, 42, 58, 0.05);
            padding: 8px 18px;
            border-radius: 50px;
            margin-bottom: 20px;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .status-vintage-led {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: vintagePulse 2s infinite;
        }

        .status-vintage-led.pending { background: #c98a1e; box-shadow: 0 0 6px #c98a1e; }
        .status-vintage-led.submitted { background: #2c5f8a; box-shadow: 0 0 6px #2c5f8a; }
        .status-vintage-led.approved { background: var(--vintage-gold); box-shadow: 0 0 10px var(--vintage-gold); }
        .status-vintage-led.rejected { background: #b5655a; box-shadow: 0 0 6px #b5655a; }

        @keyframes vintagePulse {
            0% { opacity: 0.6; transform: scale(0.9); }
            50% { opacity: 1; transform: scale(1.1); }
            100% { opacity: 0.6; transform: scale(0.9); }
        }

        /* الأزرار بنمط رجعي */
        .vintage-btn {
            background: transparent;
            border: 1.5px solid var(--vintage-gold);
            color: var(--vintage-navy);
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .vintage-btn:hover {
            background: var(--vintage-gold);
            color: var(--vintage-cream);
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 6px 12px rgba(212, 175, 55, 0.3);
        }

        .vintage-btn-solid {
            background: var(--vintage-navy);
            border-color: var(--vintage-gold);
            color: var(--vintage-gold);
        }

        .vintage-btn-solid:hover {
            background: var(--vintage-gold);
            color: var(--vintage-navy);
        }

        /* منطقة رفع الملفات */
        .vintage-upload {
            border: 2px dashed rgba(212, 175, 55, 0.4);
            border-radius: 18px;
            padding: 15px;
            text-align: center;
            margin-top: 15px;
            background: rgba(212, 175, 55, 0.03);
            transition: all 0.3s;
        }

        .vintage-upload:hover {
            border-color: var(--vintage-gold);
            background: rgba(212, 175, 55, 0.06);
        }

        /* لوحة الإضافة الفينتج */
        .vintage-form-panel {
            background: var(--vintage-cream);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 20px;
            box-shadow: inset 0 1px 4px rgba(0,0,0,0.02), 0 8px 16px var(--vintage-shadow);
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .vintage-input {
            background: white;
            border: 1.5px solid #e8dcc8;
            border-radius: 50px;
            padding: 12px 20px;
            width: 100%;
            font-weight: 700;
            transition: all 0.3s;
        }

        .vintage-input:focus {
            outline: none;
            border-color: var(--vintage-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        /* الفلاش ميسج */
        .vintage-alert {
            background: linear-gradient(135deg, #fdf8e7, #fff8e8);
            border-right: 5px solid var(--vintage-gold);
            padding: 18px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            color: var(--vintage-navy);
            font-weight: 900;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* زر العودة */
        .vintage-back {
            text-align: center;
            margin: 30px 0 20px;
        }

        .back-button {
            background: var(--vintage-navy);
            color: var(--vintage-gold);
            padding: 14px 35px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            border: 1px solid var(--vintage-gold);
        }

        .back-button:hover {
            background: var(--vintage-gold);
            color: var(--vintage-navy);
            transform: translateX(-5px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2);
        }

        /* أيقونات متحركة */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
            display: inline-block;
        }

        .action-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 18px;
        }
    </style>

    <div class="vintage-dashboard" dir="rtl">

        <!-- الهيدر الفينتج -->
        <div class="antique-card">
            <div class="vintage-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                            <i class="fa-solid fa-crown floating-icon" style="color: var(--vintage-gold); font-size: 32px;"></i>
                            <span style="color: var(--vintage-gold); letter-spacing: 3px; font-size: 12px; font-weight: 900;">EST. 2024 • MILESTONE MATRIX</span>
                        </div>
                        <h1 style="color: white; font-size: 42px; margin: 10px 0; font-weight: 900;">
                            سجل تقدم المشروع
                        </h1>
                        <p style="color: rgba(255,255,220,0.8); font-weight: 700;">
                            <i class="fa-regular fa-folder"></i> {{ $project->title }}
                        </p>
                    </div>
                    <div style="background: rgba(212,175,55,0.15); padding: 12px 25px; border-radius: 60px; border: 1px solid var(--vintage-gold);">
                        <i class="fa-regular fa-user" style="color: var(--vintage-gold);"></i>
                        <span style="color: var(--vintage-gold); font-weight: 900;">{{ auth()->user()->role }}</span>
                    </div>
                </div>
                <div class="golden-line"></div>
            </div>

            <div style="padding: 0 35px 35px 35px;">
                @if(session('success'))
                    <div class="vintage-alert">
                        <i class="fa-regular fa-circle-check" style="color: var(--vintage-gold); margin-left: 10px;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- لوحة إضافة مرحلة للمشرف (نفس الباك إند) -->
                @if(trim(strtolower(auth()->user()->role)) === 'supervisor')
                    <div class="vintage-form-panel">
                        <form action="{{ route('stages.store', $project->id) }}" method="POST" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
                            @csrf
                            <div style="flex: 2;">
                                <label style="display: block; font-weight: 900; color: var(--vintage-navy); margin-bottom: 8px;">
                                    <i class="fa-regular fa-pen-to-square"></i> اسم المرحلة
                                </label>
                                <input type="text" name="stage_name" placeholder="مثلاً: مرحلة التصميم المعماري" class="vintage-input" required>
                            </div>
                            <div style="flex: 1;">
                                <label style="display: block; font-weight: 900; color: var(--vintage-navy); margin-bottom: 8px;">
                                    <i class="fa-regular fa-calendar"></i> الموعد النهائي
                                </label>
                                <input type="date" name="due_date" class="vintage-input" required>
                            </div>
                            <button type="submit" class="vintage-btn vintage-btn-solid">
                                <i class="fa-solid fa-feather-pointed"></i> إضافة مرحلة
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- شبكة البطاقات الفينتج -->
        <div class="stages-vintage-grid">
            @forelse($project->stages as $stage)
                <div class="vintage-stage-card">
                    <div class="card-vintage-head">
                        <div class="stage-title">
                            <i class="fa-regular fa-gem"></i>
                            <span>{{ $stage->stage_name }}</span>
                        </div>
                        <div class="vintage-date">
                            <i class="fa-regular fa-hourglass-half"></i>
                            <span>{{ $stage->due_date }}</span>
                        </div>
                    </div>

                    <div class="card-vintage-body">
                        <!-- مؤشر الحالة -->
                        <div class="vintage-status">
                            <div class="status-vintage-led
                                @if($stage->status == 'approved') approved
                                @elseif($stage->status == 'submitted') submitted
                                @elseif($stage->status == 'rejected') rejected
                                @else pending
                                @endif
                            "></div>
                            <span style="font-weight: 900; font-size: 12px; color: var(--vintage-navy);">
                                @if($stage->status == 'pending') <i class="fa-regular fa-clock"></i> معلق
                                @elseif($stage->status == 'submitted') <i class="fa-regular fa-paper-plane"></i> قيد المراجعة
                                @elseif($stage->status == 'approved') <i class="fa-regular fa-check-circle"></i> معتمد
                                @elseif($stage->status == 'rejected') <i class="fa-regular fa-circle-xmark"></i> مرفوض
                                @endif
                            </span>
                        </div>

                        <!-- منطق المراحل (نفس الباك إند بالكامل) -->
                        @if(str_contains($stage->stage_name, 'مراجعة') || str_contains($stage->stage_name, 'المقترح'))
                            @if($stage->status == 'approved')
                                <div style="text-align: center; padding: 12px; background: rgba(212,175,55,0.1); border-radius: 15px;">
                                    <i class="fa-regular fa-stamp" style="color: var(--vintage-gold);"></i>
                                    <span style="color: var(--vintage-navy); font-weight: 900;">✓ تم اعتماد المقترح</span>
                                </div>
                            @else
                                <div style="text-align: center; padding: 12px; background: rgba(139,115,85,0.08); border-radius: 15px;">
                                    <i class="fa-regular fa-hourglass" style="color: var(--vintage-bronze);"></i>
                                    <span style="color: var(--vintage-bronze); font-weight: 900;">بانتظار المراجعة</span>
                                </div>
                            @endif
                        @else
                            <!-- رفع الملفات للطالب -->
                            @if(auth()->user()->role == 'student' && $stage->status != 'approved')
                                <div class="vintage-upload">
                                    <form action="{{ route('files.upload', $stage->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="project_file" required style="margin-bottom: 12px; width: 100%; font-size: 13px;">
                                        <button type="submit" class="vintage-btn" style="width: 100%; justify-content: center;">
                                            <i class="fa-solid fa-cloud-arrow-up"></i> رفع الملف
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <!-- معاينة وتحكم المشرف -->
                            @if(trim(strtolower(auth()->user()->role)) === 'supervisor' && $stage->status == 'submitted')
                                @php $lastFile = $stage->files->last(); @endphp
                                <div class="action-group">
                                    @if($lastFile)
                                        <a href="{{ asset('storage/' . $lastFile->file_path) }}" target="_blank" class="vintage-btn">
                                            <i class="fa-regular fa-eye"></i> معاينة
                                        </a>
                                        <a href="{{ asset('storage/' . $lastFile->file_path) }}" download class="vintage-btn">
                                            <i class="fa-regular fa-floppy-disk"></i> تحميل
                                        </a>
                                    @endif
                                    <form action="{{ route('stages.updateStatus', $stage->id) }}" method="POST" style="display: inline-flex; gap: 8px;">
                                        @csrf
                                        <button name="status" value="approved" class="vintage-btn" style="background: var(--vintage-gold); color: var(--vintage-navy);">
                                            <i class="fa-regular fa-thumbs-up"></i> قبول
                                        </button>
                                        <button name="status" value="rejected" class="vintage-btn" style="border-color: #b5655a; color: #b5655a;">
                                            <i class="fa-regular fa-thumbs-down"></i> رفض
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <!-- حالة الإكمال -->
                            @if($stage->status == 'approved')
                                <div style="text-align: center; margin-top: 15px; padding-top: 10px;">
                                    <i class="fa-regular fa-circle-check floating-icon" style="font-size: 38px; color: var(--vintage-gold);"></i>
                                    <p style="color: var(--vintage-navy); font-weight: 900; margin-top: 8px;">✔︎ مرحلة مكتملة</p>
                                </div>
                            @endif

                            @if($stage->status == 'rejected')
                                <div style="text-align: center; margin-top: 15px; padding-top: 10px;">
                                    <i class="fa-regular fa-circle-xmark" style="font-size: 38px; color: #b5655a;"></i>
                                    <p style="color: #b5655a; font-weight: 900; margin-top: 8px;">✘ تم الرفض - أعد التقديم</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 60px; background: var(--vintage-cream); border-radius: 30px; border: 2px dashed var(--vintage-gold);">
                    <i class="fa-regular fa-compass floating-icon" style="font-size: 55px; color: var(--vintage-gold); opacity: 0.5;"></i>
                    <h3 style="color: var(--vintage-navy); margin-top: 20px;">لا توجد مراحل بعد</h3>
                    <p style="color: var(--vintage-bronze);">ابدأ بإضافة المرحلة الأولى من لوحة التحكم</p>
                </div>
            @endforelse
        </div>

        <!-- زر العودة -->
        <div class="vintage-back">
            @php $currentRole = trim(strtolower(auth()->user()->role)); @endphp
            @if($currentRole === 'supervisor')
                <a href="/supervisor/super" class="back-button">
                    <i class="fa-regular fa-arrow-right"></i> العودة للوحة المشرف
                </a>
            @else
                <a href="/student/project" class="back-button">
                    <i class="fa-regular fa-arrow-right"></i> العودة لمشاريعي
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
