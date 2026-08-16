<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addColumnIfMissing('time_in_latitude', fn (Blueprint $table) => $table->decimal('time_in_latitude', 10, 7)->nullable()->after('time_in'));
        $this->addColumnIfMissing('time_in_longitude', fn (Blueprint $table) => $table->decimal('time_in_longitude', 10, 7)->nullable()->after('time_in_latitude'));
        $this->addColumnIfMissing('time_in_accuracy_meters', fn (Blueprint $table) => $table->decimal('time_in_accuracy_meters', 8, 2)->nullable()->after('time_in_longitude'));
        $this->addColumnIfMissing('time_in_distance_meters', fn (Blueprint $table) => $table->decimal('time_in_distance_meters', 8, 2)->nullable()->after('time_in_accuracy_meters'));
        $this->addColumnIfMissing('time_in_location_verified', fn (Blueprint $table) => $table->boolean('time_in_location_verified')->default(false)->after('time_in_distance_meters'));
        $this->addColumnIfMissing('time_in_location_status', fn (Blueprint $table) => $table->string('time_in_location_status', 40)->nullable()->after('time_in_location_verified'));
        $this->addColumnIfMissing('time_in_ip', fn (Blueprint $table) => $table->string('time_in_ip', 45)->nullable()->after('time_in_location_status'));
        $this->addColumnIfMissing('time_in_user_agent', fn (Blueprint $table) => $table->text('time_in_user_agent')->nullable()->after('time_in_ip'));
    }

    public function down(): void
    {
        $columns = array_filter([
            'time_in_latitude',
            'time_in_longitude',
            'time_in_accuracy_meters',
            'time_in_distance_meters',
            'time_in_location_verified',
            'time_in_location_status',
            'time_in_ip',
            'time_in_user_agent',
        ], fn (string $column) => Schema::hasColumn('attendance_records', $column));

        if ($columns === []) {
            return;
        }

        Schema::table('attendance_records', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    private function addColumnIfMissing(string $column, Closure $definition): void
    {
        if (Schema::hasColumn('attendance_records', $column)) {
            return;
        }

        Schema::table('attendance_records', function (Blueprint $table) use ($definition) {
            $definition($table);
        });
    }
};
