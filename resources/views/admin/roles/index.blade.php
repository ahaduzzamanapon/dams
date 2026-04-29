@extends('admin.layouts.app')
@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')
@section('header-actions')
    @can('role.create')<a href="{{ route('admin.roles.create') }}" class="btn btn-primary">+ Add Role</a>@endcan
@endsection
@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Role Name</th><th>Permissions</th><th>Users</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td>
                            <strong>{{ $role->name }}</strong>
                            @if(in_array($role->name, ['super-admin','admin','receptionist']))
                            <span class="badge badge-info" style="margin-left:6px">Built-in</span>
                            @endif
                        </td>
                        <td><span class="badge badge-secondary">{{ $role->permissions_count }} permissions</span></td>
                        <td><span class="badge badge-primary">{{ $role->users_count }} users</span></td>
                        <td class="action-btns">
                            @can('role.view')<a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline">View</a>@endcan
                            @can('role.edit')<a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline">Edit Permissions</a>@endcan
                            @can('role.delete')
                            @if(!in_array($role->name, ['super-admin','admin','receptionist']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" style="display:inline" onsubmit="return confirm('Delete this role?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">No roles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
