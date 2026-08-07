<?php

namespace Tests\Feature\Auth;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_away_from_the_admin_account_creation_screen(): void
    {
        $response = $this->get(route('admin.users.create'));

        $response->assertRedirect('/login');
    }

    public function test_non_admin_users_cannot_access_the_admin_account_creation_screen(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('admin.users.create'));

        $response->assertForbidden();
    }

    public function test_admin_account_creation_screen_uses_the_admin_sidebar_with_attendance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.create'));

        $response
            ->assertOk()
            ->assertSee('Attendance')
            ->assertSee(route('admin.attendance.index'), false);
    }

    public function test_admins_cannot_create_student_accounts(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_user',
        ]);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'full_name' => 'Student Demo',
                'username' => 'student_demo',
                'role' => 'student',
                'department_id' => null,
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
            ]);

        $response
            ->assertSessionHasErrors('role')
            ->assertRedirect(route('admin.users.create'));

        $this->assertAuthenticatedAs($admin);
        $this->assertDatabaseMissing('users', [
            'username' => 'student_demo',
        ]);
    }

    public function test_professor_and_faculty_accounts_still_require_a_department(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'full_name' => 'Faculty Demo',
                'username' => 'faculty_demo',
                'role' => 'faculty',
                'department_id' => null,
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
            ]);

        $response
            ->assertSessionHasErrors('department_id')
            ->assertRedirect(route('admin.users.create'));
    }

    public function test_admins_can_create_professor_accounts_with_a_department(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::create(['name' => 'Computer Science']);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'full_name' => 'Professor Demo',
                'username' => 'professor_demo',
                'role' => 'professor',
                'department_id' => $department->id,
                'password' => 'Password123',
                'password_confirmation' => 'Password123',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.create'));

        $this->assertDatabaseHas('users', [
            'full_name' => 'Professor Demo',
            'username' => 'professor_demo',
            'role' => 'professor',
            'department_id' => $department->id,
        ]);
    }
    public function test_admin_cannot_create_account_with_common_numeric_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::create(['name' => 'Business Administration']);

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'full_name' => 'Weak Password Professor',
                'username' => 'weak_password_professor',
                'role' => 'professor',
                'department_id' => $department->id,
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect(route('admin.users.create'));

        $this->assertDatabaseMissing('users', [
            'username' => 'weak_password_professor',
        ]);
    }

    public function test_student_registration_rejects_common_numeric_password(): void
    {
        $department = Department::create(['name' => 'Education']);

        $response = $this
            ->from(route('student.register'))
            ->post(route('student.register.store'), [
                'student_id' => '2026-12345',
                'full_name' => 'Weak Password Student',
                'email' => 'weak.student@example.test',
                'department_id' => $department->id,
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect(route('student.register'));

        $this->assertDatabaseMissing('pending_student_registrations', [
            'student_id' => '2026-12345',
        ]);
    }
}