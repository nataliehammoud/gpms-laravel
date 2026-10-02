<x-app-layout>
    <style>
        /* ====== الهوية البصرية ====== */
        :root {
            --navy: #0F2B3D;
            --navy-light: #1A3A4F;
            --gold: #D4AF37;
            --gold-dark: #B8960F;
            --cream: #F9F6F0;
            --white: #FFFFFF;
            --text-dark: #1E2A3A;
            --text-soft: #5A6E85;
            --success: #2D6A4F;
            --warning: #E8A735;
            --error: #C62828;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { background: var(--cream) !important; font-family: 'Cairo', sans-serif; }

        /* ====== سايد بار عائم ====== */
        .floating-sidebar {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 280px;
            background: rgba(15, 43, 61, 0.98);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            padding: 24px 16px;
            z-index: 1000;
            box-shadow: 0 25px 45px -12px rgba(0,0,0,0.25);
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        .floating-sidebar:hover {
            transform: translateX(-8px);
            box-shadow: 0 30px 50px -15px rgba(0,0,0,0.3);
        }

        /* هوية النظام في السايد بار */
        .sidebar-brand {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        }

        .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            transition: transform 0.3s;
        }

        .sidebar-brand:hover .brand-icon { transform: rotate(5deg) scale(1.02); }

        .brand-icon i { font-size: 28px; color: var(--navy); }

        .sidebar-brand h3 { color: white; font-size: 20px; margin: 0; letter-spacing: -0.5px; }
        .sidebar-brand p { color: rgba(212, 175, 55, 0.7); font-size: 10px; margin: 4px 0 0; letter-spacing: 1px; }

        /* عناصر القائمة */
        .sidebar-nav { display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 20px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.25s;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link i { width: 24px; font-size: 18px; transition: 0.2s; }

        .nav-link:hover { background: rgba(212, 175, 55, 0.12); color: var(--gold); transform: translateX(-5px); }
        .nav-link:hover i { transform: scale(1.1); }

        /* زر العودة المميز */
        .nav-back {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.25);
            color: var(--gold);
            margin-top: 8px;
        }

        .nav-back:hover { background: var(--gold); color: var(--navy); }
        .nav-back:hover i { color: var(--navy); }

        /* زر التحليل */
        .nav-analyze { background: rgba(212, 175, 55, 0.08); }

        /* زر تسجيل الخروج */
        .nav-logout {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: 16px;
            padding-top: 16px;
        }

        /* ====== المحتوى الرئيسي ====== */
        .main-content { margin-right: 320px; padding: 32px; min-height: 100vh; }
        .analysis-container { max-width: 1000px; margin: 0 auto; }

        /* كارد المشروع */
        .project-card {
            background: var(--white);
            border-radius: 32px;
            padding: 32px;
            margin-bottom: 28px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            border: 1px solid #EAE3D8;
        }

        .project-title { font-size: 28px; font-weight: 800; color: var(--navy); margin-bottom: 16px; }

        /* لوحة الإحصائيات */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 24px;
            padding: 24px;
            text-align: center;
            border: 1px solid #EAE3D8;
            transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06); }

        .stat-value { font-size: 42px; font-weight: 800; color: var(--gold); line-height: 1; }
        .stat-label { font-size: 13px; color: var(--text-soft); margin-top: 8px; }

        /* شريط التقدم */
        .progress-section {
            background: var(--white);
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 28px;
            border: 1px solid #EAE3D8;
        }

        .progress-bar-bg {
            background: #EDE8E0;
            border-radius: 30px;
            height: 10px;
            overflow: hidden;
        }
        .progress-fill {
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-dark) 100%);
            border-radius: 30px;
            height: 100%;
            width: 0%;
            transition: width 0.8s ease;
        }

        /* توصية */
        .recommendation {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(15, 43, 61, 0.02) 100%);
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 28px;
            border-right: 4px solid var(--gold);
        }

        /* أزرار */
        .action-buttons { display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap; }
        .btn-action { padding: 10px 24px; border-radius: 40px; font-weight: 500; cursor: pointer; transition: 0.2s; border: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-notify { background: rgba(212, 175, 55, 0.12); color: var(--gold-dark); }
        .btn-notify:hover { background: var(--gold-dark); color: white; transform: translateY(-2px); }

        @media (max-width: 768px) {
            .floating-sidebar { width: 260px; }
            .main-content { margin-right: 0; padding: 20px; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div style="min-height: 100vh; background: var(--cream);">
    <!-- ====== سايد بار عائم ====== -->
    <aside class="floating-sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fas fa-brain"></i>
            </div>
            <h3>NEURAID</h3>
            <p>ACADEMIC INTELLIGENCE</p>
        </div>

        <div class="sidebar-nav">
            <!-- 1. زر العودة للوحة المشرف -->
            <a href="{{ route('supervisor.super') }}" class="nav-link nav-back">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للوحة المشرف</span>
            </a>

            <!-- 2. زر إرسال تنبيه للطالب (يظهر فقط إذا النسبة أقل من 60) -->
            @if($readinessPercentage < 60)
           <button onclick="showInnovativeNotify({{ $project->id }})" class="btn-action btn-notify">
    <i class="fas fa-bell"></i> إرسال تنبيه للطالب
</button>
            @endif

            <!-- 3. زر طباعة التقرير -->
            <button onclick="window.print()" class="nav-link" style="width:100%; text-align:right; background:none;">
                <i class="fas fa-print"></i>
                <span>طباعة التقرير</span>
            </button>
        </div>

        <div class="sidebar-nav nav-logout">

            <!-- 4. زر تسجيل الخروج -->
            <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="nav-link" style="width:100%; text-align:right; background:none;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ====== المحتوى الرئيسي ====== -->
    <main class="main-content">
        <div class="analysis-container">

            <!-- كارد المشروع -->
            <div class="project-card">
                <div class="project-title">
                    <i class="fas fa-file-alt" style="color: var(--gold); margin-left: 12px;"></i>
                    {{ $project->title }}
                </div>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid #EAE3D8;">
                    <span><i class="fas fa-user-tie"></i> المشرف: د. {{ $project->supervisor->name ?? 'غير محدد' }}</span>
                    <span><i class="fas fa-users"></i> فريق العمل: {{ $project->users->pluck('name')->implode(', ') ?: 'غير مكتمل' }}</span>
                </div>
            </div>

            <!-- لوحة الإحصائيات الذكية -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $readinessPercentage }}<span style="font-size: 20px;">%</span></div>
                    <div class="stat-label">مؤشر الجاهزية العصبي</div>
                    <div style="margin-top: 8px;"><i class="fas fa-chart-simple" style="color: var(--gold);"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        @if($readinessPercentage > 75) A+
                        @elseif($readinessPercentage > 45) B
                        @else C @endif
                    </div>
                    <div class="stat-label">تصنيف الجودة الأكاديمية</div>
                    <div style="margin-top: 8px;"><i class="fas fa-star" style="color: var(--gold);"></i></div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ round($confidence * 100) }}%</div>
