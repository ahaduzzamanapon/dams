<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_COMPLETED = 'completed';

    /** @var array<string, string> Status labels for display */
    public const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_COMPLETED => 'Completed',
    ];

    /** @var array<string, string> Status badge CSS classes */
    public const STATUS_BADGES = [
        self::STATUS_PENDING => 'badge-warning',
        self::STATUS_CONFIRMED => 'badge-success',
        self::STATUS_CANCELLED => 'badge-danger',
        self::STATUS_COMPLETED => 'badge-info',
    ];

    protected $fillable = [
        'doctor_id',
        'patient_name',
        'patient_phone',
        'appointment_date',
        'slot_time',
        'status',
        'notes',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('appointment_date', $date);
    }

    public function scopeForDoctor($query, int|Doctor $doctor)
    {
        $id = $doctor instanceof Doctor ? $doctor->id : $doctor;

        return $query->where('doctor_id', $id);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::STATUS_BADGES[$this->status] ?? 'badge-secondary';
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }
}
