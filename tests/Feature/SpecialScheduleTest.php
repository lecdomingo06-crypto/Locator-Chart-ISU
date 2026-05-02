<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_an_on_meeting_special_schedule(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $response = $this
            ->actingAs($teacher)
            ->post(route('special_schedules.store'), [
                'type' => 'On Meeting',
                'start_datetime' => '2026-04-11 10:00:00',
                'end_datetime' => '2026-04-11 12:00:00',
                'note' => 'Department meeting',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('special_schedules.index'));

        $this->assertDatabaseHas('special_schedules', [
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'note' => 'Department meeting',
        ]);
    }

    public function test_on_meeting_can_extend_to_the_end_of_the_overlapping_class_when_requested(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $meetingEnd = Carbon::parse('2026-04-13 10:30:00');

        Schedule::create([
            'user_id' => $teacher->id,
            'subject' => 'Programming',
            'room' => 'IT-202',
            'day_of_week' => $meetingEnd->format('l'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this
            ->actingAs($teacher)
            ->post(route('special_schedules.store'), [
                'type' => 'On Meeting',
                'start_datetime' => '2026-04-13 09:00:00',
                'end_datetime' => '2026-04-13 10:30:00',
                'note' => 'Department meeting',
                'keep_until_schedule_end' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('special_schedules.index'));

        $this->assertDatabaseHas('special_schedules', [
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'end_datetime' => '2026-04-13 12:00:00',
            'keep_until_schedule_end' => true,
        ]);
    }

    public function test_on_meeting_keeps_the_entered_end_time_when_class_extension_is_not_requested(): void
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $meetingEnd = Carbon::parse('2026-04-13 10:30:00');

        Schedule::create([
            'user_id' => $teacher->id,
            'subject' => 'Programming',
            'room' => 'IT-202',
            'day_of_week' => $meetingEnd->format('l'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this
            ->actingAs($teacher)
            ->post(route('special_schedules.store'), [
                'type' => 'On Meeting',
                'start_datetime' => '2026-04-13 09:00:00',
                'end_datetime' => '2026-04-13 10:30:00',
                'note' => 'Department meeting',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('special_schedules.index'));

        $this->assertDatabaseHas('special_schedules', [
            'user_id' => $teacher->id,
            'type' => 'On Meeting',
            'end_datetime' => '2026-04-13 10:30:00',
            'keep_until_schedule_end' => false,
        ]);
    }

    public function test_faculty_cannot_apply_the_class_extension_flag_to_on_meeting(): void
    {
        $faculty = User::factory()->create([
            'role' => 'faculty',
        ]);

        $meetingEnd = Carbon::parse('2026-04-13 10:30:00');

        Schedule::create([
            'user_id' => $faculty->id,
            'subject' => 'Office Duty',
            'room' => 'Admin-101',
            'day_of_week' => $meetingEnd->format('l'),
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this
            ->actingAs($faculty)
            ->post(route('special_schedules.store'), [
                'type' => 'On Meeting',
                'start_datetime' => '2026-04-13 09:00:00',
                'end_datetime' => '2026-04-13 10:30:00',
                'note' => 'Council meeting',
                'keep_until_schedule_end' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('special_schedules.index'));

        $this->assertDatabaseHas('special_schedules', [
            'user_id' => $faculty->id,
            'type' => 'On Meeting',
            'end_datetime' => '2026-04-13 10:30:00',
            'keep_until_schedule_end' => false,
        ]);
    }
}
