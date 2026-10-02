<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        // 1. تغيير الربط من سابميشن إلى المشاريع مباشرة
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

        // 2. تغيير الربط من جدول المشرفين المستقل ليكون مرتبط بجدول اليوزرز الأساسي
        $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');

        $table->integer('grade')->nullable();
        $table->text('feedback')->nullable();
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
