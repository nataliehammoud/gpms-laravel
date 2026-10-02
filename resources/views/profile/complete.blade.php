<x-app-layout>
    <style>
        /* 1. إجبار استدعاء الخط Cairo داخل الصفحة */
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype');
            font-weight: 700;
        }
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Regular.ttf') }}") format('truetype');
            font-weight: 400;
        }

        /* 2. السيطرة الكاملة على الخط */
        * { font-family: 'Cairo', sans-serif !important; }

        nav, header, footer { display: none !important; }

        :root {
            --royal-navy: #0f172a; /* نيفي عميق وفخم */
            --soft-navy: #1e293b;  /* نيفي فاتح للتدرج */
            --royal-gold: #c5a059; /* ذهبي ملكي */
            --cream-bg: #fdfbf7;   /* أبيض كريمي مريح */
        }

        .royal-wrapper {
            min-height: 100vh;
            display: flex;
            background: var(--cream-bg);
            position: relative;
            overflow: hidden;
            direction: rtl;
        }

        /* القسم النيفي المفتح */
        .navy-side {
            width: 42%;
            background: linear-gradient(135deg, var(--royal-navy) 0%, var(--soft-navy) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            z-index: 10;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
        }

        .ruffle-divider {
            position: absolute;
            top: 0;
            right: 42%;
            width: 60px;
            height: 100%;
            z-index: 50;
            /* لون الكشكوشة متناسق مع النيفي */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://w3.org' width='60' height='100' viewBox='0 0 60 100'%3E%3Cpath d='M0 0 Q 30 25 0 50 Q 30 75 0 100 L 60 100 L 60 0 Z' fill='%231e293b'/%3E%3C/svg%3E");
            background-repeat: repeat-y;
            background-size: 45px auto;
            filter: drop-shadow(10px 0 15px rgba(0,0,0,0.2));
            transform: scaleX(-1);
        }

        /* الأزرار العلوية التفاعلية بستايل نيفي */
        .top-actions {
            position: absolute;
            top: 30px;
            left: 40px;
            display: flex;
            gap: 15px;
            z-index: 100;
        }

        .btn-utility {
            background: white;
            border: 1px solid rgba(15, 23, 42, 0.1);
            padding: 10px 22px;
            border-radius: 15px;
            font-weight: 800;
            font-size: 13px;
            color: var(--royal-navy);
            text-decoration: none !important;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }

        .btn-utility:hover {
            transform: translateY(-3px);
            border-color: var(--royal-gold);
            color: var(--royal-gold);
            background: var(--royal-navy);
        }

        .btn-logout { color: #dc2626 !important; }
        .btn-logout:hover { background: #fee2e2; border-color: #dc2626; color: #dc2626 !important; }

        /* اللوغو والحقول */
        .logo-sphere {
            width: 110px; height: 110px;
            background: linear-gradient(135deg, var(--royal-gold) 0%, #a18c2c 100%);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 35px; box-shadow: 0 15px 35px rgba(197, 160, 89, 0.3);
            animation: morphing 8s infinite alternate;
        }
        @keyframes morphing {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            100% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
        }

        .input-box {
            background: #f1f5f9; border: 2px solid transparent; border-radius: 18px;
            display: flex; align-items: center; padding: 6px 18px; transition: 0.3s; margin-top: 8px;
        }
        .input-box:focus-within {
            background: white;
            border-color: var(--royal-gold);
            transform: translateX(-8px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.05);
        }
        .input-box input, .input-box select {
            border: none !important; background: transparent !important; width: 100%;
            padding: 12px !important; outline: none !important; font-weight: 700; color: var(--royal-navy);
        }

        .btn-master {
            background: linear-gradient(90deg, var(--royal-navy) 0%, var(--soft-navy) 100%);
            color: var(--royal-gold);
            width: 100%; padding: 20px; border-radius: 20px;
            font-weight: 900; font-size: 1.2rem; border: 1px solid var(--royal-gold);
            cursor: pointer; margin-top: 40px; transition: 0.4s;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.2);
        }
        .btn-master:hover { transform: translateY(-5px); background: var(--royal-gold); color: var(--royal-navy); }
    </style>

    <div class="royal-wrapper">
        <!-- أزرار التحكم -->
        <div class="top-actions">
            <a href="{{ url('/') }}" class="btn-utility">
                <i class="fas fa-house-user"></i> العودة للواجهة الرئيسية
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-utility btn-logout">
                    <i class="fas fa-power-off"></i> تسجيل الخروج
                </button>
            </form>
        </div>

        <div class="ruffle-divider"></div>

        <!-- الجانب النيفي (هيبة التصميم) -->
        <div class="navy-side">
            <div style="position: relative; z-index: 10;">
                <div class="logo-sphere">
                    <i class="fas fa-id-badge text-white" style="font-size: 3rem;"></i>
                </div>
                <h3 style="color: #f6dcbd; font-weight: 900; font-size: 4rem; line-height: 1; margin-bottom: 20px; tracking-tighter: -2px;">
                    توثيق الهوية
                </h3>

                <div style="margin-top: 50px; border-right: 4px solid var(--royal-gold); padding-right: 20px;">
                    <p style="color: var(--royal-gold); font-weight: 800; font-size: 0.9rem;">نظام GPMS الأكاديمي</p>
                </div>
            </div>
        </div>

        <!-- الجانب الكريمي (الفورم) -->
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px;">
            <div style="width: 100%; max-width: 480px; padding-right: 40px;">
                <div class="mb-10 text-right">
                    <h2 style="color: var(--royal-navy); font-weight: 900; font-size: 2.8rem; margin-top: 10px;">إكمال البيانات</h2>
                </div>

                <form method="POST" action="{{ route('profile.complete.store') }}">
                    @csrf

                    <div class="mb-6 text-right">
                        <label style="color: #64748b; font-weight: 800; font-size: 0.85rem; margin-right: 10px;">الرقم الجامعي </label>
                        <div class="input-box">
                            <i class="fas fa-hashtag" style="color: var(--royal-gold); opacity: 0.7;"></i>
                            <input type="text" name="student_number" required placeholder="مثلاً: 20241105">
                        </div>
                    </div>

                 <div class="mb-6 text-right">
    <label style="color: #64748b; font-weight: 800; font-size: 0.85rem; margin-right: 10px;">القسم أو التخصص الأكاديمي</label>
    <div class="input-box">
        <i class="fas fa-graduation-cap" style="color: var(--royal-gold); opacity: 0.7;"></i>
        {{-- استبدلنا الـ input بـ select مع نفس الأقسام --}}
        <select name="department" required class="gate-input" style="width: 100%; border: none; background: transparent; color: var(--navy); font-weight: 700; outline: none; appearance: none;">
            <option value="" disabled selected>اختر قسمك الأكاديمي</option>
            <option value="Information Systems">معلومات</option>
            <option value="Computer Systems">نظم</option>
            <option value="Communications">اتصالات</option>
        </select>
    </div>
</div>

                    <div class="mb-6 text-right">
                        <label style="color: #64748b; font-weight: 800; font-size: 0.85rem; margin-right: 10px;">السنة الدراسية الحالية</label>
                        <div class="input-box">
                            <i class="fas fa-calendar-check" style="color: var(--royal-gold); opacity: 0.7;"></i>
                            <select name="year" required>
                                <option value="" disabled selected>اختر السنة الدراسية</option>
                                <option value="4">السنة الرابعة (Senior 1)</option>
                                <option value="5">السنة الخامسة (Senior 2)</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-master">
                        <span>اعتماد الملف الشخصي</span>
                        <i class="fas fa-chevron-left" style="margin-right: 10px;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
