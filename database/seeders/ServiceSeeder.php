<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['title' => 'Emergency Care',          'icon' => '🚑', 'description' => '24/7 emergency medical services and rapid response care for critical conditions.', 'order' => 1],
            ['title' => 'Diagnostic Imaging',       'icon' => '🩻', 'description' => 'State-of-the-art X-Ray, CT Scan, MRI, and Ultrasound imaging services.', 'order' => 2],
            ['title' => 'Pathology Laboratory',     'icon' => '🧪', 'description' => 'Comprehensive blood tests, urine analysis, and microbiological investigations.', 'order' => 3],
            ['title' => 'ECG & Echo',               'icon' => '❤️', 'description' => 'Electrocardiogram and echocardiography for heart function assessment.', 'order' => 4],
            ['title' => 'Physiotherapy',            'icon' => '🦽', 'description' => 'Rehabilitation and physiotherapy for musculoskeletal and neurological conditions.', 'order' => 5],
            ['title' => 'Pharmacy',                 'icon' => '💊', 'description' => 'In-house pharmacy stocked with all prescribed and over-the-counter medications.', 'order' => 6],
            ['title' => 'Vaccination',              'icon' => '💉', 'description' => 'Childhood immunization schedule and adult vaccination programs.', 'order' => 7],
            ['title' => 'Health Checkup Packages',  'icon' => '📋', 'description' => 'Comprehensive annual health checkup packages tailored for all age groups.', 'order' => 8],
        ];

        foreach ($services as $data) {
            Service::firstOrCreate(['title' => $data['title']], array_merge($data, ['is_active' => true]));
        }
    }
}
