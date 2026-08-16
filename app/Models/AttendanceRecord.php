<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    private const AUTO_TIME_OUT_HOUR = 19;

    protected $fillable = [
        'user_id',
        'time_in',
        'time_in_latitude',
        'time_in_longitude',
        'time_in_accuracy_meters',
        'time_in_distance_meters',
        'time_in_location_verified',
        'time_in_location_status',
        'time_in_ip',
        'time_in_user_agent',
        'time_out',
        'forced_time_out_by',
        'forced_time_out_at',
        'force_time_out_reason',
    ];

    protected function casts(): array
    {
        return [
            'time_in' => 'datetime',
            'time_out' => 'datetime',
            'forced_time_out_at' => 'datetime',
            'time_in_latitude' => 'float',
            'time_in_longitude' => 'float',
            'time_in_accuracy_meters' => 'float',
            'time_in_distance_meters' => 'float',
            'time_in_location_verified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function forcedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'forced_time_out_by');
    }

    public static function autoTimeOutExpiredOpenSessions(?Carbon $moment = null): int
    {
        $moment ??= Carbon::now();
        $updated = 0;

        static::query()
            ->whereNull('time_out')
            ->where('time_in', '<=', $moment)
            ->oldest('time_in')
            ->get()
            ->each(function (self $attendance) use ($moment, &$updated) {
                $timeOutAt = $attendance->automaticTimeOutAt();

                if ($moment->lt($timeOutAt)) {
                    return;
                }

                $attendance->forceFill(['time_out' => $timeOutAt])->save();
                $attendance->closeTemporaryAvailabilityAt($timeOutAt);
                $updated++;
            });

        return $updated;
    }

    public function automaticTimeOutAt(): Carbon
    {
        $timeIn = $this->time_in instanceof Carbon
            ? $this->time_in->copy()
            : Carbon::parse($this->time_in);
        $cutoff = $timeIn->copy()->setTime(self::AUTO_TIME_OUT_HOUR, 0, 0);

        if ($timeIn->gt($cutoff)) {
            $cutoff->addDay();
        }

        return $cutoff;
    }

    private function closeTemporaryAvailabilityAt(Carbon $timeOutAt): void
    {
        SpecialSchedule::query()
            ->where('user_id', $this->user_id)
            ->whereIn('type', ['On Break', 'Not Available'])
            ->where('start_datetime', '<=', $timeOutAt)
            ->where('end_datetime', '>=', $timeOutAt)
            ->update(['end_datetime' => $timeOutAt->copy()->subSecond()]);
    }
}



