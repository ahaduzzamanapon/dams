@extends('admin.layouts.app')
@section('title', 'Edit Service')
@section('page-title', 'Edit Service')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.services.update', $service) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" value="{{ old('title', $service->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" class="form-control" maxlength="10">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $service->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label>Order</label>
                    <input type="number" name="order" value="{{ old('order', $service->order) }}" class="form-control" min="0">
                </div>
                <div class="form-group half">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $service->is_active ? 'selected':'' }}>Active</option>
                        <option value="0" {{ !$service->is_active ? 'selected':'' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Service</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
