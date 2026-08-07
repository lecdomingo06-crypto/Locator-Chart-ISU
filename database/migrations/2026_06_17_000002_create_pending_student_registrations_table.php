<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->string('full_name');
            $table->string('email');
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('password');
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('student_id');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_student_registrations');
    }
};
