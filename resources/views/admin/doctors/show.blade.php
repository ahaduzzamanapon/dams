@extends('admin.layouts.app')
@section('title', $doctor->name)
@section('page-title', $doctor->name)

@section('header-actions')
    @can('doctor.edit')
    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-primary">Edit Doctor</a>
    @endcan
    @can('doctor.toggle')
    <form method="POST" action="{{ route('admin.doctors.toggle', $doctor) }}" style="display:inline">
        @csrf @method('PATCH')
        <button class="btn {{ $doctor->is_active ? 'btn-warning' : 'btn-success' }}">
            {{ $doctor->is_active ? 'Hide Profile' : 'Activate Profile' }}
        </button>
    </form>
    @endcan
@endsection

@section('content')
<div class="form-grid">
    <div>
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($doctor->photo_url)
                    <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}" style="width:140px;height:140px;object-fit:cover;border-radius:50%;margin-bottom:12px">
                @else
                    <div style="font-size:5rem">👨‍⚕️</div>
                @endif
                <h2>{{ $doctor->name }}</h2>
                <p class="text-muted">{{ $doctor->designation }}</p>
                <span class="badge {{ $doctor->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $doctor->is_active ? 'Active' : 'Hidden' }}</span>
                @if($doctor->is_featured) <span class="badge badge-warning">⭐ Featured</span> @endif
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><h3>Details</h3></div>
            <div class="card-body">
                <table class="detail-table">
                    <tr><td>Department</td><td>{{ $doctor->department->name ?? '—' }}</td></tr>
                    <tr><td>Degrees</td><td>{{ $doctor->degrees ?? '—' }}</td></tr>
                    <tr><td>BMDC No.</td><td>{{ $doctor->bmdc_no ?? '—' }}</td></tr>
                    @if($doctor->bio)<tr><td>Bio</td><td>{{ $doctor->bio }}</td></tr>@endif
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><h3>Consultation Fees</h3></div>
            <div class="card-body">
                @forelse($doctor->fees as $fee)
                <div class="fee-display"><span>{{ $fee->label }}</span><strong>৳{{ number_format($fee->amount, 0) }}</strong></div>
                @empty<p class="text-muted">No fees set.</p>@endforelse
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>Chamber Schedule</h3></div>
            <div class="card-body">
                @forelse($doctor->schedules as $sch)
                <div class="schedule-display">
                    <span class="day-badge">{{ $sch->day_name }}</span>
                    <span>{{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}</span>
                    <small class="text-muted">{{ $sch->slot_duration_minutes }} min/slot</small>
                </div>
                @empty<p class="text-muted">No schedule set.</p>@endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Recent Appointments (10)</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Date</th><th>Patient</th><th>Time</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($doctor->appointments as $apt)
                        <tr>
                            <td>{{ $apt->appointment_date->format('d M Y') }}</td>
                            <td>{{ $apt->patient_name }}<br><small>{{ $apt->patient_phone }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($apt->slot_time)->format('h:i A') }}</td>
                            <td><span class="badge {{ $apt->status_badge }}">{{ $apt->status_label }}</span></td>
                        </tr>
                        @empty<tr><td colspan="4" class="text-center py-3">No appointments yet.</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
