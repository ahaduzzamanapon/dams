<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Services\SlotGeneratorService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private readonly SlotGeneratorService $slotGenerator) {}

    public function showForm(Request $request)
    {
        $departments = Department::active()->with('activeDoctors')->get();
        $selectedDoctor = null;

        if ($request->filled('doctor')) {
            $selectedDoctor = Doctor::where('slug', $request->input('doctor'))
                ->where('is_active', true)
                ->with(['department', 'fees', 'schedules'])
                ->first();
        }

        return view('appointment.index', compact('departments', 'selectedDoctor'));
    }

    public function getDoctors(Request $request)
    {
        $request->validate(['department_id' => ['required', 'exists:departments,id']]);

        $doctors = Doctor::active()
            ->where('department_id', $request->integer('department_id'))
            ->select('id', 'name', 'designation', 'slug')
            ->get();

        return response()->json($doctors);
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date'      => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = $this->slotGenerator->getAvailableSlotsForDate(
            $request->integer('doctor_id'),
            $request->input('date')
        );

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'        => ['required', 'exists:doctors,id'],
            'patient_name'     => ['required', 'string', 'max:150'],
            'patient_phone'    => ['required', 'string', 'max:20'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'slot_time'        => ['required', 'date_format:H:i'],
        ]);

        // Verify slot is still available
        $exists = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('slot_time', $validated['slot_time'])
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->exists();

        if ($exists) {
            return back()->withErrors(['slot_time' => 'This slot has just been booked. Please choose another.'])->withInput();
        }

        $appointment = Appointment::create(array_merge($validated, ['status' => Appointment::STATUS_PENDING]));

        return redirect()->route('appointment.form')->with('booking_success', [
            'name'   => $appointment->patient_name,
            'doctor' => $appointment->doctor->name,
            'date'   => $appointment->appointment_date->format('d M Y'),
            'time'   => $appointment->slot_time,
        ]);
    }
}
