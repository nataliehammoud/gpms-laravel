<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
protected $fillable = [
    'project_id',
    'stage_id',
    'file_path',
    'student_notes', // أضيفي هذا السطر ضروري جداً
];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'stage_id');
    }

}
