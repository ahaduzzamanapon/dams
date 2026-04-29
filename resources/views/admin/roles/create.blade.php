@extends('admin.layouts.app')
@section('title', 'Add Role')
@section('page-title', 'Add Role')
@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="form-group" style="max-width:400px">
                <label>Role Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. data-entry" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
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
                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ in_array($perm->name, old('permissions',[])) ? 'checked':'' }}>
                                {{ explode('.', $perm->name)[1] }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Role</button>
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
});
</script>
@endpush
