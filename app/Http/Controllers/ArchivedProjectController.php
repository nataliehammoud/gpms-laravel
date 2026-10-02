<?php

namespace App\Http\Controllers;

use App\Models\ArchivedProject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ArchivedProjectController extends Controller
{
    public function store(Request $request)
    {
        try {
            $rawText = $request->raw_text;
            $title = Str::limit($rawText, 150);

            // 1. جلب الأرشيف الكامل لمقارنته في سيرفر النود
            $archive = ArchivedProject::select('id', 'title', 'year', 'department', 'supervisor', 'students_names')->get()->toArray();

            // 2. تصحيح الرابط والمنفذ والمسار ليتطابق مع سيرفر Node.js لديك 👈
            $ip = '127.0.0.1';
            $port = '3000';
            $url = 'http://' . $ip . ':' . $port . '/api/archive/check-duplicate';

            $response = Http::post($url, [
                'title' => $title,
                'description' => $rawText,
                'archiveProjects' => $archive
            ]);

            $aiResult = $response->json();

            // 3. إذا وجدت نسبة تشابه عالية جداً (أكبر من 85%)، يتم إيقاف الحفظ تلقائياً
            if (isset($aiResult['highest_similarity']) && $aiResult['highest_similarity'] > 85) {
                return response()->json([
                    'message' => 'Warning',
                    'error' => 'هذا المشروع مكرر بنسبة عالية جداً مع مشروع سابق في الأرشيف!',
                    'similar_projects' => $aiResult['similar_projects']
                ], 400);
            }

            // خوارزمية تحديد القسم الخاصة بك
            $department = 'Information Systems';
            if (Str::contains($rawText, ['Communications', 'اتصالات', 'شبكات'])) {
                $department = 'Communications';
            } elseif (Str::contains($rawText, ['Computer Systems', 'نظم حاسوب', 'برمجية'])) {
                $department = 'Computer Systems';
            }

            // حفظ المشروع في قاعدة البيانات بعد تخطي فحص التشابه بنجاح
            $project = ArchivedProject::create([
                'title' => $title,
                'students_names' => "مستخرج من: " . $request->file_name,
                'supervisor' => 'قيد التدقيق',
                'year' => date('Y'),
                'department' => $department,
            ]);

            return response()->json([
                'message' => 'Success',
                'data' => $project,
                'ai_analysis' => $aiResult
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

public function create()
{
    return view('archive.create');  // هذا هو الكود القصير اللي فوق
}
public function index()
{
    $archivedProjects = ArchivedProject::orderBy('year', 'desc')->paginate(20);
    $departments = ArchivedProject::select('department')->distinct()->pluck('department');
    $userRole = auth()->user()->role;

    return view('archive.index', compact('archivedProjects', 'departments', 'userRole'));
}
// صفحة تعديل المشروع
public function edit($id)
{
    $project = ArchivedProject::findOrFail($id);
    return view('archive.edit', compact('project'));
}

// تحديث المشروع
public function update(Request $request, $id)
{
    // 1. التحقق من صحة البيانات
    $request->validate([
        'title' => 'required|string|max:255',
        'year' => 'nullable|string',
        'department' => 'required|string',
        'description' => 'nullable|string',
        'supervisor' => 'nullable|string',
        'students_names' => 'nullable|string',
    ]);

    // 2. تجهيز البيانات
    $data = $request->all();

    // 3. 🔥 توحيد قيم القسم (تحويل العربية إلى الإنجليزية)
    $departmentMap = [
        'معلومات' => 'Information Systems',
        'نظم معلومات' => 'Information Systems',
        'نظم' => 'Information Systems',
        'اتصالات' => 'Communications',
        'أنظمة حاسوب' => 'Computer Systems',
        'أنظمة' => 'Computer Systems',
        'حاسوب' => 'Computer Systems',
    ];

    if (isset($data['department']) && array_key_exists($data['department'], $departmentMap)) {
        $data['department'] = $departmentMap[$data['department']];
    }

    // 4. التحقق من أن القيمة النهائية مقبولة في ENUM
    $allowedDepartments = ['Communications', 'Information Systems', 'Computer Systems'];
    if (!in_array($data['department'], $allowedDepartments)) {
        return back()->withErrors(['department' => 'القيمة غير مقبولة. القيم المسموحة: اتصالات، نظم معلومات، أنظمة حاسوب.']);
    }

    // 5. تحديث المشروع
    $project = ArchivedProject::findOrFail($id);
    $project->update($data);

    return redirect()->route('archive.index')->with('success', 'تم تحديث المشروع بنجاح');
}
// حذف المشروع
public function destroy($id)
{
    $project = ArchivedProject::findOrFail($id);
    $project->delete();

    return redirect()->route('archive.index')->with('success', 'تم حذف المشروع بنجاح');
}
public function manualStore(Request $request)
{
    // حفظ البيانات
    ArchivedProject::create($request->all());
    return redirect()->route('archive.index')->with('success', 'تم الحفظ');
}
    public function trainAI()
    {
        try {
            $projects = ArchivedProject::select('id', 'title', 'department')->get();
            $formattedProjects = $projects->map(function ($project) {
                return [
                    'category' => $project->department,
                    'text' => $project->title
                ];
            })->toArray();

            $ip = '127.0.0.1';
            $port = '3000';
            $url = 'http://' . $ip . ':' . $port . '/api/train';

            $response = Http::post($url, [
                'projects' => $formattedProjects
            ]);

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
