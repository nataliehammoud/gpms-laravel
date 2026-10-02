<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisor extends Model
{
    use HasFactory;

    // أضفنا الحقول الصحيحة لكي يسمح لارافيل بحفظها
    protected $fillable = [
        'user_id',
        'specialization', // التخصص
        'office'          // المكتب
    ];

    // علاقة المشرف باليوزر (صحيحة)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة المشرف بالمشاريع (تأكدي من المفتاح الخارجي)
    public function projects()
    {
        return $this->hasMany(Project::class, 'supervisor_id', 'user_id');
    }

    // علاقة المشرف بالتقييمات
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
