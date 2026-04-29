@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-actions')
    <span class="text-muted">{{ now()->format('l, d M Y') }}</span>
@endsection

@section('content')
{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-icon">📅</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['today_appointments'] }}</div>
            <div class="stat-label">Today's Appointments</div>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon">⏳</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['pending_appointments'] }}</div>
            <div class="stat-label">Pending Approval</div>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon">✅</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['today_confirmed'] }}</div>
            <div class="stat-label">Confirmed Today</div>
        </div>
    </div>
    <div class="stat-card stat-teal">
        <div class="stat-icon">👨‍⚕️</div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_doctors'] }}</div>
            <div class="stat-label">Active Doctors</div>
        </div>
    </div>
</div>

{{-- Today's Appointments Table --}}
<div class="card">
    <div class="card-header">
        <h3>Today's Appointments</h3>
        @can('appointment.create')
        <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary btn-sm">+ Add Manual</a>
        @endcan
    </div>
    <div class="card-body p-0">
        @if($todayAppointments->isEmpty())
            <div class="empty-state">No appointments scheduled for today.</div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayAppointments as $apt)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($apt->slot_time)->format('h:i A') }}</strong></td>
                        <td>{{ $apt->patient_name }}</td>
                        <td><a href="tel:{{ $apt->patient_phone }}">{{ $apt->patient_phone }}</a></td>
                        <td>{{ $apt->doctor->name }}</td>
                        <td>{{ $apt->doctor->department->name ?? '—' }}</td>
                        <td><span class="badge {{ $apt->status_badge }}">{{ $apt->status_label }}</span></td>
                        <td class="action-btns">
                            @can('appointment.view')
                            <a href="{{ route('admin.appointments.show', $apt) }}" class="btn btn-sm btn-outline">View</a>
                            @endcan
                            @can('appointment.confirm')
                            @if($apt->isPending())
                            <form method="POST" action="{{ route('admin.appointments.confirm', $apt) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Confirm</button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Recent Appointments --}}
@if($recentAppointments->isNotEmpty())
<div class="card mt-4">
    <div class="card-header"><h3>Recent Past Appointments</h3></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Date</th><th>Patient</th><th>Doctor</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($recentAppointments as $apt)
                    <tr>
                        <td>{{ $apt->appointment_date->format('d M Y') }}</td>
                        <td>{{ $apt->patient_name }}</td>
                        <td>{{ $apt->doctor->name }}</td>
                        <td><span class="badge {{ $apt->status_badge }}">{{ $apt->status_label }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
