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
    Schema::table('users', function (Blueprint $table) {
        $table->string('full_name')->after('id');
        $table->string('username')->unique()->after('full_name');
        $table->string('role')->default('student')->after('password');
        $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
        $table->string('profile_picture')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['department_id']);
        $table->dropColumn([
            'full_name',
            'username',
            'role',
            'department_id',
            'profile_picture'
        ]);
    });
}
};
