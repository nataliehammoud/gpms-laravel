<x-app-layout>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* الوضع الفاتح (الافتراضي) */
    :root {
        --cream: #FEFAF5;
        --cream-dark: #F9F5EC;
        --navy: #1A3A4A;
        --navy-light: #2A4A5A;
        --gold: #E8C96E;
        --gold-dark: #D4AF37;
        --gold-soft: rgba(232, 201, 110, 0.15);
        --sand: #EDE5D8;
        --text-soft: #6B7B8C;
        --shadow-soft: 0 12px 30px -10px rgba(0,0,0,0.05);
        --shadow-gold: 0 15px 30px -12px rgba(232, 201, 110, 0.2);
        --bg-glass: rgba(255, 255, 255, 0.7);
        --card-bg: white;
    }

    /* الوضع الداكن */
    body.dark-mode {
        --cream: #1a1f2e;
        --cream-dark: #151a28;
        --navy: #8B9DC3;
        --navy-light: #A2B4D6;
        --gold: #E8C96E;
        --gold-dark: #D4AF37;
        --sand: #2a2f3f;
        --text-soft: #A0A8C0;
        --shadow-soft: 0 12px 30px -10px rgba(0,0,0,0.2);
        --bg-glass: rgba(26, 31, 46, 0.85);
        --card-bg: #252b3d;
    }

    body {
        background: linear-gradient(145deg, var(--cream) 0%, var(--cream-dark) 100%);
        font-family: 'Cairo', 'Segoe UI', sans-serif;
        padding: 35px 40px 60px;
        transition: background 0.4s ease, color 0.3s;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* خلفية متحركة - حبيبات ذهبية ناعمة */
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 20% 40%, rgba(232, 201, 110, 0.12) 1.5px, transparent 1.5px);
        background-size: 45px 45px;
        pointer-events: none;
        animation: floatDots 25s linear infinite;
        z-index: 0;
    }

    /* خلفية متحركة - موجات ناعمة */
    body::after {
        content: '';
        position: fixed;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(ellipse at 30% 40%, rgba(232, 201, 110, 0.04) 0%, transparent 60%);
        pointer-events: none;
        animation: slowWave 18s ease-in-out infinite;
        z-index: 0;
    }

    @keyframes floatDots {
        0% { background-position: 0 0; }
        100% { background-position: 90px 90px; }
    }

    @keyframes slowWave {
        0%, 100% { transform: translate(0, 0) rotate(0deg); opacity: 0.5; }
        33% { transform: translate(3%, 2%) rotate(2deg); opacity: 0.7; }
        66% { transform: translate(-2%, 4%) rotate(-1deg); opacity: 0.6; }
    }

    /* كرات ذهبية متطايرة إضافية */
    .floating-bubbles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }

    .bubble {
        position: absolute;
        background: radial-gradient(circle, rgba(232, 201, 110, 0.15), rgba(232, 201, 110, 0.02));
        border-radius: 50%;
        animation: floatBubble linear infinite;
    }

    @keyframes floatBubble {
        0% { transform: translateY(100vh) translateX(0) scale(0); opacity: 0; }
        10% { opacity: 0.5; }
        90% { opacity: 0.3; }
        100% { transform: translateY(-20vh) translateX(50px) scale(1); opacity: 0; }
    }

    /* زر تبديل الوضعية */
    .theme-switch {
        position: fixed;
        bottom: 30px;
        left: 30px;
        width: 55px;
        height: 55px;
        background: var(--gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-gold);
        z-index: 200;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        font-size: 26px;
    }
    .theme-switch:hover { transform: scale(1.1) rotate(15deg); }

    /* زر الرجوع */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-glass);
        backdrop-filter: blur(8px);
        padding: 10px 28px;
        border-radius: 50px;
        text-decoration: none;
        color: var(--navy);
        font-weight: 600;
        margin-bottom: 30px;
        border: 1px solid var(--gold);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
        position: relative;
        z-index: 2;
    }
    .back-link:hover { transform: translateX(-6px); background: var(--gold); color: var(--cream-dark); }

    /* الهيدر */
    .header-area {
        max-width: 1400px;
        margin: 0 auto 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 2;
    }
    .title-section h1 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--navy);
        letter-spacing: -0.5px;
    }
    .title-section h1 span { color: var(--gold-dark); display: inline-block; animation: gentleWobble 3s ease-in-out infinite; }
    @keyframes gentleWobble { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-3px); } }
    .title-section p { font-size: 0.85rem; color: var(--text-soft); margin-top: 6px; }
    .stats-row {
        display: flex;
        gap: 15px;
        background: var(--bg-glass);
        backdrop-filter: blur(8px);
        padding: 8px 22px;
        border-radius: 60px;
        border: 1px solid var(--gold);
        box-shadow: var(--shadow-soft);
    }
    .stat-badge { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--navy); }

    /* بطاقة الموعد */
    .schedule-card {
        max-width: 1400px;
        margin: 0 auto 45px;
        background: var(--bg-glass);
        backdrop-filter: blur(12px);
        border-radius: 32px;
        overflow: hidden;
        border: 1px solid rgba(232, 201, 110, 0.3);
        box-shadow: var(--shadow-soft);
        transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        position: relative;
        z-index: 2;
    }
    .schedule-card:hover { transform: translateY(-5px) scale(1.01); box-shadow: var(--shadow-gold); }
    .schedule-header {
        background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
        padding: 18px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .schedule-icon {
        width: 48px;
        height: 48px;
        background: var(--gold);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        animation: softPulse 2s infinite;
    }
    @keyframes softPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(232, 201, 110, 0.4); }
        50% { transform: scale(1.08); box-shadow: 0 0 0 8px rgba(232, 201, 110, 0); }
    }
    .schedule-header-text h3 { color: white; font-weight: 700; margin: 0; }
    .schedule-header-text p { color: rgba(255,255,255,0.7); font-size: 0.75rem; margin: 4px 0 0; }
    .schedule-grid { display: flex; flex-wrap: wrap; background: var(--card-bg); }
    .schedule-item { flex: 1; padding: 22px 25px; min-width: 170px; border-left: 1px solid var(--sand); transition: all 0.25s; }
    .schedule-item:hover { background: var(--gold-soft); transform: translateX(-3px); }
    .schedule-label { font-size: 11px; text-transform: uppercase; color: var(--text-soft); letter-spacing: 1px; margin-bottom: 8px; }
    .schedule-value { font-weight: 800; color: var(--navy); font-size: 0.95rem; }
    .room-badge { background: var(--gold-soft); padding: 5px 14px; border-radius: 30px; display: inline-block; font-size: 0.8rem; color: var(--gold-dark); }

    /* قسم الإعلانات */
    .announcements-area { max-width: 1400px; margin: 0 auto; position: relative; z-index: 2; }
    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .section-head h2 { font-size: 1.6rem; font-weight: 700; color: var(--navy); display: flex; align-items: center; gap: 12px; }
    .btn-gold-soft {
        background: var(--gold);
        border: none;
        padding: 10px 25px;
        border-radius: 40px;
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--navy);
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-gold-soft:hover { transform: translateY(-2px); box-shadow: var(--shadow-gold); background: var(--gold-dark); color: white; }

    /* كروت الإعلانات - دخول متدرج */
    .cards-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 28px;
    }
    .announce-card {
        background: var(--card-bg);
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--sand);
        position: relative;
        box-shadow: var(--shadow-soft);
        opacity: 0;
        transform: translateY(30px) scale(0.98);
        animation: cardReveal 0.5s ease forwards;
        animation-delay: calc(var(--order, 0) * 0.08s);
    }
    @keyframes cardReveal {
        0% { opacity: 0; transform: translateY(30px) scale(0.96); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .announce-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: var(--gold);
        box-shadow: var(--shadow-gold);
    }
    .card-head { padding: 18px 22px 8px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--sand); }
    .card-category { background: var(--gold-soft); padding: 4px 14px; border-radius: 30px; font-size: 10px; font-weight: 700; color: var(--gold-dark); }
    .card-time { font-size: 11px; color: var(--text-soft); display: flex; align-items: center; gap: 5px; }
    .card-content { padding: 20px 22px; }
    .card-title { font-size: 1.2rem; font-weight: 800; color: var(--navy); margin-bottom: 12px; line-height: 1.4; }
    .card-desc { color: var(--text-soft); font-size: 0.85rem; line-height: 1.65; margin-bottom: 20px; }
    .card-attach {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--gold-soft);
        padding: 8px 18px;
        border-radius: 30px;
        text-decoration: none;
        color: var(--navy);
        font-size: 12px;
        font-weight: 500;
        transition: 0.2s;
    }
    .card-attach:hover { background: var(--gold); color: white; transform: translateX(-3px); }
    .delete-icon {
        position: absolute;
        top: 18px;
        right: 18px;
        background: none;
        border: none;
        color: #C5C0B5;
        cursor: pointer;
        transition: 0.2s;
        z-index: 5;
        font-size: 16px;
    }
    .delete-icon:hover { color: #E53E3E; transform: scale(1.1); }

    /* زر الإضافة */
    .fab-gold {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: var(--gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(232, 201, 110, 0.4);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 100;
        border: none;
        font-size: 28px;
    }
    .fab-gold:hover { transform: scale(1.1) rotate(90deg); background: var(--gold-dark); }

    /* مودال */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(26, 58, 74, 0.96);
        backdrop-filter: blur(12px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-container {
        width: 90%;
        max-width: 520px;
        background: var(--card-bg);
        border-radius: 36px;
        overflow: hidden;
        animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--gold);
    }
    @keyframes modalPop {
        from { opacity: 0; transform: scale(0.92) translateY(-20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header { background: linear-gradient(135deg, var(--navy), var(--navy-light)); padding: 22px; text-align: center; position: relative; }
    .modal-close { position: absolute; top: 16px; left: 22px; font-size: 1.8rem; cursor: pointer; color: white; background: none; border: none; transition: 0.2s; }
    .modal-close:hover { transform: scale(1.1); }
    .modal-body { padding: 32px; }
    .input-field {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid var(--sand);
        border-radius: 24px;
        margin-bottom: 18px;
        outline: none;
        font-family: inherit;
        background: var(--cream);
        color: var(--navy);
        transition: 0.2s;
    }
    .input-field:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-soft); }
    .upload-area {
        border: 2px dashed var(--gold);
        border-radius: 24px;
        padding: 18px;
        text-align: center;
        margin-bottom: 22px;
        cursor: pointer;
        transition: 0.2s;
    }
    .upload-area:hover { border-color: var(--gold-dark); background: var(--gold-soft); transform: scale(1.01); }
    .btn-submit {
        width: 100%;
        background: var(--gold);
        border: none;
        padding: 14px;
        border-radius: 40px;
        font-weight: 800;
        color: var(--navy);
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-submit:hover { background: var(--gold-dark); color: white; transform: scale(1.01); letter-spacing: 0.5px; }

    @media (max-width: 800px) {
        body { padding: 20px; }
        .schedule-grid { flex-direction: column; }
        .cards-gallery { grid-template-columns: 1fr; }
    }
</style>

<div>
@php
    $role = strtolower(auth()->user()->role);
    $backUrl = match($role) {
        'admin' => route('admin.dashboard'),
        'supervisor' => route('supervisor.super'),
        default => route('student.project.index')
    };
    $myProject = null;
    if($role === 'student' && auth()->user()->student) {
        $myProject = \App\Models\Project::where('student_id', auth()->user()->student->id)->first();
    }
@endphp

    <!-- زر الرجوع -->
    <a href="{{ $backUrl }}" class="back-link">← العودة للوحة الرئيسية</a>

    <!-- الهيدر -->
    <div class="header-area">
        <div class="title-section">
            <h1>لوحة <span>الإعلانات</span></h1>
            <p> أحدث الإعلانات والتحديثات الأكاديمية</p>
        </div>
        <div class="stats-row">
            <div class="stat-badge"> {{ $announcements->count() }} إعلان</div>
            <div class="stat-badge"> {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <!-- بطاقة الموعد للطالب -->
    @if($role === 'student' && $myProject && $myProject->discussion_time)
    <div class="schedule-card">
        <div class="schedule-header">
            <div class="schedule-icon">📅</div>
            <div class="schedule-header-text">
                <h3>موعد مناقشتك النهائية</h3>
                <p>تم اعتماد الجدول من قبل اللجنة الأكاديمية</p>
            </div>
        </div>
        <div class="schedule-grid">
            <div class="schedule-item"><div class="schedule-label">المشروع</div><div class="schedule-value">{{ $myProject->title }}</div></div>
            <div class="schedule-item"><div class="schedule-label">التاريخ</div><div class="schedule-value">{{ \Carbon\Carbon::parse($myProject->discussion_time)->format('Y-m-d') }}</div></div>
            <div class="schedule-item"><div class="schedule-label">الوقت</div><div class="schedule-value">{{ \Carbon\Carbon::parse($myProject->discussion_time)->format('h:i A') }}</div></div>
            <div class="schedule-item"><div class="schedule-label">القاعة</div><div class="schedule-value"><span class="room-badge">{{ $myProject->discussion_room }}</span></div></div>
        </div>
    </div>
    @endif

    <!-- معرض الإعلانات -->
    <div class="announcements-area">
        <div class="section-head">
            <h2> الإعلانات الرسمية</h2>
            @if($role === 'admin')
            <div class="admin-actions">
                <a href="{{ route('admin.projects.schedule') }}" class="btn-gold-soft"> جدولة المواعيد</a>
            </div>
            @endif
        </div>

        <div class="cards-gallery">
            @forelse($announcements as $idx => $ann)
            <div class="announce-card" style="--order: {{ $idx }}">
                @if($role === 'admin')
                <form action="{{ route('announcements.destroy', $ann->id) }}" method="POST" class="delete-icon" onsubmit="return confirm('حذف الإعلان؟')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none; border:none; cursor:pointer;">🗑️</button>
                </form>
                @endif
                <div class="card-head">
                    <span class="card-category">إعلان #{{ $idx+1 }}</span>
                    <span class="card-time"> {{ $ann->created_at->diffForHumans() }}</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">{{ $ann->title }}</h3>
                    <p class="card-desc">{{ Str::limit($ann->body, 100) }}</p>
                    @if($ann->attachment)
                    <a href="{{ asset('storage/' . $ann->attachment) }}" target="_blank" class="card-attach">📎 تحميل المرفق</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="announce-card" style="grid-column:1/-1; text-align:center; padding: 60px;">
                <div style="font-size: 3rem; margin-bottom: 15px;">📭</div>
                <p style="color: var(--text-soft);">لا توجد إعلانات حالياً</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- زر إضافة -->
    @if($role === 'admin')
    <div class="fab-gold" onclick="openModal()">+</div>
    @endif

<!-- مودال الإعلانات - مع دعم الخاص والعام -->
<div id="modalBox" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <button class="modal-close" onclick="closeModal()">×</button>
            <div style="font-size: 1.8rem;">🎙️</div>
            <h3 style="margin-top: 8px; color: white;">إعلان جديد</h3>
        </div>
        <div class="modal-body">
            <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- عنوان الإعلان -->
                <input type="text" name="title" class="input-field" placeholder="عنوان الإعلان" required>

                <!-- محتوى الإعلان -->
                <textarea name="body" rows="4" class="input-field" placeholder="محتوى الإعلان" required></textarea>

                <!-- نوع الإعلان -->
               <select name="type" id="announcementType" class="input-field" onchange="toggleProjectField()" required>
    <option value="general">📢 إعلان عام (للجميع)</option>
    <option value="project">📁 إعلان خاص (لمشروع محدد)</option>
</select>

<!-- حقل المشروع (يظهر فقط عند اختيار خاص) -->
<div id="projectField" style="display: none;">
    <select name="project_id" id="projectSelect" class="input-field">
        <option value="">-- اختر المشروع --</option>
        @foreach($projects ?? [] as $project)
            <option value="{{ $project->id }}">
                {{ $project->title }} ({{ $project->student->user->name ?? 'طالب' }})
            </option>
        @endforeach
    </select>
</div>
                <!-- رفع ملف -->
                <div class="upload-area" onclick="document.getElementById('attachFile').click()">
                    <div>☁️</div>
                    <p style="font-size: 0.75rem; margin-top: 5px;">إرفاق ملف (PDF/صورة)</p>
                    <input type="file" name="attachment" id="attachFile" hidden>
                    <div id="fileName" class="text-xs" style="color: var(--gold-dark); margin-top: 5px;"></div>
                </div>

                <button type="submit" class="btn-submit">📨 نشر الإعلان</button>
            </form>
        </div>
    </div>
</div>


<script>

    // ========== دوال المودال ==========
    function openModal() {
        document.getElementById('modalBox').style.display = 'flex';
        // إعادة تعيين الحقول عند فتح المودال
        const typeSelect = document.getElementById('announcementType');
        if(typeSelect) {
            typeSelect.value = 'general';
            toggleProjectField();
        }
        // تنظيف اسم الملف
        const fileName = document.getElementById('fileName');
        if(fileName) fileName.innerHTML = '';
    }

    function closeModal() {
        document.getElementById('modalBox').style.display = 'none';
    }

    // ========== إظهار/إخفاء حقل المشروع ==========
    function toggleProjectField() {
        const type = document.getElementById('announcementType')?.value;
        const projectField = document.getElementById('projectField');
        const projectSelect = document.getElementById('projectSelect');

        if(type === 'project') {
            if(projectField) projectField.style.display = 'block';
            if(projectSelect) projectSelect.setAttribute('required', 'required');
        } else {
            if(projectField) projectField.style.display = 'none';
            if(projectSelect) projectSelect.removeAttribute('required');
        }
    }

    // رفع الملف
    document.getElementById('attachFile')?.addEventListener('change', function(e) {
        if(e.target.files.length) {
            document.getElementById('fileName').innerHTML = '📎 ' + e.target.files[0].name;
        }
    });

    // Socket.io
    const socket = io("http://127.0.0.1:3000");
    const userId = "{{ auth()->id() }}";
    const userRole = "{{ auth()->user()->role }}";
    if(userRole === 'student') socket.on(`schedule-updated-student-${userId}`, () => location.reload());
    </script>
    <script src="{{ asset('js/socket.io.js') }}"></script>
</x-app-layout>
