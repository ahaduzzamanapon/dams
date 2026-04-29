@extends('admin.layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>New Password <small class="text-muted">(leave blank to keep current)</small></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="form-group">
                <label>Roles <span class="required">*</span></label>
                <div class="checkbox-group">
                    @php $userRoles = old('roles', $user->roles->pluck('name')->toArray()); @endphp
                    @foreach($roles as $role)
                    <label class="checkbox-item">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked':'' }}>
                        {{ ucfirst($role->name) }}
                    </label>
                    @endforeach
                </div>
                @error('roles')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $user->is_active ? 'selected':'' }}>Active</option>
                    <option value="0" {{ !$user->is_active ? 'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
