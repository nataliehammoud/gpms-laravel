<x-app-layout>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --navy-deep: #0F2B3D;
        --navy-mid: #1A4058;
        --gold: #D4AF37;
        --gold-soft: #E8D5A8;
        --gold-glow: rgba(212, 175, 55, 0.08);
        --cream: #FEFAF5;
        --cream-dark: #F5EFE3;
        --shadow: 0 8px 20px -6px rgba(0,0,0,0.05);
        --shadow-lg: 0 20px 35px -12px rgba(0,0,0,0.1);
    }

    body {
        background: linear-gradient(145deg, var(--cream) 0%, var(--cream-dark) 100%);
        font-family: 'Cairo', sans-serif;
        padding: 30px;
        min-height: 100vh;
    }

    .requests-dashboard {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* الهيدر */
    .requests-hero {
        background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
        border-radius: 28px;
        padding: 30px 35px;
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
    }
    .requests-hero::after {
        content: '\f0f3';
        font-family: 'Font Awesome 6 Free';
        position: absolute;
        bottom: -20px;
        left: -10px;
        font-size: 100px;
        opacity: 0.05;
        color: white;
    }
    .requests-hero h1 {
        color: var(--gold-soft);
        font-size: 28px;
        font-weight: 800;
        margin: 0;
    }
    .requests-hero p {
        color: rgba(232, 213, 168, 0.7);
        font-size: 14px;
        margin-top: 8px;
    }

    /* إحصائيات */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 35px;
    }
    .stat-block {
        background: white;
        border-radius: 24px;
        padding: 20px;
        text-align: center;
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: 0.25s;
    }
    .stat-block:hover {
        transform: translateY(-3px);
        border-color: var(--gold);
        box-shadow: var(--shadow);
    }
    .stat-number {
        font-size: 36px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }
    .stat-label {
        font-size: 12px;
        color: #8B9AAB;
        margin-top: 8px;
    }

    /* بطاقات الطلبات */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }
    .request-card {
        background: white;
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.35s;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }
    .request-card:hover {
        transform: translateY(-5px);
        border-color: var(--gold);
        box-shadow: var(--shadow-lg);
    }
    .card-header {
        background: linear-gradient(115deg, var(--navy-deep), var(--navy-mid));
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .card-icon {
        width: 48px;
        height: 48px;
        background: rgba(232, 213, 168, 0.15);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .card-header h3 {
        color: var(--gold-soft);
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }
    .card-header p {
        color: rgba(232, 213, 168, 0.6);
        font-size: 11px;
        margin: 4px 0 0;
    }
    .card-body {
        padding: 22px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #F0E6D5;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-size: 12px;
        color: #8B9AAB;
        font-weight: 600;
    }
    .info-value {
        font-size: 14px;
        font-weight: 800;
        color: var(--navy-deep);
    }
    .info-value i {
        color: var(--gold);
        margin-right: 6px;
    }
    .card-actions {
        padding: 18px 22px;
        background: var(--gold-glow);
        display: flex;
        gap: 12px;
        border-top: 1px solid rgba(212, 175, 55, 0.1);
    }
    .btn-approve {
        flex: 1;
        background: linear-gradient(135deg, #10B981, #059669);
        border: none;
        padding: 12px;
        border-radius: 40px;
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: 0.25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    .btn-reject {
        flex: 1;
        background: linear-gradient(135deg, #EF4444, #DC2626);
        border: none;
        padding: 12px;
        border-radius: 40px;
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: 0.25s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }

    /* زر العودة */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 10px 28px;
        border-radius: 50px;
        text-decoration: none;
        color: var(--navy-deep);
        font-weight: 700;
        margin-top: 35px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        transition: 0.25s;
    }
    .back-btn:hover {
        background: var(--gold);
        color: var(--navy-deep);
        transform: translateX(-5px);
    }

    /* حالة فارغة */
    .empty-state {
        text-align: center;
        padding: 60px;
        background: white;
        border-radius: 28px;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }
    .empty-state i {
        font-size: 64px;
        color: #8B9AAB;
        opacity: 0.3;
        margin-bottom: 15px;
    }
    .empty-state h3 {
        color: var(--navy-deep);
        margin-bottom: 8px;
    }

    @media (max-width: 750px) {
        body { padding: 15px; }
        .stats-row { grid-template-columns: 1fr; gap: 15px; }
        .cards-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="requests-dashboard" dir="rtl">

    <!-- الهيدر -->
    <div class="requests-hero">
        <h1><i class="fas fa-bell"></i> طلبات إيقاف الاستقبال</h1>
        <p>إدارة طلبات المشرفين الذين يرغبون في إيقاف استقبال مشاريع جديدة</p>
                  <div class="stat-number" style="background: var(--navy-mid); color: white; border-radius: 16px; padding: 8px 20px; display: inline-block;">
                {{ $pendingRequests->count() }}
            </div>
            <div class="stat-label">طلبات معلقة</div>
        </div>
    </div>



    <!-- بطاقات الطلبات -->
    @if($pendingRequests->count() > 0)
    <div class="cards-grid">
        @foreach($pendingRequests as $sup)
        <div class="request-card">
            <div class="card-header">
                <div class="card-icon"><i class="fas fa-user-tie"></i></div>
                <div>
                    <h3>{{ $sup->user->name ?? 'غير معروف' }}</h3>
                    <p><i class="fas fa-envelope"></i> {{ $sup->user->email ?? 'بريد غير متوفر' }}</p>
                </div>
            </div>
            <div class="card-body">

                <div class="info-row">
                    <span class="info-label"><i class="fas fa-clock"></i> تاريخ الطلب</span>
                    <span class="info-value">{{ $sup->updated_at->diffForHumans() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar"></i> التاريخ</span>
                    <span class="info-value">{{ $sup->updated_at->format('Y-m-d') }}</span>
                </div>
            </div>
            <div class="card-actions">
                <form action="{{ route('admin.approve-stop', $sup->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-approve" onclick="return confirm('قبول الطلب؟')">
                        <i class="fas fa-check"></i> قبول
                    </button>
                </form>
                <form action="{{ route('admin.reject-stop', $sup->id) }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-reject" onclick="return confirm('رفض الطلب؟')">
                        <i class="fas fa-times"></i> رفض
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>لا توجد طلبات حالياً</h3>
        <p>سيظهر هنا أي مشرف يطلب إيقاف استقبال مشاريع جديدة</p>
    </div>
    @endif

    <!-- زر العودة -->
    @php
        $role = auth()->user()->role;
        $backRoute = match($role) {
            'admin' => route('admin.dashboard'),
            default => route('dashboard')
        };
    @endphp
    <div style="text-align: center;">
        <a href="{{ $backRoute }}" class="back-btn">
            <i class="fas fa-arrow-right"></i> العودة إلى لوحة التحكم
        </a>
    </div>
</div>
</x-app-layout>
