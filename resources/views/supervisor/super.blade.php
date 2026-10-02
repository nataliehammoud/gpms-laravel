<x-app-layout>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --cream: #FDF8F0;
    --cream-dark: #F4EFE6;
    --sage: #8BA888;
    --sage-light: #B5CEB2;
    --terracotta: #D4A373;
    --terracotta-light: #E2BC8C;
    --navy-dark: #0a1622;
    --navy-medium: #1a2a4a;
    --navy-light: #2a3a5a;
    --shadow-organic: 0 15px 35px -12px rgba(0,0,0,0.08);
    --shadow-organic-sm: 0 8px 20px -8px rgba(0,0,0,0.05);
}

        body {
            background: linear-gradient(145deg, var(--cream) 0%, var(--cream-dark) 100%);
            font-family: 'Cairo', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* ===== سايد بار عضوي ===== */
       .organic-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #0a1622 0%, #1a2a4a 100%);
    backdrop-filter: blur(12px);
    z-index: 100;
    padding: 35px 25px;
    display: flex;
    flex-direction: column;
    border-radius: 40px 0 0 40px;
    transform: translateX(100%);
    transition: 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    border-left: 1px solid rgba(212, 180, 131, 0.3);
}
        .organic-sidebar.open { transform: translateX(0); }

        /* هامبرغر داخل دائرة */
        .hamburger-circle {
            width: 50px;
            height: 50px;
            background: rgba(212, 180, 131, 0.15);
            border: 2px solid var(--terracotta);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            backdrop-filter: blur(4px);
        }
        .hamburger-circle:hover {
            background: rgba(212, 180, 131, 0.3);
            transform: scale(1.05);
            border-color: var(--terracotta-light);
        }
        .hamburger-menu {
            width: 32px;
            height: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .hamburger-line {
            width: 26px;
            height: 2.5px;
            background: var(--terracotta);
            border-radius: 3px;
            transition: 0.3s;
        }
        .hamburger-circle:hover .hamburger-line { background: var(--terracotta-light); }

        .sidebar-toggle {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 101;
        }

        /* إشارة الإشعارات */
        .sidebar-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            margin: 6px 0;
            border-radius: 30px;
            color: #CBD5E1;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: 0.25s;
        }
        .notification-badge {
            position: absolute;
            left: 8px;
            top: 8px;
            background: #EF4444;
            color: white;
            font-size: 9px;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.1); } }

        .sidebar-item:hover { background: rgba(212, 180, 131, 0.15); color: var(--terracotta-light); transform: translateX(-5px); }
        .sidebar-item.active { background: rgba(212, 180, 131, 0.2); color: var(--terracotta-light); }

        /* ===== المحتوى الرئيسي ===== */
        .organic-content {
            margin-right: 0;
            padding: 30px 40px 50px;
            min-height: 100vh;
        }

        /* الهيدر العضوي - مصغر */
      .wavy-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    border-radius: 60px 60px 60px 30px;
    padding: 20px 30px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}
        .wavy-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(212, 180, 131, 0.12), transparent);
            border-radius: 50%;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 12px;
        }
        .logo-gpms {
            display: flex;
            align-items: baseline;
            gap: 4px;
            background: rgba(255,255,255,0.08);
            padding: 5px 16px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(212, 180, 131, 0.3);
        }
        .logo-gpms span {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .logo-gpms span:first-child { color: var(--terracotta-light); }
        .logo-gpms span:last-child { color: white; }

        .supervisor-icon {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.08);
            padding: 4px 16px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(212, 180, 131, 0.3);
        }
        .supervisor-icon i {
            font-size: 20px;
            color: var(--terracotta-light);
        }
        .supervisor-icon .info {
            text-align: right;
        }
        .supervisor-icon .info h3 {
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
            margin: 0;
        }
        .supervisor-icon .info p {
            color: rgba(255,255,255,0.6);
            font-size: 0.55rem;
            margin: 2px 0 0;
        }

        .header-stats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 0;
        }
        .header-stat {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border-radius: 50px;
            padding: 3px 12px;
            font-size: 11px;
            color: white;
        }
        .header-stat i { color: var(--terracotta); margin-left: 5px; font-size: 10px; }

        .capacity-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
        }
        .capacity-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.06);
            border-radius: 40px;
            padding: 5px 15px;
            border: 1px solid rgba(212, 180, 131, 0.2);
        }
        .capacity-mini-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }
        .capacity-mini-label i { color: var(--terracotta); font-size: 11px; }
        .capacity-mini-value {
            background: rgba(255,255,255,0.1);
            padding: 2px 10px;
            border-radius: 30px;
            font-size: 11px;
            color: var(--terracotta-light);
        }
        .capacity-mini-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .capacity-mini-input {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(212, 180, 131, 0.3);
            border-radius: 30px;
            padding: 4px 8px;
            width: 55px;
            text-align: center;
            color: white;
            font-size: 11px;
        }
        .capacity-mini-input:focus { outline: none; border-color: var(--terracotta); }
        .capacity-mini-btn {
            background: var(--terracotta);
            border: none;
            padding: 3px 12px;
            border-radius: 30px;
            color: var(--navy-soft);
            font-weight: 600;
            font-size: 10px;
            cursor: pointer;
            transition: 0.2s;
        }
        .capacity-mini-btn:hover { background: var(--terracotta-light); }

        /* شبكة المشاريع */
        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 25px;
        }
        .project-leaf {
            background: white;
            border-radius: 32px 32px 32px 16px;
            padding: 22px;
            transition: all 0.35s ease;
            border: 1px solid #EAE3D8;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-organic-sm);
        }
        .project-leaf::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, rgba(139, 168, 136, 0.06), transparent);
            border-radius: 50%;
        }
        .project-leaf:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-organic);
            border-color: var(--terracotta);
        }
        .project-status-leaf {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .status-pending { background: #FEF3C7; color: #D97706; }
        .status-approved { background: #D1FAE5; color: #059669; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; animation: pulse 1.5s infinite; }

        .project-title-leaf {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--navy-soft);
            margin-bottom: 12px;
        }
        .project-team {
            background: #F8F6F2;
            border-radius: 20px;
            padding: 8px 15px;
            margin: 12px 0;
            font-size: 12px;
            color: #5A6E85;
        }
        .project-actions-leaf {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .action-leaf {
            background: #F8F6F2;
            border: none;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 11px;
            cursor: pointer;
            text-decoration: none;
            color: var(--navy-soft);
            transition: 0.2s;
        }
        .action-leaf:hover { background: var(--terracotta); color: white; }

        .decision-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-approve {
            background: #10B981;
            color: white;
            border: none;
            padding: 10px;
            flex: 1;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-reject {
            background: #FEE2E2;
            color: #EF4444;
            border: none;
            width: 45px;
            border-radius: 30px;
            cursor: pointer;
        }

        /* ===== نافذة الإشعارات المنبثقة ===== */
        .notifications-modal {
            position: fixed;
            bottom: 100px;
            left: 30px;
            width: 380px;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
            overflow: hidden;
            animation: slideUp 0.3s ease;
            border: 1px solid var(--terracotta-light);
        }
        .notifications-modal.show { display: block; }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notif-modal-header {
            background: var(--navy-soft);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notif-modal-header h4 { margin: 0; font-size: 1rem; }
        .notif-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
        }
        .mark-all-btn {
            background: none;
            border: none;
            color: var(--terracotta-light);
            font-size: 11px;
            cursor: pointer;
        }
        .notif-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .notif-item {
            display: flex;
            gap: 12px;
            padding: 12px 15px;
            border-bottom: 1px solid #F1F5F9;
            cursor: pointer;
            transition: background 0.2s;
        }
        .notif-item:hover { background: #F8FAFC; }
        .notif-item.unread { background: #FEFCE8; }
        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .notif-icon.success { background: #D1FAE5; color: #10B981; }
        .notif-icon.warning { background: #FEF3C7; color: #F59E0B; }
        .notif-icon.info { background: #DBEAFE; color: #3B82F6; }
        .notif-content { flex: 1; }
        .notif-title { font-weight: bold; font-size: 13px; margin-bottom: 4px; }
        .notif-message { font-size: 11px; color: #64748B; }
        .notif-time { font-size: 10px; color: #94A3B8; margin-top: 4px; }

        /* أزرار عائمة */
        .floating-buttons {
            position: fixed;
            bottom: 25px;
            left: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 99;
        }
        .float-btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            position: relative;
        }
        .float-btn:hover { transform: scale(1.08); }
        .btn-whatsapp { background: #25D366; }
        .btn-whatsapp i { color: white; font-size: 28px; }
        .btn-notification {
            background: var(--terracotta);
            border: 2px solid var(--terracotta-light);
        }
        .btn-notification i { color: white; font-size: 24px; }
        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #EF4444;
            color: white;
            font-size: 10px;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 1.5s infinite;
        }

        @media (max-width: 768px) {
            .organic-content { padding: 20px; }
            .project-grid { grid-template-columns: 1fr; }
            .notifications-modal { width: 320px; left: 20px; bottom: 80px; }
        }
           /* ====== بطاقة طلب إيقاف الاستقبال ====== */
    .stop-request-card {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.03));
        border-radius: 20px;
        padding: 4px;
        margin-top: 15px;
        border: 1px solid rgba(239, 68, 68, 0.2);
        transition: all 0.3s ease;
    }

    .stop-request-card:hover {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
    }

    .stop-request-btn {
        background: none;
        border: none;
        width: 100%;
        padding: 14px 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 18px;
        transition: all 0.25s ease;
    }

    .stop-request-btn i {
        font-size: 22px;
        color: #ef4444;
    }

    .stop-request-btn span {
        font-weight: 700;
        color: #dc2626;
        font-size: 14px;
    }

    .stop-request-btn small {
        font-size: 10px;
        color: #9ca3af;
        display: block;
        margin-top: 2px;
    }

    .stop-request-btn > div {
        text-align: right;
        flex: 1;
    }

    /* حالة قيد المراجعة */
    .stop-request-pending {
        background: rgba(245, 158, 11, 0.1);
        border-radius: 20px;
        padding: 14px 18px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .stop-request-pending i {
        font-size: 20px;
        color: #f59e0b;
    }

    .stop-request-pending span {
        font-weight: 600;
        color: #b45309;
        font-size: 13px;
    }

    /* حالة التوقف المفعل */
    .stop-request-active {
        background: rgba(16, 185, 129, 0.1);
        border-radius: 20px;
        padding: 14px 18px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .stop-request-active i {
        font-size: 20px;
        color: #10b981;
    }

    .stop-request-active span {
        font-weight: 600;
        color: #065f46;
        font-size: 13px;
    }
    </style>

    @php
        $user = auth()->user();
        $supervisor = $user->supervisor;
        $projectsCount = $projects->count();
        $pendingCount = $projects->where('status', 'pending')->count();
        $approvedCount = $projects->where('status', 'approved')->count();

        $unreadAnnouncements = \App\Models\Announcement::count();
        $unreadDiscussions = $projects->where('status', 'pending')->count();

        // الإشعارات للمشرف
        $hasStopRequestNotification = false;
        $stopRequestMessage = '';
        if($supervisor && $supervisor->stop_request_pending) {
            $hasStopRequestNotification = true;
            $stopRequestMessage = '⏳ طلب إيقاف الاستقبال قيد المراجعة';
        } elseif($supervisor && $supervisor->stop_receiving) {
            $hasStopRequestNotification = true;
            $stopRequestMessage = '🛑 تم إيقاف استقبال مشاريع جديدة';
        }
    @endphp

    <!-- زر فتح السايد بار -->
    <div class="sidebar-toggle" id="sidebarToggle">
        <div class="hamburger-circle">
            <div class="hamburger-menu">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
        </div>
    </div>
<!-- سايد بار -->
<div class="organic-sidebar" id="organicSidebar">
    <div class="sidebar-logo" style="text-align: center; margin-bottom: 30px;">
        <h3 style="color: var(--terracotta-light); font-size: 1.1rem;">NEURAID | GPMS</h3>
        <p style="color: rgba(255,255,255,0.4); font-size: 0.6rem;">ORGANIC INTELLIGENCE</p>
    </div>
    <div style="flex:1;">
        <a href="{{ route('supervisor.super') }}" class="sidebar-item active"><i class="fas fa-chart-line"></i> لوحة التحكم</a>
        <a href="{{ route('supervisor.dashboard') }}" class="sidebar-item"><i class="fas fa-chart-simple"></i> الإحصائيات</a>
        <a href="{{ route('announcements.index') }}" class="sidebar-item" id="announcementsLink">
            <i class="fas fa-bullhorn"></i> الإعلانات
            <span class="notification-badge" id="announcementsBadge">{{ $unreadAnnouncements > 0 ? $unreadAnnouncements : '' }}</span>
        </a>
        <a href="{{ route('supervisor.my-schedule') }}" class="sidebar-item" id="scheduleLink">
            <i class="fas fa-calendar-alt"></i> جدول مناقشاتي
            <span class="notification-badge" id="scheduleBadge">{{ $unreadDiscussions > 0 ? $unreadDiscussions : '' }}</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="sidebar-item"><i class="fas fa-user-gear"></i> إعدادات</a>

        {{-- رابط الأرشيف --}}
        <a href="{{ route('archive.index') }}" class="sidebar-item">
            <i class="fas fa-archive"></i> أرشيف المشاريع
        </a>
    </div>
    <div style="margin-top: auto;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item" style="width:100%; background:none; border:none; cursor:pointer; color:#f87171;">
                <i class="fas fa-sign-out-alt"></i> خروج
            </button>
        </form>
    </div>
</div>

    <!-- نافذة الإشعارات المنبثقة -->
    <div class="notifications-modal" id="notificationsModal">
        <div class="notif-modal-header">
            <h4><i class="fas fa-bell"></i> الإشعارات</h4>
            <button class="mark-all-btn" onclick="markAllNotificationsRead()">تحديد الكل كمقروء</button>
            <button class="notif-modal-close" id="closeNotifModal">&times;</button>
        </div>
        <div class="notif-list" id="notificationsList">
            <div class="text-center p-4 text-muted">جاري التحميل...</div>
        </div>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="organic-content">

        <!-- هيدر -->
        <div class="wavy-header">
            <div class="header-top">
                <div class="logo-gpms">
                    <span>MS</span><span>GP</span>
                </div>
                <div class="supervisor-icon">
                    <i class="fas fa-user-tie"></i>
                    <div class="info">
                        <h3>{{ $user->name }}</h3>
                        <p>مشرف أكاديمي</p>
                    </div>
                </div>
            </div>
            <div class="header-stats">
                <div class="header-stat"><i class="fas fa-layer-group"></i> {{ $projectsCount }} مشروع</div>
                <div class="header-stat"><i class="fas fa-hourglass-half"></i> {{ $pendingCount }} قيد المراجعة</div>
                <div class="header-stat"><i class="fas fa-check-circle"></i> {{ $approvedCount }} مقبول</div>
            </div>
            <div class="capacity-wrapper">
                <div class="capacity-mini">
                    <div class="capacity-mini-label"><i class="fas fa-users"></i><span>سعة الفرق</span></div>
                    <div class="capacity-mini-value">{{ $user->max_team_size ?? '8' }}</div>
                    <form action="{{ route('supervisor.update-size') }}" method="POST" class="capacity-mini-form">
                        @csrf
                        <input type="number" name="max_team_size" value="{{ $user->max_team_size }}" class="capacity-mini-input" min="1" max="20">
                        <button type="submit" class="capacity-mini-btn">تحديث</button>
                    </form>
                </div>
                {{-- طلب إيقاف استقبال المشاريع --}}
@if(!$supervisor->stop_receiving)
    <div class="stop-request-card">
        <form action="{{ route('supervisor.request-stop') }}" method="POST" onsubmit="return confirm('⚠️ هل أنت متأكد من طلب إيقاف استقبال مشاريع جديدة؟\n\nسيتم إرسال طلبك إلى الأدمن للمراجعة.')">
            @csrf
            <button type="submit" class="stop-request-btn">
                <i class="fas fa-pause-circle"></i>
                <span>إيقاف استقبال المشاريع</span>
                <small>سيتم مراجعة الطلب من قبل الأدمن</small>
            </button>
        </form>
    </div>
@elseif($supervisor->stop_request_pending)
    <div class="stop-request-pending">
        <i class="fas fa-clock"></i>
        <span>طلب إيقاف الاستقبال قيد المراجعة...</span>
    </div>
@else
    <div class="stop-request-active">
        <i class="fas fa-check-circle"></i>
        <span>تم إيقاف استقبال المشاريع</span>
    </div>
@endif
            </div>
        </div>

<!-- شبكة المشاريع -->
<div class="project-grid">
    @forelse($projects as $project)
    <div class="project-leaf">
        {{-- حالة المشروع --}}
        <div class="project-status-leaf {{ $project->status == 'pending' ? 'status-pending' : 'status-approved' }}">
            <span class="status-dot"></span>
            @if($project->status == 'pending') قيد المراجعة
            @elseif($project->status == 'approved') مقبول
            @else مرفوض @endif
        </div>

        {{-- عنوان المشروع --}}
        <div class="project-title-leaf">{{ $project->title }}</div>

        {{-- فريق العمل --}}
        <div class="project-team">
            <i class="fas fa-users"></i> فريق العمل:
            <strong>{{ $project->student->user->name ?? 'غير محدد' }}</strong>
            @foreach($project->users as $member)
                @if($member->id != $project->student->user_id)
                    | {{ $member->name }}
                @endif
            @endforeach
        </div>

        {{-- أزرار الإجراءات الأساسية --}}
<div class="project-actions-leaf">
    <a href="{{ route('supervisor.stages.index', $project->id) }}" class="action-leaf"><i class="fas fa-tasks"></i> مراحل</a>
    <a href="/direct-ai-analysis/{{ $project->id }}" class="action-leaf"><i class="fas fa-brain"></i> تحليل ذكي</a>
    <a href="{{ route('supervisor.notes.create', $project->id) }}" class="action-leaf"><i class="fas fa-pen-nib"></i> ملاحظة</a>
    @if($lastFile = $project->files->first())
    <a href="{{ asset('storage/' . $lastFile->file_path) }}" target="_blank" class="action-leaf"><i class="fas fa-eye"></i> ملف</a>
    @endif

    {{-- 🔥 زر الأرشفة (مندمج هنا) --}}
    @if($project->status == 'approved' && $project->archive_status == 'pending')
        <form action="{{ route('supervisor.requestArchive', $project->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="action-leaf" style="background: #9a92ac; color: white;">
                <i class="fas fa-archive"></i> طلب أرشفة
            </button>
        </form>
    @elseif($project->archive_status == 'requested')
        <span class="action-leaf" style="background: #F59E0B; color: white; cursor: default;">
            <i class="fas fa-clock"></i> بانتظار الأرشفة
        </span>
    @elseif($project->archive_status == 'archived')
        <span class="action-leaf" style="background: #10B981; color: white; cursor: default;">
            <i class="fas fa-check-circle"></i> مؤرشف
        </span>
    @endif
</div>

{{-- تم حذف قسم الأرشفة المنفصل الذي كان بعد هذا --}}
{{-- (لأنه أصبح جزءاً من project-actions-leaf) --}}
        {{-- أزرار القبول/الرفض (للمشاريع المعلقة) --}}
        @if($project->status == 'pending')
        <div class="decision-buttons">
            <form action="{{ route('project.updateStatus', $project->id) }}" method="POST" style="flex:1;">
                @csrf <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-approve">قبول</button>
            </form>
            <form action="{{ route('project.updateStatus', $project->id) }}" method="POST">
                @csrf <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn-reject"><i class="fas fa-times"></i></button>
            </form>
        </div>
        @endif

        {{-- رصد الدرجة (للمشاريع المقبولة) --}}
        @if($project->status == 'approved')
            @php $evaluation = \DB::table('evaluations')->where('project_id', $project->id)->first(); @endphp
            <button type="button" class="action-leaf" style="width:100%; margin-top:12px; background:var(--terracotta); color:white;" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $project->id }}">
                <i class="fas fa-graduation-cap"></i> {{ $evaluation ? 'تعديل الدرجة' : 'رصد الدرجة' }}
            </button>
        @endif


        {{-- تنبيه التشابه (إن وجد) --}}
        @if(isset($project->similarity_warning) && $project->similarity_warning && isset($project->similar_with))
        <div style="background:#FEF3C7; border-radius:16px; padding:8px 12px; margin-top:12px; font-size:11px;">
            <i class="fas fa-exclamation-triangle"></i> تشابه مع: {{ $project->similar_with->title }} ({{ $project->similarity_percentage }}%)
        </div>
        @endif
    </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">لا توجد مشاريع مسندة إليك حالياً.</p>
        </div>
    @endforelse
</div>

   <!-- زر الإشعارات العائم -->
<div class="floating-buttons">
<div class="float-btn btn-notification" id="notificationFloatBtn">        <i class="fas fa-bell"></i>
        <span class="notif-badge" id="floatNotifBadge" style="display: none;">0</span>
    </div>
    <div class="float-btn btn-whatsapp" onclick="window.open('https://wa.me/{{ $whatsappNumber ?? '966500000000' }}','_blank')">
        <i class="fab fa-whatsapp"></i>
    </div>
</div>

    <!-- مودالات رصد الدرجة -->
    @foreach($projects as $project)
    <div class="modal fade" id="gradeModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 28px; border:none; overflow:hidden;">
                <form action="{{ route('supervisor.project.grade', ['id' => $project->id]) }}" method="POST">
                    @csrf
                    <div style="background: linear-gradient(135deg, #2C3E50, #3D5A6C); padding:18px; text-align:center;">
                        <h5 style="color:white; font-weight:700; margin:0;">تقييم المشروع</h5>
                        <p style="color:rgba(255,255,255,0.7); font-size:12px; margin-top:4px;">{{ $project->title }}</p>
                    </div>
                    <div style="padding:25px;">
                        <label style="font-weight:600;">الدرجة (من 100)</label>
                        <input type="number" name="grade" value="{{ $evaluation->grade ?? '' }}" style="width:100%; padding:12px; border-radius:20px; border:2px solid #EDE5D8; margin-top:8px; margin-bottom:18px;">
                        <label style="font-weight:600;">ملاحظات اللجنة</label>
                        <textarea name="feedback" rows="3" style="width:100%; padding:12px; border-radius:20px; border:2px solid #EDE5D8; margin-top:8px;">{{ $evaluation->feedback ?? '' }}</textarea>
                    </div>
                    <div style="padding:15px 25px; background:#F8FAFC; display:flex; gap:12px;">
                        <button type="submit" class="btn-approve" style="flex:2;">اعتماد</button>
                        <button type="button" data-bs-dismiss="modal" class="btn-reject" style="background:#E2E8F0; color:#64748B;">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
<script>
    // فتح وإغلاق السايد بار
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('organicSidebar')?.classList.toggle('open');
    });

    // ========== نافذة الإشعارات ==========
    const notifBtn = document.getElementById('notificationFloatBtn');
    const notifModal = document.getElementById('notificationsModal');
    const closeNotif = document.getElementById('closeNotifModal');

    if(notifBtn) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if(notifModal) notifModal.classList.toggle('show');
            if(notifModal?.classList.contains('show')) {
                loadNotifications();
            }
        });
    }
    if(closeNotif) {
        closeNotif.addEventListener('click', () => {
            if(notifModal) notifModal.classList.remove('show');
        });
    }
    document.addEventListener('click', (e) => {
        if(notifModal && notifBtn && !notifModal.contains(e.target) && !notifBtn.contains(e.target)) {
            notifModal.classList.remove('show');
        }
    });

    // ========== دوال الإشعارات ==========
    async function loadNotifications() {
        try {
            const response = await fetch('/notifications');
            const data = await response.json();
            const listDiv = document.getElementById('notificationsList');
            const badgeSpan = document.getElementById('floatNotifBadge');

            if(badgeSpan) {
                badgeSpan.textContent = data.unread_count || 0;
                badgeSpan.style.display = data.unread_count > 0 ? 'flex' : 'none';
            }

            // تحديث البادجات في السايد بار
            const announcementsBadge = document.getElementById('announcementsBadge');
            const scheduleBadge = document.getElementById('scheduleBadge');
            if(announcementsBadge) announcementsBadge.style.display = 'none';
            if(scheduleBadge) scheduleBadge.style.display = 'none';

            if(listDiv && data.notifications) {
                if(data.notifications.length === 0) {
                    listDiv.innerHTML = '<div class="text-center p-4 text-muted">📭 لا توجد إشعارات</div>';
                    return;
                }
                listDiv.innerHTML = data.notifications.map(n => `
                    <div class="notif-item ${n.is_read ? '' : 'unread'}" onclick="markNotificationRead(${n.id})">
                        <div class="notif-icon ${n.type}">
                            <i class="fas ${n.icon}"></i>
                        </div>
                        <div class="notif-content">
                            <div class="notif-title">${n.title}</div>
                            <div class="notif-message">${n.message}</div>
                            <div class="notif-time">${n.time_ago}</div>
                        </div>
                    </div>
                `).join('');
            }
        } catch(e) { console.error('خطأ في تحميل الإشعارات:', e); }
    }

    async function markNotificationRead(id) {
        try {
            await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            loadNotifications();
        } catch(e) { console.error(e); }
    }

    async function markAllNotificationsRead() {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            loadNotifications();
        } catch(e) { console.error(e); }
    }

    // عند الضغط على رابط الإعلانات أو جدول المناقشات
    document.getElementById('announcementsLink')?.addEventListener('click', () => {
        setTimeout(() => loadNotifications(), 500);
    });
    document.getElementById('scheduleLink')?.addEventListener('click', () => {
        setTimeout(() => loadNotifications(), 500);
    });

    // تحديث الإشعارات كل 30 ثانية
    setInterval(loadNotifications, 30000);
    loadNotifications();

    // ========== دالة تنبيه التشابه ==========
    function testNotify(projectId, similarTitle, percentage) {
        if(confirm('إرسال تنبيه للطالب عن التشابه مع: ' + similarTitle + ' بنسبة ' + percentage + '%؟')) {
            fetch(`/supervisor/notify-similarity/${projectId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ similar_title: similarTitle, percentage: percentage })
            })
            .then(res => res.json())
            .then(data => alert(data.success ? '✅ تم الإرسال' : '❌ فشل الإرسال'))
            .catch(() => alert('❌ خطأ في الاتصال'));
        }
    }
</script>

<style>
    .swal-popup-organic {
        font-family: 'Cairo', sans-serif;
        border-radius: 28px;
        border: 1px solid #D4A373;
    }
</style>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
</x-app-layout>
