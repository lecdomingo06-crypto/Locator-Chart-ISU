<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameRole('teacher', 'professor');

        if (Schema::hasTable('academic_events') && Schema::hasColumn('academic_events', 'scope')) {
            DB::table('academic_events')
                ->where('scope', 'teachers')
                ->update(['scope' => 'professors']);
        }
    }

    public function down(): void
    {
        $this->renameRole('professor', 'teacher');

        if (Schema::hasTable('academic_events') && Schema::hasColumn('academic_events', 'scope')) {
            DB::table('academic_events')
                ->where('scope', 'professors')
                ->update(['scope' => 'teachers']);
        }
    }

    private function renameRole(string $from, string $to): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            DB::table('users')
                ->where('role', $from)
                ->update(['role' => $to]);
        }

        if (! Schema::hasTable('roles')) {
            return;
        }

        $now = now();

        DB::table('roles')->insertOrIgnore([
            'name' => $to,
            'guard_name' => 'web',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $fromRoleId = DB::table('roles')
            ->where('name', $from)
            ->where('guard_name', 'web')
            ->value('id');
        $toRoleId = DB::table('roles')
            ->where('name', $to)
            ->where('guard_name', 'web')
            ->value('id');

        if (! $fromRoleId || ! $toRoleId) {
            return;
        }

        $this->movePivotRows('model_has_roles', $fromRoleId, $toRoleId);
        $this->movePivotRows('role_has_permissions', $fromRoleId, $toRoleId);

        DB::table('roles')->where('id', $fromRoleId)->delete();
    }

    private function movePivotRows(string $table, int $fromRoleId, int $toRoleId): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'role_id')) {
            return;
        }

        $rows = DB::table($table)
            ->where('role_id', $fromRoleId)
            ->get()
            ->map(function ($row) use ($toRoleId) {
                $values = (array) $row;
                $values['role_id'] = $toRoleId;

                return $values;
            })
            ->all();

        if ($rows !== []) {
            DB::table($table)->insertOrIgnore($rows);
        }

        DB::table($table)->where('role_id', $fromRoleId)->delete();
    }
};
