<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\SpecialSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnedRecordAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_pages_only_show_and_allow_the_signed_in_users_records(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $otherProfessor = User::factory()->create(['role' => 'professor']);

        $ownSchedule = Schedule::create([
            'user_id' => $professor->id,
            'subject' => 'Data Structures',
            'room' => 'IT-201',
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $otherSchedule = Schedule::create([
            'user_id' => $otherProfessor->id,
            'subject' => 'Private Other Schedule',
            'room' => 'IT-202',
            'day_of_week' => 'Tuesday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $this->actingAs($professor)
            ->get(route('schedules.create'))
            ->assertOk()
            ->assertSeeText($ownSchedule->subject)
            ->assertDontSeeText($otherSchedule->subject);

        $this->actingAs($professor)
            ->get(route('schedules.edit', $otherSchedule))
            ->assertRedirect(route('schedules.create'))
            ->assertSessionHas('error');

        $this->actingAs($professor)
            ->put(route('schedules.update', $otherSchedule), [
                'subject' => 'Tampered Subject',
                'room' => 'IT-303',
                'day_of_week' => 'Friday',
                'start_time' => '13:00',
                'end_time' => '14:00',
                'semester' => '2nd Semester',
                'school_year' => '2026-2027',
            ])
            ->assertRedirect(route('schedules.create'))
            ->assertSessionHas('error');

        $this->assertSame('Private Other Schedule', $otherSchedule->fresh()->subject);
    }

    public function test_special_schedule_routes_block_records_owned_by_other_staff(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $otherProfessor = User::factory()->create(['role' => 'professor']);

        $otherSpecialSchedule = SpecialSchedule::create([
            'user_id' => $otherProfessor->id,
            'type' => 'On Meeting',
            'start_datetime' => '2026-08-07 08:00:00',
            'end_datetime' => '2026-08-07 09:00:00',
            'note' => 'Private status',
        ]);

        $this->actingAs($professor)
            ->get(route('special_schedules.edit', $otherSpecialSchedule))
            ->assertForbidden();

        $this->actingAs($professor)
            ->put(route('special_schedules.update', $otherSpecialSchedule), [
                'type' => 'Emergency',
                'start_datetime' => '2026-08-07 10:00:00',
                'end_datetime' => '2026-08-07 11:00:00',
                'note' => 'Tampered status',
            ])
            ->assertForbidden();

        $this->assertSame('On Meeting', $otherSpecialSchedule->fresh()->type);
    }
}
