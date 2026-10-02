// database/migrations/2026_05_26_000000_add_readiness_to_projects_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('readiness')->default(0)->after('description');
            $table->timestamp('analyzed_at')->nullable()->after('readiness');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['readiness', 'analyzed_at']);
        });
    }
};
