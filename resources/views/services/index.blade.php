@extends('layouts.app')
@section('title', 'Our Services — DAMS Medical Center')
@section('meta-description', 'Comprehensive medical services at DAMS Medical Center including diagnostics, emergency care, pharmacy, and specialist consultations.')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our Services</h1>
        <p>Comprehensive healthcare facilities under one roof</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="services-grid">
            @forelse($services as $service)
            <div class="service-card">
                <div class="service-icon">{{ $service->icon }}</div>
                <h3>{{ $service->title }}</h3>
                @if($service->description)
                <p>{{ $service->description }}</p>
                @endif
            </div>
            @empty
            <div class="empty-state-public"><p>No services listed yet.</p></div>
            @endforelse
        </div>

        <div class="services-cta">
            <h2>Need to speak with a specialist?</h2>
            <p>Book an appointment and our team will guide you to the right doctor.</p>
            <a href="{{ route('appointment.form') }}" class="btn btn-primary btn-xl">Book an Appointment</a>
        </div>
    </div>
</section>
@endsection
