<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();

        if ($doctors->isEmpty()) {
            return;
        }

        $statuses = [
            Appointment::STATUS_PENDING,
            Appointment::STATUS_CONFIRMED,
            Appointment::STATUS_COMPLETED,
            Appointment::STATUS_CANCELLED,
        ];

        $names = [
            'Rahim Uddin', 'Karim Ali', 'Fatema Begum', 'Nasrin Akter',
            'Mohammed Hasan', 'Sadia Islam', 'Rubel Mia', 'Shirin Sultana',
            'Tariq Ahmed', 'Parvin Khatun', 'Abul Bashar', 'Monira Begum',
            'Jamal Hossain', 'Roksana Khanam', 'Delwar Hossain',
        ];

        $phones = [
            '01711-234567', '01812-345678', '01913-456789',
            '01614-567890', '01715-678901', '01516-789012',
        ];

        $slots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                  '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'];

        $today = Carbon::today();

        // Today's appointments (mix of pending & confirmed)
        foreach ($doctors->take(4) as $i => $doctor) {
            foreach (['09:00', '09:30', '10:00'] as $j => $slot) {
                Appointment::create([
                    'doctor_id'        => $doctor->id,
                    'patient_name'     => $names[($i * 3 + $j) % count($names)],
                    'patient_phone'    => $phones[$j % count($phones)],
                    'appointment_date' => $today->toDateString(),
                    'slot_time'        => $slot,
                    'status'           => $j === 0 ? Appointment::STATUS_CONFIRMED : Appointment::STATUS_PENDING,
                    'confirmed_at'     => $j === 0 ? now() : null,
                ]);
            }
        }

        // Past appointments (last 30 days)
        for ($d = 1; $d <= 30; $d++) {
            $date = $today->copy()->subDays($d)->toDateString();
            $count = rand(3, 8);
            for ($k = 0; $k < $count; $k++) {
                $doctor = $doctors->random();
                Appointment::create([
                    'doctor_id'        => $doctor->id,
                    'patient_name'     => $names[array_rand($names)],
                    'patient_phone'    => $phones[array_rand($phones)],
                    'appointment_date' => $date,
                    'slot_time'        => $slots[array_rand($slots)],
                    'status'           => $statuses[array_rand($statuses)],
                    'confirmed_at'     => now()->subDays($d),
                ]);
            }
        }

        // Future appointments (next 7 days)
        for ($d = 1; $d <= 7; $d++) {
            $date = $today->copy()->addDays($d)->toDateString();
            $count = rand(2, 5);
            for ($k = 0; $k < $count; $k++) {
                $doctor = $doctors->random();
                Appointment::create([
                    'doctor_id'        => $doctor->id,
                    'patient_name'     => $names[array_rand($names)],
                    'patient_phone'    => $phones[array_rand($phones)],
                    'appointment_date' => $date,
                    'slot_time'        => $slots[array_rand($slots)],
                    'status'           => Appointment::STATUS_PENDING,
                    'confirmed_at'     => null,
                ]);
            }
        }
    }
}
