<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctors = Doctor::active()
            ->with(['department', 'schedules' => fn ($q) => $q->active()->orderBy('day_of_week')])
            ->orderBy('department_id')
            ->orderBy('order')
            ->get();

        return view('schedule.index', compact('doctors'));
    }
}
