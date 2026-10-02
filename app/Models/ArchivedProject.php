<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedProject extends Model
{
    // هذا السطر هو الذي يسمح للسكريبت بإدخال البيانات
    protected $fillable = ['title', 'students_names', 'supervisor', 'year', 'department'];
}
