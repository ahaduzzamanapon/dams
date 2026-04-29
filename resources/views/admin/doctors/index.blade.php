@extends('admin.layouts.app')
@section('title', 'Doctors')
@section('page-title', 'Doctors')

@section('header-actions')
    @can('doctor.create')
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">+ Add Doctor</a>
    @endcan
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Photo</th><th>Name</th><th>Department</th><th>BMDC No</th><th>Featured</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            @if($doctor->photo_url)
                                <img src="{{ $doctor->photo_url }}" alt="{{ $doctor->name }}" class="avatar-sm">
                            @else
                                <div class="avatar-placeholder">👨‍⚕️</div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $doctor->name }}</strong><br>
                            <small class="text-muted">{{ $doctor->designation }}</small>
                        </td>
                        <td>{{ $doctor->department->name ?? '—' }}</td>
                        <td>{{ $doctor->bmdc_no ?? '—' }}</td>
                        <td>
                            @if($doctor->is_featured)
                                <span class="badge badge-warning">⭐ Featured</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $doctor->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $doctor->is_active ? 'Active' : 'Hidden' }}</span></td>
                        <td class="action-btns">
                            @can('doctor.view')
                            <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline">View</a>
                            @endcan
                            @can('doctor.edit')
                            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-outline">Edit</a>
                            @endcan
                            @can('doctor.toggle')
                            <form method="POST" action="{{ route('admin.doctors.toggle', $doctor) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $doctor->is_active ? 'btn-warning' : 'btn-success' }}">
                                    {{ $doctor->is_active ? 'Hide' : 'Activate' }}
                                </button>
                            </form>
                            @endcan
                            @can('doctor.delete')
                            <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}" style="display:inline"
                                  onsubmit="return confirm('Delete Dr. {{ $doctor->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">No doctors found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $doctors->links() }}</div>
    </div>
</div>
@endsection
