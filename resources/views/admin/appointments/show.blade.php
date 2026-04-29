@extends('admin.layouts.app')
@section('title', 'Appointment #{{ $appointment->id }}')
@section('page-title', 'Appointment Details')

@section('header-actions')
    <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline">← Back</a>
@endsection

@section('content')
<div class="form-grid">
    <div class="card">
        <div class="card-header">
            <h3>Patient Information</h3>
            <span class="badge {{ $appointment->status_badge }} badge-lg">{{ $appointment->status_label }}</span>
        </div>
        <div class="card-body">
            <table class="detail-table">
                <tr><td>Patient Name</td><td><strong>{{ $appointment->patient_name }}</strong></td></tr>
                <tr><td>Phone</td><td><a href="tel:{{ $appointment->patient_phone }}">{{ $appointment->patient_phone }}</a></td></tr>
                <tr><td>Doctor</td><td>{{ $appointment->doctor->name }}</td></tr>
                <tr><td>Department</td><td>{{ $appointment->doctor->department->name ?? '—' }}</td></tr>
                <tr><td>Date</td><td>{{ $appointment->appointment_date->format('l, d F Y') }}</td></tr>
                <tr><td>Time Slot</td><td>{{ \Carbon\Carbon::parse($appointment->slot_time)->format('h:i A') }}</td></tr>
                @if($appointment->notes)
                <tr><td>Notes</td><td>{{ $appointment->notes }}</td></tr>
                @endif
                @if($appointment->confirmed_at)
                <tr><td>Confirmed At</td><td>{{ $appointment->confirmed_at->format('d M Y, h:i A') }}</td></tr>
                @endif
                <tr><td>Booked At</td><td>{{ $appointment->created_at->format('d M Y, h:i A') }}</td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Actions</h3></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
            @can('appointment.confirm')
            @if($appointment->isPending())
            <form method="POST" action="{{ route('admin.appointments.confirm', $appointment) }}">
                @csrf @method('PATCH')
                <button class="btn btn-success btn-block">✓ Confirm Appointment</button>
            </form>
            @endif
            @endcan

            @can('appointment.complete')
            @if($appointment->isConfirmed())
            <form method="POST" action="{{ route('admin.appointments.complete', $appointment) }}">
                @csrf @method('PATCH')
                <button class="btn btn-primary btn-block">✔ Mark as Completed</button>
            </form>
            @endif
            @endcan

            @can('appointment.cancel')
            @if(!in_array($appointment->status, ['cancelled','completed']))
            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}"
                  onsubmit="return confirm('Cancel this appointment?')">
                @csrf @method('PATCH')
                <button class="btn btn-danger btn-block">✕ Cancel Appointment</button>
            </form>
            @endif
            @endcan

            @if(in_array($appointment->status, ['cancelled','completed']))
            <p class="text-muted text-center">No further actions available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
