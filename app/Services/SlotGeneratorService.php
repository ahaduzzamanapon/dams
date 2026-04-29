<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class SlotGeneratorService
{
    /**
     * Generate available time slots for a given schedule and date.
     *
     * @return array<string> Available HH:MM slots
     */
    public function generateSlots(DoctorSchedule $schedule, Carbon $date): array
    {
        $bookedSlots = Appointment::query()
            ->forDoctor($schedule->doctor_id)
            ->forDate($date->toDateString())
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->pluck('slot_time')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $slots = [];
        $current = Carbon::parse($date->toDateString().' '.$schedule->start_time);
        $end = Carbon::parse($date->toDateString().' '.$schedule->end_time);

        while ($current->lt($end)) {
            $slotTime = $current->format('H:i');

            if (! in_array($slotTime, $bookedSlots)) {
                $slots[] = $slotTime;
            }

            $current->addMinutes($schedule->slot_duration_minutes);
        }

        return $slots;
    }

    /**
     * Get available slots for a doctor on a given date across all their schedules.
     *
     * @return array<string>
     */
    public function getAvailableSlotsForDate(int $doctorId, string $date): array
    {
        $carbon = Carbon::parse($date);
        $dayOfWeek = (int) $carbon->dayOfWeek; // 0=Sunday

        $schedules = \App\Models\DoctorSchedule::query()
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->active()
            ->get();

        $allSlots = [];
        foreach ($schedules as $schedule) {
            $allSlots = array_merge($allSlots, $this->generateSlots($schedule, $carbon));
        }

        sort($allSlots);

        return array_unique($allSlots);
    }
}
