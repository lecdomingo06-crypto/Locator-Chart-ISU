<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exists = DB::table('departments')
            ->where('name', 'PS')
            ->exists();

        if (! $exists) {
            DB::table('departments')->insert([
                'name' => 'PS',
                'map_label' => 'PS',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('departments')
            ->where('name', 'PS')
            ->delete();
    }
};
