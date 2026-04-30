<?php

namespace App\Models;

use Database\Factories\DoctorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Doctor extends Model
{
    /** @use HasFactory<DoctorFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'department_id',
        'name',
        'slug',
        'designation',
        'bmdc_no',
        'degrees',
        'photo',
        'bio',
        'is_active',
        'is_featured',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor) {
            if (empty($doctor->slug)) {
                $doctor->slug = Str::slug($doctor->name);
            }
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(DoctorFee::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class)->orderBy('day_of_week');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->orderByDesc('appointment_date');
    }

    /** @return string|null URL of doctor photo or null */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return asset('storage/'.$this->photo);
        }

        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true)->orderBy('order');
    }

    public function scopeForDepartment($query, int|Department $department)
    {
        $id = $department instanceof Department ? $department->id : $department;

        return $query->where('department_id', $id);
    }
}
