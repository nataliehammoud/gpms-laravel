<x-app-layout>
    <style>
        .kpi-card {
            background: white;
            border-radius: 20px;
            padding: 18px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        .kpi-value { font-size: 32px; font-weight: 700; color: #1a2a4f; line-height: 1; }
        .kpi-label { font-size: 12px; color: #6c757d; margin-top: 6px; }
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            height: 100%;
        }
        .chart-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a2a4f;
            margin-bottom: 16px;
            border-right: 3px solid #c5a059;
            padding-right: 10px;
        }
        .btn-back {
            background: #1a2a4f;
            color: #c5a059;
            padding: 8px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .btn-back:hover { background: #c5a059; color: #1a2a4f; }
    </style>

    <div style="background: #f8f9fa; min-height: 100vh; padding: 25px;">
        <div style="max-width: 1300px; margin: 0 auto;">

            <!-- رأس الصفحة -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 style="font-size: 22px; font-weight: 700; color: #1a2a4f; margin: 0;">
                        لوحة التحليل والإحصائيات
                    </h2>
                    <p style="color: #6c757d; margin: 4px 0 0; font-size: 13px;">نظرة شاملة على أداء المشاريع</p>
                </div>
                <a href="{{ route('supervisor.super') }}" class="btn-back">
                    ← العودة للمشاريع
                </a>
            </div>

            <!-- كروت الأداء -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="kpi-card">
                        <div class="kpi-value">{{ $chartData['kpis']['total'] }}</div>
                        <div class="kpi-label">إجمالي المشاريع</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="kpi-card">
                        <div class="kpi-value">{{ $chartData['kpis']['pending'] }}</div>
                        <div class="kpi-label">قيد الانتظار</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="kpi-card">
                        <div class="kpi-value">{{ $chartData['kpis']['need_review'] }}</div>
                        <div class="kpi-label">بحاجة مراجعة</div>
                    </div>
                </div>

            </div>

            <!-- الرسوم البيانية -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="chart-card">
                        <div class="chart-title">توزيع المشاريع حسب الجودة</div>
                        <canvas id="qualityChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="chart-card">
                        <div class="chart-title">المشاريع المقبولة والمرفوضة شهرياً</div>
                        <canvas id="monthlyChart" style="max-height: 280px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // الرسم البياني الدائري
            const qualityCtx = document.getElementById('qualityChart').getContext('2d');
            new Chart(qualityCtx, {
                type: 'doughnut',
                data: {
                    labels: ['ضعيف (<50%)', 'متوسط (50-75%)', 'ممتاز (>75%)', 'لم يحلل بعد'],
                    datasets: [{
                        data: [
                            {{ $chartData['quality']['weak'] }},
                            {{ $chartData['quality']['medium'] }},
                            {{ $chartData['quality']['excellent'] }},
                            {{ $chartData['quality']['unanalyzed'] }}
                        ],
                        backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#94a3b8'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
            });

            // الرسم البياني الشريطي
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['monthly']['labels']) !!},
                    datasets: [
                        { label: 'مقبول', data: {!! json_encode($chartData['monthly']['approved']) !!}, backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'مرفوض', data: {!! json_encode($chartData['monthly']['rejected']) !!}, backgroundColor: '#ef4444', borderRadius: 4 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } } }
            });
        });
    </script>
</x-app-layout>
