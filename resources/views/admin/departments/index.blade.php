@extends('admin.layouts.app')
@section('title', 'Departments')
@section('page-title', 'Departments')

@section('header-actions')
    @can('department.create')
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">+ Add Department</a>
    @endcan
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>#</th><th>Icon</th><th>Name</th><th>Doctors</th><th>Status</th><th>Order</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="font-size:1.5rem">{{ $dept->icon }}</td>
                        <td><strong>{{ $dept->name }}</strong><br><small class="text-muted">{{ $dept->description }}</small></td>
                        <td><span class="badge badge-info">{{ $dept->doctors_count }}</span></td>
                        <td><span class="badge {{ $dept->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $dept->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td>{{ $dept->order }}</td>
                        <td class="action-btns">
                            @can('department.edit')
                            <a href="{{ route('admin.departments.edit', $dept) }}" class="btn btn-sm btn-outline">Edit</a>
                            @endcan
                            @can('department.delete')
                            <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" style="display:inline"
                                  onsubmit="return confirm('Delete this department?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">No departments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $departments->links() }}</div>
    </div>
</div>
@endsection
