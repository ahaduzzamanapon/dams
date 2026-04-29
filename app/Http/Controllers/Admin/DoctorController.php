<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDoctorRequest;
use App\Http\Requests\Admin\UpdateDoctorRequest;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorFee;
use App\Models\DoctorSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $this->authorize('doctor.view');
        $doctors = Doctor::with('department')->orderBy('order')->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        $this->authorize('doctor.create');
        $departments = Department::active()->get();

        return view('admin.doctors.create', compact('departments'));
    }

    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $fees = $data['fees'] ?? [];
        $schedules = $data['schedules'] ?? [];
        unset($data['fees'], $data['schedules']);

        $doctor = Doctor::create($data);

        foreach ($fees as $fee) {
            $doctor->fees()->create($fee);
        }

        foreach ($schedules as $schedule) {
            $doctor->schedules()->create($schedule);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function show(Doctor $doctor): View
    {
        $this->authorize('doctor.view');
        $doctor->load(['department', 'fees', 'schedules', 'appointments' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor): View
    {
        $this->authorize('doctor.edit');
        $departments = Department::active()->get();
        $doctor->load(['fees', 'schedules']);

        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $fees = $data['fees'] ?? null;
        $schedules = $data['schedules'] ?? null;
        unset($data['fees'], $data['schedules']);

        $doctor->update($data);

        if ($fees !== null) {
            $doctor->fees()->delete();
            foreach ($fees as $fee) {
                $doctor->fees()->create($fee);
            }
        }

        if ($schedules !== null) {
            $doctor->schedules()->delete();
            foreach ($schedules as $schedule) {
                $doctor->schedules()->create($schedule);
            }
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $this->authorize('doctor.delete');

        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }

        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted.');
    }

    public function toggle(Doctor $doctor): RedirectResponse
    {
        $this->authorize('doctor.toggle');
        $doctor->update(['is_active' => ! $doctor->is_active]);
        $status = $doctor->is_active ? 'activated' : 'hidden';

        return back()->with('success', "Doctor profile {$status} successfully.");
    }
}
