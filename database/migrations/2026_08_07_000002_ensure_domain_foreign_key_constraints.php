<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->relationships() as $relationship) {
            if (! Schema::hasTable($relationship['table']) ||
                ! Schema::hasColumn($relationship['table'], $relationship['column']) ||
                $this->foreignKeyExists($relationship['table'], $relationship['column'])) {
                continue;
            }

            $this->repairInvalidReferences($relationship);

            Schema::table($relationship['table'], function (Blueprint $table) use ($relationship) {
                $foreign = $table
                    ->foreign($relationship['column'], $this->foreignKeyName($relationship['table'], $relationship['column']))
                    ->references($relationship['references'])
                    ->on($relationship['on']);

                match ($relationship['delete']) {
                    'cascade' => $foreign->cascadeOnDelete(),
                    'set null' => $foreign->nullOnDelete(),
                    default => null,
                };
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->relationships() as $relationship) {
            $constraint = $this->foreignKeyName($relationship['table'], $relationship['column']);

            if (! Schema::hasTable($relationship['table']) || ! $this->constraintExists($relationship['table'], $constraint)) {
                continue;
            }

            Schema::table($relationship['table'], function (Blueprint $table) use ($constraint) {
                $table->dropForeign($constraint);
            });
        }
    }

    private function relationships(): array
    {
        return [
            ['table' => 'users', 'column' => 'department_id', 'on' => 'departments', 'references' => 'id', 'delete' => 'set null'],
            ['table' => 'users', 'column' => 'suspended_by', 'on' => 'users', 'references' => 'id', 'delete' => 'set null'],
            ['table' => 'schedules', 'column' => 'user_id', 'on' => 'users', 'references' => 'id', 'delete' => 'cascade'],
            ['table' => 'special_schedules', 'column' => 'user_id', 'on' => 'users', 'references' => 'id', 'delete' => 'cascade'],
            ['table' => 'status_overrides', 'column' => 'user_id', 'on' => 'users', 'references' => 'id', 'delete' => 'cascade'],
            ['table' => 'status_overrides', 'column' => 'set_by_admin_id', 'on' => 'users', 'references' => 'id', 'delete' => 'set null'],
            ['table' => 'academic_events', 'column' => 'department_id', 'on' => 'departments', 'references' => 'id', 'delete' => 'set null'],
            ['table' => 'pending_student_registrations', 'column' => 'department_id', 'on' => 'departments', 'references' => 'id', 'delete' => 'cascade'],
            ['table' => 'pending_student_registrations', 'column' => 'reviewed_by', 'on' => 'users', 'references' => 'id', 'delete' => 'set null'],
            ['table' => 'attendance_records', 'column' => 'user_id', 'on' => 'users', 'references' => 'id', 'delete' => 'cascade'],
            ['table' => 'attendance_records', 'column' => 'forced_time_out_by', 'on' => 'users', 'references' => 'id', 'delete' => 'set null'],
        ];
    }

    private function foreignKeyExists(string $table, string $column): bool
    {
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }

    private function repairInvalidReferences(array $relationship): void
    {
        $table = $this->quoteIdentifier($relationship['table']);
        $column = $this->quoteIdentifier($relationship['column']);
        $parentTable = $this->quoteIdentifier($relationship['on']);
        $parentColumn = $this->quoteIdentifier($relationship['references']);

        if ($relationship['delete'] === 'cascade') {
            DB::statement("
                DELETE child FROM {$table} child
                LEFT JOIN {$parentTable} parent
                    ON parent.{$parentColumn} = child.{$column}
                WHERE child.{$column} IS NOT NULL
                    AND parent.{$parentColumn} IS NULL
            ");

            return;
        }

        if ($relationship['delete'] === 'set null') {
            DB::statement("
                UPDATE {$table} child
                LEFT JOIN {$parentTable} parent
                    ON parent.{$parentColumn} = child.{$column}
                SET child.{$column} = NULL
                WHERE child.{$column} IS NOT NULL
                    AND parent.{$parentColumn} IS NULL
            ");
        }
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`'.str_replace('`', '``', $identifier).'`';
    }

    private function constraintExists(string $table, string $constraint): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }

    private function foreignKeyName(string $table, string $column): string
    {
        return "{$table}_{$column}_domain_fk";
    }
};
