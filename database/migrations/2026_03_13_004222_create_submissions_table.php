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
    Schema::create('submissions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('stage_id');
        $table->unsignedBigInteger('student_id');
        $table->string('file_path')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('stage_id')->references('id')->on('project_stages')->onDelete('cascade');
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
