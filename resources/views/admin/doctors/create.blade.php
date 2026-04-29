@extends('admin.layouts.app')
@section('title', 'Add Doctor')
@section('page-title', 'Add Doctor')

@section('content')
<form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
@csrf
<div class="form-grid">
    {{-- Left Column --}}
    <div class="card">
        <div class="card-header"><h3>Basic Information</h3></div>
        <div class="card-body">
            <div class="form-group">
                <label>Department <span class="required">*</span></label>
                <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->icon }} {{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Dr. Full Name" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Designation <span class="required">*</span></label>
                <input type="text" name="designation" value="{{ old('designation') }}" class="form-control @error('designation') is-invalid @enderror" placeholder="Consultant Neurologist" required>
                @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Degrees / Qualifications</label>
                <input type="text" name="degrees" value="{{ old('degrees') }}" class="form-control" placeholder="MBBS, MD (Neurology)">
            </div>
            <div class="form-group">
                <label>BMDC Registration No.</label>
                <input type="text" name="bmdc_no" value="{{ old('bmdc_no') }}" class="form-control" placeholder="A-12345">
            </div>
            <div class="form-group">
                <label>Biography</label>
                <textarea name="bio" class="form-control" rows="4" placeholder="Brief professional bio...">{{ old('bio') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group half">
                    <label>Order</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="form-control" min="0">
                </div>
                <div class="form-group half">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
            </div>
            <label class="form-check">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                Feature this doctor on homepage
            </label>
        </div>
    </div>

    {{-- Right Column --}}
    <div>
        {{-- Photo Upload --}}
        <div class="card mb-4">
            <div class="card-header"><h3>Photo</h3></div>
            <div class="card-body">
                <div class="photo-upload-area" id="photoArea">
                    <div class="photo-placeholder" id="photoPlaceholder">👨‍⚕️<br><small>Click to upload</small></div>
                    <img src="" alt="" id="photoPreview" style="display:none;width:100%;border-radius:8px">
                </div>
                <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none"
                       onchange="previewPhoto(event)">
                <button type="button" class="btn btn-outline btn-block mt-2" onclick="document.getElementById('photoInput').click()">
                    Upload Photo
                </button>
                @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Consultation Fees --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3>Consultation Fees</h3>
                <button type="button" class="btn btn-sm btn-outline" onclick="addFee()">+ Add</button>
            </div>
            <div class="card-body" id="feesContainer">
                <div class="fee-row">
                    <input type="text" name="fees[0][label]" class="form-control" placeholder="New Patient" value="New Patient">
                    <input type="number" name="fees[0][amount]" class="form-control" placeholder="500" min="0" step="0.01">
                    <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">✕</button>
                </div>
                <div class="fee-row">
                    <input type="text" name="fees[1][label]" class="form-control" placeholder="Report Showing" value="Report Showing">
                    <input type="number" name="fees[1][amount]" class="form-control" placeholder="300" min="0" step="0.01">
                    <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">✕</button>
                </div>
            </div>
        </div>

        {{-- Schedules --}}
        <div class="card">
            <div class="card-header">
                <h3>Chamber Schedule</h3>
                <button type="button" class="btn btn-sm btn-outline" onclick="addSchedule()">+ Add Day</button>
            </div>
            <div class="card-body" id="schedulesContainer">
                <div class="schedule-row">
                    <select name="schedules[0][day_of_week]" class="form-control">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $day)
                        <option value="{{ $i }}">{{ $day }}</option>
                        @endforeach
                    </select>
                    <input type="time" name="schedules[0][start_time]" class="form-control" value="17:00">
                    <input type="time" name="schedules[0][end_time]" class="form-control" value="20:00">
                    <input type="number" name="schedules[0][slot_duration_minutes]" class="form-control" placeholder="Mins" value="20" min="5" max="120">
                    <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">✕</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-actions mt-4">
    <button type="submit" class="btn btn-primary btn-lg">Save Doctor</button>
    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline btn-lg">Cancel</a>
</div>
</form>
@endsection

@push('scripts')
<script>
let feeIdx = 2, schIdx = 1;
const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

function addFee() {
    const c = document.getElementById('feesContainer');
    c.insertAdjacentHTML('beforeend', `
        <div class="fee-row">
            <input type="text" name="fees[${feeIdx}][label]" class="form-control" placeholder="Fee Type">
            <input type="number" name="fees[${feeIdx}][amount]" class="form-control" placeholder="Amount" min="0" step="0.01">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">✕</button>
        </div>`);
    feeIdx++;
}

function addSchedule() {
    const opts = days.map((d,i) => `<option value="${i}">${d}</option>`).join('');
    const c = document.getElementById('schedulesContainer');
    c.insertAdjacentHTML('beforeend', `
        <div class="schedule-row">
            <select name="schedules[${schIdx}][day_of_week]" class="form-control">${opts}</select>
            <input type="time" name="schedules[${schIdx}][start_time]" class="form-control" value="17:00">
            <input type="time" name="schedules[${schIdx}][end_time]" class="form-control" value="20:00">
            <input type="number" name="schedules[${schIdx}][slot_duration_minutes]" class="form-control" placeholder="Mins" value="20" min="5" max="120">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()">✕</button>
        </div>`);
    schIdx++;
}

function previewPhoto(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('photoPreview').src = ev.target.result;
        document.getElementById('photoPreview').style.display = 'block';
        document.getElementById('photoPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
