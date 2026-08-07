<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->decimal('time_in_latitude', 10, 7)->nullable()->after('time_in');
            $table->decimal('time_in_longitude', 10, 7)->nullable()->after('time_in_latitude');
            $table->decimal('time_in_accuracy_meters', 8, 2)->nullable()->after('time_in_longitude');
            $table->decimal('time_in_distance_meters', 8, 2)->nullable()->after('time_in_accuracy_meters');
            $table->boolean('time_in_location_verified')->default(false)->after('time_in_distance_meters');
            $table->string('time_in_location_status', 40)->nullable()->after('time_in_location_verified');
            $table->string('time_in_ip', 45)->nullable()->after('time_in_location_status');
            $table->text('time_in_user_agent')->nullable()->after('time_in_ip');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn([
                'time_in_latitude',
                'time_in_longitude',
                'time_in_accuracy_meters',
                'time_in_distance_meters',
                'time_in_location_verified',
                'time_in_location_status',
                'time_in_ip',
                'time_in_user_agent',
            ]);
        });
    }
};
