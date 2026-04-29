@extends('layouts.app')
@section('title', 'Our Doctors — DAMS Medical Center')
@section('meta-description', 'Browse our team of specialist doctors. Filter by department and book appointments instantly.')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our Doctors</h1>
        <p>Expert specialists across all medical fields</p>
    </div>
</div>

<section class="section">
    <div class="container">
        {{-- Filters --}}
        <div class="filter-bar">
            <form method="GET" class="filter-form-public">
                <div class="filter-dept-tabs">
                    <a href="{{ route('doctors.index') }}" class="dept-tab {{ !$selectedDept ? 'active' : '' }}">All</a>
                    @foreach($departments as $dept)
                    <a href="{{ route('doctors.index', ['department' => $dept->slug]) }}"
                       class="dept-tab {{ $selectedDept == $dept->slug ? 'active' : '' }}">
                        {{ $dept->icon }} {{ $dept->name }}
                    </a>
                    @endforeach
                </div>
                <div class="search-box">
                    <input type="hidden" name="department" value="{{ $selectedDept }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search doctor..." class="form-control">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        {{-- Doctor Grid --}}
        @if($doctors->isEmpty())
        <div class="empty-state-public">
            <div style="font-size:4rem">🔍</div>
            <h3>No doctors found</h3>
            <p>Try a different department or search term.</p>
            <a href="{{ route('doctors.index') }}" class="btn btn-primary mt-4">View All Doctors</a>
        </div>
        @else
        <div class="doctors-grid">
            @foreach($doctors as $doctor)
            <div class="doctor-card">
                <div class="doctor-photo">
                    @if($doctor->photo_url)
                        <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}">
                    @else
                        <div class="photo-placeholder-lg">👨‍⚕️</div>
                    @endif
                    <div class="dept-tag">{{ $doctor->department->name }}</div>
                </div>
                <div class="doctor-info">
                    <h3>{{ $doctor->name }}</h3>
                    <p class="designation">{{ $doctor->designation }}</p>
                    @if($doctor->degrees)<p class="degrees">{{ $doctor->degrees }}</p>@endif
                    <div class="doctor-card-actions">
                        <a href="{{ route('doctors.show', $doctor->slug) }}" class="btn btn-outline btn-sm">Profile</a>
                        <a href="{{ route('appointment.form', ['doctor' => $doctor->slug]) }}" class="btn btn-primary btn-sm">Book</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination-public">{{ $doctors->links() }}</div>
        @endif
    </div>
</section>
@endsection
