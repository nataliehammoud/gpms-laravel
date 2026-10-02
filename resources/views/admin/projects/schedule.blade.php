<x-app-layout>
    <div class="container py-4" dir="rtl">
        <!-- عنوان الصفحة -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2" style="border-bottom: 2px solid #D4AF37;">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0F2B3D;">
                    <i class="fas fa-calendar-alt" style="color: #D4AF37;"></i> جدول المناقشات
                </h3>
                <p class="small mb-0" style="color: #8B9AAB;">إدارة مواعيد مناقشات المشاريع</p>
            </div>
            <div class="d-flex gap-2">
                {{-- زر الجدولة التلقائية (A*) --}}
                <form action="{{ route('admin.schedule.auto') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-pill px-3" style="background: #D4AF37; color: #0F2B3D; border: none;">
                        <i class="fas fa-magic me-1"></i> جدولة تلقائية (A*)
                    </button>
                </form>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-sm rounded-pill px-3" style="background: #0F2B3D; color: #E8D5A8; border: none;">
                    <i class="fas fa-arrow-right me-1"></i> العودة
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 py-2" role="alert" style="background: #E8D5A8; color: #0F2B3D; border: none;">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close py-0" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2" role="alert" style="background: #FEFAF5; color: #DC2626; border-right: 3px solid #DC2626;">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close py-0" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 py-2" role="alert" style="background: #E8F4FD; color: #0F2B3D; border-right: 3px solid #D4AF37;">
                <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                <button type="button" class="btn-close py-0" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- عرض نتائج الجدولة التلقائية المقترحة --}}
        @if(session('auto_schedule_result'))
        <div class="card mb-4 rounded-3" style="border: 2px solid #D4AF37; background: #FEFAF5;">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0" style="color: #0F2B3D;">
                    <i class="fas fa-star" style="color: #D4AF37;"></i> الجدول المقترح (A*)
                </h5>
                <span class="badge rounded-pill" style="background: #D4AF37; color: #0F2B3D;">تم بواسطة الذكاء الاصطناعي</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="min-width: 500px;">
                        <thead style="background: #FEFAF5;">
                            <tr>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">المشروع</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">الطالب</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">المشرف</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">التاريخ</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">القاعة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(session('auto_schedule_result') as $projectId => $details)
                            <tr>
                                <td class="py-2 fw-semibold" style="color: #0F2B3D;">{{ $details['title'] }}</td>
                                <td class="py-2" style="color: #4B6B8A;">{{ $details['student'] }}</td>
                                <td class="py-2" style="color: #4B6B8A;">{{ $details['supervisor'] }}</td>
                                <td class="py-2" style="color: #4B6B8A; direction: ltr;">{{ \Carbon\Carbon::parse($details['time'])->format('Y-m-d h:i A') }}</td>
                                <td class="py-2"><span class="badge rounded-pill" style="background: #E8D5A8; color: #0F2B3D;">{{ $details['room'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 d-flex justify-content-end gap-2">
                <form action="{{ route('admin.schedule.apply') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: #0F2B3D; color: #E8D5A8; border: none;">
                        <i class="fas fa-check-circle me-1"></i> تطبيق الجدولة
                    </button>
                </form>
                <a href="{{ route('admin.projects.schedule') }}" class="btn btn-sm rounded-pill px-4" style="background: transparent; color: #8B9AAB; border: 1px solid #8B9AAB;">
                    إلغاء
                </a>
            </div>
        </div>
        @endif

        <!-- المشاريع المجدولة -->
        <div class="card mb-4 rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.2); background: white;">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h5 class="fw-bold mb-0" style="color: #0F2B3D;">
                    <i class="fas fa-check-circle me-1" style="color: #10B981;"></i> المشاريع المجدولة
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="min-width: 500px;">
                        <thead style="background: #FEFAF5;">
                            <tr>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">المشروع</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">الطالب</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">المشرف</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">التاريخ</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;">القاعة</th>
                                <th class="py-2 small fw-semibold" style="color: #1A4058;"></th>
                            </tr>
                        </thead>
                        <tbody>
@forelse($projects->whereNotNull('discussion_time')->where('status', 'approved') as $project)                            <tr>
                                <td class="py-2 fw-semibold" style="color: #0F2B3D;">{{ $project->title }}</td>
                                <td class="py-2" style="color: #4B6B8A;">{{ $project->student->user->name ?? 'غير محدد' }}</td>
                                <td class="py-2" style="color: #4B6B8A;">{{ $project->supervisor->name ?? 'غير محدد' }}</td>
                                <td class="py-2" style="color: #4B6B8A; direction: ltr;">{{ \Carbon\Carbon::parse($project->discussion_time)->format('Y-m-d h:i A') }}</td>
                                <td class="py-2"><span class="badge rounded-pill" style="background: #E8D5A8; color: #0F2B3D;">{{ $project->discussion_room }}</span></td>
                                <td class="py-2">
                                    <button class="btn btn-sm rounded-pill px-2 py-0" data-bs-toggle="modal" data-bs-target="#modal{{ $project->id }}" style="background: transparent; color: #D4AF37; border: 1px solid #D4AF37; font-size: 11px;">
                                        <i class="fas fa-eye"></i> تفاصيل
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color: #8B9AAB;">
                                    <i class="fas fa-calendar-times"></i> لا توجد مشاريع مجدولة
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <!-- المشاريع غير المجدولة -->
<div class="card rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.2); background: white;">
    <div class="card-header bg-transparent border-0 pt-3 pb-0">
        <h5 class="fw-bold mb-0" style="color: #0F2B3D;">
            <i class="fas fa-clock me-1" style="color: #D4AF37;"></i> المشاريع غير المجدولة
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="min-width: 500px;">
                <thead style="background: #FEFAF5;">
                    <tr>
                        <th class="py-2 small fw-semibold" style="color: #1A4058;">المشروع</th>
                        <th class="py-2 small fw-semibold" style="color: #1A4058;">الطالب</th>
                        <th class="py-2 small fw-semibold" style="color: #1A4058;">المشرف</th>
                        <th class="py-2 small fw-semibold" style="color: #1A4058;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects->whereNull('discussion_time')->where('status', 'approved') as $project)
                    <tr>
                        <td class="py-2 fw-semibold" style="color: #0F2B3D;">{{ $project->title }}</td>
                        <td class="py-2" style="color: #4B6B8A;">{{ $project->student->user->name ?? 'غير محدد' }}</td>
                        <td class="py-2" style="color: #4B6B8A;">{{ $project->supervisor->name ?? 'غير محدد' }}</td>
                        <td class="py-2">
                            <button class="btn btn-sm rounded-pill schedule-btn px-3" data-id="{{ $project->id }}" data-title="{{ $project->title }}" data-bs-toggle="modal" data-bs-target="#scheduleModal" style="background: #0F2B3D; color: #E8D5A8; border: none; font-size: 11px;">
                                <i class="fas fa-calendar-plus"></i> جدولة
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4" style="color: #8B9AAB;">
                            <i class="fas fa-check-circle"></i> جميع المشاريع المقبولة مجدولة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- مودال التفاصيل -->
    @foreach($projects as $project)
    <div class="modal fade" id="modal{{ $project->id }}" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0" style="background: #FEFAF5;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="color: #0F2B3D;">{{ $project->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row small g-2">
                        <div class="col-4" style="color: #1A4058;">الطالب:</div>
                        <div class="col-8" style="color: #4B6B8A;">{{ $project->student->user->name ?? 'غير محدد' }}</div>

                        <div class="col-4" style="color: #1A4058;">المشرف:</div>
                        <div class="col-8" style="color: #4B6B8A;">{{ $project->supervisor->name ?? 'غير محدد' }}</div>

                        <div class="col-4" style="color: #1A4058;">القسم:</div>
                        <div class="col-8" style="color: #4B6B8A;">{{ $project->student->department ?? 'عام' }}</div>

                        <div class="col-4" style="color: #1A4058;">الوصف:</div>
                        <div class="col-8" style="color: #4B6B8A;">{{ Str::limit($project->description ?? 'لا يوجد', 100) }}</div>

                        @if($project->discussion_time)
                        <div class="col-4" style="color: #1A4058;">التاريخ:</div>
                        <div class="col-8" style="color: #4B6B8A; direction: ltr;">{{ \Carbon\Carbon::parse($project->discussion_time)->format('Y-m-d h:i A') }}</div>

                        <div class="col-4" style="color: #1A4058;">القاعة:</div>
                        <div class="col-8"><span class="badge rounded-pill" style="background: #E8D5A8; color: #0F2B3D;">{{ $project->discussion_room }}</span></div>
                        @else
                        <div class="col-12 mt-2" style="color: #D4AF37;"><i class="fas fa-clock"></i> لم يتم جدولة هذا المشروع بعد</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm rounded-pill px-4" data-bs-dismiss="modal" style="background: #0F2B3D; color: #E8D5A8;">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- مودال الجدولة -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0" style="background: #FEFAF5;">
                <form method="POST" id="scheduleForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" style="color: #0F2B3D;">جدولة مشروع</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="small mb-3"><span id="scheduleProjectTitle" class="fw-semibold" style="color: #0F2B3D;"></span></p>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">التاريخ والوقت</label>
                            <input type="datetime-local" name="discussion_time" class="form-control form-control-sm" style="border-color: rgba(212, 175, 55, 0.3);">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">القاعة</label>
                            <input type="text" name="discussion_room" class="form-control form-control-sm" placeholder="مثال: قاعة A" style="border-color: rgba(212, 175, 55, 0.3);">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm rounded-pill px-4" data-bs-dismiss="modal" style="background: transparent; color: #8B9AAB; border: 1px solid #8B9AAB;">إلغاء</button>
                        <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: #0F2B3D; color: #E8D5A8;">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.schedule-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                let title = this.dataset.title;
                document.getElementById('scheduleProjectTitle').innerText = title;
                document.getElementById('scheduleForm').action = '/admin/projects/' + id + '/schedule';
            });
        });
    </script>

    <style>
        .dir-ltr {
            direction: ltr;
            text-align: left;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(212, 175, 55, 0.03);
        }
        .form-control:focus, .form-select:focus {
            border-color: #D4AF37;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }
        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
    </style>
</x-app-layout>