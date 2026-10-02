<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title', 'description', 'student_id', 'supervisor_id',
        'status', 'file_path', 'discussion_time', 'discussion_room',
        'readiness',
        'analyzed_at',
    ];

    protected $casts = [
        'analyzed_at' => 'datetime',
        'readiness' => 'integer',
        'discussion_time' => 'datetime',
    ];

    // علاقة الطالب
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // علاقة المشرف
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // علاقة الرغبات
    public function preferences()
    {
        return $this->hasMany(ProjectPreference::class, 'project_id');
    }

    // علاقة المراحل
    public function stages()
    {
        return $this->hasMany(ProjectStage::class, 'project_id');
    }

    // علاقة الملفات
    public function files()
    {
        return $this->hasMany(ProjectFile::class, 'project_id');
    }

    // علاقة أعضاء الفريق
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    // علاقة الملاحظات
    public function notes()
    {
        return $this->hasMany(ProjectNote::class)->latest();
    }

    // علاقة الطلاب المهتمين
    public function interestedStudents()
    {
        return $this->hasMany(ProjectPreference::class, 'project_id');
    }
}
