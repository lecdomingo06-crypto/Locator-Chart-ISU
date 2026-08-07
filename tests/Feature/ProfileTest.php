<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_professor_profile_page_is_displayed(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($professor)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSeeText('Change Password')
            ->assertSee('data-mobile-nav-toggle', false);
    }

    public function test_student_profile_page_includes_change_password(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSeeText('Change Password')
            ->assertSee(route('password.update'), false);

        $this->actingAs($student)
            ->get(route('student.viewer'))
            ->assertOk()
            ->assertSee(route('profile.edit'), false)
            ->assertSee('data-mobile-nav-toggle', false);
    }

    public function test_faculty_profile_page_includes_change_password(): void
    {
        $faculty = User::factory()->create(['role' => 'faculty']);

        $this->actingAs($faculty)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSeeText('Change Password')
            ->assertSee(route('password.update'), false);
    }

    public function test_student_campus_map_is_a_standalone_sidebar_page(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSeeText('Campus Map')
            ->assertSee(route('student.viewer'), false)
            ->assertSee(route('profile.edit'), false)
            ->assertDontSeeText('Check Staff Availability');
    }
    public function test_admin_cannot_access_the_shared_profile_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('profile.edit'))
            ->assertForbidden();
    }

    public function test_professor_faculty_and_student_can_update_their_account_details(): void
    {
        $department = Department::create(['name' => 'CCSICT']);
        $otherDepartment = Department::create(['name' => 'CED']);

        foreach (['professor', 'faculty', 'student'] as $index => $role) {
            $user = User::factory()->create([
                'role' => $role,
                'department_id' => $department->id,
            ]);

            $this->actingAs($user)
                ->from(route('profile.edit'))
                ->post(route('profile.update'), [
                    'full_name' => ucfirst($role).' Updated',
                    'username' => $role.'_updated_'.$index,
                    'email' => $role.'_updated_'.$index.'@example.test',
                    'role' => 'admin',
                    'department_id' => $otherDepartment->id,
                ])
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('profile.edit'));

            $user->refresh();

            $this->assertSame(ucfirst($role).' Updated', $user->full_name);
            $this->assertSame($user->full_name, $user->name);
            $this->assertSame($role.'_updated_'.$index, $user->username);
            $this->assertSame($role.'_updated_'.$index.'@example.test', $user->email);
            $this->assertSame($role, $user->role);
            $this->assertSame($department->id, $user->department_id);
        }
    }

    public function test_profile_username_and_email_must_remain_unique(): void
    {
        $existing = User::factory()->create();
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->from(route('profile.edit'))
            ->post(route('profile.update'), [
                'full_name' => 'Student Name',
                'username' => $existing->username,
                'email' => $existing->email,
            ])
            ->assertSessionHasErrors(['username', 'email'])
            ->assertRedirect(route('profile.edit'));
    }
    public function test_professor_can_update_their_profile_picture(): void
    {
        Storage::fake('public');

        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this
            ->actingAs($professor)
            ->from(route('profile.edit'))
            ->post(route('profile.update'), [
                'profile_picture' => UploadedFile::fake()->createWithContent('profile.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=')),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $profilePicture = $professor->fresh()->profile_picture;

        $this->assertNotNull($profilePicture);
        Storage::disk('public')->assertExists($profilePicture);
    }
}
