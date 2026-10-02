<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // أضيفي 'phone' أو أي حقل آخر إذا كان موجوداً بقاعدة بياناتك
    protected $fillable = ['user_id', 'student_number', 'department', 'year'];

    // 1. علاقة الطالب باليوزر (لجلب الاسم والإيميل) - صحيحة كما هي
 public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function project()
{
    // افترضنا هنا أن student_id في المشاريع يطابق الـ ID في جدول students
    return $this->hasOne(Project::class, 'student_id');
}

public function preferences()
{
    // الرغبات مرتبطة بالطالب ومرتبة حسب الأولوية
    return $this->hasMany(ProjectPreference::class, 'student_id')->orderBy('rank');
}


    // 3. علاقة الطالب بالتسليمات (الملفات المرفوعة) - صحيحة
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }


}
