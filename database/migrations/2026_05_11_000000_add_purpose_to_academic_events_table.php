<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_events', function (Blueprint $table) {
            $table->text('purpose')->nullable()->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('academic_events', function (Blueprint $table) {
            $table->dropColumn('purpose');
        });
    }
};
