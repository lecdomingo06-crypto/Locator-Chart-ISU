<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->dateTime('time_in');
            $table->dateTime('time_out')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'time_in']);
            $table->index(['user_id', 'time_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
