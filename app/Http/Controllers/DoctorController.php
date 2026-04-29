<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::active()->get();
        $selectedDept = $request->input('department');
        $search = $request->input('search');

        $query = Doctor::active()->with('department')->select(
            'id', 'name', 'slug', 'designation', 'department_id', 'photo', 'degrees', 'bmdc_no'
        );

        if ($selectedDept) {
            $query->whereHas('department', fn ($q) => $q->where('slug', $selectedDept));
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('order')->paginate(12)->withQueryString();

        return view('doctors.index', compact('doctors', 'departments', 'selectedDept', 'search'));
    }

    public function show(string $slug)
    {
        $doctor = Doctor::where('slug', $slug)
            ->where('is_active', true)
            ->with(['department', 'fees', 'schedules'])
            ->firstOrFail();

        return view('doctors.show', compact('doctor'));
    }
}
