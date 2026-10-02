<x-app-layout>
    <div class="container py-4" dir="rtl">
        {{-- عنوان الصفحة --}}
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2" style="border-bottom: 2px solid #D4AF37;">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0F2B3D;">
                    <i class="fas fa-edit" style="color: #D4AF37;"></i> تعديل مشروع مؤرشف
                </h3>
                <p class="small mb-0" style="color: #8B9AAB;">تحديث بيانات المشروع في الأرشيف</p>
            </div>
            <a href="{{ route('archive.index') }}" class="btn btn-sm rounded-pill px-3" style="background: #0F2B3D; color: #E8D5A8; border: none;">
                <i class="fas fa-arrow-right me-1"></i> العودة للأرشيف
            </a>
        </div>

        {{-- رسائل التنبيه --}}
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

        @if($errors->any())
            <div class="alert alert-danger rounded-3 py-2" style="background: #FEFAF5; color: #DC2626; border-right: 3px solid #DC2626;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- نموذج التعديل --}}
        <div class="card rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.2); background: white;">
            <div class="card-body p-4">
                <form action="{{ route('admin.archive.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- عنوان المشروع --}}
                        <div class="col-12">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-heading me-1" style="color: #D4AF37;"></i> عنوان المشروع <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   value="{{ old('title', $project->title) }}" required
                                   style="border-color: rgba(212, 175, 55, 0.3);">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الوصف --}}
                        <div class="col-12">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-align-left me-1" style="color: #D4AF37;"></i> الوصف
                            </label>
                            <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      rows="4" style="border-color: rgba(212, 175, 55, 0.3);">{{ old('description', $project->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- القسم الأكاديمي --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-building me-1" style="color: #D4AF37;"></i> القسم الأكاديمي <span class="text-danger">*</span>
                            </label>
                            <select name="department" class="form-select form-select-sm @error('department') is-invalid @enderror" required
                                    style="border-color: rgba(212, 175, 55, 0.3);">
                                <option value="">اختر القسم...</option>
                                <option value="Communications" @selected(old('department', $project->department) == 'Communications')>اتصالات</option>
                                <option value="Information Systems" @selected(old('department', $project->department) == 'Information Systems')>نظم معلومات</option>
                                <option value="Computer Systems" @selected(old('department', $project->department) == 'Computer Systems')>أنظمة حاسوب</option>
                            </select>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- السنة --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-calendar me-1" style="color: #D4AF37;"></i> السنة الأكاديمية
                            </label>
                            <input type="text" name="year" class="form-control form-control-sm @error('year') is-invalid @enderror"
                                   value="{{ old('year', $project->year) }}" placeholder="مثال: 2025/2026"
                                   style="border-color: rgba(212, 175, 55, 0.3);">
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- المشرف --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-chalkboard-teacher me-1" style="color: #D4AF37;"></i> المشرف
                            </label>
                            <input type="text" name="supervisor" class="form-control form-control-sm @error('supervisor') is-invalid @enderror"
                                   value="{{ old('supervisor', $project->supervisor) }}" placeholder="اسم المشرف"
                                   style="border-color: rgba(212, 175, 55, 0.3);">
                            @error('supervisor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- أسماء الطلاب --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color: #1A4058;">
                                <i class="fas fa-users me-1" style="color: #D4AF37;"></i> أسماء الطلاب
                            </label>
                            <input type="text" name="students_names" class="form-control form-control-sm @error('students_names') is-invalid @enderror"
                                   value="{{ old('students_names', $project->students_names) }}" placeholder="أسماء الطلاب (مفصولة بفاصلة)"
                                   style="border-color: rgba(212, 175, 55, 0.3);">
                            @error('students_names')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- أزرار الإجراءات --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top: 1px solid rgba(212, 175, 55, 0.15);">
                        <a href="{{ route('archive.index') }}" class="btn btn-sm rounded-pill px-4" style="background: transparent; color: #8B9AAB; border: 1px solid #8B9AAB;">
                            <i class="fas fa-times me-1"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: #D4AF37; color: #0F2B3D; border: none;">
                            <i class="fas fa-save me-1"></i> تحديث المشروع
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- معلومات إضافية (للقراءة فقط) --}}
        <div class="card mt-3 rounded-3" style="border: 1px solid rgba(212, 175, 55, 0.1); background: #FEFAF5;">
            <div class="card-body py-2 px-3">
                <div class="row small text-muted">
                    <div class="col-md-4">
                        <span class="fw-semibold" style="color: #1A4058;">تاريخ الإضافة:</span>
                        {{ $project->created_at ? $project->created_at->format('Y-m-d H:i') : 'غير محدد' }}
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold" style="color: #1A4058;">آخر تحديث:</span>
                        {{ $project->updated_at ? $project->updated_at->format('Y-m-d H:i') : 'غير محدد' }}
                    </div>
                    <div class="col-md-4">
                        <span class="fw-semibold" style="color: #1A4058;">رقم المعرف:</span>
                        #{{ $project->id }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
