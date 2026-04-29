@extends('admin.layouts.app')
@section('title', $role->name)
@section('page-title', 'Role: {{ $role->name }}')
@section('header-actions')
    @can('role.edit')<a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">Edit Permissions</a>@endcan
@endsection
@section('content')
<div class="card">
    <div class="card-header"><h3>Permissions for "{{ $role->name }}"</h3></div>
    <div class="card-body">
        @php $grouped = $role->permissions->groupBy(fn($p) => explode('.',$p->name)[0])->sortKeys(); @endphp
        @forelse($grouped as $module => $perms)
        <div style="margin-bottom:20px">
            <h4 style="text-transform:capitalize;margin-bottom:8px;color:#0d6eaf">{{ $module }}</h4>
            <div style="display:flex;flex-wrap:wrap;gap:6px">
                @foreach($perms as $perm)
                <span class="badge badge-success" style="padding:6px 12px">{{ explode('.',$perm->name)[1] }}</span>
                @endforeach
            </div>
        </div>
        @empty
        <p class="text-muted">No permissions assigned to this role.</p>
        @endforelse
    </div>
</div>
@endsection
