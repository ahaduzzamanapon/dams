@extends('admin.layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('header-actions')
    @can('appointment.create')
    <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">+ Manual Entry</a>
    @endcan
    @can('appointment.print')
    <a href="{{ route('admin.appointments.daily-sheet') }}" class="btn btn-outline">🖨️ Daily Sheet</a>
    @endcan
@endsection

@section('content')
{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="filter-form">
            <div class="form-group">
                <label>Doctor</label>
                <select name="doctor_id" class="form-control">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected':'' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    @foreach(\App\Models\Appointment::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('status') == $val ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="align-self:flex-end">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="46">#</th>
                        <th>Date ↓</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td class="text-muted" style="font-size:12px;font-weight:600">
                            {{ $appointments->firstItem() + $loop->index }}
                        </td>
                        <td><strong>{{ $apt->appointment_date->format('d M Y') }}</strong></td>
                        <td><span class="badge badge-secondary">{{ \Carbon\Carbon::parse($apt->slot_time)->format('h:i A') }}</span></td>
                        <td>{{ $apt->patient_name }}</td>
                        <td><a href="tel:{{ $apt->patient_phone }}" style="color:var(--primary)">{{ $apt->patient_phone }}</a></td>
                        <td>{{ $apt->doctor->name }}</td>
                        <td><span class="badge {{ $apt->status_badge }}">{{ $apt->status_label }}</span></td>
                        <td class="action-btns">
                            @can('appointment.view')
                            <a href="{{ route('admin.appointments.show', $apt) }}" class="btn btn-sm btn-outline">View</a>
                            @endcan
                            @can('appointment.confirm')
                            @if($apt->isPending())
                            <form method="POST" action="{{ route('admin.appointments.confirm', $apt) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">✓ Confirm</button>
                            </form>
                            @endif
                            @endcan
                            @can('appointment.cancel')
                            @if(!in_array($apt->status, ['cancelled','completed']))
                            <form method="POST" action="{{ route('admin.appointments.cancel', $apt) }}" style="display:inline"
                                  onsubmit="return confirm('Cancel this appointment?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-danger">✕ Cancel</button>
                            </form>
                            @endif
                            @endcan
                            @can('appointment.complete')
                            @if($apt->isConfirmed())
                            <form method="POST" action="{{ route('admin.appointments.complete', $apt) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline">✔ Done</button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No appointments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
        <div class="pagination-wrap">{{ $appointments->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
