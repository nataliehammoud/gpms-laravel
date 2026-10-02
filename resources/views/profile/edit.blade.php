<x-app-layout>
<style>
    /* باليت الألوان الملكية المحدثة */
    :root {
        --royal-navy: #1a2a40;
        --royal-gold: #c5a059;
        --pearl-bg: #fdfbf7;
        --glass-white: rgba(255, 255, 255, 0.85);
    }

    body {
        background-color: var(--pearl-bg) !important;
        background-image: radial-gradient(circle at 20% 30%, rgba(197, 160, 89, 0.05) 0%, transparent 50%),
                          radial-gradient(circle at 80% 70%, rgba(26, 42, 64, 0.03) 0%, transparent 50%);
        min-height: 100vh;
    }

    /* هيدر النظام الفخم مع اللوغو */
    .system-header {
        background: var(--royal-navy);
        padding: 60px 20px 140px;
        text-align: center;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
    }

    .logo-container {
        display: inline-block;
        position: relative;
        margin-bottom: 20px;
    }

    .system-logo {
        font-size: 3rem;
        font-weight: 900;
        color: white;
        text-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .system-logo span { color: var(--royal-gold); }

    /* زر العودة الذكي العائم */
    .smart-back-btn {
        position: fixed;
        top: 30px;
        right: 30px;
        z-index: 1000;
        background: var(--royal-gold);
        color: var(--royal-navy) !important;
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 900;
        text-decoration: none !important;
        box-shadow: 0 10px 25px rgba(197, 160, 89, 0.4);
        transition: 0.4s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .smart-back-btn:hover {
        transform: translateX(-10px);
        background: white;
    }

    /* شبكة الكريستال للإعدادات */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: -80px auto 50px;
        padding: 0 20px;
    }

    .crystal-card {
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        border: 1px solid white;
        border-radius: 40px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(26, 42, 64, 0.05);
        transition: 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
    }

    .crystal-card::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: 0.5s;
    }

    .crystal-card:hover::before { left: 100%; }

    .crystal-card:hover {
        transform: translateY(-15px);
        border-color: var(--royal-gold);
        box-shadow: 0 30px 60px rgba(197, 160, 89, 0.15);
    }

    .icon-sphere {
        width: 70px; height: 70px;
        background: var(--royal-navy);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        color: var(--royal-gold);
        font-size: 1.8rem;
        transform: rotate(-10deg);
        transition: 0.4s;
    }

    .crystal-card:hover .icon-sphere {
        transform: rotate(0deg) scale(1.1);
        background: var(--royal-gold);
        color: white;
    }

    .form-wrapper {
        margin-top: 20px;
        opacity: 0.9;
    }

    /* تنسيق زر الحذف الخاص */
    .danger-zone {
        border: 2px dashed rgba(239, 68, 68, 0.2);
    }

    .danger-zone .icon-sphere {
        background: #fee2e2;
        color: #ef4444;
    }
     /* --- تنسيق الأزرار (التركيز الأساسي) --- */
    .crystal-card button, 
    .crystal-card [type="submit"] {
        width: 100%;
        background-color: var(--royal-navy) !important;
        color: var(--royal-gold) !important;
        border: 2px solid var(--royal-gold) !important;
        padding: 14px !important;
        border-radius: 15px !important;
        font-weight: 900 !important;
        font-size: 16px !important;
        cursor: pointer !important;
        transition: 0.3s !important;
        margin-top: 20px;
    }

    .crystal-card button:hover {
        background-color: var(--royal-gold) !important;
        color: var(--royal-navy) !important;
    }

    /* زر الحذف الأحمر */
    .danger-zone button {
        background-color: #ef4444 !important;
        color: white !important;
        border: none !important;
    }
    .danger-zone button:hover {
        background-color: #dc2626 !important;
        color: white !important;
    }

    /* زر العودة */
    .smart-back-btn {
        position: fixed; top: 30px; right: 30px; z-index: 1000;
        background: var(--royal-gold); color: var(--royal-navy) !important;
        padding: 12px 25px; border-radius: 50px; font-weight: 900;
        text-decoration: none !important; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        display: flex; align-items: center; gap: 10px;
    }
</style>
<div dir="rtl">
    @php
        $backUrl = url('/');
        $role = trim(strtolower(auth()->user()->role));
        if ($role === 'admin') { $backUrl = route('admin.dashboard'); }
        elseif ($role === 'supervisor') { $backUrl = route('supervisor.super'); }
        elseif ($role === 'student') { $backUrl = route('student.project.index'); }
    @endphp

    <a href="{{ $backUrl }}" class="smart-back-btn">
        <i class="fas fa-chevron-right"></i>
        <span>العودة للوحة التحكم</span>
    </a>

    <header class="system-header">
        <div class="logo-container">
            <div class="system-logo">GP<span>MS</span></div>
            <div style="color: var(--royal-gold); font-size: 12px; font-weight: bold; letter-spacing: 3px; margin-top: -10px; text-transform: uppercase;">الإعدادات المركزية</div>
        </div>
        <h1 style="color: white; font-weight: 900; font-size: 2.5rem; margin-top: 15px;">إدارة الهوية الرقمية</h1>
    </header>

    <div class="settings-grid">
        
        <!-- كرت المعلومات الشخصية -->
        <div class="crystal-card animate__animated animate__fadeInUp">
            <div class="icon-sphere">
                <i class="fas fa-id-badge"></i>
            </div>
            <h3 style="color: var(--royal-navy); font-weight: 900; font-size: 1.4rem;">المعلومات الشخصية</h3>
            <p style="color: #64748b; font-size: 0.9rem;">تحديث بيانات الحساب والبريد الإلكتروني الرسمي</p>
            <div class="form-wrapper">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- كرت الأمن -->
        <div class="crystal-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="icon-sphere">
                <i class="fas fa-key"></i>
            </div>
            <h3 style="color: var(--royal-navy); font-weight: 900; font-size: 1.4rem;">بوابة الأمان</h3>
            <p style="color: #64748b; font-size: 0.9rem;">إدارة كلمات المرور وتأمين الدخول للمنصة</p>
            <div class="form-wrapper">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- كرت منطقة الخطر -->
        <div class="crystal-card danger-zone animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="icon-sphere">
                <i class="fas fa-user-slash"></i>
            </div>
            <h3 style="color: #ef4444; font-weight: 900; font-size: 1.4rem;">منطقة الخطر</h3>
            <p style="color: #64748b; font-size: 0.9rem;">حذف الحساب نهائياً من قاعدة بيانات النظام</p>
            <div class="form-wrapper">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
<script>
    // سكريبت لإضافة حركة "تتبع الماوس" للكريستالات
    document.querySelectorAll('.crystal-card').forEach(card => {
        card.onmousemove = function(e) {
            let x = e.pageX - card.offsetLeft;
            let y = e.pageY - card.offsetTop;
            card.style.setProperty('--x', x + 'px');
            card.style.setProperty('--y', y + 'px');
        };
    });
</script>
</x-app-layout>
