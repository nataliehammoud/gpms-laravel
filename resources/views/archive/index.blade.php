<x-app-layout>
<style>
    /* ✅ استخدام الخط المحلي بدلاً من Google Fonts */
    @font-face {
        font-family: 'Inter';
        src: url("{{ asset('fonts/Inter-Regular.ttf') }}") format('truetype');
        font-weight: 400;
    }
    @font-face {
        font-family: 'Inter';
        src: url("{{ asset('fonts/Inter-Bold.ttf') }}") format('truetype');
        font-weight: 700;
    }
    @font-face {
        font-family: 'Inter';
        src: url("{{ asset('fonts/Inter-Black.ttf') }}") format('truetype');
        font-weight: 800;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --navy-deep: #0F2B3D;
        --navy-mid: #1A4058;
        --gold: #D4AF37;
        --gold-soft: #E8D5A8;
        --cream: #FEFAF5;
        --cream-dark: #F5EFE3;
    }

    body {
        background: linear-gradient(145deg, var(--cream) 0%, var(--cream-dark) 100%);
        font-family: 'Inter', 'Cairo', sans-serif;
        padding: 30px;
    }
    /* الحاوية الرئيسية */
    .registry-book {
        max-width: 1300px;
        margin: 0 auto;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px -12px rgba(15, 43, 61, 0.12);
        overflow: hidden;
    }

    /* رأس الدفتر */
    .registry-header {
        background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-mid) 100%);
        padding: 22px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .registry-header h1 {
        color: var(--gold-soft);
        font-size: 20px;
        font-weight: 800;
        margin: 0;
        letter-spacing: 1px;
    }
    .registry-stats {
        display: flex;
        gap: 25px;
        color: rgba(232, 213, 168, 0.7);
        font-size: 13px;
    }

    /* ===== البحث الدلالي ===== */
    .semantic-search-wrapper {
        padding: 18px 32px;
        background: #f8f9fa;
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }
    .semantic-search-wrapper input {
        flex: 1;
        min-width: 200px;
        padding: 10px 18px;
        border: 1.5px solid #EDE5D5;
        border-radius: 40px;
        background: white;
        font-size: 13px;
        color: var(--navy-deep);
        outline: none;
        transition: 0.2s;
    }
    .semantic-search-wrapper input:focus {
        border-color: var(--gold);
    }
    .semantic-search-wrapper .btn-semantic {
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        color: var(--gold-soft);
        padding: 10px 28px;
        border-radius: 40px;
        border: none;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    .semantic-search-wrapper .btn-semantic:hover {
        background: var(--gold);
        color: var(--navy-deep);
        transform: translateY(-2px);
    }
    .semantic-results {
        padding: 0 32px 20px;
        display: none;
    }
    .semantic-results.show {
        display: block;
    }
    .semantic-results .result-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 12px 18px;
        margin-bottom: 8px;
        border-right: 4px solid var(--gold);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }
    .semantic-results .result-card .score {
        background: var(--navy-deep);
        color: var(--gold-soft);
        padding: 2px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }
    .semantic-results .result-card .title {
        font-weight: 700;
        color: var(--navy-deep);
    }
    .semantic-results .result-card .meta {
        font-size: 12px;
        color: #6c757d;
    }
    .semantic-results .no-results {
        text-align: center;
        color: #6c757d;
        padding: 20px;
    }

    /* شريط الأدوات */
    .registry-tools {
        padding: 18px 32px;
        background: var(--cream);
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .search-area {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .search-area input, .search-area select {
        padding: 8px 18px;
        border: 1.5px solid #EDE5D5;
        border-radius: 40px;
        background: white;
        font-size: 13px;
        color: var(--navy-deep);
        outline: none;
        transition: 0.2s;
    }
    .search-area input:focus, .search-area select:focus {
        border-color: var(--gold);
    }
    .btn-add {
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        color: var(--gold-soft);
        padding: 8px 24px;
        border-radius: 40px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: 0.2s;
        border: 1px solid rgba(212, 175, 55, 0.3);
    }
    .btn-add:hover {
        background: var(--gold);
        color: var(--navy-deep);
        transform: translateY(-2px);
    }

    /* الجدول */
    .registry-table {
        padding: 0 32px 20px 32px;
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    th {
        text-align: right;
        padding: 16px 12px;
        background: #FCF9F3;
        color: var(--navy-mid);
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(212, 175, 55, 0.2);
    }
    td {
        padding: 14px 12px;
        border-bottom: 1px solid #F0E8DD;
        color: var(--navy-deep);
    }
    tr:hover td {
        background: rgba(212, 175, 55, 0.03);
    }
    .dept-chip {
        background: rgba(212, 175, 55, 0.1);
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        color: var(--gold);
        display: inline-block;
    }
    .admin-actions {
        display: flex;
        gap: 12px;
    }
    .admin-actions a, .admin-actions button {
        background: none;
        border: none;
        color: #9B8E7A;
        cursor: pointer;
        font-size: 14px;
        transition: 0.2s;
    }
    .admin-actions a:hover, .admin-actions button:hover {
        color: #DC2626;
    }

    .registry-pagination {
        padding: 16px 32px 28px;
        border-top: 1px solid rgba(212, 175, 55, 0.1);
    }
    .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        justify-content: center;
        margin: 0;
        padding: 0;
    }
    .pagination .page-item {
        margin: 0;
    }
    .pagination .page-link {
        padding: 5px 11px;
        border-radius: 30px;
        color: var(--navy-mid);
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: 0.2s;
        background: transparent;
        border: 1px solid transparent;
    }
    .pagination .page-link:hover {
        background: rgba(212, 175, 55, 0.1);
        color: var(--gold);
        border-color: rgba(212, 175, 55, 0.3);
    }
    .pagination .active .page-link {
        background: linear-gradient(135deg, var(--navy-deep), var(--navy-mid));
        color: var(--gold-soft);
        border-color: var(--gold);
    }
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 5px 10px;
        font-size: 11px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(212, 175, 55, 0.1);
        padding: 10px 24px;
        border-radius: 40px;
        text-decoration: none;
        color: var(--navy-deep);
        font-size: 13px;
        font-weight: 700;
        transition: 0.2s;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }
    .back-link:hover {
        background: var(--gold);
        color: var(--navy-deep);
        transform: translateX(-3px);
    }

    .back-section {
        padding: 20px 32px;
    }

    @media (max-width: 750px) {
        body { padding: 15px; }
        .registry-header, .registry-tools, .registry-table { padding-left: 20px; padding-right: 20px; }
        .semantic-search-wrapper { padding: 15px 20px; }
        th, td { font-size: 11px; padding: 8px; }
        .back-section { padding: 15px 20px; }
    }
</style>

<div class="registry-book" dir="rtl">

    <div class="registry-header">
        <h1><i class="fas fa-archive"></i> أرشيف المشاريع</h1>
        <div class="registry-stats">
            <span><i class="fas fa-folder-open"></i> {{ $archivedProjects->total() }} مشروع</span>
            <span><i class="fas fa-building"></i> {{ $departments->count() }} قسم</span>
            <span><i class="fas fa-calendar"></i> {{ $archivedProjects->pluck('year')->unique()->count() }} سنة</span>
        </div>
    </div>



    <div class="registry-tools">
        <div class="search-area">
            <input type="text" id="searchReg" placeholder="🔍 بحث في العناوين..." onkeyup="filterRegistry()">
            <select id="deptReg" onchange="filterRegistry()">
                <option value="all">جميع الأقسام</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}">{{ $dept }}</option>
                @endforeach
            </select>
            <select id="yearReg" onchange="filterRegistry()">
                <option value="all">جميع السنوات</option>
                @for($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        @if($userRole === 'admin')
            <a href="{{ route('admin.archive.create') }}" class="btn-add"><i class="fas fa-plus"></i> إضافة مشروع</a>
        @endif
    </div>

    <div class="registry-table">
        <table>
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الطلاب</th>
                    <th>المشرف</th>
                    <th>السنة</th>
                    <th>القسم</th>
                    @if($userRole === 'admin')<th style="width: 70px;"></th>@endif
                </tr>
            </thead>
            <tbody id="registryBody">
                @foreach($archivedProjects as $project)
                <tr class="registry-row"
                    data-title="{{ strtolower($project->title) }}"
                    data-dept="{{ $project->department }}"
                    data-year="{{ $project->year }}">
                    <td style="font-weight: 700;">{{ $project->title }}</td>
                    <td>{{ $project->students_names }}</td>
                    <td>{{ $project->supervisor }}</td>
                    <td>{{ $project->year }}</td>
                    <td><span class="dept-chip">{{ $project->department }}</span></td>
                    @if($userRole === 'admin')
                    <td class="admin-actions">
                        <a href="{{ route('admin.archive.edit', $project->id) }}"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('admin.archive.destroy', $project->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('حذف المشروع؟')"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="registry-pagination">
        <div style="display: flex; gap: 4px; justify-content: center;">
            @if ($archivedProjects->onFirstPage())
                <span style="padding: 4px 8px; font-size: 11px; color: #ccc;">‹</span>
            @else
                <a href="{{ $archivedProjects->previousPageUrl() }}" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: var(--gold); border-radius: 20px;">‹</a>
            @endif

            @foreach(range(1, $archivedProjects->lastPage()) as $page)
                @if($page == $archivedProjects->currentPage())
                    <span style="padding: 4px 8px; font-size: 11px; background: var(--navy-deep); color: var(--gold-soft); border-radius: 20px;">{{ $page }}</span>
                @else
                    <a href="{{ $archivedProjects->url($page) }}" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: var(--navy-mid); border-radius: 20px;">{{ $page }}</a>
                @endif
            @endforeach

            @if ($archivedProjects->hasMorePages())
                <a href="{{ $archivedProjects->nextPageUrl() }}" style="padding: 4px 8px; font-size: 11px; text-decoration: none; color: var(--gold); border-radius: 20px;">›</a>
            @else
                <span style="padding: 4px 8px; font-size: 11px; color: #ccc;">›</span>
            @endif
        </div>
    </div>

    {{-- زر العودة (يختلف حسب الدور) --}}
    <div class="back-section">
        @php
            $role = auth()->user()->role;
            $backRoute = match($role) {
                'admin' => route('admin.dashboard'),
                'supervisor' => route('supervisor.super'),
                default => route('student.project.index')
            };
        @endphp
        <a href="{{ $backRoute }}" class="back-link">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>
</div>

<script>
    // ===== التصفية العادية (بالكلمات) =====
    function filterRegistry() {
        const search = document.getElementById('searchReg').value.toLowerCase();
        const dept = document.getElementById('deptReg').value;
        const year = document.getElementById('yearReg').value;

        document.querySelectorAll('.registry-row').forEach(row => {
            const title = row.dataset.title;
            const rowDept = row.dataset.dept;
            const rowYear = row.dataset.year;
            const match = title.includes(search) &&
                        (dept === 'all' || rowDept === dept) &&
                        (year === 'all' || rowYear == year);
            row.style.display = match ? '' : 'none';
        });
    }
</script>
</x-app-layout>
