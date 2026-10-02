<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // أضفنا الحقول اللازمة لتعمل الواجهة الجديدة مع قاعدة البيانات
protected $fillable = ['title', 'body', 'user_id', 'type', 'attachment'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
