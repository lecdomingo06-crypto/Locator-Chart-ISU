<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('special_schedules', function (Blueprint $table) {
            $table->boolean('keep_until_schedule_end')->default(false)->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('special_schedules', function (Blueprint $table) {
            $table->dropColumn('keep_until_schedule_end');
        });
    }
};
