<x-app-layout>
    <style>
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        * { font-family: 'Cairo', sans-serif !important; }

        .edit-wrapper { background-color: #fdfbf7; min-height: 100vh; padding: 50px 0; }
        .navy-bg { background-color: #1a2a40 !important; }
        .gold-text { color: #D4AF37 !important; }
        .gold-border { border: 2px solid #D4AF37 !important; }
        .custom-card { border-radius: 40px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .form-input { border-radius: 15px; background-color: #f8f9fa; border: 2px solid transparent; padding: 12px; transition: 0.3s; font-weight: 700; }
        .form-input:focus { border-color: #D4AF37; background-color: #fff; outline: none; }

        /* تنبيه الخطأ */
        #team_error { display: none; color: #dc2626; font-size: 11px; font-weight: 900; margin-top: 10px; padding: 10px; background: #fef2f2; border-radius: 10px; border: 1px solid #fee2e2; }
    </style>

    <div class="edit-wrapper" dir="rtl">
        <div class="container">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="d-flex align-items-center gap-3">
                    <div class="navy-bg p-3 rounded-4 shadow gold-border">
                        <i class="fa-solid fa-pen-to-square gold-text fs-3"></i>
                    </div>
                    <h2 class="fw-bold mb-0" style="color: #1a2a40;">تعديل بيانات <span class="gold-text">المشروع</span></h2>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url('/') }}" class="btn btn-white shadow-sm rounded-pill px-4 fw-bold border">الرئيسية</a>
                    <a href="{{ route('student.project.index') }}" class="btn navy-bg gold-text rounded-pill px-4 fw-bold shadow-lg border-0">قائمة المشاريع</a>
                </div>
            </div>

            <div class="card custom-card">
                <div class="row g-0">
                    <!-- الجانب النيفي -->
                    <div class="col-lg-4 navy-bg p-5 text-white d-flex flex-column justify-content-between position-relative">
                        <div>
                            <h3 class="fw-bold mb-4 gold-text">تحديث المشروع</h3>
                            <p class="opacity-75">يمكنك هنا تعديل العنوان، الوصف، أو تغيير أعضاء الفريق بما يتناسب مع خطة البحث المعتمدة.</p>
                        </div>
                        <div class="p-3 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                            <i class="fa-solid fa-filter gold-text me-2"></i>
                            <span class="small font-bold">تظهر فقط الأسماء المطابقة لقسمك وسنتك الدراسية.</span>
                        </div>
                    </div>

                    <!-- الجانب الكريمي (الفورم) -->
                    <div class="col-lg-8 p-5 bg-white">
                        <form action="{{ route('student.project.update', $project->id) }}" method="POST" enctype="multipart/form-data" id="editProjectForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-2 d-block">عنوان المشروع</label>
                                <input type="text" name="title" value="{{ $project->title }}" class="form-control form-input shadow-none" required>
                            </div>

                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-2 d-block">وصف المشروع</label>
                                <textarea name="description" rows="3" class="form-control form-input shadow-none">{{ $project->description }}</textarea>
                            </div>

                            <!-- تعديل الفريق (الفلترة الذكية) -->
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-2 d-block">أعضاء الفريق (المتاحين من قسمك)</label>
                                <select name="team_members[]" id="team_select" multiple class="form-select form-input shadow-none" style="height: 150px;">
                                    @foreach($students as $student)
                                        @php
                                            $myProfile = auth()->user()->student;
                                            $targetProfile = $student->student;

                                            // 1. فحص مطابقة القسم والسنة
                                            $isSameDept = $targetProfile && ($targetProfile->department == $myProfile->department);
                                            $isSameYear = $targetProfile && ($targetProfile->year == $myProfile->year);

                                            // 2. فحص إذا كان الطالب في هذا المشروع حالياً
                                            $isAlreadyInThisProject = $project->users->contains($student->id);

                                            // 3. فحص إذا كان الطالب في مشروع آخر
                                            $hasOtherProject = DB::table('project_user')
                                                ->where('user_id', $student->id)
                                                ->where('project_id', '!=', $project->id)
                                                ->exists();
                                        @endphp

                                        @if($student->id != auth()->id() && $isSameDept && $isSameYear && (!$hasOtherProject || $isAlreadyInThisProject))
                                            <option value="{{ $student->id }}" @if($isAlreadyInThisProject) selected @endif>
                                                {{ $student->name }} @if($isAlreadyInThisProject) (عضو حالي) @endif
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="team_error">تنبيه: لقد تجاوزت عدد الأعضاء المسموح به للمشرف المختار.</div>
                            </div>

                            <div class="mb-4 p-4 text-center border-2 border-dashed rounded-4" style="border-color: #D4AF37; background: #fdfbf7;">
                                <i class="fa-solid fa-file-pdf fa-2x mb-2" style="color: #1a2a40;"></i>
                                <p class="small fw-bold mb-2">تحديث ملف الـ PDF (اختياري)</p>
                                <input type="file" name="project_file" class="form-control form-control-sm border-0 bg-transparent mx-auto" style="max-width: 250px;" accept=".pdf">
                            </div>

                            <div class="mb-5">
                                <label class="small fw-bold text-muted mb-2 d-block">المشرف المسؤول</label>
                                <select name="supervisor_id" id="super_select" class="form-select form-input shadow-none">
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" data-max="{{ $supervisor->max_team_size ?? 4 }}" @if($project->supervisor_id == $supervisor->id) selected @endif>
                                            د. {{ $supervisor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-lg w-100 py-3 shadow-lg fw-bold navy-bg gold-text border-0" style="border-radius: 15px;">
                                <i class="fa-solid fa-save me-2"></i> حفظ كافة التعديلات
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const teamSelect = document.getElementById('team_select');
        const superSelect = document.getElementById('super_select');
        const teamError = document.getElementById('team_error');

        function validateTeamSize() {
            const selectedOption = superSelect.options[superSelect.selectedIndex];
            const maxTotal = selectedOption ? parseInt(selectedOption.getAttribute('data-max')) : 4;
            const maxAllowedMembers = maxTotal - 1; // استثناء صاحب المشروع

            let selectedCount = 0;
            for (let i = 0; i < teamSelect.options.length; i++) {
                if (teamSelect.options[i].selected) selectedCount++;
            }

            if (selectedCount > maxAllowedMembers) {
                teamError.style.display = 'block';
                teamError.innerText = `عذراً، هذا المشرف يقبل ${maxTotal} طلاب كحد أقصى (أنت + ${maxAllowedMembers} زملاء).`;
                return false;
            } else {
                teamError.style.display = 'none';
                return true;
            }
        }

        teamSelect.addEventListener('change', validateTeamSize);
        superSelect.addEventListener('change', validateTeamSize);

        document.getElementById('editProjectForm').addEventListener('submit', function(e) {
            if (!validateTeamSize()) {
                e.preventDefault();
                alert("يرجى تصحيح عدد أعضاء الفريق قبل الحفظ.");
            }
        });
    </script>
</x-app-layout>
