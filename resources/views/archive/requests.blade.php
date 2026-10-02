<x-app-layout>
    <style>
        /* تنسيق أكاديمي نظيف */
        .archive-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
            font-family: 'Segoe UI', 'Cairo', sans-serif;
        }

        .archive-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .archive-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 0.5px;
        }

        .archive-title i {
            color: #3b82f6;
            margin-left: 10px;
        }

        .back-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* بطاقة الطلب */
        .request-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 22px 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            transition: border-color 0.2s;
        }

        .request-card:hover {
            border-color: #94a3b8;
        }

        .request-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .project-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .project-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            font-size: 0.9rem;
            color: #475569;
            margin: 8px 0 12px 0;
        }

        .project-meta i {
            color: #3b82f6;
            width: 18px;
            margin-left: 4px;
        }

        .team-badge {
            background: #f1f5f9;
            padding: 4px 14px;
            border-radius: 30px;
            font-size: 0.85rem;
            color: #334155;
            display: inline-block;
        }

        .status-badge {
            padding: 4px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #facc15;
            white-space: nowrap;
        }

        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-approve {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-reject:hover {
            background: #fecaca;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #cbd5e1;
        }

        .empty-state h3 {
            font-weight: 500;
            color: #64748b;
            margin-bottom: 5px;
        }

        .total-count {
            text-align: center;
            margin-top: 30px;
            font-size: 0.95rem;
            color: #475569;
        }

        .total-count strong {
            color: #0f172a;
        }

        /* استجابة للشاشات الصغيرة */
        @media (max-width: 640px) {
            .archive-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .project-meta {
                gap: 10px;
                font-size: 0.8rem;
            }
            .action-group {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-approve, .btn-reject {
                text-align: center;
            }
        }
    </style>

    <div class="archive-container">
        {{-- رأس الصفحة --}}
        <div class="archive-header">
            <h1 class="archive-title">
                <i class="fas fa-archive"></i> طلبات الأرشفة المعلقة
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> العودة
            </a>
        </div>

        {{-- قائمة الطلبات --}}
        @forelse($requests as $project)
            <div class="request-card">
                <div class="request-header">
                    <div>
                        <div class="project-title">{{ $project->title }}</div>
                        <div class="project-meta">
                            <span><i class="fas fa-user-tie"></i> المشرف: {{ $project->supervisor->name ?? 'غير محدد' }}</span>
                            <span><i class="fas fa-user-graduate"></i> الطالب: {{ $project->student->user->name ?? 'غير محدد' }}</span>
                            <span><i class="fas fa-calendar-alt"></i> تاريخ الطلب: {{ $project->updated_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="team-badge">
                            <i class="fas fa-users"></i> الفريق:
                            @forelse($project->users as $member)
                                {{ $member->name }}@if(!$loop->last), @endif
                            @empty
                                لا يوجد أعضاء إضافيون
                            @endforelse
                        </div>
                    </div>
                    <span class="status-badge">⏳ بانتظار الموافقة</span>
                </div>

                {{-- أزرار الإجراء --}}
                <div class="action-group">
                    <form action="{{ route('admin.archive.approve', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-approve" onclick="return confirm('تأكيد أرشفة المشروع؟')">
                            <i class="fas fa-check"></i> قبول الأرشفة
                        </button>
                    </form>
                    <form action="{{ route('admin.archive.reject', $project->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-reject" onclick="return confirm('تأكيد رفض طلب الأرشفة؟')">
                            <i class="fas fa-times"></i> رفض
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>لا توجد طلبات أرشفة معلقة</h3>
                <p style="color: #94a3b8;">جميع الطلبات تمت معالجتها</p>
            </div>
        @endforelse

        @if($requests->count() > 0)
            <div class="total-count">
                إجمالي الطلبات المعلقة: <strong>{{ $requests->count() }}</strong>
            </div>
        @endif
    </div>
</x-app-layout>
