<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['submission_id', 'supervisor_id', 'grade', 'feedback'];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}

