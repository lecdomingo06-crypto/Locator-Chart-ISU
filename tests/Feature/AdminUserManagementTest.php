<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_only_admins_can_open_user_management(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($professor)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_management_list_only_contains_professor_and_faculty_and_filters_them(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'full_name' => 'System Admin']);
        $student = User::factory()->create(['role' => 'student', 'full_name' => 'Student Hidden']);
        $ccsict = Department::create(['name' => 'CCSICT']);
        $ced = Department::create(['name' => 'CED']);

        $professor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Professor Visible',
            'department_id' => $ccsict->id,
        ]);
        User::factory()->create([
            'role' => 'faculty',
            'full_name' => 'Faculty Visible',
            'department_id' => $ced->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSeeText('Professor Visible')
            ->assertSeeText('Faculty Visible')
            ->assertDontSee(route('admin.users.edit', $admin), false)
            ->assertDontSeeText('Student Hidden');

        $this->actingAs($admin)
            ->get(route('admin.users.index', [
                'role' => 'professor',
                'department_id' => $ccsict->id,
            ]))
            ->assertOk()
            ->assertSeeText($professor->full_name)
            ->assertDontSeeText('Faculty Visible');
    }

    public function test_non_staff_accounts_cannot_be_opened_through_management_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $student))
            ->assertNotFound();
    }

    public function test_pencil_page_displays_attendance_and_schedule_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::create(['name' => 'CCSICT']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'department_id' => $department->id,
        ]);

        AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => now()->subHour(),
            'time_out' => now(),
        ]);
        Schedule::create([
            'user_id' => $professor->id,
            'subject' => 'Database Systems',
            'room' => 'IT-201',
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'semester' => 'First Semester',
            'school_year' => '2026-2027',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.users.edit', $professor))
            ->assertOk()
            ->assertSeeText('Recent attendance history')
            ->assertSeeText('Saved class schedule')
            ->assertSeeText('Database Systems')
            ->assertSeeText('IT-201');
    }
    public function test_admin_can_edit_professor_details_and_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $oldDepartment = Department::create(['name' => 'Old Department']);
        $newDepartment = Department::create(['name' => 'New Department']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'department_id' => $oldDepartment->id,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $professor), [
                'full_name' => 'Updated Faculty Member',
                'username' => 'updated_staff',
                'role' => 'faculty',
                'department_id' => $newDepartment->id,
            ])
            ->assertRedirect(route('admin.users.edit', $professor))
            ->assertSessionHas('success');

        $professor->refresh();

        $this->assertSame('Updated Faculty Member', $professor->full_name);
        $this->assertSame('updated_staff', $professor->username);
        $this->assertSame('faculty', $professor->role);
        $this->assertSame($newDepartment->id, $professor->department_id);
        $this->assertTrue($professor->hasRole('faculty'));
    }

    public function test_admin_can_reset_a_staff_password_securely(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($admin)
            ->post(route('admin.users.reset_password', $professor), [
                'password' => 'NewSecurePassword123!',
                'password_confirmation' => 'NewSecurePassword123!',
            ])
            ->assertRedirect(route('admin.users.edit', $professor))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewSecurePassword123!', $professor->fresh()->password));
    }

    public function test_suspension_closes_attendance_and_blocks_login(): void
    {
        Carbon::setTestNow('2026-06-19 09:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'username' => 'suspended_professor',
            'password' => 'password',
        ]);
        $attendance = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subHour(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.toggle_suspension', $professor))
            ->assertSessionHas('success');

        $this->assertTrue($professor->fresh()->is_suspended);
        $this->assertNotNull($attendance->fresh()->time_out);

        $this->post('/logout');
        $this->get('/login')->assertOk();

        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true], 200),
        ]);

        $this->post('/login', [
            'username' => 'suspended_professor',
            'password' => 'password',
            'h-captcha-response' => 'test-token',
        ])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_force_time_out_only_closes_the_selected_staff_session(): void
    {
        Carbon::setTestNow('2026-06-19 11:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);
        $faculty = User::factory()->create(['role' => 'faculty']);

        $professorAttendance = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subHour(),
        ]);
        $facultyAttendance = AttendanceRecord::create([
            'user_id' => $faculty->id,
            'time_in' => Carbon::now()->subHour(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.force_time_out', $professor))
            ->assertSessionHas('success');

        $this->assertNotNull($professorAttendance->fresh()->time_out);
        $this->assertNull($facultyAttendance->fresh()->time_out);
    }
    public function test_admin_cannot_reset_staff_password_to_common_numeric_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($admin)
            ->from(route('admin.users.edit', $professor))
            ->post(route('admin.users.reset_password', $professor), [
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ])
            ->assertSessionHasErrorsIn('passwordReset', 'password')
            ->assertRedirect(route('admin.users.edit', $professor));

        $this->assertTrue(Hash::check('password', $professor->fresh()->password));
    }
}