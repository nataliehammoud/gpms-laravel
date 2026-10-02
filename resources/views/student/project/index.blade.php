<x-app-layout>
    <style>
        @font-face {
            font-family: 'Font Awesome 6 Free';
            src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2');
            font-weight: 900;
        }
        .fa-solid, .fas { font-family: 'Font Awesome 6 Free' !important; font-weight: 900 !important; }

        :root {
            --navy: #0f172a;
            --gold: #c5a059;
            --gold-light: #d4b47a;
            --cream: #fdfbf7;
            --soft-gold: rgba(197, 160, 89, 0.1);
            --shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        body {
            background: linear-gradient(145deg, var(--cream) 0%, #f0ece4 100%);
            font-family: 'Cairo', sans-serif;
        }

        /* خلفية متحركة */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.1;
            animation: floatShape 20s ease-in-out infinite;
        }
        .bg-shape-1 { width: 300px; height: 300px; background: var(--gold); top: 10%; right: -50px; }
        .bg-shape-2 { width: 200px; height: 200px; background: var(--navy); bottom: 20%; left: 10%; animation-delay: 5s; }
        @keyframes floatShape {
            0%,100% { transform: translate(0,0); }
            50% { transform: translate(30px,-30px); }
        }

        /* تخطيط */
        .page-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            min-height: 100vh;
        }

        /* سايد بار */
        .sidebar-wrapper { padding: 25px 15px 25px 0; position: sticky; top: 0; height: 100vh; }
        .sidebar-inner {
            background: linear-gradient(180deg, #1a2738 0%, #0f172a 100%);
            height: 100%; border-radius: 48px;
            display: flex; flex-direction: column;
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border: 1px solid rgba(197, 160, 89, 0.15);
        }
        .sidebar-logo {
            padding: 35px 20px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .logo-sphere {
            width: 75px; height: 75px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
            animation: rotateSphere 6s infinite ease;
        }
        @keyframes rotateSphere {
            0% { transform: rotate(0deg); border-radius: 50%; }
            50% { transform: rotate(180deg); border-radius: 35% 65% 65% 35%; }
            100% { transform: rotate(360deg); border-radius: 50%; }
        }
        .logo-sphere i { font-size: 32px; color: var(--navy); }
        .logo-text h3 { color: white; font-size: 1.3rem; font-weight: 800; margin: 0; }
        .logo-text p { color: var(--gold); font-size: 0.6rem; letter-spacing: 3px; }

        .nav-menu { padding: 25px 15px; flex: 1; }
        .nav-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px; color: #94a3b8; text-decoration: none;
            border-radius: 20px; margin-bottom: 8px; transition: 0.3s;
            border: 1px solid transparent;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(197, 160, 89, 0.1);
            color: var(--gold);
            transform: translateX(-5px);
            border-color: rgba(197, 160, 89, 0.2);
        }

        .settings-nav { padding: 20px; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-settings {
            display: flex; align-items: center; gap: 12px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
            color: var(--navy); padding: 14px 18px; border-radius: 20px;
            font-weight: 700; text-decoration: none; transition: 0.3s;
        }
        .btn-settings:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(197,160,89,0.3); }

        /* المحتوى الرئيسي */
        .main-content { padding: 30px 40px 50px 30px; }

        /* الهيدر */
        .hero-header {
            background: linear-gradient(135deg, var(--navy) 0%, #1e293b 100%);
            border-radius: 32px;
            padding: 30px 35px;
            margin-bottom: 35px;
            position: relative;
            overflow: hidden;
        }
        .hero-header::after {
            content: '\f19d';
            font-family: 'Font Awesome 6 Free';
            position: absolute;
            bottom: -30px;
            right: -20px;
            font-size: 100px;
            opacity: 0.05;
            color: white;
        }
        .hero-header h1 { color: white; font-size: 1.8rem; font-weight: 800; margin: 0; }
        .hero-header p { color: var(--gold); font-size: 0.75rem; letter-spacing: 2px; margin-top: 5px; }

        /* كرت المشروع */
        .project-card {
            background: white;
            border-radius: 32px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: all 0.35s;
            border: 1px solid rgba(197, 160, 89, 0.12);
            overflow: hidden;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            border-color: rgba(197, 160, 89, 0.25);
        }
        .card-inner { padding: 28px 32px; display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; align-items: center; }
        .card-info { flex: 2; min-width: 250px; }

        .status-chip {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px; border-radius: 50px;
            font-size: 0.7rem; font-weight: 700; margin-bottom: 15px;
        }
        .status-chip.pending { background: #fef3c7; color: #d97706; }
        .status-chip.approved { background: #d1fae5; color: #059669; }
        .status-chip.rejected { background: #fee2e2; color: #dc2626; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

        .project-title { font-size: 1.5rem; font-weight: 800; color: var(--navy); margin: 0 0 8px 0; }

        .team-medal {
            display: inline-flex; align-items: center; gap: 12px;
            background: var(--soft-gold); padding: 8px 18px; border-radius: 50px;
            margin: 15px 0; border: 1px solid rgba(197, 160, 89, 0.2);
        }
        .team-medal i { color: var(--gold); }

        .actions-strip {
            display: flex; flex-wrap: wrap; gap: 12px;
            margin-top: 20px; padding-top: 20px;
            border-top: 1px dashed #e5dfd5;
        }
        .action-item {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 18px; border-radius: 40px;
            background: #f8f6f2; color: #4a5568;
            text-decoration: none; font-size: 0.8rem; font-weight: 500;
            transition: 0.25s; cursor: pointer; border: none;
        }
        .action-item:hover { background: var(--gold); color: white; transform: translateY(-2px); }

        .badge-notif {
            background: #ef4444; color: white;
            font-size: 9px; min-width: 18px; height: 18px;
            border-radius: 9px; display: inline-flex; align-items: center; justify-content: center;
            margin-left: 5px;
        }

        .card-actions { min-width: 240px; text-align: left; }
        .btn-primary-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            color: var(--navy); font-weight: 700; padding: 14px 20px;
            border-radius: 28px; border: none; width: 100%;
            cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-primary-gold:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(197,160,89,0.3); }

        .file-link {
            display: inline-flex; align-items: center; gap: 8px;
            background: #f1efea; padding: 10px 18px; border-radius: 50px;
            text-decoration: none; color: #4a5568; font-size: 0.75rem;
            margin-top: 12px; transition: 0.25s;
        }
        .file-link:hover { background: var(--navy); color: white; }

        /* أزرار عائمة */
        /* أزرار عائمة */
.floating-actions {
    position: fixed;
    left: 25px;
    bottom: 25px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.float-circle {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    position: relative;
}

/* ألوان الأزرار */
.float-ai {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
}
.float-ai i { color: var(--navy); }

.float-notif {
    background: linear-gradient(135deg, var(--navy) 0%, #1e293b 100%);
}
.float-notif i { color: var(--gold); }

.float-wa {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
}
.float-wa i { color: white; }

/* تأثير الطفو */
.float-circle {
    animation: floatUp 3s ease-in-out infinite;
}
.float-circle:nth-child(2) { animation-delay: 0.5s; }
.float-circle:nth-child(3) { animation-delay: 1s; }

@keyframes floatUp {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

.float-circle:hover {
    transform: scale(1.1);
    animation: none;
}

/* Tooltip */
.tooltip-text {
    position: absolute;
    left: 65px;
    background: var(--navy);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: 0.2s;
}

.float-circle:hover .tooltip-text {
    opacity: 1;
    visibility: visible;
}

.badge-float {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    font-size: 10px;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
      /* مودالات */
.custom-modal {
    position: fixed;
    bottom: 100px;
    left: 100px;
    width: 400px;
    max-height: 80vh;                /* أقصى ارتفاع 80% من ارتفاع الشاشة */
    background: white;
    border-radius: 32px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    z-index: 1001;
    display: none;
    animation: slideUpModal 0.3s ease;
    flex-direction: column;          /* لتقسيم الرأس والمحتوى */
    overflow: hidden;                /* منع التمرير على الحاوية الخارجية */
}

.modal-head {
    background: var(--navy);
    color: white;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;                  /* يمنع انكماش الرأس */
    position: sticky;                /* يبقى الرأس ثابتاً عند التمرير */
    top: 0;
    z-index: 10;
}

/* الحاوية القابلة للتمرير (المحتوى) */
.modal-body-scroll {
    flex: 1;
    overflow-y: auto;                /* يسمح بالتمرير العمودي */
    padding: 20px;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    line-height: 1;
}

/* للشاشات الصغيرة */
@media (max-width: 992px) {
    .custom-modal {
        left: 20px;
        right: 20px;
        width: auto;
        bottom: 20px;
        max-height: 85vh;
    }
}

    </style>
<div class="animated-bg">
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
</div>

<div class="page-grid" dir="rtl">
    <!-- السايد بار -->
    <aside class="sidebar-wrapper">
        <div class="sidebar-inner">
            <div class="sidebar-logo">
                <div class="logo-sphere"><i class="fa-solid fa-brain"></i></div>
                <div class="logo-text"><h3>GPMS</h3><p>ACADEMIC INTELLIGENCE</p></div>
            </div>
            <nav class="nav-menu">
                <a href="{{ url('/') }}" class="nav-item"><i class="fa-solid fa-house"></i> <span>الرئيسية</span></a>
                <a href="{{ route('student.project.index') }}" class="nav-item active"><i class="fa-solid fa-diagram-project"></i> <span>مشروع التخرج</span></a>
                <a href="{{ route('archive.index') }}" class="nav-item"><i class="fa-solid fa-archive"></i> <span>أرشيف المشاريع</span></a>
            </nav>
            <div class="settings-nav">
                <a href="{{ route('profile.edit') }}" class="btn-settings">
                    <i class="fa-solid fa-user-gear"></i> <span>إعدادات الحساب</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="nav-item w-100" style="justify-content: flex-start; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 16px; transition: all 0.3s; font-weight: 600; border: 1px solid rgba(239, 68, 68, 0.2);">
                        <i class="fa-solid fa-power-off"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- المحتوى الرئيسي -->
    <main class="main-content">
        <div class="hero-header">
            <h1>إدارة المشاريع <span style="color: var(--gold);">الأكاديمية</span></h1>
            <p>ACADEMIC INNOVATION MANAGEMENT SYSTEM</p>
        </div>

        @if(session('success'))
            <div style="background: #d1fae5; border-radius: 20px; padding: 15px 20px; margin-bottom: 25px; border-right: 4px solid #059669;">
                <i class="fa-solid fa-circle-check text-success me-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- قائمة المشاريع -->
        @if($projects->isEmpty())
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open" style="color:var(--gold); opacity:0.3; font-size:4rem;"></i>
                <p class="mt-3 text-secondary">لا يوجد مشاريع مسجلة حالياً.</p>
                <a href="{{ route('student.project.create') }}" class="btn btn-gold mt-3">إضافة مشروع جديد</a>
            </div>
        @else
            @foreach($projects as $project)
          <div class="project-card">
    <div class="card-inner">
        <div class="card-info">
            <div class="status-chip {{ $project->status == 'pending' ? 'pending' : ($project->status == 'approved' ? 'approved' : 'rejected') }}">
                <span class="status-dot"></span>
                @if($project->status == 'pending') قيد المراجعة
                @elseif($project->status == 'approved') مقبول
                @else مرفوض @endif
            </div>

            <h3 class="project-title">{{ $project->title }}</h3>

            {{-- فريق العمل --}}
            <div class="team-medal">
                <i class="fa-solid fa-medal"></i>
                <span>فريق العمل:</span>
                <strong>{{ $project->student->user->name ?? 'غير محدد' }}</strong>
                @foreach($project->users as $member)
                    @if($member->id != $project->student->user_id)
                        <span>| {{ $member->name }}</span>
                    @endif
                @endforeach
            </div>

            {{-- المشرف --}}
            <div class="team-medal mt-2">
                <i class="fa-solid fa-user-tie"></i>
                <span>المشرف:</span>

                @if($project->is_distributed && $project->supervisor_id && $project->supervisor)
                    <strong style="color: #10b981;">
                        <i class="fa-solid fa-check-circle"></i>
                        د. {{ $project->supervisor->name }}
                    </strong>
                    <span class="badge bg-success text-white px-2 py-1 rounded-pill ms-2">تم الاعتماد</span>
                @else
                    @php
                        $pref = \App\Models\ProjectPreference::where('student_id', $project->student_id)
                            ->where('rank', 1)
                            ->first();
                    @endphp
                    @if($pref && $pref->supervisor_id)
                        @php
                            $supervisorName = \App\Models\User::find($pref->supervisor_id)?->name ?? 'غير محدد';
                        @endphp
                        <strong style="color: #f59e0b;">
                            <i class="fa-solid fa-clock"></i>
                            د. {{ $supervisorName }}
                        </strong>
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill ms-2">قيد التعيين</span>
                    @else
                        <strong style="color: #f59e0b;">
                            <i class="fa-solid fa-clock"></i>
                            لم يتم التعيين بعد
                        </strong>
                    @endif
                @endif
            </div>

            {{-- الأزرار --}}
            <div class="actions-strip">
                <a href="{{ route('student.project.notes', $project->id) }}" class="action-item">
                    <i class="fa-solid fa-comment-dots"></i> الملاحظات
                    @if($project->has_notes)<span class="badge-notif">!</span>@endif
                </a>

                <a href="{{ route('announcements.index', $project->id) }}" class="action-item">
                    <i class="fa-solid fa-bullhorn"></i> الإعلانات
                    @if($project->has_unread_announcements)<span class="badge-notif">!</span>@endif
                </a>

                <button type="button" class="action-item" data-bs-toggle="modal" data-bs-target="#viewGrade{{ $project->id }}">
                    <i class="fa-solid fa-graduation-cap"></i> التقييم
                    @if($project->has_new_grade)<span class="badge-notif">✓</span>@endif
                </button>

                @if($project->status === 'approved')
                    <a href="{{ route('student.stages.index', $project->id) }}" class="action-item">
                        <i class="fa-solid fa-list-check"></i> مراحل المشروع
                    </a>
                @endif

                @if($project->status === 'pending' || $project->status === 'rejected')
                    <a href="{{ route('student.project.edit', $project->id) }}" class="action-item">
                        <i class="fa-solid fa-pen-fancy"></i> تعديل
                    </a>
                    <form action="{{ route('student.project.destroy', $project->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-item" onclick="return confirm('حذف المشروع؟')">
                            <i class="fa-solid fa-trash-can text-danger"></i> حذف
                        </button>
                    </form>
                @endif

                @if($project->files->first())
                    <a href="{{ asset('storage/' . $project->files->first()->file_path) }}" target="_blank" class="action-item">
                        <i class="fa-solid fa-file-pdf text-danger"></i> ملف المشروع
                    </a>
                @endif
            </div>
        </div>

        {{-- ❌ تم حذف pending-message و rejected-message نهائياً --}}
    </div>
</div>
            <!-- مودال التقييم -->
            <div class="modal fade" id="viewGrade{{ $project->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="bg-navy p-4 text-center" style="background:var(--navy); border-radius:20px 20px 0 0;">
                            <div style="width:55px; height:55px; background:var(--gold); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                                <i class="fa-solid fa-star text-navy fa-lg"></i>
                            </div>
                            <h5 class="text-white font-black mb-0">سجل التقييم النهائي</h5>
                        </div>
                        <div class="modal-body p-4 text-center bg-cream">
                            @php $evaluation = DB::table('evaluations')->where('project_id', $project->id)->first(); @endphp
                            @if($evaluation)
                                <div class="mb-3">
                                    <span class="text-secondary small">نتيجة المشروع</span>
                                    <h1 class="fw-bold text-navy" style="font-size:3rem;">{{ $evaluation->grade }}<span class="h4 text-gold">/100</span></h1>
                                </div>
                                <div class="p-3 bg-white rounded-3 border-end border-3 border-gold">
                                    <p class="text-navy fw-bold mb-1 small">ملاحظات اللجنة:</p>
                                    <p class="text-secondary small mb-0">{{ $evaluation->feedback ?? 'لا ملاحظات إضافية.' }}</p>
                                </div>
                            @else
                                <div class="py-4">
                                    <i class="fa-solid fa-hourglass-start text-gold opacity-30 fa-3x mb-2"></i>
                                    <p class="text-secondary">النتيجة لم تُرصد بعد.</p>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 bg-white text-center">
                            <button type="button" class="btn btn-navy gold-text px-4 rounded-pill" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
        <!-- الأزرار العائمة -->
<div class="floating-actions">
    {{-- زر واتساب --}}
    @if(isset($project) && $project->supervisor_id && $project->supervisor && $project->supervisor->phone)
    <div class="float-circle float-wa" id="whatsappBtn">
        <i class="fab fa-whatsapp"></i>
        <div class="tooltip-text">تواصل مع المشرف</div>
    </div>
    @endif

    {{-- زر المساعد الذكي --}}
    <div class="float-circle float-ai" id="aiAssistantBtn">
        <i class="fa-solid fa-microchip"></i>
        <div class="tooltip-text">مساعد الابتكار الذكي</div>
    </div>

    {{-- زر الإشعارات --}}
    <div class="float-circle float-notif" id="notificationFloatBtn">
        <i class="fa-solid fa-bell"></i>
        <span class="badge-float" id="floatNotificationCount">0</span>
        <div class="tooltip-text">الإشعارات</div>
    </div>
</div>

<!-- مودال المساعد الذكي -->
<div class="custom-modal" id="aiAssistantModal">
    <div class="modal-head">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-brain"></i>
            <h5 class="mb-0">مساعد الابتكار الذكي</h5>
        </div>
        <button class="modal-close" id="aiModalClose">&times;</button>
    </div>
    <div class="modal-body-scroll">    <!-- ✅ الحاوية القابلة للتمرير -->
        <form id="aiForm">
            @csrf
            <input type="text" name="title" class="form-control" style="width:100%; padding:12px; border-radius:50px; border:1px solid #e5dfd5; background:#f8f6f2; margin-bottom:15px;" placeholder="اكتب فكرة مشروعك هنا..." required>
            <div class="d-flex gap-2">
                <button type="submit" name="action" value="suggest" class="btn-primary-gold" style="padding:12px;"><i class="fa-solid fa-lightbulb"></i> خطة عمل</button>
                <button type="submit" name="action" value="check" class="btn-primary-gold" style="background:transparent; border:2px solid var(--gold); color:var(--gold);"><i class="fa-solid fa-magnifying-glass"></i> فحص تميز</button>
            </div>
        </form>
        <div id="aiResult" class="mt-3"></div>
    </div>
</div>

<!-- مودال الإشعارات -->
<div class="custom-modal" id="notificationFloatModal">
    <div class="modal-head">
        <div class="d-flex gap-2">
            <i class="fa-solid fa-bell"></i>
            <h5 class="mb-0">الإشعارات</h5>
        </div>
        <button class="modal-close" id="floatModalClose">&times;</button>
    </div>
    <div style="max-height: 400px; overflow-y: auto;">
        <div id="floatNotificationList" class="p-2"><div class="text-center p-4">جاري التحميل...</div></div>
    </div>
    <div class="p-3 text-center border-top">
        <button onclick="markAllNotificationsReadFloat()" style="background:none; border:none; color:var(--gold); font-weight:bold;">تحديد الكل كمقروء</button>
    </div>
</div>
    </main>
</div>

<script>
    // فتح وإغلاق المودال
    const aiBtn = document.getElementById('aiAssistantBtn');
    const aiModal = document.getElementById('aiAssistantModal');
    const aiClose = document.getElementById('aiModalClose');
    if (aiBtn && aiModal) {
        aiBtn.onclick = () => aiModal.style.display = 'flex';
        if (aiClose) aiClose.onclick = () => aiModal.style.display = 'none';
        window.onclick = (e) => { if (e.target === aiModal) aiModal.style.display = 'none'; };
    }

    // تحديد الزر الذي تم الضغط عليه
    document.querySelectorAll('#aiForm button[type="submit"]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#aiForm button[type="submit"]').forEach(b => b.removeAttribute('clicked'));
            this.setAttribute('clicked', 'true');
        });
    });

    // معالجة إرسال النموذج
    document.getElementById('aiForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const title = this.querySelector('input[name="title"]').value;
        const action = this.querySelector('button[clicked="true"]')?.value || 'suggest';
        const resultDiv = document.getElementById('aiResult');
        if (!title.trim()) {
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '<div class="alert alert-danger">⚠️ الرجاء إدخال فكرة المشروع</div>';
            return;
        }
        resultDiv.style.display = 'block';
        resultDiv.innerHTML = '<div class="text-center p-3"><i class="fa-solid fa-spinner fa-spin"></i> جاري التحليل...</div>';

        let finalHtml = '';
        // 1. جلب نتيجة التحليل الأساسية
        try {
            const res = await fetch('{{ route("student.ai.suggest") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ title, action })
            });
            const data = await res.json();
            finalHtml = data.success
                ? `<div class="p-3 rounded-4" style="background:#FFF8E7; border-right:4px solid var(--gold);"><i class="fa-solid fa-robot"></i> <strong>نتيجة التحليل:</strong><p style="white-space:pre-line; margin-top:10px;">${data.result}</p></div>`
                : `<div class="alert alert-danger">❌ ${data.error || 'حدث خطأ'}</div>`;
        } catch (err) {
            finalHtml = '<div class="alert alert-danger">❌ خطأ في الاتصال</div>';
        }

        // 2. إضافة النصائح الذكية (بشكل مستقل تمامًا)
        try {
            const recRes = await fetch('/ai/recommendations', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json' },
                body: JSON.stringify({ title })
            });
            const recData = await recRes.json();
            if (recData.recommendations && recData.recommendations.length) {
                finalHtml += `<div class="mt-3 p-3 rounded-4" style="background:#FFF3E0; border-right:4px solid #f59e0b;">
                                <i class="fas fa-lightbulb"></i> <strong>نصائح ذكية لتحسين فكرتك:</strong>
                                <ul style="margin-top:8px;">${recData.recommendations.map(tip => `<li>${tip}</li>`).join('')}</ul>
                            </div>`;
            }
        } catch (err) { console.log('نصائح غير متاحة'); }

        resultDiv.innerHTML = finalHtml;
    });

    // ========== الإشعارات العائم ==========
    const notifBtn = document.getElementById('notificationFloatBtn');
    const notifModal = document.getElementById('notificationFloatModal');
    const notifClose = document.getElementById('floatModalClose');

    if (notifBtn && notifModal) {
        notifBtn.onclick = () => { notifModal.style.display = 'block'; loadFloatNotifications(); };
        if (notifClose) notifClose.onclick = () => notifModal.style.display = 'none';
        window.onclick = (e) => { if (e.target === notifModal) notifModal.style.display = 'none'; };
    }

    async function loadFloatNotifications() {
        try {
            const res = await fetch('/notifications');
            const data = await res.json();
            const countSpan = document.getElementById('floatNotificationCount');
            const listDiv = document.getElementById('floatNotificationList');
            if (countSpan) {
                countSpan.style.display = data.unread_count > 0 ? 'flex' : 'none';
                if (data.unread_count > 0) countSpan.textContent = data.unread_count;
            }
            if (listDiv) {
                if (data.notifications.length === 0) {
                    listDiv.innerHTML = '<div class="text-center p-4 text-muted">📭 لا توجد إشعارات</div>';
                    return;
                }
                listDiv.innerHTML = data.notifications.map(n => `
                    <div class="d-flex gap-2 p-3 border-bottom ${n.is_read ? '' : 'bg-warning bg-opacity-10'}" onclick="markFloatNotificationRead(${n.id})" style="cursor:pointer;">
                        <div style="width:40px;height:40px;border-radius:50%;background:rgba(212,175,55,0.1);display:flex;align-items:center;justify-content:center;"><i class="fas ${n.icon}"></i></div>
                        <div style="flex:1;"><div class="fw-bold">${n.title}</div><div class="small text-muted">${n.message}</div><div class="small text-muted mt-1">${n.time_ago}</div></div>
                    </div>
                `).join('');
            }
        } catch (e) { console.error(e); }
    }

    async function markFloatNotificationRead(id) {
        try {
            await fetch(`/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            loadFloatNotifications();
        } catch (e) {}
    }

    async function markAllNotificationsReadFloat() {
        try {
            await fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
            loadFloatNotifications();
            const modal = document.getElementById('notificationFloatModal');
            if (modal) modal.style.display = 'none';
        } catch (e) {}
    }

    // ========== زر واتساب ==========
    const waBtn = document.getElementById('whatsappBtn');
    if (waBtn) {
        waBtn.onclick = function() {
            let phone = @json($project->supervisor->phone ?? '');
            phone = phone.replace(/^0+/, '');
            if (phone) {
                const msg = encodeURIComponent('السلام عليكم دكتور، لدي استفسار بخصوص مشروع التخرج.');
                window.open(`https://wa.me/${phone}?text=${msg}`, '_blank');
            } else {
                alert('رقم المشرف غير متوفر حالياً.');
            }
        };
    }
</script>
</x-app-layout>
