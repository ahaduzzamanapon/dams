<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — DAMS Medical Center</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="login-body">
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <span style="font-size:2.5rem">🏥</span>
            <h1>DAMS Admin</h1>
            <p>Doctor Appointment Management System</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $e)<p style="margin:0">{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="login-form">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="admin@dams.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required>
            </div>
            <label class="form-check">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>

        <div class="login-hint">
            <small>💡 Demo: superadmin@dams.com / password</small>
        </div>
    </div>
</div>
</body>
</html>
