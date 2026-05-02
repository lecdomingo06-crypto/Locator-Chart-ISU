<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'full_name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@local.test',
                'role' => 'admin',
                'department_id' => null,
                'password' => Hash::make('admin12345'),
            ]
        );
    }
}