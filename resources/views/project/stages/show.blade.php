<x-app-layout>
    <style>
        /* الربط المحلي (نفسه ما تغير) */
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        @font-face { font-family: 'Font Awesome 6 Free'; src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2'); font-weight: 900; }
        * { font-family: 'Cairo', sans-serif !important; }
        .fas, .fa-solid { font-family: 'Font Awesome 6 Free' !important; font-weight: 900 !important; }

        /* الألوان الفينتج */
        :root {
            --vintage-navy: #1a2a3a;
            --vintage-gold: #d4af37;
            --vintage-cream: #fdf8e7;
            --vintage-dark: #f5ecd7;
            --vintage-bronze: #8b7355;
        }

        body {
            background: linear-gradient(135deg, var(--vintage-cream) 0%, #f0e5d2 100%);
            padding: 40px 20px;
        }
        nav, header { display: none !important; }

        /* الحاوية الرئيسية */
        .timeline-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* البطاقات بشكل تايم لاين عمودي */
        .timeline-card {
            background: var(--vintage-cream);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 8px 8px 20px rgba(0,0,0,0.08), -4px -4px 10px rgba(255,255,240,0.8);
            border-right: 5px solid var(--vintage-gold);
            transition: all 0.3s ease;
            position: relative;
        }

        .timeline-card:hover {
            transform: translateX(-5px);
            box-shadow: 12px 12px 25px rgba(0,0,0,0.12);
        }

        /* العلامة الزمنية (الدائرة الذهبية) */
        .timeline-marker {
            position: absolute;
            right: -12px;
            top: 30px;
            width: 24px;
            height: 24px;
            background: var(--vintage-gold);
            border-radius: 50%;
            border: 3px solid var(--vintage-cream);
            box-shadow: 0 0 0 3px var(--vintage-gold);
        }

        /* الهيدر */
        .vintage-header {
            background: var(--vintage-navy);
            border-radius: 30px;
            padding: 30px 40px;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .vintage-header h1 {
            color: var(--vintage-gold);
            font-size: 36px;
            margin: 0 0 10px 0;
        }

        .vintage-header p {
            color: #cbd5e1;
            font-size: 16px;
            margin: 0;
        }

        /* الفورم */
        .form-card {
            background: var(--vintage-cream);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .form-card h3 {
            color: var(--vintage-navy);
            margin: 0 0 20px 0;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card input {
            background: white;
            border: 1.5px solid #e8dcc8;
            border-radius: 50px;
            padding: 12px 20px;
            width: 100%;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .form-card input:focus {
            outline: none;
            border-color: var(--vintage-gold);
        }

        /* الأزرار */
        .vintage-btn {
            background: var(--vintage-navy);
            color: var(--vintage-gold);
            border: 1px solid var(--vintage-gold);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s;
        }

        .vintage-btn:hover {
            background: var(--vintage-gold);
            color: var(--vintage-navy);
            transform: scale(1.02);
        }

        .btn-success {
            background: transparent;
            border-color: #2e7d64;
            color: #2e7d64;
        }
        .btn-success:hover { background: #2e7d64; color: white; }

        .btn-danger {
            background: transparent;
            border-color: #b5655a;
            color: #b5655a;
        }
        .btn-danger:hover { background: #b5655a; color: white; }

        .btn-warning {
            background: transparent;
            border-color: #c98a1e;
            color: #c98a1e;
        }
        .btn-warning:hover { background: #c98a1e; color: white; }

        /* شارات الحالة */
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 900;
        }
        .badge-pending { background: rgba(201, 138, 30, 0.15); color: #c98a1e; border: 1px solid #c98a1e; }
        .badge-submitted { background: rgba(44, 95, 138, 0.15); color: #2c5f8a; border: 1px solid #2c5f8a; }
        .badge-approved { background: rgba(212, 175, 55, 0.15); color: var(--vintage-gold); border: 1px solid var(--vintage-gold); }
        .badge-rejected { background: rgba(181, 101, 90, 0.15); color: #b5655a; border: 1px solid #b5655a; }

        /* أزرار العودة */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--vintage-navy);
            color: var(--vintage-gold);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 900;
            margin-top: 30px;
            transition: all 0.3s;
        }
        .back-link:hover {
            background: var(--vintage-gold);
            color: var(--vintage-navy);
            transform: translateX(-5px);
        }

        hr { display: none; }
    </style>

    <div class="timeline-container" dir="rtl">

        <!-- الهيدر -->
        <div class="vintage-header">
            <h1><i class="fa-regular fa-timeline"></i> سير عمل المشروع</h1>
            <p>المشروع: {{ $project->title }}</p>
            <div style="margin-top: 15px;">
                <span style="background: rgba(212,175,55,0.2); padding: 5px 15px; border-radius: 50px; color: var(--vintage-gold); font-size: 12px;">
                    <i class="fa-regular fa-user"></i> {{ Auth::user()->role }}
                </span>
            </div>
        </div>

        <!-- رسائل النجاح -->
        @if(session('success'))
            <div style="background: rgba(212,175,55,0.1); border-right: 4px solid var(--vintage-gold); padding: 15px 20px; border-radius: 15px; margin-bottom: 25px;">
                <i class="fa-regular fa-circle-check" style="color: var(--vintage-gold);"></i> {{ session('success') }}
            </div>
        @endif

        <!-- إضافة مرحلة جديدة (للمشرف فقط) -->
        @if(Auth::user()->role == 'supervisor')
            <div class="form-card">
                <h3><i class="fa-regular fa-plus"></i> إضافة مرحلة جديدة</h3>
                <form action="{{ route('stages.store', $project->id) }}" method="POST">
                    @csrf
                    <input type="text" name="stage_name" placeholder="اسم المرحلة" required>
                    <input type="date" name="due_date" required>
                    <button type="submit" class="vintage-btn" style="width: 100%;">
                        <i class="fa-regular fa-paper-plane"></i> إضافة المرحلة
                    </button>
                </form>
            </div>
        @endif

        <!-- عرض المراحل (Timeline) -->
        @forelse($project->stages as $stage)
            <div class="timeline-card">
                <div class="timeline-marker"></div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                    <h4 style="color: var(--vintage-navy); margin: 0; font-size: 20px;">
                        <i class="fa-regular fa-flag" style="color: var(--vintage-gold);"></i>
                        {{ $stage->stage_name }}
                    </h4>
                    <span class="badge
                        @if($stage->status == 'pending') badge-pending
                        @elseif($stage->status == 'submitted') badge-submitted
                        @elseif($stage->status == 'approved') badge-approved
                        @elseif($stage->status == 'rejected') badge-rejected
                        @endif
                    ">
                        @if($stage->status == 'pending') ⏳ معلق
                        @elseif($stage->status == 'submitted') 📤 قيد المراجعة
                        @elseif($stage->status == 'approved') ✅ معتمد
                        @elseif($stage->status == 'rejected') ❌ مرفوض
                        @endif
                    </span>
                </div>

                <div style="color: var(--vintage-bronze); font-size: 13px; margin-bottom: 20px;">
                    <i class="fa-regular fa-calendar"></i> تاريخ الاستحقاق: {{ $stage->due_date }}
                </div>

                <!-- رفع ملف (للطالب) -->
                @if(Auth::user()->role == 'student' && $stage->status == 'rejected')
                    <div style="background: rgba(181,101,90,0.1); border-right: 4px solid #b5655a; padding: 15px; border-radius: 15px; margin-bottom: 15px;">
                        <i class="fa-regular fa-circle-exclamation" style="color: #b5655a;"></i>
                        تم رفض هذه المرحلة، يرجى رفع الملف المعدل:
                    </div>
                    <form action="{{ route('files.upload', $stage->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                        @csrf
                        <input type="file" name="project_file" required style="margin-bottom: 12px; width: 100%;">
                        <button type="submit" class="vintage-btn btn-warning" style="width: 100%;">
                            <i class="fa-regular fa-arrow-up"></i> تحديث التسليم
                        </button>
                    </form>
                @elseif(Auth::user()->role == 'student' && $stage->status != 'approved' && $stage->status != 'submitted')
                    <form action="{{ route('files.upload', $stage->id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                        @csrf
                        <input type="file" name="project_file" required style="margin-bottom: 12px; width: 100%;">
                        <button type="submit" class="vintage-btn" style="width: 100%;">
                            <i class="fa-regular fa-cloud-arrow-up"></i> رفع الملف
                        </button>
                    </form>
                @endif

                <!-- مراجعة الملفات (للمشرف) -->
                @if(Auth::user()->role == 'supervisor' && $stage->status == 'submitted')
                    @foreach($stage->files as $file)
                        <div style="background: rgba(212,175,55,0.08); padding: 12px 15px; border-radius: 15px; margin-bottom: 15px;">
                            <i class="fa-regular fa-file"></i>
                            <a href="{{ asset('storage/'.$file->file_path) }}" download style="color: var(--vintage-navy); font-weight: 700; text-decoration: none;">
                                تحميل الملف المرفوع
                            </a>
                        </div>
                    @endforeach

                    <form action="{{ route('stages.updateStatus', $stage->id) }}" method="POST" style="display: flex; gap: 12px; margin-top: 15px;">
                        @csrf
                        <button name="status" value="approved" class="vintage-btn btn-success" style="flex: 1;">
                            <i class="fa-regular fa-check"></i> قبول
                        </button>
                        <button name="status" value="rejected" class="vintage-btn btn-danger" style="flex: 1;">
                            <i class="fa-regular fa-xmark"></i> رفض
                        </button>
                    </form>
                @endif

                <!-- عرض رسالة عند القبول -->
                @if($stage->status == 'approved')
                    <div style="margin-top: 15px; text-align: center; color: var(--vintage-gold);">
                        <i class="fa-regular fa-circle-check"></i> ✓ تم إكمال هذه المرحلة بنجاح
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 60px; background: var(--vintage-cream); border-radius: 25px;">
                <i class="fa-regular fa-inbox" style="font-size: 50px; color: var(--vintage-gold); opacity: 0.5;"></i>
                <p style="color: var(--vintage-bronze); margin-top: 20px;">لا توجد مراحل مسجلة بعد</p>
            </div>
        @endforelse

        <!-- زر العودة -->
        <div style="text-align: center;">
            @if(Auth::user()->role == 'supervisor')
                <a href="/supervisor/super" class="back-link">
                    <i class="fa-regular fa-arrow-right"></i> العودة للوحة المشرف
                </a>
            @else
                <a href="/student/project" class="back-link">
                    <i class="fa-regular fa-arrow-right"></i> العودة لمشاريعي
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
