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
        Schema::create('archived_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');          // عنوان المشروع
            $table->text('students_names');   // أسماء الطلاب (نص لأنهم كثر)
            $table->string('supervisor');     // اسم المشرف
            $table->string('year');           // السنة
            $table->enum('department', ['Communications', 'Information Systems', 'Computer Systems']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_projects');
    }
};
