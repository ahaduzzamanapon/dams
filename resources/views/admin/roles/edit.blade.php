@extends('admin.layouts.app')
@section('title', 'Edit Role')
@section('page-title', 'Edit Role: {{ $role->name }}')
@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PUT')
            @if(!in_array($role->name, ['super-admin','admin','receptionist']))
            <div class="form-group" style="max-width:400px">
                <label>Role Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @else
            <input type="hidden" name="name" value="{{ $role->name }}">
            <div class="alert alert-warning" style="max-width:500px">⚠️ Built-in role name cannot be changed, but permissions can be updated.</div>
            @endif
            <div class="form-group">
                <label>Permissions</label>
                <div class="permissions-grid">
                    @foreach($permissions as $module => $perms)
                    <div class="permission-module">
                        <div class="module-header">
                            <label class="checkbox-item module-label">
                                <input type="checkbox" class="module-toggle" data-module="{{ $module }}"> <strong>{{ ucfirst($module) }}</strong>
                            </label>
                        </div>
                        <div class="module-perms">
                            @foreach($perms as $perm)
                            <label class="checkbox-item perm-item" data-module="{{ $module }}">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, $rolePermissions) ? 'checked':'' }}>
                                {{ explode('.', $perm->name)[1] }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Permissions</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.module-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const module = this.dataset.module;
        document.querySelectorAll(`.perm-item[data-module="${module}"] input`).forEach(cb => cb.checked = this.checked);
    });
    // Set indeterminate state on load
    const module = toggle.dataset.module;
    const allPerms = document.querySelectorAll(`.perm-item[data-module="${module}"] input`);
    const checkedCount = [...allPerms].filter(cb => cb.checked).length;
    if (checkedCount === allPerms.length) toggle.checked = true;
    else if (checkedCount > 0) toggle.indeterminate = true;
});
</script>
@endpush
