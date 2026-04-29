<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — DAMS Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-wrapper">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <span class="brand-icon">🏥</span>
            <span class="brand-name">DAMS Admin</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>

            @can('appointment.view')
            <div class="nav-group-label">Appointments</div>
            <a href="{{ route('admin.appointments.index') }}" class="nav-item {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                <span class="nav-icon">📅</span> Appointments
            </a>
            @can('appointment.print')
            <a href="{{ route('admin.appointments.daily-sheet') }}" class="nav-item">
                <span class="nav-icon">🖨️</span> Daily Sheet
            </a>
            @endcan
            @endcan

            @can('doctor.view')
            <div class="nav-group-label">Doctors</div>
            <a href="{{ route('admin.doctors.index') }}" class="nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="nav-icon">👨‍⚕️</span> Doctors
            </a>
            @endcan

            @can('department.view')
            <a href="{{ route('admin.departments.index') }}" class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                <span class="nav-icon">🏥</span> Departments
            </a>
            @endcan

            @can('service.view')
            <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <span class="nav-icon">🩺</span> Services
            </a>
            @endcan

            @can('user.view')
            <div class="nav-group-label">System</div>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Users
            </a>
            @endcan

            @can('role.view')
            <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <span class="nav-icon">🔐</span> Roles & Permissions
            </a>
            @endcan
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->getRoleNames()->first() }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="admin-main">
        <header class="admin-header">
            <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <div class="header-actions">
                @yield('header-actions')
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
