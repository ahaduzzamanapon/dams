@extends('layouts.app')
@section('title', 'DAMS Medical Center — Quality Healthcare')
@section('meta-description', 'Book doctor appointments online at DAMS Medical Center. Expert specialists in Neurology, Cardiology, Orthopedics and more.')

@section('content')

{{-- Hero Section --}}
<section class="hero">
    <div class="hero-bg-pattern"></div>

    {{-- Floating shapes --}}
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>
    <div class="hero-shape hero-shape-3"></div>

    <div class="container hero-container">

        {{-- Left: Text Content --}}
        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                🏥 Trusted Healthcare Since 2010
            </div>
            <h1 class="hero-title">
                Your Health, <br>
                <span class="gradient-text">Our Priority</span>
            </h1>
            <p class="hero-subtitle">
                Book appointments with top specialists instantly.<br>No waiting, no hassle — just quality care.
            </p>

            {{-- Quick Search --}}
            <div class="hero-search-wrap">
                <form action="{{ route('home') }}" method="GET" class="hero-search">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Search doctor, specialty..." class="search-input">
                    <button type="submit" class="btn btn-primary search-btn">Search</button>
                </form>

                @if($searchQuery && $searchResults->isNotEmpty())
                <div class="search-results">
                    @foreach($searchResults as $doc)
                    <a href="{{ route('doctors.show', $doc->slug) }}" class="search-result-item">
                        <span>👨‍⚕️</span>
                        <div>
                            <strong>{{ $doc->name }}</strong>
                            <small>{{ $doc->designation }} — {{ $doc->department->name }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>
                @elseif($searchQuery)
                <div class="search-results">
                    <p style="padding:12px 16px;color:#999;font-size:13px">No doctors found for "{{ $searchQuery }}"</p>
                </div>
                @endif
            </div>

            <div class="hero-cta">
                <a href="{{ route('appointment.form') }}" class="btn btn-primary btn-xl hero-btn-book">
                    📅 Book Appointment
                </a>
                <a href="tel:+8801700000000" class="btn btn-outline-white btn-xl">
                    📞 Emergency Call
                </a>
            </div>

            {{-- Trust indicators --}}
            <div class="hero-trust">
                <div class="trust-item">
                    <strong>500+</strong>
                    <span>Doctors</span>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <strong>50,000+</strong>
                    <span>Patients Served</span>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <strong>15 Years</strong>
                    <span>Of Excellence</span>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <strong>24/7</strong>
                    <span>Emergency</span>
                </div>
            </div>
        </div>

        {{-- Right: Image Stack --}}
        <div class="hero-image-wrap">
            <div class="hero-img-main">
                <img src="{{ asset('images/hero-doctor.jpg') }}" alt="DAMS Medical Center doctors" loading="eager">
                <div class="hero-img-overlay"></div>
            </div>
            <div class="hero-img-secondary">
                <img src="{{ asset('images/hero-doctor2.jpg') }}" alt="Doctor consultation" loading="eager">
            </div>
            {{-- Floating info cards --}}
            <div class="hero-float-card hero-float-top">
                <span class="float-icon">✅</span>
                <div>
                    <strong>Appointment Confirmed</strong>
                    <small>Today 4:30 PM · Dr. Rahman</small>
                </div>
            </div>
            <div class="hero-float-card hero-float-bottom">
                <span class="float-icon">⭐</span>
                <div>
                    <strong>4.9 / 5.0 Rating</strong>
                    <small>Based on 12,000+ reviews</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Wave bottom --}}
    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

{{-- Department Icons --}}
<section class="section departments-section">
    <div class="container">
        <div class="section-header">
            <h2>Find by Department</h2>
            <p>Choose your specialty and connect with the right specialist</p>
        </div>
        <div class="dept-grid">
            @foreach($departments as $dept)
            <a href="{{ route('doctors.index', ['department' => $dept->slug]) }}" class="dept-card">
                <div class="dept-icon">{{ $dept->icon }}</div>
                <div class="dept-name">{{ $dept->name }}</div>
                <div class="dept-count">{{ $dept->active_doctors_count }} doctors</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Doctors --}}
@if($featuredDoctors->isNotEmpty())
<section class="section featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Specialists</h2>
            <p>Meet our top-rated doctors ready to help you</p>
        </div>
        <div class="doctors-grid">
            @foreach($featuredDoctors as $doctor)
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
                    <a href="{{ route('appointment.form', ['doctor' => $doctor->slug]) }}" class="btn btn-primary btn-sm btn-block">Book Appointment</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('doctors.index') }}" class="btn btn-outline btn-lg">View All Doctors →</a>
        </div>
    </div>
</section>
@endif

{{-- Why Choose Us --}}
<section class="section why-section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose DAMS?</h2>
            <p style="color:rgba(255,255,255,.7)">Everything you need for quality healthcare in one place</p>
        </div>
        <div class="why-grid">
            <div class="why-card"><div class="why-icon">⚡</div><h3>Instant Booking</h3><p>Book your appointment online in under 2 minutes — no calls, no queues.</p></div>
            <div class="why-card"><div class="why-icon">👨‍⚕️</div><h3>Top Specialists</h3><p>Access verified, experienced doctors across 8+ medical specialties.</p></div>
            <div class="why-card"><div class="why-icon">✅</div><h3>Confirmed Slots</h3><p>Your time slot is confirmed by our admin team before your visit.</p></div>
            <div class="why-card"><div class="why-icon">📱</div><h3>SMS Reminders</h3><p>Get instant confirmation and reminders sent directly to your phone.</p></div>
        </div>
    </div>
</section>

{{-- CTA Banner --}}
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to See a Doctor?</h2>
            <p>Book your appointment now — it only takes a minute.</p>
            <div class="cta-btns">
                <a href="{{ route('appointment.form') }}" class="btn btn-white btn-xl">Book Appointment</a>
                <a href="tel:+8801700000000" class="btn btn-outline-white btn-xl">📞 +880 1700-000000</a>
            </div>
        </div>
    </div>
</section>

@endsection
