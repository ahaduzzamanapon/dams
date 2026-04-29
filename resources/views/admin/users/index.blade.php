@extends('admin.layouts.app')
@section('title', 'Users')
@section('page-title', 'User Management')
@section('header-actions')
    @can('user.create')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
    @endcan
@endsection
@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-avatar-inline">{{ strtoupper(substr($user->name,0,1)) }}</div>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id()) <span class="badge badge-info">You</span> @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                            <span class="badge badge-primary">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td><span class="badge {{ $user->is_active ? 'badge-success':'badge-secondary' }}">{{ $user->is_active ? 'Active':'Inactive' }}</span></td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="action-btns">
                            @can('user.view')<a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline">View</a>@endcan
                            @can('user.edit')<a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline">Edit</a>@endcan
                            @can('user.toggle')
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $user->is_active ? 'btn-warning':'btn-success' }}">{{ $user->is_active ? 'Deactivate':'Activate' }}</button>
                            </form>
                            @endif
                            @endcan
                            @can('user.delete')
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $users->links() }}</div>
    </div>
</div>
@endsection
