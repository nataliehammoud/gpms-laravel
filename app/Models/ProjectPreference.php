<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPreference extends Model
{
    // السماح بحفظ هذه الحقول في قاعدة البيانات
    protected $fillable = ['student_id', 'supervisor_id', 'rank'];
}
