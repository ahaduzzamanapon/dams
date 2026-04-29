@extends('layouts.app')
@section('title', 'Doctor Schedule — DAMS Medical Center')
@section('meta-description', 'View the weekly duty roster and availability of all doctors at DAMS Medical Center.')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Doctor Schedule</h1>
        <p>Weekly duty roster — know when your doctor is available</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @php
            $days = \App\Models\DoctorSchedule::DAY_SHORT;
        @endphp
        <div class="table-responsive">
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th class="doctor-col">Doctor</th>
                        <th>Department</th>
                        @foreach($days as $d)<th>{{ $d }}</th>@endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr>
                        <td class="doctor-col">
                            <div class="schedule-doctor">
                                @if($doctor->photo_url)
                                    <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}" class="schedule-avatar">
                                @else
                                    <div class="schedule-avatar-placeholder">👨‍⚕️</div>
                                @endif
                                <div>
                                    <strong>{{ $doctor->name }}</strong><br>
                                    <small>{{ $doctor->designation }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $doctor->department->name }}</td>
                        @foreach(range(0,6) as $dayNum)
                        @php
                            $sch = $doctor->schedules->firstWhere('day_of_week', $dayNum);
                        @endphp
                        <td>
                            @if($sch)
                            <div class="schedule-cell">
                                <span class="time-tag">{{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }}</span>
                                <span class="time-tag">{{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}</span>
                            </div>
                            @else
                            <span class="unavailable">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-8">No schedules available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="schedule-note">
            <p>📞 For same-day appointments, please call: <strong>+880 1700-000000</strong></p>
            <a href="{{ route('appointment.form') }}" class="btn btn-primary">Book Appointment Online →</a>
        </div>
    </div>
</section>
@endsection
