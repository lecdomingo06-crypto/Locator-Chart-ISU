<?php

namespace Tests\Feature;

use App\Models\AcademicEvent;
use App\Models\AttendanceRecord;
use App\Models\SpecialSchedule;
use App\Models\StatusOverride;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveStatusPriorityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-06-19 10:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_special_schedule_replaces_admin_override_in_live_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);

        SpecialSchedule::create([
            'user_id' => $professor->id,
            'type' => 'Emergency',
            'start_datetime' => Carbon::now()->subHour(),
            'end_datetime' => Carbon::now()->addHour(),
            'keep_until_schedule_end' => false,
        ]);

        StatusOverride::create([
            'user_id' => $professor->id,
            'status' => 'On Meeting',
            'start_datetime' => Carbon::now()->subHour(),
            'end_datetime' => Carbon::now()->addHour(),
            'set_by_admin_id' => $admin->id,
        ]);

        $status = $professor->fresh()->live_status;

        $this->assertSame('Emergency', $status['status']);
        $this->assertSame('special_schedule', $status['source']);
    }

    public function test_admin_override_applies_when_teacher_has_no_special_schedule(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);

        StatusOverride::create([
            'user_id' => $professor->id,
            'status' => 'On Meeting',
            'start_datetime' => Carbon::now()->subHour(),
            'end_datetime' => Carbon::now()->addHour(),
            'set_by_admin_id' => $admin->id,
        ]);

        $status = $professor->fresh()->live_status;

        $this->assertSame('On Meeting', $status['status']);
        $this->assertSame('admin_override', $status['source']);
    }

    public function test_special_schedule_ranks_above_holiday_and_attendance(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->createAcademicEvent('Holiday', 'Campus Holiday');

        SpecialSchedule::create([
            'user_id' => $professor->id,
            'type' => 'Emergency',
            'start_datetime' => Carbon::now()->subHour(),
            'end_datetime' => Carbon::now()->addHour(),
            'keep_until_schedule_end' => false,
        ]);

        $status = $professor->fresh()->live_status;

        $this->assertSame('Emergency', $status['status']);
        $this->assertSame('special_schedule', $status['source']);
    }

    public function test_holiday_is_visible_without_time_in(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->createAcademicEvent('Holiday', 'Campus Holiday');

        $status = $professor->fresh()->live_status;

        $this->assertSame('Campus Holiday', $status['status']);
        $this->assertSame('Holiday', $status['event_type']);
        $this->assertSame('academic_event', $status['source']);
    }

    public function test_attendance_ranks_above_non_holiday_academic_events(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->createAcademicEvent('University Event', 'Foundation Program');

        $timedOutStatus = $professor->fresh()->live_status;

        $this->assertSame('Not Available', $timedOutStatus['status']);
        $this->assertSame('attendance', $timedOutStatus['source']);

        AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subHour(),
        ]);

        $timedInStatus = $professor->fresh()->live_status;

        $this->assertSame('Foundation Program', $timedInStatus['status']);
        $this->assertSame('academic_event', $timedInStatus['source']);
    }

    private function createAcademicEvent(string $type, string $title): AcademicEvent
    {
        return AcademicEvent::create([
            'title' => $title,
            'type' => $type,
            'start_datetime' => Carbon::now()->subHour(),
            'end_datetime' => Carbon::now()->addHour(),
            'scope' => 'all',
        ]);
    }
}
