<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreignId('forced_time_out_by')
                ->nullable()
                ->after('time_out')
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('forced_time_out_at')->nullable()->after('forced_time_out_by');
            $table->string('force_time_out_reason')->nullable()->after('forced_time_out_at');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropForeign(['forced_time_out_by']);
            $table->dropColumn([
                'forced_time_out_by',
                'forced_time_out_at',
                'force_time_out_reason',
            ]);
        });
    }
};
