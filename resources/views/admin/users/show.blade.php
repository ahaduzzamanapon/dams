@extends('admin.layouts.app')
@section('title', $user->name)
@section('page-title', $user->name)
@section('header-actions')
    @can('user.edit')<a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit User</a>@endcan
@endsection
@section('content')
<div class="form-grid">
    <div class="card">
        <div class="card-body">
            <div style="text-align:center;margin-bottom:20px">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#0d6eaf,#00c4cc);display:flex;align-items:center;justify-content:center;font-size:2rem;color:#fff;margin:0 auto 12px">{{ strtoupper(substr($user->name,0,1)) }}</div>
                <h2>{{ $user->name }}</h2>
                <p class="text-muted">{{ $user->email }}</p>
                <div style="margin-top:8px">
                    @foreach($user->roles as $role)<span class="badge badge-primary" style="margin:2px">{{ $role->name }}</span>@endforeach
                    <span class="badge {{ $user->is_active ? 'badge-success':'badge-secondary' }}" style="margin:2px">{{ $user->is_active ? 'Active':'Inactive' }}</span>
                </div>
            </div>
            <table class="detail-table">
                <tr><td>Member Since</td><td>{{ $user->created_at->format('d M Y') }}</td></tr>
                <tr><td>Last Updated</td><td>{{ $user->updated_at->format('d M Y, h:i A') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Effective Permissions</h3></div>
        <div class="card-body">
            @php $perms = $user->getAllPermissions()->pluck('name')->groupBy(fn($p) => explode('.',$p)[0])->sortKeys(); @endphp
            @forelse($perms as $module => $modulePerms)
            <div style="margin-bottom:14px">
                <div style="font-weight:600;text-transform:capitalize;margin-bottom:6px">{{ $module }}</div>
                <div style="display:flex;flex-wrap:wrap;gap:4px">
                    @foreach($modulePerms as $p)
                    <span class="badge badge-info" style="font-size:11px">{{ explode('.',$p)[1] }}</span>
                    @endforeach
                </div>
            </div>
            @empty
            <p class="text-muted">No permissions assigned.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
