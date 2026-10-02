<x-app-layout>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
        --cream-bg: #FEFAF5;
        --cream-card: #FFFFFF;
        --navy-deep: #0F2B3D;
        --navy-mid: #1A4058;
        --navy-light: #2B5A7A;
        --gold: #D4AF37;
        --gold-soft: #E8D5A8;
        --gold-glow: rgba(212, 175, 55, 0.08);
        --shadow: 0 8px 20px -6px rgba(0,0,0,0.05);
        --shadow-lg: 0 20px 30px -12px rgba(0,0,0,0.1);
        --shadow-gold: 0 12px 25px -10px rgba(212, 175, 55, 0.15);
        --text-soft: #8B9AAB;
        --danger: #EF4444;
        --danger-glow: rgba(239, 68, 68, 0.08);
    }

    body {
        background: linear-gradient(145deg, var(--cream-bg) 0%, #F5EFE3 100%);
        font-family: 'Cairo', sans-serif;
        padding: 25px 30px 50px;
        min-height: 100vh;
    }

    .users-container { max-width: 1400px; margin: 0 auto; }

    /* ========== هيدر ========== */
    .top-header {
        background: var(--cream-card);
        border-radius: 60px;
        padding: 12px 30px;
        margin-bottom: 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border: 1px solid rgba(212, 175, 55, 0.12);
        box-shadow: var(--shadow);
    }
    .logo-area { display: flex; align-items: baseline; gap: 12px; }
    .logo { font-size: 26px; font-weight: 800; background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid)); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .logo span { background: linear-gradient(135deg, var(--gold), #B8942E); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .stats-badge {
        background: var(--gold-glow);
        padding: 6px 18px;
        border-radius: 40px;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy-mid);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: var(--cream-bg);
        border: 1px solid rgba(212, 175, 55, 0.15);
        border-radius: 50px;
        padding: 8px 18px;
        gap: 10px;
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        width: 200px;
    }
    .add-btn {
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        border: none;
        padding: 10px 28px;
        border-radius: 50px;
        color: var(--gold-soft);
        font-weight: 700;
        cursor: pointer;
        transition: 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .add-btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(15, 43, 61, 0.2); }

    /* ========== مودال الإضافة ========== */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 43, 61, 0.95);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; animation: fadeIn 0.25s ease; }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .modal-container {
        background: var(--cream-card);
        border-radius: 32px;
        width: 90%;
        max-width: 550px;
        overflow: hidden;
        animation: slideUp 0.3s ease;
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-header {
        padding: 20px 28px;
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        color: var(--gold-soft);
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        transition: 0.2s;
    }
    .modal-close:hover { transform: scale(1.1); }
    .modal-body { padding: 28px; }
    .form-group { margin-bottom: 20px; }
    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--navy-mid);
        margin-bottom: 8px;
    }
    .form-group input, .form-group select {
        width: 100%;
        padding: 12px 16px;
        background: var(--cream-bg);
        border: 1.5px solid #F0E6D5;
        border-radius: 18px;
        font-size: 14px;
        transition: 0.2s;
    }
    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: var(--gold);
        background: white;
    }
    .extra-fields {
        background: var(--gold-glow);
        border-radius: 20px;
        padding: 20px;
        margin-top: 15px;
        display: none;
    }
    .extra-fields.show { display: block; animation: fadeIn 0.3s ease; }
    .btn-submit {
        background: linear-gradient(135deg, var(--gold), #B8942E);
        border: none;
        padding: 14px 20px;
        border-radius: 50px;
        color: var(--navy-deep);
        font-weight: 800;
        cursor: pointer;
        transition: 0.25s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        justify-content: center;
        font-size: 15px;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: var(--shadow-gold); }

    /* ========== شبكة البطاقات ========== */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }
    .user-card {
        background: var(--cream-card);
        border-radius: 28px;
        padding: 22px;
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
    }
    .user-card:hover {
        border-color: var(--gold);
        box-shadow: var(--shadow-gold);
        transform: translateY(-3px);
    }
    .user-avatar {
        width: 65px;
        height: 65px;
        background: var(--gold-glow);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }
    .user-info { flex: 1; }
    .user-name {
        font-size: 16px;
        font-weight: 800;
        color: var(--navy-deep);
        margin-bottom: 4px;
    }
    .user-email {
        font-size: 11px;
        color: var(--text-soft);
        margin-bottom: 8px;
        word-break: break-all;
    }
    .user-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 700;
    }
    .role-supervisor {
        background: rgba(212, 175, 55, 0.12);
        color: var(--gold);
    }
    .role-student {
        background: rgba(139, 154, 171, 0.12);
        color: var(--text-soft);
    }
    .role-admin {
        background: rgba(15, 43, 61, 0.12);
        color: var(--navy-deep);
    }
    .delete-btn {
        background: var(--danger-glow);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;
        color: var(--danger);
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .delete-btn:hover {
        background: var(--danger);
        color: white;
        transform: scale(1.05);
    }

    .alert-success {
        background: var(--gold-glow);
        border-right: 3px solid var(--gold);
        padding: 12px 20px;
        border-radius: 16px;
        margin-bottom: 25px;
        color: var(--navy-mid);
        font-weight: 600;
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .back-btn {
        position: fixed;
        bottom: 25px;
        left: 25px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s;
        z-index: 100;
        box-shadow: var(--shadow);
    }
    .back-btn:hover { transform: scale(1.1); background: var(--gold); }
    .back-btn i { color: var(--gold-soft); font-size: 20px; }
    .back-btn:hover i { color: var(--navy-deep); }

    @media (max-width: 700px) {
        body { padding: 15px; }
        .top-header { flex-direction: column; text-align: center; }
        .search-box input { width: 150px; }
        .users-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="users-container" dir="rtl">

    <!-- الهيدر -->
    <div class="top-header">
        <div class="logo-area">
            <div class="logo">GP<span>MS</span></div>
            <div class="stats-badge">
                <i class="fas fa-users"></i> إجمالي المستخدمين: {{ $users->count() }}
            </div>
        </div>
        <div style="display: flex; gap: 15px; align-items: center;">
            <div class="search-box">
                <i class="fas fa-search" style="color: var(--text-soft);"></i>
                <input type="text" id="searchInput" placeholder="بحث بالاسم أو البريد..." onkeyup="searchUsers()">
            </div>
            <button class="add-btn" onclick="openModal()">
                <i class="fas fa-plus"></i> إضافة مستخدم جديد
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- شبكة المستخدمين -->
    <div class="users-grid" id="usersGrid">
        @foreach($users as $user)
        @php
            $role = strtolower(trim($user->role));
            $roleClass = match($role) {
                'supervisor' => 'role-supervisor',
                'student' => 'role-student',
                default => 'role-admin'
            };
            $roleName = match($role) {
                'supervisor' => 'مشرف أكاديمي',
                'student' => 'طالب',
                'admin' => 'مدير النظام'
            };
            $avatarIcon = match($role) {
                'supervisor' => '<i class="fas fa-chalkboard-user" style="color: var(--gold);"></i>',
                'student' => '<i class="fas fa-graduation-cap" style="color: var(--text-soft);"></i>',
                default => '<i class="fas fa-user-shield" style="color: var(--navy-deep);"></i>'
            };
        @endphp
        <div class="user-card" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
            <div class="user-avatar">
                {!! $avatarIcon !!}
            </div>
            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
                <span class="user-role {{ $roleClass }}">{{ $roleName }}</span>
            </div>
            @if(auth()->id() !== $user->id && $role !== 'admin')
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>

    <!-- زر العودة -->
    <a href="{{ route('admin.dashboard') }}" class="back-btn">
        <i class="fas fa-arrow-right"></i>
    </a>

    <!-- مودال إضافة مستخدم -->
    <div id="userModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> إضافة مستخدم جديد</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> الاسم الكامل</label>
                        <input type="text" name="name" required placeholder="أدخل الاسم الكامل">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                        <input type="email" name="email" required placeholder="example@domain.com">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> كلمة المرور</label>
                        <input type="password" name="password" required placeholder="********">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-tag"></i> نوع المستخدم</label>
                        <select name="role" id="roleSelect" onchange="toggleExtraFields()">
                            <option value="student">طالب</option>
                            <option value="supervisor">مشرف أكاديمي</option>
                            @if(auth()->user()->role === 'admin')
                            <option value="admin">مدير نظام (أدمن)</option>
                            @endif
                        </select>
                    </div>

                    <!-- حقول إضافية للمشرف -->
                    <div id="supervisorFields" class="extra-fields">
                        <h4 style="font-size: 14px; font-weight: 800; color: var(--navy-deep); margin-bottom: 15px;">
                            <i class="fas fa-chalkboard-user"></i> بيانات المشرف الأكاديمي
                        </h4>
                        <div class="form-group">
                            <label><i class="fas fa-building"></i> القسم / التخصص</label>
                            <select name="specialization" id="specialization">
                                <option value="">اختر القسم</option>
                                <option value="Information Systems">نظم المعلومات</option>
                                <option value="Computer Systems">نظم الحاسوب</option>
                                <option value="Communications">اتصالات</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-door-open"></i> رقم المكتب</label>
                            <input type="text" name="office" placeholder="مثال: B201">
                        </div>
                    </div>

                    <!-- حقول إضافية للطالب -->
                    <div id="studentFields" class="extra-fields">
                        <h4 style="font-size: 14px; font-weight: 800; color: var(--navy-deep); margin-bottom: 15px;">
                            <i class="fas fa-graduation-cap"></i> بيانات الطالب
                        </h4>
                        <div class="form-group">
                            <label><i class="fas fa-building"></i> القسم / التخصص</label>
                            <select name="department" id="department">
                                <option value="">اختر القسم</option>
                                <option value="Information Systems">نظم المعلومات</option>
                                <option value="Computer Systems">نظم الحاسوب</option>
                                <option value="Communications">اتصالات</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> سنة التخرج المتوقعة</label>
                            <input type="number" name="graduation_year" placeholder="مثال: 2025">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> إضافة المستخدم
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('userModal').classList.add('show');
    }
    function closeModal() {
        document.getElementById('userModal').classList.remove('show');
        document.getElementById('userForm').reset();
        document.getElementById('supervisorFields').classList.remove('show');
        document.getElementById('studentFields').classList.remove('show');
    }

    function toggleExtraFields() {
        const role = document.getElementById('roleSelect').value;
        const supFields = document.getElementById('supervisorFields');
        const stuFields = document.getElementById('studentFields');

        if(role === 'supervisor') {
            supFields.classList.add('show');
            stuFields.classList.remove('show');
        } else if(role === 'student') {
            supFields.classList.remove('show');
            stuFields.classList.add('show');
        } else {
            supFields.classList.remove('show');
            stuFields.classList.remove('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleExtraFields();
    });

    function searchUsers() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.user-card');

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const email = card.dataset.email || '';
            if(name.includes(searchTerm) || email.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    document.getElementById('userModal').addEventListener('click', function(e) {
        if(e.target === this) closeModal();
    });
</script>

<!-- أيقونات Font Awesome محلية -->
<link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/solid.min.css') }}">
</x-app-layout>
