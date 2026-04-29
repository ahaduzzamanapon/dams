@extends('admin.layouts.app')
@section('title', 'Services')
@section('page-title', 'Services')
@section('header-actions')
    @can('service.create')
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Add Service</a>
    @endcan
@endsection
@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td style="font-size:1.5rem">{{ $service->icon }}</td>
                        <td><strong>{{ $service->title }}</strong></td>
                        <td><small class="text-muted">{{ Str::limit($service->description, 60) }}</small></td>
                        <td><span class="badge {{ $service->is_active ? 'badge-success':'badge-secondary' }}">{{ $service->is_active ? 'Active':'Hidden' }}</span></td>
                        <td>{{ $service->order }}</td>
                        <td class="action-btns">
                            @can('service.edit')<a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline">Edit</a>@endcan
                            @can('service.delete')
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" style="display:inline" onsubmit="return confirm('Delete this service?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">No services found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $services->links() }}</div>
    </div>
</div>
@endsection
