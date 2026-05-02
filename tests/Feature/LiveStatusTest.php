<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_teacher_returns_to_in_class_when_special_schedule_has_no_class_carry(): void
    {
        Carbon::setTestNow('2026-04-18 13:45:00');

        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        Schedule::create([
            'user_id' => $teacher->id,
            'subject' => 'Physics',
            'room' => 'Room 204',
            'day_of_week' => Carbon::now()->format('l'),
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        SpecialSchedule::create([
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'start_datetime' => '2026-04-18 13:00:00',
            'end_datetime' => '2026-04-18 13:30:00',
            'note' => 'Department meeting',
            'keep_until_schedule_end' => false,
        ]);

        $status = $teacher->fresh()->live_status;

        $this->assertSame('In Class', $status['status']);
        $this->assertSame('weekly_schedule', $status['source']);
        $this->assertSame('Physics', $status['subject']);
        $this->assertNull($status['status_start_datetime']);
        $this->assertNull($status['status_end_datetime']);
        $this->assertSame('13:00:00', $status['class_start_time']);
        $this->assertSame('14:00:00', $status['class_end_time']);
    }

    public function test_active_on_meeting_status_includes_its_start_and_end_time(): void
    {
        Carbon::setTestNow('2026-04-18 13:15:00');

        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        SpecialSchedule::create([
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'start_datetime' => '2026-04-18 13:00:00',
            'end_datetime' => '2026-04-18 13:30:00',
            'note' => 'Department meeting',
            'keep_until_schedule_end' => false,
        ]);

        $status = $teacher->fresh()->live_status;

        $this->assertSame('On Meeting', $status['status']);
        $this->assertSame('special_schedule', $status['source']);
        $this->assertSame('2026-04-18 13:00:00', $status['status_start_datetime']);
        $this->assertSame('2026-04-18 13:30:00', $status['status_end_datetime']);
        $this->assertNull($status['class_start_time']);
        $this->assertNull($status['class_end_time']);
    }

    public function test_active_on_meeting_shows_the_overlapping_class_end_time_when_class_extension_is_enabled(): void
    {
        Carbon::setTestNow('2026-04-18 13:15:00');

        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        Schedule::create([
            'user_id' => $teacher->id,
            'subject' => 'Physics',
            'room' => 'Room 204',
            'day_of_week' => Carbon::now()->format('l'),
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        SpecialSchedule::create([
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'start_datetime' => '2026-04-18 13:00:00',
            'end_datetime' => '2026-04-18 13:30:00',
            'note' => 'Department meeting',
            'keep_until_schedule_end' => true,
        ]);

        $status = $teacher->fresh()->live_status;

        $this->assertSame('On Meeting', $status['status']);
        $this->assertSame('special_schedule', $status['source']);
        $this->assertSame('2026-04-18 13:00:00', $status['status_start_datetime']);
        $this->assertSame('2026-04-18 14:00:00', $status['status_end_datetime']);
        $this->assertSame('13:00:00', $status['class_start_time']);
        $this->assertSame('14:00:00', $status['class_end_time']);
    }

    public function test_teacher_keeps_special_status_for_the_rest_of_the_current_class_when_enabled(): void
    {
        Carbon::setTestNow('2026-04-18 13:45:00');

        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        Schedule::create([
            'user_id' => $teacher->id,
            'subject' => 'Physics',
            'room' => 'Room 204',
            'day_of_week' => Carbon::now()->format('l'),
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        SpecialSchedule::create([
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'start_datetime' => '2026-04-18 13:00:00',
            'end_datetime' => '2026-04-18 13:30:00',
            'note' => 'Department meeting',
            'keep_until_schedule_end' => true,
        ]);

        $status = $teacher->fresh()->live_status;

        $this->assertSame('On Meeting', $status['status']);
        $this->assertSame('special_schedule_extended', $status['source']);
        $this->assertSame('Department meeting', $status['event_note']);
        $this->assertNull($status['subject']);
        $this->assertSame('2026-04-18 13:00:00', $status['status_start_datetime']);
        $this->assertSame('2026-04-18 14:00:00', $status['status_end_datetime']);
        $this->assertSame('13:00:00', $status['class_start_time']);
        $this->assertSame('14:00:00', $status['class_end_time']);
    }
}
