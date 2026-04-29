<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions grouped by module
        $permissions = [
            // Department
            'department.view', 'department.create', 'department.edit', 'department.delete',
            // Doctor
            'doctor.view', 'doctor.create', 'doctor.edit', 'doctor.delete', 'doctor.toggle',
            // Appointment
            'appointment.view', 'appointment.create', 'appointment.confirm',
            'appointment.cancel', 'appointment.complete', 'appointment.print',
            // Service
            'service.view', 'service.create', 'service.edit', 'service.delete',
            // User Management
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.toggle',
            // Role Management
            'role.view', 'role.create', 'role.edit', 'role.delete',
            // Reports
            'report.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- Receptionist: appointments & reports only ---
        $receptionist = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionist->syncPermissions([
            'appointment.view', 'appointment.create', 'appointment.confirm',
            'appointment.cancel', 'appointment.complete', 'appointment.print',
            'report.view',
        ]);

        // --- Admin: everything except user & role management ---
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'department.view', 'department.create', 'department.edit', 'department.delete',
            'doctor.view', 'doctor.create', 'doctor.edit', 'doctor.delete', 'doctor.toggle',
            'appointment.view', 'appointment.create', 'appointment.confirm',
            'appointment.cancel', 'appointment.complete', 'appointment.print',
            'service.view', 'service.create', 'service.edit', 'service.delete',
            'report.view',
        ]);

        // --- Super Admin: ALL permissions ---
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());
    }
}
