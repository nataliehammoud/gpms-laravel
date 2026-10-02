<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ArchivedProject;
use App\Models\User;

class SmartArchiveController extends Controller
{
    private string $nodeUrl = 'http://127.0.0.1:3000';

    // 1. تدريب الشبكة العصبونية
    public function trainNetwork()
    {
        $projects = ArchivedProject::select('id', 'title', 'department', 'description')->get();

        $formattedProjects = $projects->map(function ($project) {
            return [
                'category' => $project->department,
                'text'     => $project->title . ' ' . ($project->description ?? '')
            ];
        })->toArray();

        $response = Http::post("{$this->nodeUrl}/api/train", ['projects' => $formattedProjects]);
        return response()->json($response->json(), $response->status());
    }

    // 2. تحليل المقترح وتوليد خطة
    public function analyzeStudentProposal(Request $request)
    {
        $response = Http::post("{$this->nodeUrl}/api/analyze-project", ['text' => $request->text]);
        return response()->json($response->json(), $response->status());
    }

    // 4. ترشيح لجان المناقشة
    public function recommendExaminers(Request $request)
    {
        $doctors = User::where('role', 'supervisor')->select('id', 'name', 'specialization as interests')->get()->toArray();

        $response = Http::post("{$this->nodeUrl}/api/admin/recommend-examiners", [
            'project_description' => $request->project_description,
            'doctors'             => $doctors
        ]);
        return response()->json($response->json(), $response->status());
    }
}