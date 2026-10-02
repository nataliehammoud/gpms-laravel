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

    .main-content {
        padding: 25px 30px;
        transition: all 0.3s ease;
    }

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

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    @media (max-width: 768px) {
        .metric-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .metric-grid { grid-template-columns: 1fr; }
    }

    .metric-card {
        background: var(--cream-card);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid rgba(212, 175, 55, 0.1);
        text-align: center;
    }
    .metric-card .number {
        font-size: 32px;
        font-weight: 700;
        color: var(--navy-deep);
    }
    .metric-card .label {
        font-size: 13px;
        color: #8B9AAB;
        font-weight: 500;
        margin-top: 5px;
    }
    .metric-card.primary .number { color: #0F2B3D; }
    .metric-card.success .number { color: #16a34a; }
    .metric-card.warning .number { color: #ca8a04; }
    .metric-card.danger .number { color: #dc2626; }

    .chart-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    @media (max-width: 768px) {
        .chart-row { grid-template-columns: 1fr; }
    }

    .chart-box {
        background: var(--cream-card);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(212, 175, 55, 0.1);
    }
    .chart-box .chart-title {
        font-weight: 700;
        font-size: 14px;
        color: var(--navy-deep);
        margin-bottom: 15px;
        border-right: 3px solid #c5a059;
        padding-right: 10px;
    }
    .chart-box canvas {
        max-height: 280px;
        max-width: 100%;
    }

    .table-wrap {
        overflow-x: auto;
        background: var(--cream-card);
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(212, 175, 55, 0.1);
        margin-top: 10px;
    }
    .table-wrap table {
        width: 100%;
        border-collapse: collapse;
    }
    .table-wrap th {
        text-align: right;
        padding: 12px 8px;
        background: #f8f6f2;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-mid);
        border-bottom: 1px solid #e2e8f0;
    }
    .table-wrap td {
        padding: 10px 8px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f0ece4;
    }
    .table-wrap tr:hover td {
        background: var(--gold-glow);
    }
    .badge-progress {
        background: var(--gold-glow);
        padding: 2px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        color: var(--navy-mid);
    }
    .progress-bar-custom {
        height: 8px;
        background: #f0ece4;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 5px;
    }
    .progress-bar-custom .fill {
        height: 100%;
        background: var(--gold);
        border-radius: 10px;
        transition: width 0.5s;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 15px;
        color: var(--navy-mid);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }
    .back-link:hover {
        color: var(--gold);
    }

    .analysis-note {
        background: #fffbeb;
        border-right: 4px solid var(--gold);
        padding: 15px 20px;
        border-radius: 12px;
        margin-top: 15px;
        font-size: 14px;
        color: #78350f;
    }
    .analysis-note strong {
        color: #92400e;
    }
</style>

<div class="main-content">
    {{-- رابط العودة --}}
    <a href="{{ route('admin.dashboard') }}" class="back-link">← العودة إلى لوحة التحكم</a>

    {{-- الهيدر --}}
    <div class="simple-header">
        <div>
            <span class="logo">GP<span>MS</span></span>
            <span class="role-tag">📊 إحصائيات الأقسام</span>
        </div>
        <div class="admin-name">{{ Auth::user()->name }}</div>
    </div>
{{-- كروت إحصائيات الأرشيف فقط --}}
<div class="metric-grid">
    <div class="metric-card primary">
        <div class="number">{{ $totalArchived }}</div>
        <div class="label"> إجمالي المشاريع المؤرشفة</div>
    </div>
    <div class="metric-card success">
        <div class="number">{{ $departmentsCount }}</div>
        <div class="label"> عدد الأقسام</div>
    </div>
    <div class="metric-card warning">
        <div class="number">{{ $topDepartment }}</div>
        <div class="label"> القسم الأكثر تكراراً</div>
    </div>
    <div class="metric-card danger">
        <div class="number">{{ $bottomDepartment }}</div>
        <div class="label"> القسم الأقل تكراراً</div>
    </div>
</div>

    {{-- المخططات البيانية --}}
    <div class="chart-row">
        <div class="chart-box">
            <div class="chart-title"> توزيع المشاريع (نسبة مئوية)</div>
            <canvas id="departmentChart"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-title"> عدد المشاريع الفعلي في كل قسم</div>
            <canvas id="barChart"></canvas>
        </div>
    </div>

    {{-- الجدول التفصيلي --}}
    <div class="table-wrap">
        <h5 style="font-weight:700; color:var(--navy-deep); margin-bottom:15px;"> تفاصيل إحصائية حسب القسم</h5>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم القسم</th>
                    <th>عدد المشاريع</th>
                    <th>النسبة المئوية</th>
                    <th>التوزيع</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $total = array_sum($chartData); 
                @endphp
                @foreach($chartLabels as $index => $label)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $chartData[$index] }}</td>
                    <td>
                        @if($total > 0)
                            <span class="badge-progress">{{ round(($chartData[$index] / $total) * 100, 2) }}%</span>
                        @else
                            <span class="badge-progress">0%</span>
                        @endif
                    </td>
                    <td>
                        <div style="min-width:100px;">
                            @php $percent = ($total > 0) ? ($chartData[$index] / $total) * 100 : 0; @endphp
                            <div class="progress-bar-custom">
                                <div class="fill" style="width: {{ $percent }}%;"></div>
                            </div>
                            <span style="font-size:11px; color:#8B9AAB;">{{ round($percent, 1) }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight:bold; background:#f8f6f2;">
                <tr>
                    <td colspan="2" style="text-align:center;">المجموع الكلي</td>
                    <td>{{ $total }}</td>
                    <td colspan="2">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- ملاحظة تحليلية مهمة (ارتباط بمشكلة الشبكة العصبية) --}}
    <div class="analysis-note">
        <strong> ملاحظة تحليلية:</strong> 
        إذا كان أحد الأقسام (مثل "اتصالات") يسيطر على نسبة كبيرة من المشاريع المؤرشفة، فهذا يفسر سبب تحيز نموذج 
        <strong>الشبكة العصبية (Perceptron)</strong> نحو تصنيف جميع المشاريع الجديدة ضمن هذا القسم (ظاهرة الـ 
        <strong>Imbalanced Dataset</strong>). يُوصى في التطويرات المستقبلية بإعادة تدريب النموذج على بيانات متوازنة.
    </div>
</div>

{{-- ====== السكربتات في الأسفل مباشرة (نفس أسلوب المشرف) ====== --}}
<script src="{{ asset('js/chart.js') }}"></script>
{{-- إذا كان اسم ملف Chart.js مختلفاً عندك (مثل chart.min.js)، غيري المسار أعلاه --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // البيانات القادمة من الـ Controller
        const labels = @json($chartLabels);
        const data = @json($chartData);
        const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'];

        // إذا كانت البيانات فاضية، نعرض رسالة بدلاً من الرسم
        if (data.length === 0) {
            document.getElementById('departmentChart').parentElement.innerHTML = 
                '<p style="text-align:center;color:#8B9AAB;padding:40px 0;">📭 لا توجد مشاريع مؤرشفة لعرض الإحصائيات.</p>';
            document.getElementById('barChart').parentElement.innerHTML = 
                '<p style="text-align:center;color:#8B9AAB;padding:40px 0;">📭 لا توجد بيانات كافية.</p>';
            return;
        }

        // 1. المخطط الدائري (Doughnut)
        const ctx1 = document.getElementById('departmentChart').getContext('2d');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12, weight: 'bold' } }
                    }
                }
            }
        });

        // 2. المخطط الشريطي (Bar)
        const ctx2 = document.getElementById('barChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد المشاريع',
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
</x-app-layout>