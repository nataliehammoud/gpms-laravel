<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistributionService
{
    public function runGaleShapley()
    {
        // 1. جلب الطلاب الذين لديهم مشاريع غير موزعة فقط
        $students = Student::with(['user', 'project', 'preferences' => function($q) {
            $q->orderBy('rank', 'asc');
        }])
        ->whereHas('project', function($q) {
            $q->where('is_distributed', false);
        })
        ->get();

        // 2. المشرفين المتاحين (لم يطلبوا إيقاف الاستقبال)
        $availableSupervisors = Supervisor::with('user')
            ->where('stop_receiving', false)
            ->get();

        if ($availableSupervisors->isEmpty()) {
            Log::error('لا يوجد مشرفون متاحون للتوزيع');
            return false;
        }

        $assignedFromPreferences = 0;
        $assignedFallback = 0;

        foreach ($students as $student) {
            $project = $student->project;
            if (!$project) {
                Log::warning("الطالب {$student->id} ليس لديه مشروع");
                continue;
            }

            $studentDepartment = $student->department;
            $assigned = false;
            $assignedSupervisorId = null;

            // ========== 3. تجميع الرغبات وتصنيفها ==========
            $matchingPreferences = []; // رغبات من نفس التخصص
            $otherPreferences = [];   // رغبات من تخصصات أخرى

            foreach ($student->preferences as $preference) {
                $supervisorId = $preference->supervisor_id;
                $supervisor = $availableSupervisors->firstWhere('user_id', $supervisorId);

                if (!$supervisor) {
                    continue; // مشرف موقف أو غير موجود
                }

                // ✅ لا حاجة للتحقق من السعة (لا يوجد حد أقصى للمشاريع)
                // تصنيف حسب التخصص
                if ($supervisor->specialization == $studentDepartment) {
                    $matchingPreferences[] = $preference;
                } else {
                    $otherPreferences[] = $preference;
                }
            }

            // ========== 4. محاولة التعيين من الرغبات المتطابقة في التخصص (أولوية أولى) ==========
            foreach ($matchingPreferences as $preference) {
                $supervisorId = $preference->supervisor_id;
                $supervisor = $availableSupervisors->firstWhere('user_id', $supervisorId);

                if (!$supervisor) continue;

                // ✅ لا حاجة للتحقق من السعة
                $assignedSupervisorId = $supervisorId;
                $assigned = true;
                $assignedFromPreferences++;
                Log::info("✅ تم توزيع مشروع {$project->id} على المشرف {$supervisorId} (رغبة رقم {$preference->rank} - نفس التخصص)");
                break;
            }

            // ========== 5. إذا لم يتم التعيين من الرغبات المتطابقة، حاول من الرغبات الأخرى ==========
            if (!$assigned) {
                foreach ($otherPreferences as $preference) {
                    $supervisorId = $preference->supervisor_id;
                    $supervisor = $availableSupervisors->firstWhere('user_id', $supervisorId);

                    if (!$supervisor) continue;

                    // ✅ لا حاجة للتحقق من السعة
                    $assignedSupervisorId = $supervisorId;
                    $assigned = true;
                    $assignedFromPreferences++;
                    Log::info("✅ تم توزيع مشروع {$project->id} على المشرف {$supervisorId} (رغبة رقم {$preference->rank} - تخصص مختلف)");
                    break;
                }
            }

            // ========== 6. إذا لم يتم التعيين لأي رغبة ==========
            if (!$assigned) {
                // البحث عن مشرف من نفس التخصص كخطة بديلة
                $fallbackSupervisor = $availableSupervisors->filter(function($sup) use ($studentDepartment) {
                    return $sup->specialization == $studentDepartment;
                })->first();

                if ($fallbackSupervisor) {
                    // ✅ لا حاجة للتحقق من السعة
                    $assignedSupervisorId = $fallbackSupervisor->user_id;
                    $assigned = true;
                    $assignedFallback++;
                    Log::warning("⚠️ تم توزيع مشروع {$project->id} على مشرف احتياطي {$assignedSupervisorId} (نفس التخصص)");
                } else {
                    // خطة أخيرة: أول مشرف متاح في النظام
                    $anySupervisor = $availableSupervisors->first();
                    if ($anySupervisor) {
                        $assignedSupervisorId = $anySupervisor->user_id;
                        $assigned = true;
                        $assignedFallback++;
                        Log::error("❌ تم توزيع مشروع {$project->id} على أول مشرف متاح {$assignedSupervisorId} (حالة طوارئ)");
                    } else {
                        Log::error("❌ لم يتم العثور على أي مشرف للطالب {$student->id}");
                        continue;
                    }
                }
            }

            // ========== 7. تحديث المشرف في جدول projects ==========
            if ($assigned && $assignedSupervisorId) {
                DB::table('projects')->where('id', $project->id)->update([
                    'supervisor_id' => $assignedSupervisorId,
                    'is_distributed' => true,
                    'updated_at' => now()
                ]);

                $supervisorName = User::find($assignedSupervisorId)->name ?? 'المشرف';

                // إشعار للطالب
                Notification::create([
                    'user_id' => $student->user_id,
                    'project_id' => $project->id,
                    'title' => '✅ تم تعيين مشرف لمشروعك',
                    'message' => "تم تعيين د. {$supervisorName} كمشرف على مشروعك '{$project->title}'",
                    'type' => 'success',
                    'icon' => 'fa-user-check',
                    'is_read' => false,
                ]);

                // إشعار للمشرف
                Notification::create([
                    'user_id' => $assignedSupervisorId,
                    'project_id' => $project->id,
                    'title' => '📋 تم تعيين مشروع لك',
                    'message' => "تم تعيين مشروع '{$project->title}' للطالب {$student->user->name} تحت إشرافك",
                    'type' => 'success',
                    'icon' => 'fa-folder-open',
                    'is_read' => false,
                ]);
            }
        }

        Log::info("📊 ملخص التوزيع: {$assignedFromPreferences} من الرغبات, {$assignedFallback} احتياطي");

        return [
            'success' => true,
            'from_preferences' => $assignedFromPreferences,
            'fallback' => $assignedFallback,
            'total' => $assignedFromPreferences + $assignedFallback
        ];
    }
}
