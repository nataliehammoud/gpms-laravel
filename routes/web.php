<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\ProjectStageController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AIProjectController;
use App\Http\Controllers\ArchivedProjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SmartArchiveController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

// ========== 1. الصفحة العامة ==========
Route::get('/', function () {
    return view('welcome');
});

// ========== 2. مسارات المصادقة ==========
require __DIR__.'/auth.php';

// ========== 3. المسارات المحمية ==========
Route::middleware('auth')->group(function () {
    Route::post('/student/recommend-supervisors', [AIProjectController::class, 'recommendSupervisors'])
        ->name('student.recommend-supervisors');
    Route::post('/analyze/sentiment', [AIProjectController::class, 'analyzeSentiment'])->name('analyze.sentiment');
    
    // 3.1 مسار التوصيات الذكية
    Route::post('/ai/recommendations', [AIProjectController::class, 'getSmartRecommendations'])->name('ai.recommendations');

    // 3.2 التوجيه الذكي للـ Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = trim(strtolower($user->role));
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'supervisor') return redirect()->route('supervisor.super');
        if ($role === 'student') return redirect()->route('student.project.index');
        return redirect('/');
    })->name('dashboard');

    // 3.3 مسارات تكميل الملف الشخصي
    Route::get('/complete-profile', [ProfileCompletionController::class, 'show'])->name('profile.complete');
    Route::post('/complete-profile', [ProfileCompletionController::class, 'store'])->name('profile.complete.store');

    // ========== 4. مسارات المشرف ==========
    Route::prefix('supervisor')->group(function () {
        Route::get('/super', [SupervisorController::class, 'super'])->name('supervisor.super');
        Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
        Route::get('/project/{id}/analysis', [SupervisorController::class, 'showProjectAnalysis'])->name('supervisor.project_analysis');
        Route::post('/analyze/{id}', [SupervisorController::class, 'analyzeProject'])->name('supervisor.analyze');
        Route::post('/analyze-all', [SupervisorController::class, 'analyzeAllProjects'])->name('supervisor.analyze-all');
        Route::post('/check-deep-similarity', [SupervisorController::class, 'checkDeepSimilarity'])->name('supervisor.deep-similarity');
        Route::post('/send-notification/{id}', [SupervisorController::class, 'notifyStudent'])->name('supervisor.send-notification');
        Route::post('/notify-similarity/{id}', [SupervisorController::class, 'notifySimilarity'])->name('supervisor.notify-similarity');
        Route::post('/request-stop', [SupervisorController::class, 'requestStopReceiving'])->name('supervisor.request-stop');
        Route::get('/project/{project_id}/notes/create', [SupervisorController::class, 'createNote'])->name('supervisor.notes.create');
        Route::post('/project/{project_id}/notes', [SupervisorController::class, 'storeNote'])->name('supervisor.notes.store');
        Route::get('/project/{project_id}/stages', [ProjectStageController::class, 'index'])->name('supervisor.stages.index');
        Route::post('/project/{id}/grade', [SupervisorController::class, 'storeGrade'])->name('supervisor.project.grade');
        Route::post('/update-size', [SupervisorController::class, 'updateSize'])->name('supervisor.update-size');
        Route::get('/my-schedule', [SupervisorController::class, 'mySchedule'])->name('supervisor.my-schedule');
        Route::post('/request-archive/{id}', [SupervisorController::class, 'requestArchive'])->name('supervisor.requestArchive');
    });

    // 4.1 تحديث حالة المشروع (خارج مجموعة المشرف)
    Route::post('/project/{id}/update-status', [ProjectController::class, 'updateStatus'])->name('project.updateStatus');

    // ========== 5. مسارات الطالب ==========
    Route::middleware(['check.profile'])->group(function () {
        Route::get('/student/dashboard', [ProjectController::class, 'index'])->name('student.dashboard');
        Route::get('/student/project/create', [ProjectController::class, 'create'])->name('student.project.create');
        Route::resource('student/project', ProjectController::class)->names('student.project')->except(['show', 'create']);
        Route::post('/submission/store', [SubmissionController::class, 'store'])->name('submission.store');
        Route::get('/student/project/{project_id}/stages', [ProjectStageController::class, 'index'])->name('student.stages.index');
        Route::get('/student/project/{project_id}/notes', [ProjectController::class, 'showNotes'])->name('student.project.notes');
        Route::post('/preferences/store', [StudentController::class, 'storePreferences'])->name('preferences.store');
        Route::post('/student/ai-suggest', [AIProjectController::class, 'handleStudentAction'])->name('student.ai.suggest');
    });

    // 5.1 مسار الحفظ المباشر (force-save)
    Route::post('/force-save-direct', function(Request $request) {
        $request->merge(['force_save' => true]);
        $controller = new App\Http\Controllers\ProjectController();
        return $controller->store($request);
    })->name('force.save.direct');

    // ========== 6. مسارات الأدمن ==========
    Route::middleware(['can:is-admin'])->prefix('admin')->name('admin.')->group(function () {
        // اللوحة الرئيسية
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // جدولة المناقشات
        Route::get('/projects/schedule', [AdminController::class, 'showDiscussionSchedule'])->name('projects.schedule');
        Route::put('/projects/{project}/schedule', [AdminController::class, 'updateDiscussion'])->name('projects.update_schedule');
        Route::post('/check-schedule-conflict', [AdminController::class, 'checkScheduleConflict'])->name('check.schedule.conflict');

        // الذكاء الاصطناعي والتدريب
        Route::post('/train-ai-network', [AIProjectController::class, 'trainNetworkOffline'])->name('train.ai');
        Route::post('/run-ai', [AdminController::class, 'runAIAllocation'])->name('distribute');

        // إعدادات النظام
        Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');

        // إدارة المستخدمين
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users.index');
        Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // إشعارات الأدمن
        Route::post('/notifications/{id}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');

        // طلبات إيقاف الاستقبال
        Route::get('/stop-requests', [AdminController::class, 'showStopRequests'])->name('stop-requests');
        Route::post('/approve-stop/{id}', [AdminController::class, 'approveStopReceiving'])->name('approve-stop');
        Route::post('/reject-stop/{id}', [AdminController::class, 'rejectStopReceiving'])->name('reject-stop');

        // صفحة إحصائيات الأقسام (الرسوم البيانية)
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');

        // مسار الجدولة التلقائية (A*)
        Route::post('/schedule-auto', [AdminController::class, 'autoSchedule'])->name('schedule.auto');
        
        // مسار تطبيق الجدولة المقترحة
        Route::post('/schedule-apply', [AdminController::class, 'applyAutoSchedule'])->name('schedule.apply');

        // مسار JSON للمشاريع
        Route::get('/projects/{id}/json', [AdminController::class, 'getProjectJson'])->name('projects.json');

        // ========== تحليل وتصور المشاريع (PCA + K-Means) ==========
        Route::get('/visualization', [AdminController::class, 'showVisualization'])->name('visualization');
        Route::get('/visualization-data', [AdminController::class, 'getVisualizationData'])->name('visualization.data');

        // ===== مسارات الأرشيف الخاصة بالأدمن =====
        Route::get('/archive/create', [ArchivedProjectController::class, 'create'])->name('archive.create');
        Route::post('/archive/store', [ArchivedProjectController::class, 'manualStore'])->name('archive.store');
        Route::post('/archive/train-ai', [ArchivedProjectController::class, 'trainAI'])->name('archive.train-ai');
        Route::get('/archive/{id}/edit', [ArchivedProjectController::class, 'edit'])->name('archive.edit');
        Route::put('/archive/{id}', [ArchivedProjectController::class, 'update'])->name('archive.update');
        Route::delete('/archive/{id}', [ArchivedProjectController::class, 'destroy'])->name('archive.destroy');

        // مسارات طلبات الأرشفة
        Route::get('/archive/requests', [AdminController::class, 'archiveRequests'])->name('archive.requests');
        Route::post('/archive/approve/{id}', [AdminController::class, 'approveArchive'])->name('archive.approve');
        Route::post('/archive/reject/{id}', [AdminController::class, 'rejectArchive'])->name('archive.reject');
    });

    // ========== 7. مسارات الأرشيف العامة (للكل) ==========
    Route::get('/archive', [ArchivedProjectController::class, 'index'])->name('archive.index');

    // ========== 8. مسارات البروفايل ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== 9. مسارات الإعلانات ==========
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AdminController::class, 'showAnnouncements'])->name('announcements.index');
        Route::get('/project/{projectId}', [AdminController::class, 'showProjectAnnouncements'])->name('announcements.project');
        Route::get('/unread', [AdminController::class, 'getUnreadAnnouncements'])->name('announcements.unread');

        Route::middleware('can:is-admin')->group(function () {
            Route::post('/store', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
            Route::delete('/{announcement}', [AdminController::class, 'destroyAnnouncement'])->name('announcements.destroy');
        });
    });

    // ========== 10. مسارات مراحل المشروع ==========
    Route::post('/stages/{project_id}/store', [ProjectStageController::class, 'store'])->name('stages.store');
    Route::post('/stages/{stage_id}/update-status', [ProjectStageController::class, 'updateStatus'])->name('stages.updateStatus');
    Route::post('/files/upload/{stage_id}', [ProjectStageController::class, 'uploadFile'])->name('files.upload');

    // ========== 11. مسارات الذكاء الاصطناعي المباشر (SmartArchive) ==========
    Route::post('/ai/train', [SmartArchiveController::class, 'trainNetwork']);
    Route::post('/ai/analyze', [SmartArchiveController::class, 'analyzeStudentProposal']);
    Route::post('/ai/archive-check', [SmartArchiveController::class, 'checkArchiveDuplicates']);
    Route::post('/ai/recommend-examiners', [SmartArchiveController::class, 'recommendExaminers']);

    // ========== 12. مسارات الإشعارات العامة (API) ==========
    Route::get('/notifications', function () {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'icon' => $notif->icon,
                    'is_read' => $notif->is_read,
                    'time_ago' => $notif->created_at->diffForHumans(),
                ];
            });
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ]);
    });

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    Route::post('/notifications/read-all', function () {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    // ========== 13. مسارات اختبارية ==========
    Route::get('/test-check', function() {
        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:3000/api/check-similarity', [
                'title' => 'نظام إدارة المستشفيات',
                'archive_projects' => []
            ]);
            return $response->json();
        } catch (\Exception $e) {
            return 'خطأ: ' . $e->getMessage();
        }
    });

    Route::get('/train-network', function() {
        try {
            $projects = DB::table('archived_projects')->get()->map(function($p) {
                return [
                    'text' => $p->title . ' ' . ($p->description ?? ''),
                    'category' => $p->department ?? 'مشروع تقني'
                ];
            })->toArray();

            $response = Http::post('http://127.0.0.1:3000/api/train', [
                'projects' => $projects
            ]);
            return $response->json();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    });

}); // نهاية مجموعة auth

// ========== 14. مسارات خارجية (بدون auth) ==========
Route::get('/test-archive', function() {
    return view('archive.create');
});

Route::get('/direct-ai-analysis/{id}', [SupervisorController::class, 'showProjectAnalysis']);