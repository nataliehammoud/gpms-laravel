<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ProjectStage extends Model
{
    protected $fillable = [
        'project_id',
        'stage_name',
        'due_date',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
public function files() { return $this->hasMany(ProjectFile::class, 'stage_id'); }


    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
