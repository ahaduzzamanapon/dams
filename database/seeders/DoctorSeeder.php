<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorFee;
use App\Models\DoctorSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $neurology = Department::where('name', 'Neurology')->first();
        $cardiology = Department::where('name', 'Cardiology')->first();
        $medicine = Department::where('name', 'Medicine')->first();
        $gynecology = Department::where('name', 'Gynecology')->first();

        $doctors = [
            [
                'department_id' => $neurology?->id ?? 1,
                'name'          => 'Dr. Md. Rafiqul Islam',
                'designation'   => 'Consultant Neurologist',
                'bmdc_no'       => 'A-12345',
                'degrees'       => 'MBBS, MD (Neurology)',
                'is_featured'   => true,
                'fees'          => [['label' => 'New Patient', 'amount' => 800], ['label' => 'Report Showing', 'amount' => 500]],
                'schedules'     => [
                    ['day_of_week' => 1, 'start_time' => '17:00', 'end_time' => '20:00', 'slot_duration_minutes' => 20],
                    ['day_of_week' => 3, 'start_time' => '17:00', 'end_time' => '20:00', 'slot_duration_minutes' => 20],
                ],
            ],
            [
                'department_id' => $cardiology?->id ?? 2,
                'name'          => 'Dr. Farzana Begum',
                'designation'   => 'Senior Cardiologist',
                'bmdc_no'       => 'A-23456',
                'degrees'       => 'MBBS, FCPS (Cardiology)',
                'is_featured'   => true,
                'fees'          => [['label' => 'New Patient', 'amount' => 1000], ['label' => 'Report Showing', 'amount' => 600]],
                'schedules'     => [
                    ['day_of_week' => 0, 'start_time' => '16:00', 'end_time' => '19:00', 'slot_duration_minutes' => 15],
                    ['day_of_week' => 4, 'start_time' => '16:00', 'end_time' => '19:00', 'slot_duration_minutes' => 15],
                ],
            ],
            [
                'department_id' => $medicine?->id ?? 4,
                'name'          => 'Dr. Kamal Hossain',
                'designation'   => 'Professor, Internal Medicine',
                'bmdc_no'       => 'A-34567',
                'degrees'       => 'MBBS, FCPS (Medicine)',
                'is_featured'   => true,
                'fees'          => [['label' => 'New Patient', 'amount' => 700], ['label' => 'Report Showing', 'amount' => 400]],
                'schedules'     => [
                    ['day_of_week' => 6, 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration_minutes' => 20],
                    ['day_of_week' => 2, 'start_time' => '17:00', 'end_time' => '21:00', 'slot_duration_minutes' => 20],
                ],
            ],
            [
                'department_id' => $gynecology?->id ?? 7,
                'name'          => 'Dr. Nasrin Akter',
                'designation'   => 'Consultant Gynecologist & Obstetrician',
                'bmdc_no'       => 'A-45678',
                'degrees'       => 'MBBS, MS (Gynecology)',
                'is_featured'   => false,
                'fees'          => [['label' => 'New Patient', 'amount' => 900], ['label' => 'Report Showing', 'amount' => 500]],
                'schedules'     => [
                    ['day_of_week' => 0, 'start_time' => '10:00', 'end_time' => '14:00', 'slot_duration_minutes' => 25],
                    ['day_of_week' => 3, 'start_time' => '15:00', 'end_time' => '19:00', 'slot_duration_minutes' => 25],
                ],
            ],
        ];

        foreach ($doctors as $data) {
            $fees = $data['fees'];
            $schedules = $data['schedules'];
            unset($data['fees'], $data['schedules']);

            $doctor = Doctor::firstOrCreate(
                ['bmdc_no' => $data['bmdc_no']],
                array_merge($data, ['slug' => Str::slug($data['name']), 'is_active' => true, 'order' => 0])
            );

            foreach ($fees as $fee) {
                DoctorFee::firstOrCreate(['doctor_id' => $doctor->id, 'label' => $fee['label']], $fee + ['doctor_id' => $doctor->id]);
            }

            foreach ($schedules as $schedule) {
                DoctorSchedule::firstOrCreate(
                    ['doctor_id' => $doctor->id, 'day_of_week' => $schedule['day_of_week']],
                    $schedule + ['doctor_id' => $doctor->id, 'is_active' => true]
                );
            }
        }
    }
}
