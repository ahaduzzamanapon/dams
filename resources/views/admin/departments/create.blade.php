@extends('admin.layouts.app')
@section('title', 'Add Department')
@section('page-title', 'Add Department')

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf
            <div class="form-group">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon') }}" class="form-control" placeholder="🧠" maxlength="10">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label>Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" min="0">
                </div>
                <div class="form-group half">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Department</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
