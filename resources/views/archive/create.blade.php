<x-app-layout>
    <style>
        /* إجبار الخط Cairo والأيقونات محلياً */
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        @font-face { font-family: 'Font Awesome 6 Free'; src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2'); font-weight: 900; }

        * { font-family: 'Cairo', sans-serif !important; direction: rtl; }
        .fa-solid, .fas { font-family: 'Font Awesome 6 Free' !important; font-weight: 900 !important; }

        :root {
            --navy: #1a2a40;
            --gold: #c5a059;
            --pearl: #fdfbf7;
            --glass-white: rgba(255, 255, 255, 0.8);
        }

        /* منع التضارب مع الداشبورد */
        nav, header { display: none !important; }

        .archive-page {
            background-color: var(--pearl);
            background-image: radial-gradient(circle at 1px 1px, #e5e7eb 1px, transparent 0);
            background-size: 40px 40px;
            min-height: 100vh;
            padding: 20px 0 60px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* الشريط العلوي */
        .top-action-bar {
            width: 100%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .btn-glass {
            background: var(--glass-white);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(197, 160, 89, 0.2);
            padding: 10px 20px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 13px;
            color: var(--navy);
            text-decoration: none !important;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-glass:hover { transform: translateY(-3px); border-color: var(--gold); background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .btn-logout { color: #dc2626 !important; border-color: rgba(220, 38, 38, 0.1); }
        .btn-logout:hover { background: #fef2f2; border-color: #dc2626; }

        /* اللوجو */
        .brand-logo {
            background: var(--navy);
            padding: 12px 40px;
            border-radius: 20px;
            margin-bottom: 40px;
            border-bottom: 4px solid var(--gold);
            box-shadow: 0 20px 40px rgba(26, 42, 64, 0.15);
        }

        /* الكرت الرئيسي */
        .main-card {
            background: #fff;
            width: 95%;
            max-width: 800px;
            border-radius: 40px;
            border: 1px solid rgba(197, 160, 89, 0.15);
            box-shadow: 0 30px 60px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        /* الهيدر */
        .card-header {
            background: var(--navy);
            padding: 30px 40px;
            text-align: center;
        }
        .card-header h2 {
            color: var(--gold);
            font-size: 24px;
            font-weight: 900;
            margin: 0;
        }
        .card-header p {
            color: rgba(232, 213, 168, 0.7);
            font-size: 13px;
            margin-top: 8px;
        }

        /* جسم الكرت */
        .card-body {
            padding: 40px;
        }

        /* الحقول */
        .input-luxe {
            width: 100%;
            padding: 15px 20px;
            background: var(--pearl);
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            outline: none;
            transition: 0.3s;
            font-weight: 700;
            color: var(--navy);
        }
        .input-luxe:focus {
            border-color: var(--gold);
            background: #fff;
        }

        .input-luxe-select {
            width: 100%;
            padding: 15px 20px;
            background: var(--pearl);
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            outline: none;
            transition: 0.3s;
            font-weight: 700;
            color: var(--navy);
            cursor: pointer;
        }
        .input-luxe-select:focus {
            border-color: var(--gold);
            background: #fff;
        }

        label {
            font-weight: 700;
            color: var(--navy);
            font-size: 13px;
            display: block;
            margin-bottom: 10px;
        }

        /* الأزرار */
        .action-footer {
            background: #f9fafb;
            padding: 25px 40px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        .btn-gold {
            background: var(--gold);
            color: #fff;
            padding: 12px 30px;
            border-radius: 18px;
            border: none;
            font-weight: 900;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            flex: 1;
        }
        .btn-gold:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
        }
        .btn-navy {
            background: var(--navy);
            color: var(--gold);
            padding: 12px 30px;
            border-radius: 18px;
            border: none;
            font-weight: 900;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            flex: 1;
        }
        .btn-navy:hover {
            transform: scale(1.02);
            background: #1e3a5f;
        }

        .text-gold {
            color: var(--gold);
        }

        /* رسائل التنبيه */
        .alert-custom {
            border-radius: 20px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
    </style>

    <div class="archive-page">
        <!-- Top Navigation Bar -->
        <div class="top-action-bar">
            <a href="{{ url('/') }}" class="btn-glass shadow-sm">
                <i class="fa-solid fa-house"></i> واجهة الترحيب
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-glass btn-logout shadow-sm">
                    <i class="fa-solid fa-power-off"></i> خروج
                </button>
            </form>
        </div>

        <!-- Logo -->
        <div class="brand-logo">
            <span style="font-size: 28px; font-weight: 900; color: white;">GP<span style="color: var(--gold);">MS</span></span>
            <span style="color: var(--gold); font-size: 12px; display: block; text-align: center;">أرشيف المشاريع</span>
        </div>

        <div class="main-card">
            <!-- Header -->
            <div class="card-header">
                <h2><i class="fa-solid fa-folder-open"></i> إضافة مشروع إلى الأرشيف</h2>
                <p>أدخل بيانات المشروع لحفظه في قاعدة بيانات الأرشيف</p>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.archive.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert-custom" style="background: #d1fae5; border-right: 4px solid #10b981; color: #065f46;">
                            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-custom" style="background: #fee2e2; border-right: 4px solid #dc2626; color: #991b1b;">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <!-- عنوان المشروع -->
                    <div style="margin-bottom: 25px;">
                        <label>عنوان المشروع <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="title" class="input-luxe" placeholder="أدخل عنوان المشروع..." required>
                    </div>

                    <!-- أسماء الطلاب -->
                    <div style="margin-bottom: 25px;">
                        <label>أسماء الطلاب <span style="color: #dc2626;">*</span></label>
                        <textarea name="students_names" rows="2" class="input-luxe" placeholder="  ..." required></textarea>
                        <p style="font-size: 11px; color: #9ca3af; margin-top: 5px;">افصل بين الأسماء بفاصلة</p>
                    </div>

                    <!-- اسم المشرف -->
                    <div style="margin-bottom: 25px;">
                        <label>اسم المشرف <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="supervisor" class="input-luxe" placeholder="د...  " required>
                    </div>

                    <!-- السنة والقسم -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        <div>
                            <label>السنة <span style="color: #dc2626;">*</span></label>
                            <input type="number" name="year" value="{{ date('Y') }}" class="input-luxe" required>
                        </div>
                        <div>
                            <label>القسم <span style="color: #dc2626;">*</span></label>
                            <select name="department" class="input-luxe-select" required>
                                <option value="Communications"> Communications Engineering</option>
                                <option value="Information Systems"> Information Systems</option>
                                <option value="Computer Systems">Computer Systems Engineering</option>
                            </select>
                        </div>
                    </div>


                <!-- Footer -->
                <div class="action-footer">
                    <a href="{{ route('archive.index') }}" class="btn-navy">
                        <i class="fa-solid fa-arrow-right"></i> إلغاء
                    </a>
                    <button type="submit" class="btn-gold">
                        <i class="fa-solid fa-save"></i> حفظ المشروع
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