<div class="stat-label">ثقة النموذج العصبي</div>
                    <div style="margin-top: 8px;"><i class="fas fa-microchip" style="color: var(--gold);"></i></div>
                </div>
            </div>

            <!-- شريط التقدم -->
            <div class="progress-section">
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <span><i class="fas fa-waveform"></i> مقياس الجاهزية العصبية</span>
                    <span style="color: var(--gold); font-weight: bold;">{{ $readinessPercentage }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-fill" style="width: {{ $readinessPercentage }}%;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 11px; color: var(--text-soft);">
                    <span>ضعيف</span>
                    <span>مقبول</span>
                    <span>ممتاز</span>
                </div>
            </div>

            <!-- التوصية الذكية -->
            <div class="recommendation">
                <div style="display: flex; gap: 16px; align-items: flex-start;">
                    <div style="background: var(--gold); width: 48px; height: 48px; border-radius: 24px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-robot" style="color: var(--navy); font-size: 24px;"></i>
                    </div>
                    <div>
                        <strong style="color: var(--navy);"> توصية النظام العصبي</strong>
                        <p style="margin: 8px 0 0; line-height: 1.7;">
                            @if($readinessPercentage > 75)
                                🟢 <strong>جودة ممتازة</strong> — المشروع يلبي أعلى معايير الجودة الأكاديمية وفقاً لتحليل الشبكة العصبونية.
                            @elseif($readinessPercentage > 45)
                                🟡 <strong>جودة مقبولة</strong> — المشروع جيد لكن يحتاج إضافة تفاصيل تقنية إضافية للوصول للتميز.
                            @else
                                🔴 <strong>جودة غير كافية</strong> — المشروع لا يفي بالحد الأدنى من المتطلبات الأكاديمية حالياً.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- أزرار القبول والرفض (في المحتوى الرئيسي) -->
            <div class="action-buttons">
                @if($project->status == 'pending')
                <form action="{{ route('project.updateStatus', $project->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn-action" style="background: rgba(45, 106, 79, 0.1); color: #2D6A4F;">
                        <i class="fas fa-check-circle"></i> قبول المشروع
                    </button>
                </form>
                <form action="{{ route('project.updateStatus', $project->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn-action" style="background: rgba(198, 40, 40, 0.1); color: #C62828;">
                        <i class="fas fa-times-circle"></i> رفض المشروع
                    </button>
                </form>
                @endif
            </div>

            @if($project->status == 'approved')
            <div style="margin-top: 20px; background: #E8F5E9; border-radius: 20px; padding: 12px; text-align: center;">
                <i class="fas fa-stamp" style="color: #2D6A4F;"></i> هذا المشروع معتمد ومعفى من المراجعة الإضافية
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    // نافذة منبثقة مبتكرة
    function showSmartModal(title, message, type) {
        const oldModal = document.querySelector('.smart-modal-overlay');
        if(oldModal) oldModal.remove();

        const overlay = document.createElement('div');
        overlay.className = 'smart-modal-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 43, 61, 0.85);
            backdrop-filter: blur(6px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeInModal 0.2s ease;
        `;

        let iconColor = type === 'success' ? '#2D6A4F' : (type === 'error' ? '#C62828' : '#D4AF37');
        let iconName = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-times-circle' : 'fa-bell');

        overlay.innerHTML = `
            <div style="background: white; border-radius: 32px; width: 420px; max-width: 90%; overflow: hidden; animation: scaleInModal 0.3s ease; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div style="background: linear-gradient(135deg, #0F2B3D 0%, #1A3A4F 100%); padding: 28px; text-align: center;">
                    <div style="background: ${iconColor}; width: 64px; height: 64px; border-radius: 32px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas ${iconName}" style="color: white; font-size: 32px;"></i>
                    </div>
                    <h3 style="color: white; margin-top: 16px; margin-bottom: 0;">${title}</h3>
                </div>
                <div style="padding: 32px; text-align: center;">
                    <p style="color: #1A2A3A; line-height: 1.7; margin-bottom: 24px;">${message}</p>
                    <button onclick="this.closest('.smart-modal-overlay').remove()" style="background: linear-gradient(135deg, #D4AF37 0%, #B8960F 100%); border: none; padding: 12px 32px; border-radius: 60px; color: #0F2B3D; font-weight: bold; cursor: pointer; transition: 0.2s;">حسنًا</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        if(!document.querySelector('#modal-keyframes')) {
            const style = document.createElement('style');
            style.id = 'modal-keyframes';
            style.textContent = `
                @keyframes fadeInModal { from { opacity: 0; } to { opacity: 1; } }
                @keyframes scaleInModal { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
            `;
            document.head.appendChild(style);
        }
    }
function showInnovativeNotify(projectId) {
    showSmartModal(
        '📢 تنبيه أكاديمي ذكي',
        'سيتم إرسال إشعار فوري للطالب يتضمن توصيات النظام العصبي لتحسين جودة المشروع. هل تريد المتابعة؟',
        'warning'
    );

    setTimeout(() => {
        // ✅ هنا الكود - عدل الرابط إلى send-notification
        fetch(`/supervisor/send-notification/${projectId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showSmartModal(
                    '✅ تم الإرسال بنجاح',
                    'تم إرسال التنبيه الذكي إلى الطالب. سيظهر الإشعار فوراً في حساب الطالب.',
                    'success'
                );
            } else {
                showSmartModal(
                    '❌ فشل الإرسال',
                    data.error || 'حدث خطأ أثناء إرسال التنبيه، يرجى المحاولة مرة أخرى.',
                    'error'
                );
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            showSmartModal(
                '❌ خطأ في الاتصال',
                'تعذر الاتصال بالخادم: ' + error.message,
                'error'
            );
        });
    }, 500);
}
</script>
</x-app-layout>
