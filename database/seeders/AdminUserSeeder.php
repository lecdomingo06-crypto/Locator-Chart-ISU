<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'student', 'professor', 'faculty'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        $admin = User::where('username', 'admin')->first();

        if (! $admin) {
            $password = (string) config('auth.initial_admin_password');

            if ($password === '') {
                if (app()->environment('production')) {
                    throw new RuntimeException('Set ADMIN_INITIAL_PASSWORD before seeding the production database.');
                }

                $password = 'admin12345';
            }

            if (app()->environment('production') && strlen($password) < 12) {
                throw new RuntimeException('ADMIN_INITIAL_PASSWORD must contain at least 12 characters.');
            }

            $admin = User::create([
                'name' => 'Administrator',
                'full_name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@local.test',
                'role' => 'admin',
                'department_id' => null,
                'password' => Hash::make($password),
            ]);
        }

        $admin->syncRoles(['admin']);
    }
}