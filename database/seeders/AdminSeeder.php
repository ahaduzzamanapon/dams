<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@dams.com'],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@dams.com'],
            [
                'name'      => 'Admin User',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        // Receptionist
        $receptionist = User::firstOrCreate(
            ['email' => 'receptionist@dams.com'],
            [
                'name'      => 'Reception Desk',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $receptionist->syncRoles(['receptionist']);
    }
}
