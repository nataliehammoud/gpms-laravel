<x-app-layout>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --cream-bg: #FEFAF5;
        --cream-card: #FFFFFF;
        --navy-deep: #0F2B3D;
        --navy-mid: #1A4058;
        --gold: #D4AF37;
        --gold-soft: #E8D5A8;
        --gold-glow: rgba(212, 175, 55, 0.08);
        --shadow: 0 8px 20px -6px rgba(0,0,0,0.05);
    }

    body {
        background: linear-gradient(145deg, var(--cream-bg) 0%, #F5EFE3 100%);
        font-family: 'Cairo', sans-serif;
        min-height: 100vh;
    }

    /* ====== سايد بار قابل للطي ====== */
    .sidebar {
        position: fixed;
        top: 0;
        right: -280px;
        width: 280px;
        height: 100vh;
        background: linear-gradient(180deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
        z-index: 1000;
        transition: right 0.3s ease;
        box-shadow: -5px 0 20px rgba(0,0,0,0.1);
        overflow-y: auto;
    }

    .sidebar.open {
        right: 0;
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }

    .sidebar-overlay.open {
        display: block;
    }

    .menu-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
        background: var(--navy-deep);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        border: 1px solid rgba(212, 175, 55, 0.3);
        transition: all 0.3s;
    }

    .menu-btn:hover {
        background: var(--gold);
    }

    .menu-btn:hover svg {
        stroke: var(--navy-deep);
    }

    .sidebar-header {
        padding: 30px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        margin-bottom: 20px;
    }

    .sidebar-header h2 {
        color: white;
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .sidebar-header h2 span {
        color: var(--gold);
    }

    .sidebar-header p {
        color: rgba(232, 213, 168, 0.6);
        font-size: 10px;
        margin-top: 5px;
    }

    .sidebar-nav {
        padding: 0 15px;
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        margin-bottom: 8px;
        border-radius: 12px;
        color: #cbd5e1;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.25s;
    }

    .sidebar-item:hover {
        background: rgba(212, 175, 55, 0.12);
        color: var(--gold);
        transform: translateX(-4px);
    }

    .badge-count {
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        margin-right: auto;
    }

    .sidebar-logout {
        margin-top: 30px;
        padding: 15px;
        border-top: 1px solid rgba(212, 175, 55, 0.1);
    }

    .logout-btn {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #f87171;
        width: 100%;
        cursor: pointer;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    /* ====== المحتوى الرئيسي ====== */
    .main-content {
        padding: 25px 30px;
        transition: all 0.3s ease;
    }

    /* ====== هيدر ====== */
    .simple-header {
        background: var(--cream-card);
        border-radius: 16px;
        padding: 12px 24px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border: 1px solid rgba(212, 175, 55, 0.12);
        box-shadow: var(--shadow);
    }

    .logo { font-size: 22px; font-weight: 700; color: var(--navy-deep); }
    .logo span { color: var(--gold); }
    .role-tag { background: var(--gold-glow); padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; color: var(--navy-mid); }
    .admin-name { color: var(--navy-mid); font-size: 13px; font-weight: 500; }

    /* ====== شبكة المحتوى ====== */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .info-card {
        background: var(--cream-card);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .info-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-deep);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gold-glow);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0ece4;
    }

    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 13px; color: #8B9AAB; font-weight: 500; }
    .info-value { font-size: 20px; font-weight: 700; color: var(--navy-deep); }

    .control-card {
        background: var(--cream-card);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-deep);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gold-glow);
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--navy-mid); margin-bottom: 8px; }
    .form-group input { width: 100%; padding: 10px 14px; background: var(--cream-bg); border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px; }
    .form-group input:focus { outline: none; border-color: var(--gold); }

    .btn-primary {
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        border: none; padding: 10px 20px; border-radius: 30px; color: var(--gold-soft); font-weight: 600;
        cursor: pointer; transition: 0.25s; width: 100%;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(15, 43, 61, 0.2); }

    .btn-gold {
        background: linear-gradient(135deg, var(--gold), #B8942E);
        border: none; padding: 12px 20px; border-radius: 30px;
        color: var(--navy-deep); font-weight: 700; cursor: pointer; transition: 0.25s;
        width: 100%;
    }
    .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }
    .action-buttons .btn-primary,
    .action-buttons .btn-gold { flex: 1; }

    /* نتائج التوزيع - جدول مرتب */
    .results-card {
        background: var(--cream-card);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(212, 175, 55, 0.1);
        margin-top: 10px;
    }

    .results-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--navy-deep);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--gold-glow);
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
    }

    .results-table th {
        text-align: right;
        padding: 12px 8px;
        background: #f8f6f2;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-mid);
        border-bottom: 1px solid #e2e8f0;
    }

    .results-table td {
        padding: 10px 8px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f0ece4;
    }

    .results-table tr:hover td {
        background: var(--gold-glow);
    }

    .results-empty {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
        font-size: 13px;
    }

    .alert-success {
        background: var(--gold-glow);
        border-right: 3px solid var(--gold);
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        color: var(--navy-mid);
        font-weight: 600;
    }

    /* ========== أزرار عائمة (جهة اليسار) ========== */
    .floating-buttons {
        position: fixed;
        bottom: 25px;
        left: 25px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 100;
    }

    .float-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        text-decoration: none;
        font-size: 24px;
    }

    .float-circle:hover { transform: scale(1.1); }
    .float-notif { background: var(--navy-deep); position: relative; }
    .float-notif-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #EF4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 30px;
        min-width: 20px;
        text-align: center;
    }
    .float-whatsapp { background: #25D366; color: white; }

    /* مودال الإشعارات */
    .notif-modal {
        position: fixed;
        bottom: 90px;
        left: 25px;
        width: 340px;
        background: var(--cream-card);
        border-radius: 24px;
        border: 1px solid rgba(212, 175, 55, 0.15);
        box-shadow: 0 20px 30px -12px rgba(0,0,0,0.1);
        z-index: 99;
        display: none;
        overflow: hidden;
    }
    .notif-modal.show { display: block; animation: slideUp 0.25s ease; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .notif-modal-header { padding: 12px 18px; background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid)); color: var(--gold-soft); font-weight: 700; font-size: 13px; display: flex; justify-content: space-between; }
    .mark-all-read { font-size: 11px; cursor: pointer; opacity: 0.7; }
    .notif-modal-list { max-height: 380px; overflow-y: auto; }
    .notif-modal-item { padding: 12px 16px; border-bottom: 1px solid #F0E6D5; cursor: pointer; display: flex; gap: 10px; transition: 0.2s; }
    .notif-modal-item:hover { background: var(--gold-glow); }
    .notif-modal-item.unread { background: rgba(212, 175, 55, 0.08); border-right: 3px solid var(--gold); }
    .notif-modal-icon { width: 32px; height: 32px; background: var(--gold-glow); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .notif-modal-content { flex: 1; }
    .notif-modal-title { font-weight: 700; font-size: 12px; color: var(--navy-deep); }
    .notif-modal-time { font-size: 10px; color: #8B9AAB; margin-top: 3px; }
    .notif-empty { padding: 40px; text-align: center; color: #8B9AAB; font-size: 13px; }

    @media (max-width: 850px) {
        .main-content { padding: 15px; }
        .content-grid { grid-template-columns: 1fr; gap: 20px; }
        .menu-btn { top: 15px; right: 15px; width: 40px; height: 40px; }
        .notif-modal { left: 15px; width: calc(100% - 30px); }
    }
</style>

<!-- خلفية مظلمة -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- زر الهامبرغر -->
<div class="menu-btn" id="menuBtn">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
        <path d="M3 12h18M3 6h18M3 18h18"/>
    </svg>
</div>

<!-- سايد بار -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>GP<span>MS</span></h2>
        <p>Academic Management System</p>
    </div>

    <div class="sidebar-nav">
        <a href="{{ route('admin.users.index') }}" class="sidebar-item">المستخدمين</a>
        <a href="{{ route('announcements.index') }}" class="sidebar-item">الإعلانات</a>
        <a href="{{ route('admin.stop-requests') }}" class="sidebar-item">
            طلبات الإيقاف
            @if($pendingStopRequestsCount > 0)
                <span class="badge-count">{{ $pendingStopRequestsCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.visualization') }}" class="sidebar-item">
    <i class="fas fa-chart-scatter"></i> خريطة المشاريع
</a>
    <a href="{{ route('admin.statistics') }}" class="sidebar-item">
    إحصائيات الأقسام
</a>

        <a href="{{ route('admin.archive.create') }}" class="sidebar-item">الأرشيف</a>
        <a href="{{ route('profile.edit') }}" class="sidebar-item"> إعدادات الحساب</a>
    </div>

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item logout-btn">تسجيل الخروج</button>
        </form>
    </div>
</div>

<!-- المحتوى الرئيسي -->
<div class="main-content">


    <div class="simple-header">
        <div>
            <span class="logo">GP<span>MS</span></span>
            <span class="role-tag">مدير النظام</span>
        </div>
        <div class="admin-name">{{ Auth::user()->name }}</div>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="content-grid">
        <div class="info-card">
            <div class="info-title">معلومات النظام</div>
            <div class="info-row">
                <span class="info-label">إجمالي المستخدمين</span>
                <span class="info-value">{{ $stats['users_count'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">المشرفون</span>
                <span class="info-value">{{ $stats['supervisors_count'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">الطلاب</span>
                <span class="info-value">{{ $stats['students_count'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">المشاريع النشطة</span>
                <span class="info-value">{{ $stats['total_projects'] }}</span>
            </div>
            <a href="{{ route('admin.archive.requests') }}" class="sidebar-item">
    <i class="fas fa-archive"></i> طلبات الأرشفة
    @if($archiveRequestsCount > 0)
        <span class="badge-count">{{ $archiveRequestsCount }}</span>
    @endif
</a>
        </div>

        <div class="control-card">
            <div class="card-title">إعدادات النظام</div>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @php $max_students = \App\Models\Setting::where('key', 'max_projects_per_supervisor')->value('value') ?? 5; @endphp
                <div class="form-group">
                    <label>الحد الأقصى للمشاريع لكل مشرف</label>
                    <input type="number" name="max_projects_per_supervisor" value="{{ $max_students }}" required>
                </div>
                <button type="submit" class="btn-primary">حفظ التغييرات</button>
            </form>

            <div class="action-buttons">
                <form action="{{ route('admin.distribute') }}" method="POST" id="distributeForm" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-gold">تشغيل التوزيع الذكي</button>
                </form>
                <form action="{{ route('admin.train.ai') }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-primary">تدريب الشبكة</button>
                </form>
            </div>
        </div>
    </div>

    <!-- نتائج التوزيع - جدول مرتب -->
    <div class="results-card">
        <div class="results-title">نتائج التوزيع</div>
        @if(session()->has('results') && !empty(session('results')))
            <table class="results-table">
                <thead>
                    <tr><th>عنوان المشروع</th><th>المشرف المعين</th></tr>
                </thead>
                <tbody>
                    @foreach(session('results') as $res)
                        @php $svName = \App\Models\User::find($res->supervisor_id)->name ?? 'غير محدد'; @endphp
                        <tr><td>{{ $res->title }}</td><td>{{ $svName }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="results-empty">لم يتم تشغيل التوزيع بعد. اضغط على "تشغيل التوزيع الذكي" لعرض النتائج.</div>
        @endif
    </div>
</div>

<!-- أزرار عائمة (جهة اليسار) -->
<div class="floating-buttons">
    <div class="float-circle float-notif" onclick="toggleNotifModal()">
        🔔
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="float-notif-badge">{{ $unreadCount }}</span>
        @endif
    </div>
    @php $whatsappNumber = \App\Models\Setting::where('key', 'whatsapp_number')->value('value') ?? '966500000000'; @endphp
    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="float-circle float-whatsapp">💬</a>
</div>

<!-- مودال الإشعارات -->
<div id="notifModal" class="notif-modal">
    <div class="notif-modal-header">
        <span>🔔 الإشعارات</span>
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="mark-all-read" onclick="markAllRead()">تحديد الكل كمقروء</span>
        @endif
    </div>
    <div class="notif-modal-list" id="notifList">
        @forelse($notifications ?? [] as $notif)
        <div class="notif-modal-item {{ !$notif->is_read ? 'unread' : '' }}" data-id="{{ $notif->id }}" onclick="markAsReadModal(this)">
            <div class="notif-modal-icon">📋</div>
            <div class="notif-modal-content">
                <div class="notif-modal-title">{{ $notif->title }}</div>
                <div class="notif-modal-time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @empty
        <div class="notif-empty">📭 لا توجد إشعارات جديدة</div>
        @endforelse
    </div>
</div>

<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script>
    // سايد بار
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    menuBtn.addEventListener('click', () => {
        sidebar.classList.add('open');
        overlay.classList.add('open');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    });

    // أنيميشن الأرقام
    document.querySelectorAll('.count-number').forEach(el => {
        const target = parseInt(el.dataset.target);
        if (isNaN(target)) return;
        let current = 0;
        const increment = target / 30;
        const timer = setInterval(() => {
            current += increment;
            if(current >= target) {
                el.innerText = target;
                clearInterval(timer);
            } else {
                el.innerText = Math.floor(current);
            }
        }, 20);
    });

    // الإشعارات
    function toggleNotifModal() {
        document.getElementById('notifModal').classList.toggle('show');
    }

    function markAsReadModal(element) {
        const id = element.dataset.id;
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => {
            element.classList.remove('unread');
            const badge = document.querySelector('.float-notif-badge');
            if(badge) {
                let count = parseInt(badge.innerText);
                if(count > 1) badge.innerText = count - 1;
                else badge.remove();
            }
        });
    }

    function markAllRead() {
        fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => location.reload());
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('notifModal');
        const btn = document.querySelector('.float-notif');
        if(modal && btn && !btn.contains(e.target) && !modal.contains(e.target)) {
            modal.classList.remove('show');
        }
    });

    const form = document.getElementById('distributeForm');
    if(form) {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button');
            btn.disabled = true;
            btn.innerHTML = 'جاري التوزيع...';
        });
    }

    // تحديث الإشعارات كل 30 ثانية
    setInterval(() => {
        fetch('/notifications').then(res => res.json()).then(data => {
            const badge = document.querySelector('.float-notif-badge');
            if(data.unread_count > 0) {
                if(badge) badge.innerText = data.unread_count;
            } else if(badge) badge.remove();
        }).catch(() => {});
    }, 30000);

    // تدريب الشبكة
    const trainForm = document.querySelector('form[action="{{ route('admin.train.ai') }}"]');
    if(trainForm) {
        trainForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'جاري التدريب...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ تم التدريب بنجاح!',
                        text: data.message || 'تم تدريب الشبكة العصبونية بنجاح.',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#D4AF37',
                        background: '#FEFAF5'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ فشل التدريب',
                        text: data.error || 'حدث خطأ أثناء التدريب.',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#EF4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '⚠️ خطأ في الاتصال',
                    text: 'تعذر الاتصال بسيرفر الذكاء الاصطناعي.',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#D4AF37'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    }
async function analyzeSentimentAdmin() {
    const text = prompt('أدخل النص لتحليل مشاعره:', 'هذا المشروع رائع ومبتكر');
    if (!text) return;
    const resultDiv = document.getElementById('sentimentResultAdmin');
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<p style="color: #6c757d; margin: 0;">⏳ جاري التحليل...</p>';
    try {
        const response = await fetch('{{ route("analyze.sentiment") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ text: text })
        });
        const data = await response.json();
        if (data.success) {
            const emoji = data.label === 'POSITIVE' ? '😊' : '😞';
            const bgColor = data.label === 'POSITIVE' ? '#d4edda' : '#f8d7da';
            const textColor = data.label === 'POSITIVE' ? '#155724' : '#721c24';
            resultDiv.innerHTML = `
                <div style="background: ${bgColor}; padding: 8px 15px; border-radius: 6px; border-right: 4px solid ${data.label === 'POSITIVE' ? '#28a745' : '#dc3545'};">
                    <p style="margin: 0; font-weight: 600; color: ${textColor}; font-size:0.9rem;">
                        ${emoji} ${data.label === 'POSITIVE' ? 'إيجابية' : 'سلبية'} (${Math.round(data.score * 100)}%)
                    </p>
                    <p style="margin: 0; font-size: 0.8rem; color: ${textColor};">"${text}"</p>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `<p style="color: #dc3545; margin: 0;">❌ فشل التحليل</p>`;
        }
    } catch (error) {
        resultDiv.innerHTML = `<p style="color: #dc3545; margin: 0;">❌ خطأ: ${error.message}</p>`;
    }
}
</script>
</x-app-layout>
