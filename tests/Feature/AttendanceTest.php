<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use App\Services\AttendanceCalendarService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_professor_and_faculty_can_open_attendance_but_students_cannot(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $faculty = User::factory()->create(['role' => 'faculty']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($professor)->get(route('attendance.show'))->assertOk();
        $this->actingAs($faculty)->get(route('attendance.show'))->assertOk();
        $this->actingAs($student)->get(route('attendance.show'))->assertForbidden();
    }

    public function test_time_in_activates_availability_and_duplicate_time_in_is_blocked(): void
    {
        Carbon::setTestNow('2026-06-19 08:00:00');

        $professor = User::factory()->create(['role' => 'professor']);

        $this->assertSame('Not Available', $professor->live_status['status']);

        $this->actingAs($professor)
            ->post(route('attendance.time_in'))
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('success');

        $this->assertSame('Available', $professor->fresh()->live_status['status']);
        $this->assertDatabaseCount('attendance_records', 1);

        $this->actingAs($professor)
            ->post(route('attendance.time_in'))
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('attendance_records', 1);
    }

    public function test_weekly_schedule_only_controls_live_status_while_timed_in(): void
    {
        Carbon::setTestNow('2026-06-19 10:30:00');

        $professor = User::factory()->create(['role' => 'professor']);

        Schedule::create([
            'user_id' => $professor->id,
            'subject' => 'Software Engineering',
            'room' => 'IT-202',
            'day_of_week' => Carbon::now()->format('l'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $this->assertSame('Not Available', $professor->fresh()->live_status['status']);

        AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subHour(),
        ]);

        $timedInStatus = $professor->fresh()->live_status;

        $this->assertSame('In Class', $timedInStatus['status']);
        $this->assertSame('Software Engineering', $timedInStatus['subject']);

        $this->actingAs($professor)
            ->post(route('attendance.time_out'))
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('success');

        $this->assertSame('Not Available', $professor->fresh()->live_status['status']);
        $this->assertNotNull(AttendanceRecord::first()->time_out);
    }

    public function test_open_attendance_is_automatically_timed_out_at_seven_pm(): void
    {
        Carbon::setTestNow('2026-06-19 19:05:00');

        $professor = User::factory()->create(['role' => 'professor']);
        $attendance = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::parse('2026-06-19 08:00:00'),
        ]);

        $this->actingAs($professor)
            ->get(route('attendance.show'))
            ->assertOk();

        $attendance->refresh();

        $this->assertSame('2026-06-19 19:00:00', $attendance->time_out->format('Y-m-d H:i:s'));
        $this->assertSame('Not Available', $professor->fresh()->live_status['status']);
    }

    public function test_time_out_without_an_open_session_is_rejected(): void
    {
        $faculty = User::factory()->create(['role' => 'faculty']);

        $this->actingAs($faculty)
            ->post(route('attendance.time_out'))
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('attendance_records', 0);
    }

    public function test_clear_sessions_hides_own_completed_sessions_without_deleting_attendance(): void
    {
        Carbon::setTestNow('2026-06-19 20:00:00');

        $professor = User::factory()->create(['role' => 'professor']);
        $otherProfessor = User::factory()->create(['role' => 'professor']);

        $completedToday = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subHours(2),
            'time_out' => Carbon::now()->subHour(),
        ]);

        $activeToday = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subMinutes(30),
        ]);

        $completedYesterday = AttendanceRecord::create([
            'user_id' => $professor->id,
            'time_in' => Carbon::now()->subDay()->subHours(2),
            'time_out' => Carbon::now()->subDay()->subHour(),
        ]);

        $otherUserSession = AttendanceRecord::create([
            'user_id' => $otherProfessor->id,
            'time_in' => Carbon::now()->subHours(2),
            'time_out' => Carbon::now()->subHour(),
        ]);

        $this->actingAs($professor)
            ->post(route('attendance.clear_sessions'))
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_records', ['id' => $completedToday->id]);
        $this->assertDatabaseHas('attendance_records', ['id' => $activeToday->id]);
        $this->assertDatabaseHas('attendance_records', ['id' => $completedYesterday->id]);
        $this->assertDatabaseHas('attendance_records', ['id' => $otherUserSession->id]);
        $this->assertEquals(
            [$completedToday->id],
            session("attendance.cleared_sessions.{$professor->id}.2026-06-19")
        );
    }

    public function test_self_attendance_calendar_respects_weekend_schedule_and_excused_status(): void
    {
        Carbon::setTestNow('2026-06-21 18:00:00');

        $professor = User::factory()->create(['role' => 'professor']);
        $calendar = app(AttendanceCalendarService::class);

        $this->actingAs($professor)
            ->get(route('attendance.show', ['month' => '2026-06']))
            ->assertOk()
            ->assertSeeText('No Class');

        $sunday = $calendar
            ->daysForUser($professor->fresh(), Carbon::parse('2026-06-21'), Carbon::parse('2026-06-22'), now(), collect())
            ->first();
        $this->assertSame('no_class', $sunday['state']);

        Schedule::create([
            'user_id' => $professor->id,
            'subject' => 'Weekend Class',
            'room' => 'IT-202',
            'day_of_week' => 'Sunday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $this->actingAs($professor)
            ->get(route('attendance.show', ['month' => '2026-06']))
            ->assertOk();

        $sunday = $calendar
            ->daysForUser($professor->fresh(), Carbon::parse('2026-06-21'), Carbon::parse('2026-06-22'), now(), collect())
            ->first();
        $this->assertSame('absent', $sunday['state']);

        SpecialSchedule::create([
            'user_id' => $professor->id,
            'type' => 'On Meeting',
            'start_datetime' => Carbon::parse('2026-06-21 08:00:00'),
            'end_datetime' => Carbon::parse('2026-06-21 11:00:00'),
        ]);

        $this->actingAs($professor)
            ->get(route('attendance.show', ['month' => '2026-06']))
            ->assertOk()
            ->assertSeeText('Excused');

        $sunday = $calendar
            ->daysForUser($professor->fresh(), Carbon::parse('2026-06-21'), Carbon::parse('2026-06-22'), now(), collect())
            ->first();
        $this->assertSame('excused', $sunday['state']);
    }
    public function test_geofenced_time_in_requires_a_location_inside_the_allowed_radius(): void
    {
        config([
            'attendance.geofence.enabled' => true,
            'attendance.geofence.latitude' => 16.987778,
            'attendance.geofence.longitude' => 121.718611,
            'attendance.geofence.radius_meters' => 75,
            'attendance.geofence.max_accuracy_meters' => 150,
        ]);

        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($professor)
            ->post(route('attendance.time_in'), [
                'latitude' => 16.987778,
                'longitude' => 121.718611,
                'accuracy' => 20,
            ])
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('success');

        $record = AttendanceRecord::first();

        $this->assertTrue($record->time_in_location_verified);
        $this->assertSame('verified', $record->time_in_location_status);
        $this->assertEqualsWithDelta(16.987778, $record->time_in_latitude, 0.000001);
        $this->assertEqualsWithDelta(121.718611, $record->time_in_longitude, 0.000001);
        $this->assertLessThanOrEqual(1, $record->time_in_distance_meters);
    }

    public function test_geofenced_time_in_blocks_people_outside_the_allowed_radius(): void
    {
        config([
            'attendance.geofence.enabled' => true,
            'attendance.geofence.latitude' => 16.987778,
            'attendance.geofence.longitude' => 121.718611,
            'attendance.geofence.radius_meters' => 75,
            'attendance.geofence.max_accuracy_meters' => 150,
        ]);

        $professor = User::factory()->create(['role' => 'professor']);

        $this->actingAs($professor)
            ->post(route('attendance.time_in'), [
                'latitude' => 16.990000,
                'longitude' => 121.718611,
                'accuracy' => 20,
            ])
            ->assertRedirect(route('attendance.show'))
            ->assertSessionHas('error', 'You are outside the allowed time-in area.');

        $this->assertDatabaseCount('attendance_records', 0);
    }
}
