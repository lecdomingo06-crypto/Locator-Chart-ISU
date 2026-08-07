<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $roles = ['admin', 'student', 'professor', 'faculty'];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'name' => $role,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $roleIds = DB::table('roles')
            ->where('guard_name', 'web')
            ->whereIn('name', $roles)
            ->pluck('id', 'name');

        DB::table('users')
            ->select(['id', 'role'])
            ->whereIn('role', $roles)
            ->orderBy('id')
            ->chunk(100, function ($users) use ($roleIds) {
                $assignments = $users
                    ->map(fn ($user) => [
                        'role_id' => $roleIds[$user->role],
                        'model_type' => User::class,
                        'model_id' => $user->id,
                    ])
                    ->all();

                DB::table('model_has_roles')->insertOrIgnore($assignments);
            });
    }

    public function down(): void
    {
        DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->whereIn('role_id', function ($query) {
                $query->select('id')
                    ->from('roles')
                    ->where('guard_name', 'web')
                    ->whereIn('name', ['admin', 'student', 'professor', 'faculty']);
            })
            ->delete();
    }
};
