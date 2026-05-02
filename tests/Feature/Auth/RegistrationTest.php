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

    public function test_admins_can_create_student_accounts_without_losing_their_session(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_user',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'full_name' => 'Student Demo',
                'username' => 'student_demo',
                'role' => 'student',
                'department_id' => null,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.create'));

        $this->assertAuthenticatedAs($admin);
        $this->assertDatabaseHas('users', [
            'full_name' => 'Student Demo',
            'username' => 'student_demo',
            'role' => 'student',
            'email' => 'student_demo@local.test',
        ]);
    }

    public function test_teacher_and_faculty_accounts_still_require_a_department(): void
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
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertSessionHasErrors('department_id')
            ->assertRedirect(route('admin.users.create'));
    }

    public function test_admins_can_create_teacher_accounts_with_a_department(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::create(['name' => 'Computer Science']);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'full_name' => 'Teacher Demo',
                'username' => 'teacher_demo',
                'role' => 'teacher',
                'department_id' => $department->id,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users.create'));

        $this->assertDatabaseHas('users', [
            'full_name' => 'Teacher Demo',
            'username' => 'teacher_demo',
            'role' => 'teacher',
            'department_id' => $department->id,
        ]);
    }
}
