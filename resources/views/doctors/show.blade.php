@extends('layouts.app')
@section('title', $doctor->name . ' — DAMS Medical Center')
@section('meta-description', $doctor->designation . ' at DAMS Medical Center. ' . ($doctor->degrees ?? ''))

@section('content')
<div class="page-header">
    <div class="container">
        <a href="{{ route('doctors.index') }}" class="breadcrumb-link">← Our Doctors</a>
        <h1>{{ $doctor->name }}</h1>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="doctor-profile-grid">
            {{-- Left: Profile Card --}}
            <div>
                <div class="doctor-profile-card">
                    <div class="profile-photo">
                        @if($doctor->photo_url)
                            <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}">
                        @else
                            <div class="photo-placeholder-xl">👨‍⚕️</div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <h2>{{ $doctor->name }}</h2>
                        <p class="designation">{{ $doctor->designation }}</p>
                        <span class="dept-badge">{{ $doctor->department->name }}</span>
                        @if($doctor->degrees)<p class="degrees mt-2">{{ $doctor->degrees }}</p>@endif
                        @if($doctor->bmdc_no)<p class="bmdc">BMDC Reg: {{ $doctor->bmdc_no }}</p>@endif
                    </div>
                    <a href="{{ route('appointment.form', ['doctor' => $doctor->slug]) }}" class="btn btn-primary btn-xl btn-block">
                        📅 Book Appointment
                    </a>
                    <a href="tel:+8801700000000" class="btn btn-outline btn-block mt-2">📞 Call to Book</a>
                </div>

                {{-- Fees --}}
                @if($doctor->fees->isNotEmpty())
                <div class="info-card mt-4">
                    <h3>💰 Consultation Fees</h3>
                    @foreach($doctor->fees as $fee)
                    <div class="fee-row-public">
                        <span>{{ $fee->label }}</span>
                        <strong>৳{{ number_format($fee->amount, 0) }}</strong>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right: Bio + Schedule --}}
            <div>
                @if($doctor->bio)
                <div class="info-card mb-4">
                    <h3>About Dr. {{ explode(' ', $doctor->name)[count(explode(' ', $doctor->name))-1] }}</h3>
                    <p style="line-height:1.7;color:#555">{{ $doctor->bio }}</p>
                </div>
                @endif

                {{-- Schedule --}}
                @if($doctor->schedules->isNotEmpty())
                <div class="info-card">
                    <h3>🗓️ Chamber Schedule</h3>
                    <div class="schedule-list">
                        @foreach($doctor->schedules as $sch)
                        <div class="schedule-row-public">
                            <div class="day-pill">{{ $sch->day_name }}</div>
                            <div class="time-range">
                                {{ \Carbon\Carbon::parse($sch->start_time)->format('h:i A') }}
                                – {{ \Carbon\Carbon::parse($sch->end_time)->format('h:i A') }}
                            </div>
                            <div class="slot-duration">{{ $sch->slot_duration_minutes }} min/patient</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="book-cta-card mt-4">
                    <h3>Ready to book?</h3>
                    <p>Select a convenient date and slot to book your appointment online.</p>
                    <a href="{{ route('appointment.form', ['doctor' => $doctor->slug]) }}" class="btn btn-primary btn-xl">Book Now →</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
