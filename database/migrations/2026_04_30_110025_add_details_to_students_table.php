<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::table('students', function (Blueprint $table) {
    $table->decimal('gpa', 4, 2)->nullable(); // المعدل التراكمي (مثلاً 3.75)
    $table->text('skills')->nullable(); // مهارات الطالب (Laravel, AI, Python)
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
