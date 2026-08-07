<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\PendingStudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudentRegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admins_can_view_request_details_and_clear_review_history(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $registration = $this->registration(['status' => 'declined']);

        $this->actingAs($student)
            ->get(route('admin.student_registrations.show', $registration))
            ->assertForbidden();

        $this->actingAs($student)
            ->delete(route('admin.student_registrations.clear_reviewed'))
            ->assertForbidden();
    }

    public function test_admin_request_list_uses_table_actions_and_links_to_full_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $registration = $this->registration([
            'full_name' => 'Maria Student',
            'student_id' => '2026-00123',
            'email' => 'maria@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.student_registrations.index'))
            ->assertOk()
            ->assertSeeText('Maria Student')
            ->assertSeeText('Approve')
            ->assertSeeText('Decline')
            ->assertSee(route('admin.student_registrations.show', $registration), false);
    }

    public function test_admin_can_view_all_submitted_and_review_information(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'full_name' => 'Review Administrator',
        ]);
        $department = Department::create(['name' => 'CCSICT']);
        $registration = $this->registration([
            'full_name' => 'Reviewed Student',
            'student_id' => '2026-00456',
            'email' => 'reviewed@example.test',
            'department_id' => $department->id,
            'status' => 'declined',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'decline_reason' => 'The submitted ID needs verification.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.student_registrations.show', $registration))
            ->assertOk()
            ->assertSeeText('Reviewed Student')
            ->assertSeeText('2026-00456')
            ->assertSeeText('reviewed@example.test')
            ->assertSeeText('CCSICT')
            ->assertSeeText('Review Administrator')
            ->assertSeeText('The submitted ID needs verification.');
    }

    public function test_clearing_review_history_keeps_pending_requests_and_approved_students(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $approvedStudent = User::factory()->create([
            'role' => 'student',
            'student_id' => '2026-00999',
            'username' => '2026-00999',
        ]);
        $pending = $this->registration(['student_id' => '2026-00001']);
        $approved = $this->registration([
            'student_id' => '2026-00999',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);
        $declined = $this->registration([
            'student_id' => '2026-00888',
            'status' => 'declined',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.student_registrations.clear_reviewed'))
            ->assertRedirect(route('admin.student_registrations.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pending_student_registrations', ['id' => $pending->id]);
        $this->assertDatabaseMissing('pending_student_registrations', ['id' => $approved->id]);
        $this->assertDatabaseMissing('pending_student_registrations', ['id' => $declined->id]);
        $this->assertDatabaseHas('users', ['id' => $approvedStudent->id]);
    }

    private function registration(array $attributes = []): PendingStudentRegistration
    {
        $departmentId = $attributes['department_id'] ?? Department::firstOrCreate([
            'name' => 'Test Department',
        ])->id;

        return PendingStudentRegistration::create(array_merge([
            'student_id' => fake()->unique()->numerify('2026-#####'),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'department_id' => $departmentId,
            'password' => Hash::make('StudentPassword123!'),
            'status' => 'pending',
        ], $attributes));
    }
}
