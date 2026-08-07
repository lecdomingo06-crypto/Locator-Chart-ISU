<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAttendanceDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_admin_can_view_attendance_reports_with_present_absent_and_rendered_hours(): void
    {
        Carbon::setTestNow('2026-06-19 18:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::create(['name' => 'CCSICT']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Present Professor',
            'department_id' => $department->id,
        ]);
        User::factory()->create([
            'role' => 'faculty',
            'full_name' => 'Absent Faculty',
            'department_id' => $department->id,
        ]);

        AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::parse('2026-06-19 08:00:00'),
            'time_out' => Carbon::parse('2026-06-19 11:30:00'),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.attendance.index', [
                'period' => 'daily',
                'date' => '2026-06-19',
                'department_id' => $department->id,
            ]))
            ->assertOk()
            ->assertSeeText('Attendance Dashboard')
            ->assertSeeText('Present Professor')
            ->assertSeeText('Absent Faculty')
            ->assertSeeText('Present')
            ->assertSeeText('Absent')
            ->assertSeeText('3 hrs 30 min');
    }

    public function test_admin_force_time_out_requires_and_records_reason(): void
    {
        Carbon::setTestNow('2026-06-19 18:15:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Open Session Professor',
        ]);
        $attendance = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::parse('2026-06-19 08:00:00'),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.attendance.force_time_out', $professor), [
                'reason' => 'Professor forgot to close the attendance session.',
            ])
            ->assertSessionHas('success');

        $attendance->refresh();

        $this->assertNotNull($attendance->time_out);
        $this->assertSame($admin->id, $attendance->forced_time_out_by);
        $this->assertNotNull($attendance->forced_time_out_at);
        $this->assertSame('Professor forgot to close the attendance session.', $attendance->force_time_out_reason);
    }

    public function test_admin_can_open_printable_attendance_report_per_staff_member(): void
    {
        Carbon::setTestNow('2026-06-19 18:30:00');

        $admin = User::factory()->create([
            'role' => 'admin',
            'full_name' => 'Report Admin',
        ]);
        $department = Department::create(['name' => 'CCSICT']);
        $professor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Report Professor',
            'username' => 'reportprof',
            'department_id' => $department->id,
        ]);

        AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::parse('2026-06-19 07:30:00'),
            'time_out' => Carbon::parse('2026-06-19 11:00:00'),
            'forced_time_out_by' => $admin->id,
            'forced_time_out_at' => Carbon::parse('2026-06-19 11:00:00'),
            'force_time_out_reason' => 'Power interruption.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.attendance.print', [
                'user_id' => $professor->id,
                'period' => 'custom',
                'start_date' => '2026-06-18',
                'end_date' => '2026-06-19',
            ]))
            ->assertOk()
            ->assertSeeText('Professor/Faculty Attendance Report')
            ->assertSeeText('Report Professor')
            ->assertSeeText('CCSICT')
            ->assertSeeText('Present')
            ->assertSeeText('Absent')
            ->assertSeeText('3 hrs 30 min')
            ->assertSeeText('Power interruption.');
    }

    public function test_admin_calendar_treats_weekends_without_schedules_and_excused_days_as_not_absent(): void
    {
        Carbon::setTestNow('2026-06-21 18:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $noClassProfessor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Weekend No Class Professor',
        ]);
        $scheduledProfessor = User::factory()->create([
            'role' => 'professor',
            'full_name' => 'Weekend Scheduled Professor',
        ]);
        $excusedFaculty = User::factory()->create([
            'role' => 'faculty',
            'full_name' => 'Excused Faculty',
        ]);

        Schedule::create([
            'user_id' => $scheduledProfessor->id,
            'subject' => 'Weekend Class',
            'room' => 'IT-202',
            'day_of_week' => 'Sunday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        SpecialSchedule::create([
            'user_id' => $excusedFaculty->id,
            'type' => 'On Leave',
            'start_datetime' => Carbon::parse('2026-06-21 00:00:00'),
            'end_datetime' => Carbon::parse('2026-06-21 23:59:00'),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.attendance.index', [
                'period' => 'daily',
                'date' => '2026-06-21',
            ]))
            ->assertOk()
            ->assertSee('Absent Today</span><strong>1</strong>', false)
            ->assertSeeText('1 absent')
            ->assertSeeText('1 excused')
            ->assertSeeText('1 no class');
    }
}
