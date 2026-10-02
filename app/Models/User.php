<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Supervisor;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'max_team_size',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // علاقة الطالب بالمشاريع (كعضو فريق)
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    // علاقة المشرف بالمشاريع (التي يشرف عليها)
    public function supervisedProjects()
    {
        return $this->hasMany(Project::class, 'supervisor_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }
// داخل كلاس User
public function supervisor()
{
    // الربط مع جدول المشرفين (التخصص والمكتب)
    return $this->hasOne(Supervisor::class, 'user_id');
}

// في موديل User.php

// علاقة المشرف ببياناته الإضافية (التخصص والمكتب)
public function supervisorInfo()
{
    // نربط اليوزر بجدول السوبرفايزر عن طريق الـ user_id
    return $this->hasOne(Supervisor::class, 'user_id');
}

// app/Models/User.php

public function notifications()
{
    return $this->hasMany(Notification::class)->latest();
}

public function unreadNotifications()
{
    return $this->hasMany(Notification::class)->where('is_read', false);
}
    // وظائف مساعدة للأدوار
    public function isSupervisor() { return $this->role === 'supervisor'; }
    public function isStudent() { return $this->role === 'student'; }
}
