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
    Schema::table('project_files', function (Blueprint $table) {
        // هذا السطر يخبر قاعدة البيانات أن الحقل ليس إجبارياً عند الرفع لأول مرة
        $table->unsignedBigInteger('stage_id')->nullable()->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
