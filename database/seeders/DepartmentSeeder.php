<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Neurology',     'icon' => '🧠', 'description' => 'Brain, spine and nervous system disorders.', 'order' => 1],
            ['name' => 'Cardiology',    'icon' => '❤️', 'description' => 'Heart and cardiovascular system care.', 'order' => 2],
            ['name' => 'Orthopedics',   'icon' => '🦴', 'description' => 'Bones, joints, ligaments and muscles.', 'order' => 3],
            ['name' => 'Medicine',      'icon' => '💊', 'description' => 'General internal medicine and chronic disease.', 'order' => 4],
            ['name' => 'Surgery',       'icon' => '🔪', 'description' => 'General and specialized surgical procedures.', 'order' => 5],
            ['name' => 'ENT',           'icon' => '👂', 'description' => 'Ear, nose and throat specialist care.', 'order' => 6],
            ['name' => 'Gynecology',    'icon' => '🤱', 'description' => "Women's health and reproductive medicine.", 'order' => 7],
            ['name' => 'Dermatology',   'icon' => '🩺', 'description' => 'Skin, hair and nail conditions.', 'order' => 8],
        ];

        foreach ($departments as $data) {
            Department::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['slug' => \Illuminate\Support\Str::slug($data['name'])])
            );
        }
    }
}
