<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DAMS Medical Center')</title>
    <meta name="description" content="@yield('meta-description', 'Book doctor appointments online at DAMS Medical Center. Specialists in Neurology, Cardiology, Orthopedics and more.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

{{-- Navigation --}}
<nav class="navbar" id="navbar">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <span class="brand-icon">🏥</span>
            <span>DAMS <span class="brand-sub">Medical Center</span></span>
        </a>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">☰</button>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">Our Doctors</a></li>
            <li><a href="{{ route('schedule.index') }}" class="{{ request()->routeIs('schedule.*') ? 'active' : '' }}">Schedule</a></li>
            <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a></li>
            <li><a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">Contact</a></li>
            <li><a href="{{ route('appointment.form') }}" class="btn btn-primary nav-cta">Book Appointment</a></li>
        </ul>
    </div>
</nav>

{{-- Page Content --}}
@yield('content')

{{-- Footer --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-brand">
                    <span class="brand-icon">🏥</span> DAMS Medical Center
                </div>
                <p class="footer-desc">Providing quality healthcare with compassion and expertise since 2010.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('doctors.index') }}">Our Doctors</a></li>
                    <li><a href="{{ route('appointment.form') }}">Book Appointment</a></li>
                    <li><a href="{{ route('schedule.index') }}">Doctor Schedule</a></li>
                    <li><a href="{{ route('services.index') }}">Our Services</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p>📍 123 Medical Road, Dhaka 1200</p>
                <p>📞 <a href="tel:+8801700000000">+880 1700-000000</a></p>
                <p>📞 <a href="tel:+8801800000000">+880 1800-000000</a></p>
                <p>✉️ info@dams.com</p>
            </div>
            <div class="footer-col">
                <h4>Hours</h4>
                <p>Sat–Thu: 9:00 AM – 9:00 PM</p>
                <p>Friday: 5:00 PM – 9:00 PM</p>
                <p class="emergency">🚑 Emergency: 24/7</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} DAMS Medical Center. All rights reserved.</p>
            <a href="{{ route('admin.login') }}" class="admin-link">Admin</a>
        </div>
    </div>
</footer>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
    });
    // Mobile nav toggle
    document.getElementById('navToggle').addEventListener('click', () => {
        document.getElementById('navLinks').classList.toggle('open');
    });
</script>
@stack('scripts')
</body>
</html>
