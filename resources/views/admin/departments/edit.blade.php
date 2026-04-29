@extends('admin.layouts.app')
@section('title', 'Edit Department')
@section('page-title', 'Edit Department')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.departments.update', $department) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $department->icon) }}" class="form-control" maxlength="10">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label>Order</label>
                    <input type="number" name="order" value="{{ old('order', $department->order) }}" class="form-control" min="0">
                </div>
                <div class="form-group half">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $department->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $department->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Department</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
