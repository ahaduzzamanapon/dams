<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stats = [
            'total_doctors'         => Doctor::where('is_active', true)->count(),
            'total_departments'     => Department::where('is_active', true)->count(),
            'pending_appointments'  => Appointment::pending()->count(),
            'today_appointments'    => Appointment::forDate($today)->count(),
            'today_confirmed'       => Appointment::forDate($today)->confirmed()->count(),
        ];

        $todayAppointments = Appointment::with('doctor.department')
            ->forDate($today)
            ->orderBy('slot_time')
            ->get();

        $recentAppointments = Appointment::with('doctor')
            ->where('appointment_date', '<', $today)
            ->orderByDesc('appointment_date')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'todayAppointments', 'recentAppointments'));
    }
}
