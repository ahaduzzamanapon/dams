<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Notifications\AppointmentConfirmed;
use App\Services\SlotGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private readonly SlotGeneratorService $slotGenerator) {}

    public function index(Request $request): View
    {
        $this->authorize('appointment.view');

        $query = Appointment::with('doctor.department')->orderByDesc('appointment_date')->orderBy('slot_time');

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->integer('doctor_id'));
        }
        if ($request->filled('date')) {
            $query->forDate($request->input('date'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $appointments = $query->paginate(20)->withQueryString();
        $doctors = Doctor::active()->select('id', 'name')->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    public function show(Appointment $appointment): View
    {
        $this->authorize('appointment.view');
        $appointment->load('doctor.department');

        return view('admin.appointments.show', compact('appointment'));
    }

    public function create(): View
    {
        $this->authorize('appointment.create');
        $departments = Department::active()->with('activeDoctors')->get();
        $doctors = Doctor::active()->select('id', 'name', 'department_id')->get();

        return view('admin.appointments.create', compact('departments', 'doctors'));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = Appointment::create(array_merge($request->validated(), ['status' => Appointment::STATUS_PENDING]));

        return redirect()->route('admin.appointments.show', $appointment)->with('success', 'Appointment created successfully.');
    }

    public function confirm(Appointment $appointment): RedirectResponse
    {
        $this->authorize('appointment.confirm');

        if (! $appointment->isPending()) {
            return back()->with('error', 'Only pending appointments can be confirmed.');
        }

        $appointment->update([
            'status'       => Appointment::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);

        // Load doctor for notification
        $appointment->load('doctor');

        // Send confirmation notification (queued)
        $appointment->doctor->notify(new AppointmentConfirmed($appointment));

        return back()->with('success', "Appointment confirmed. Notification sent to {$appointment->patient_name}.");
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $this->authorize('appointment.cancel');

        if ($appointment->status === Appointment::STATUS_COMPLETED) {
            return back()->with('error', 'Completed appointments cannot be cancelled.');
        }

        $appointment->update(['status' => Appointment::STATUS_CANCELLED]);

        return back()->with('success', 'Appointment cancelled.');
    }

    public function complete(Appointment $appointment): RedirectResponse
    {
        $this->authorize('appointment.complete');

        $appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        return back()->with('success', 'Appointment marked as completed.');
    }

    public function dailySheet(Request $request): View
    {
        $this->authorize('appointment.print');

        $date    = $request->input('date', now()->toDateString());
        $doctorId = $request->input('doctor_id');

        $query = Appointment::with('doctor.department')
            ->forDate($date)
            ->orderBy('slot_time');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get();
        $doctors      = Doctor::active()->select('id', 'name')->get();

        return view('admin.appointments.daily-sheet', compact('appointments', 'doctors', 'date'));
    }
}
