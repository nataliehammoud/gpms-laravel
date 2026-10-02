<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['stage_id', 'student_id', 'file_path', 'notes'];

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }
}

