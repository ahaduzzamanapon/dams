<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory;

    /** @var array<int, string> Day names indexed by day_of_week (0=Sunday) */
    public const DAY_NAMES = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    /** @var array<int, string> Short day names */
    public const DAY_SHORT = [
        0 => 'Sun',
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
    ];

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'slot_duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getDayNameAttribute(): string
    {
        return self::DAY_NAMES[$this->day_of_week] ?? 'Unknown';
    }

    public function getDayShortAttribute(): string
    {
        return self::DAY_SHORT[$this->day_of_week] ?? '?';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
