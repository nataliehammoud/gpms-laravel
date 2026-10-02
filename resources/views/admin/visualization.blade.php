<x-app-layout>
    <div class="container py-4" dir="rtl">
        {{-- عنوان الصفحة --}}
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2" style="border-bottom: 2px solid #D4AF37;">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0F2B3D;">
                    <i class="fas fa-chart-scatter" style="color: #D4AF37;"></i> خريطة توزيع المشاريع (PCA + K-Means)
                </h3>
                <p class="small mb-0" style="color: #8B9AAB;">
                    تصور بصري لتوزيع المشاريع المؤرشفة حسب التشابه الموضوعي مع تجميعها في مجموعات متقاربة.
                </p>
            </div>
            <div>
                <button onclick="fetchData()" class="btn btn-sm rounded-pill px-3" style="background: #D4AF37; color: #0F2B3D; border: none;">
                    <i class="fas fa-sync-alt me-1"></i> تحديث
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm rounded-pill px-3" style="background: #0F2B3D; color: #E8D5A8; border: none;">
                    <i class="fas fa-arrow-right me-1"></i> العودة
                </a>
            </div>
        </div>

        {{-- رسائل التنبيه --}}
        <div id="messageArea"></div>

        {{-- بطاقة الرسم البياني --}}
        <div class="card rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.2); background: white;">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-12">
                        <div style="position: relative; height: 500px; width: 100%;">
                            <canvas id="clusterChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 text-center small" style="color: #8B9AAB;">
                        <i class="fas fa-info-circle me-1"></i>
                        النقاط هي المشاريع، وكل لون يمثل مجموعة موضوعية متقاربة (K-Means).
                    </div>
                </div>
            </div>
        </div>

        {{-- إحصائيات سريعة عن المجموعات --}}
        <div class="row mt-4 g-3" id="clusterStats" style="display: none;">
            <div class="col-12">
                <div class="card rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.2); background: white;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-2" style="color: #0F2B3D;">
                            <i class="fas fa-layer-group" style="color: #D4AF37;"></i> توزيع المجموعات
                        </h6>
                        <div id="statsContent" class="row g-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cluster-legend {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 6px;
        }
        .cluster-badge {
            font-size: 12px;
            font-weight: 600;
        }
    </style>

    {{-- استيراد Chart.js محلياً --}}
    <script src="{{ asset('js/chart.min.js') }}"></script>

    <script>
        let chartInstance = null;

        const clusterColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
            '#C9CBCF', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'
        ];

        function showMessage(message, type = 'info') {
            const area = document.getElementById('messageArea');
            const colors = {
                success: 'background: #E8D5A8; color: #0F2B3D; border: none;',
                error: 'background: #FEFAF5; color: #DC2626; border-right: 3px solid #DC2626;',
                info: 'background: #E8F4FD; color: #0F2B3D; border-right: 3px solid #D4AF37;'
            };
            area.innerHTML = `
                <div class="alert alert-dismissible fade show rounded-3 py-2" role="alert" style="${colors[type] || colors.info}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-1"></i>
                    ${message}
                    <button type="button" class="btn-close py-0" data-bs-dismiss="alert" onclick="this.parentElement.remove()"></button>
                </div>
            `;
        }

        async function fetchData() {
            try {
                showMessage('⏳ جاري تحليل البيانات...', 'info');
                const response = await fetch('{{ route("admin.visualization.data") }}');
                const result = await response.json();

                if (!result.success) {
                    showMessage('❌ ' + (result.message || 'فشل في تحميل البيانات'), 'error');
                    return;
                }

                if (!result.data || result.data.length === 0) {
                    showMessage('⚠️ لا توجد بيانات كافية لعرض الخريطة. يلزم على الأقل 3 مشاريع في الأرشيف.', 'error');
                    return;
                }

                renderChart(result.data);
                renderStats(result.data);
                showMessage('✅ تم تحميل ' + result.data.length + ' مشروع بنجاح', 'success');

            } catch (error) {
                console.error(error);
                showMessage('❌ خطأ في الاتصال: ' + error.message, 'error');
            }
        }

        function renderChart(data) {
            const ctx = document.getElementById('clusterChart').getContext('2d');

            const clusters = {};
            data.forEach(item => {
                const cluster = item.cluster !== undefined ? item.cluster : 0;
                if (!clusters[cluster]) clusters[cluster] = [];
                clusters[cluster].push({
                    x: item.x,
                    y: item.y,
                    title: item.title,
                    department: item.department
                });
            });

            const datasets = Object.keys(clusters).map((key, index) => {
                const color = clusterColors[index % clusterColors.length];
                return {
                    label: `مجموعة ${parseInt(key) + 1}`,
                    data: clusters[key].map(p => ({ x: p.x, y: p.y })),
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 0,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    _metaData: clusters[key]
                };
            });

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'scatter',
                data: { datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 12, weight: 'bold' },
                                color: '#0F2B3D',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const dataset = context.dataset;
                                    if (dataset._metaData && dataset._metaData[index]) {
                                        const item = dataset._metaData[index];
                                        return `${item.title} (${item.department})`;
                                    }
                                    return context.parsed.x + ', ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'المكون الرئيسي الأول (PCA1)', color: '#8B9AAB' },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { color: '#8B9AAB' }
                        },
                        y: {
                            title: { display: true, text: 'المكون الرئيسي الثاني (PCA2)', color: '#8B9AAB' },
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { color: '#8B9AAB' }
                        }
                    }
                }
            });

            chartInstance.update();
        }

        function renderStats(data) {
            const statsDiv = document.getElementById('clusterStats');
            const contentDiv = document.getElementById('statsContent');

            const clusterCounts = {};
            data.forEach(item => {
                const cluster = item.cluster !== undefined ? item.cluster : 0;
                clusterCounts[cluster] = (clusterCounts[cluster] || 0) + 1;
            });

            let html = '';
            const total = data.length;
            Object.keys(clusterCounts).forEach((key, index) => {
                const count = clusterCounts[key];
                const percent = ((count / total) * 100).toFixed(1);
                const color = clusterColors[index % clusterColors.length];
                html += `
                    <div class="col-md-3 col-sm-6">
                        <div class="d-flex align-items-center p-2 rounded-2" style="background: #FEFAF5; border: 1px solid rgba(212, 175, 55, 0.1);">
                            <span class="cluster-legend" style="background: ${color};"></span>
                            <span class="cluster-badge me-1">مجموعة ${parseInt(key) + 1}</span>
                            <span class="text-muted small">${count} مشروع (${percent}%)</span>
                        </div>
                    </div>
                `;
            });

            contentDiv.innerHTML = html;
            statsDiv.style.display = 'block';
        }

        document.addEventListener('DOMContentLoaded', fetchData);
    </script>
</x-app-layout>