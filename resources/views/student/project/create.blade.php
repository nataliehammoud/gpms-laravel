<x-app-layout>
    <style>
        /* إجبار الخط Cairo والأيقونات محلياً */
        @font-face { font-family: 'Cairo'; src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype'); font-weight: 700; }
        @font-face { font-family: 'Font Awesome 6 Free'; src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2'); font-weight: 900; }

        * { font-family: 'Cairo', sans-serif !important; direction: rtl; }
        .fa-solid, .fas { font-family: 'Font Awesome 6 Free' !important; font-weight: 900 !important; }

        :root {
            --navy: #1a2a40;
            --gold: #c5a059;
            --pearl: #fdfbf7;
            --glass-white: rgba(255, 255, 255, 0.8);
        }

        nav, header { display: none !important; }

        .wizard-page {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0 60px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .top-action-bar {
            width: 100%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .btn-glass {
            background: white;
            border: 1px solid #dee2e6;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            color: #2c3e50;
            text-decoration: none !important;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-glass:hover { background: #f1f3f5; border-color: #adb5bd; }
        .btn-logout { color: #dc2626 !important; border-color: #fecaca; }
        .btn-logout:hover { background: #fef2f2; border-color: #dc2626; }

        .brand-logo {
            background: var(--navy);
            padding: 10px 35px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--gold);
        }

        .main-wizard-card {
            background: #fff;
            width: 95%;
            max-width: 850px;
            border-radius: 16px;
            border: 1px solid #dee2e6;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .stepper-nav {
            background: #2c3e50;
            padding: 30px 40px;
            position: relative;
        }
        .line-bg { position: absolute; top: 65px; left: 100px; right: 100px; height: 1px; background: rgba(255,255,255,0.1); }
        .line-fill { position: absolute; top: 65px; right: 100px; height: 1px; background: var(--gold); width: 0%; transition: 0.6s; }

        .step-node {
            width: 40px; height: 40px;
            background: #3d566e;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            position: relative; z-index: 5; transition: 0.4s;
            color: rgba(255,255,255,0.3);
        }
        .step-node.active { background: var(--gold); color: #1a2a40; border-color: var(--gold); transform: scale(1.1); }

        .step-content { display: none; padding: 40px 50px; }
        .step-content.active { display: block; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .input-luxe {
            width: 100%; padding: 12px 18px; background: #fff; border: 1px solid #ced4da;
            border-radius: 8px; outline: none; transition: 0.2s; font-size: 0.95rem;
        }
        .input-luxe:focus { border-color: #2c3e50; box-shadow: 0 0 0 3px rgba(44,62,80,0.1); }

        .action-footer { background: #f1f3f5; padding: 20px 50px; display: flex; justify-content: space-between; }
        .btn-gold-main { background: #2c3e50; color: #fff; padding: 10px 30px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-gold-main:hover { background: #1a2a40; }

        /* تنسيق جدول النتائج الأكاديمي */
        .recommendations-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .recommendations-table th {
            padding: 8px 10px;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            text-align: right;
        }
        .recommendations-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .rec-number { color: #6c757d; }
        .rec-name { font-weight: 500; color: #2c3e50; }
        .rec-specialization { color: #495057; }
        .rec-score {
            background: #4a6fa5;
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        .rec-empty {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 10px 0;
        }
    </style>

    <div class="wizard-page">
        <!-- Top Navigation Bar -->
        <div class="top-action-bar">
            <a href="{{ url('/') }}" class="btn-glass">
                <i class="fa-solid fa-house"></i> الرئيسية
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-glass btn-logout">
                    <i class="fa-solid fa-power-off"></i> خروج
                </button>
            </form>
        </div>

        <!-- Logo -->
        <div class="brand-logo">
            <span style="font-size: 24px; font-weight: 900; color: white;">GP<span style="color: var(--gold);">MS</span></span>
        </div>

        <div class="main-wizard-card">
            <!-- Stepper Progress -->
            <div class="stepper-nav">
                <div class="line-bg"></div>
                <div id="fill-line" class="line-fill"></div>
                <div style="display: flex; justify-content: space-between; position: relative; z-index: 10;">
                    <div style="text-align: center;">
                        <div id="node-1" class="step-node active"><i class="fa-solid fa-lightbulb"></i></div>
                        <span style="font-size: 10px; font-weight: 700; color: var(--gold); margin-top: 8px; display: block;">الفكرة</span>
                    </div>
                    <div style="text-align: center;">
                        <div id="node-2" class="step-node"><i class="fa-solid fa-users-viewfinder"></i></div>
                        <span id="text-2" style="font-size: 10px; font-weight: 700; color: #94a3b8; margin-top: 8px; display: block;">الفريق</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('student.project.store') }}" method="POST" enctype="multipart/form-data" id="wizardForm">
                @csrf

                <!-- عرض رسائل التحذير والأخطاء -->
                @if(session('error'))
                    <div style="background: #fee2e2; border-right: 4px solid #dc2626; padding: 12px 18px; border-radius: 8px; margin: 20px 50px 0; color: #991b1b; font-size: 0.9rem;">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: #fee2e2; border-right: 4px solid #dc2626; padding: 12px 18px; border-radius: 8px; margin: 20px 50px 0;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        @foreach($errors->all() as $error)
                            <div style="font-size: 0.9rem;">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Step 1: Idea -->
                <div class="step-content active" id="step1">
                    <h3 style="font-weight: 700; color: var(--navy); margin-bottom: 25px; border-right: 4px solid var(--gold); padding-right: 15px; font-size: 1.1rem;">المرحلة 1: الفكرة</h3>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: var(--navy); font-size: 13px; display: block; margin-bottom: 6px;">عنوان المشروع</label>
                        <input type="text" name="title" class="input-luxe" placeholder="أدخل عنوان مشروعك..." value="{{ old('title') }}" required>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; color: var(--navy); font-size: 13px; display: block; margin-bottom: 6px;">وصف المشروع</label>
                        <textarea name="description" rows="4" class="input-luxe" placeholder="ما هي القيمة المضافة لمشروعك؟">{{ old('description') }}</textarea>
                    </div>

                    {{-- زر التوصيات الذكية --}}
                    <div style="margin: 15px 0 20px; text-align: center;">
                        <button type="button" id="recommendBtn" style="background: #4a6fa5; color: white; border: none; padding: 8px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 0.9rem;">
                            <i class="fa-solid fa-robot"></i> اقتراح مشرفين
                        </button>
                    </div>

                    {{-- منطقة عرض النتائج --}}
                    <div id="recommendationsResult" style="display: none; margin-bottom: 20px; padding: 16px 20px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px;">
                        <h6 style="font-weight: 600; color: #2c3e50; margin-bottom: 12px; font-size: 0.95rem;">
                            <i class="fa-solid fa-robot" style="color: #4a6fa5;"></i> المشرفون المقترحون (حسب التوافق)
                        </h6>
                        <div id="recommendationsList">
                            <!-- سيتم ملؤها بواسطة JavaScript -->
                        </div>
                    </div>

                    {{-- رفع الملف --}}
                    <div style="background: var(--navy); padding: 30px; border-radius: 12px; border: 2px dashed var(--gold); text-align: center; position: relative;">
                        <i class="fa-solid fa-cloud-arrow-up" style="color: var(--gold); font-size: 1.8rem; margin-bottom: 6px; display: block;"></i>
                        <p style="color: white; font-weight: 600; margin: 0; font-size: 0.9rem;">رفع المقترح الأولي (PDF)</p>
                        <input type="file" name="project_file" required style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                    </div>
                </div>

                <!-- Step 2: Team -->
                <div class="step-content" id="step2">
                    <h3 style="font-weight: 700; color: var(--navy); margin-bottom: 25px; border-right: 4px solid var(--gold); padding-right: 15px; font-size: 1.1rem;">المرحلة 2: الفريق والمشرف</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                        <div>
                            <label style="font-weight: 600; color: var(--navy); display: block; margin-bottom: 6px; font-size: 13px;">أعضاء الفريق المتاحين</label>
                            <select name="team_members[]" id="team_select" multiple style="width: 100%; height: 180px; background: #fff; border: 1px solid #ced4da; border-radius: 8px; padding: 10px; outline: none; font-size: 0.9rem;">
                                @foreach($students as $student)
                                    @php
                                        $myProfile = auth()->user()->student;
                                        $targetProfile = $student->student;
                                        $isSameDept = $targetProfile && ($targetProfile->department == $myProfile->department);
                                        $isSameYear = $targetProfile && ($targetProfile->year == $myProfile->year);
                                    @endphp
                                    @if($student->id != auth()->id() && $isSameDept && $isSameYear)
                                        @php
                                            $oldTeamMembers = is_array(old('team_members')) ? old('team_members') : [];
                                        @endphp
                                        <option value="{{ $student->id }}" {{ in_array($student->id, $oldTeamMembers) ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $targetProfile->department }} - سنة {{ $targetProfile->year }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <p style="font-size: 10px; color: #6c757d; margin-top: 6px;">
                                <i class="fa-solid fa-filter"></i> يظهر فقط زملاؤك في ({{ auth()->user()->student->department }}) - (سنة {{ auth()->user()->student->year }}).
                            </p>
                        </div>

                        <div>
                            <label style="font-weight: 600; color: var(--navy); display: block; margin-bottom: 6px; font-size: 13px;">رتب رغباتك للمشرفين (3 رغبات)</label>
                            <div style="margin-bottom: 10px;">
                                <small style="color: #6c757d; font-size: 11px;">الرغبة رقم 1 (المشرف الأساسي)</small>
                                <select name="supervisor_id" required style="width: 100%; padding: 10px; background: #2c3e50; color: white; border-radius: 8px; border: none; outline: none; font-size: 0.9rem;">
                                    <option value="" disabled selected>اختر المشرف الأساسي</option>
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>د. {{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @for ($i = 2; $i <= 3; $i++)
                                <div style="margin-bottom: 10px;">
                                    <small style="color: #6c757d; font-size: 11px;">الرغبة رقم {{ $i }} (احتياطية)</small>
                                    <select name="supervisors[]" required style="width: 100%; padding: 10px; background: #2c3e50; color: white; border-radius: 8px; border: none; outline: none; font-size: 0.9rem;">
                                        <option value="" disabled selected>اختر المشرف للرغبة {{ $i }}</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ (is_array(old('supervisors')) && in_array($supervisor->id, old('supervisors'))) ? 'selected' : '' }}>
                                                د. {{ $supervisor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="action-footer">
                    <button type="button" id="btnPrev" style="background: none; border: none; color: #94a3b8; font-weight: 600; cursor: pointer; visibility: hidden; font-size: 0.9rem;">
                        <i class="fa-solid fa-arrow-right"></i> السابق
                    </button>
                    <div>
                        <button type="button" id="btnNext" class="btn-gold-main">
                            التالي <i class="fa-solid fa-chevron-left" style="margin-right: 8px;"></i>
                        </button>
                        <button type="submit" id="btnSubmit" class="btn-gold-main" style="display: none; background: #1a2a40; color: var(--gold);">
                            تأكيد الإرسال <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ========== إدارة الخطوات (Stepper) ==========
        window.onload = function() {
            let step = 1;
            const fillLine = document.getElementById('fill-line');
            const btnNext = document.getElementById('btnNext');
            const btnPrev = document.getElementById('btnPrev');
            const btnSubmit = document.getElementById('btnSubmit');

            function updateUI() {
                document.querySelectorAll('.step-content').forEach(s => {
                    s.style.display = 'none';
                    s.classList.remove('active');
                });
                const currentStepDiv = document.getElementById('step' + step);
                if (currentStepDiv) {
                    currentStepDiv.style.display = 'block';
                    currentStepDiv.classList.add('active');
                }
                if (fillLine) fillLine.style.width = (step === 1) ? '0%' : '100%';
                const node2 = document.getElementById('node-2');
                if (node2) node2.classList.toggle('active', step === 2);
                if (btnPrev) btnPrev.style.visibility = (step === 1) ? 'hidden' : 'visible';
                if (btnNext) btnNext.style.display = (step === 1) ? 'inline-block' : 'none';
                if (btnSubmit) btnSubmit.style.display = (step === 2) ? 'inline-block' : 'none';
            }

            if (btnNext) btnNext.onclick = function(e) { e.preventDefault(); step = 2; updateUI(); };
            if (btnPrev) btnPrev.onclick = function(e) { e.preventDefault(); step = 1; updateUI(); };
            updateUI();
        };

        // ========== التوصيات الذكية ==========
        document.getElementById('recommendBtn')?.addEventListener('click', function() {
            const title = document.querySelector('input[name="title"]')?.value;
            const description = document.querySelector('textarea[name="description"]')?.value;

            if (!title) {
                alert('⚠️ يرجى كتابة عنوان المشروع أولاً');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري التحليل...';

            fetch('{{ route("student.recommend-supervisors") }}', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    project_title: title,
                    project_description: description || ''
                })
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('recommendationsResult');
                const listDiv = document.getElementById('recommendationsList');

                if (data.success && data.recommendations?.length > 0) {
                    let html = `<table class="recommendations-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المشرف</th>
                                <th>التخصص</th>
                                <th style="text-align: center;">التوافق</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    data.recommendations.forEach((rec, index) => {
                        html += `
                            <tr>
                                <td class="rec-number">${index+1}</td>
                                <td class="rec-name">${rec.name}</td>
                                <td class="rec-specialization">${rec.specialization}</td>
                                <td style="text-align: center;"><span class="rec-score">${rec.similarity_score}%</span></td>
                            </tr>
                        `;
                    });
                    html += `</tbody></table>`;
                    listDiv.innerHTML = html;
                    resultDiv.style.display = 'block';
                } else {
                    listDiv.innerHTML = `<p class="rec-empty">⚠️ لا توجد توصيات متاحة حالياً. تأكد من وجود مشرفين مسجلين في النظام.</p>`;
                    resultDiv.style.display = 'block';
                }
            })
            .catch(error => {
                alert('❌ حدث خطأ: ' + error.message);
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fa-solid fa-robot"></i> اقتراح مشرفين';
            });
        });
    </script>
</x-app-layout>