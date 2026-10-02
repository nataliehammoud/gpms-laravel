<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // أضيفي هذا السطر
use App\Models\User; // أضيفي هذا السطر

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // تعريف الصلاحيات (Gates) هنا
        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('is-supervisor', function (User $user) {
            return $user->role === 'supervisor';
        });
    }
}

