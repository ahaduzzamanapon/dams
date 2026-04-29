@extends('admin.layouts.app')
@section('title', 'Manual Appointment')
@section('page-title', 'Add Manual Appointment')

@section('content')
<div class="card" style="max-width:700px">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.appointments.store') }}" id="manualForm">
            @csrf
            <div class="form-group">
                <label>Doctor <span class="required">*</span></label>
                <select name="doctor_id" id="doctorSelect" class="form-control" required onchange="loadSlots()">
                    <option value="">Select Doctor</option>
                    @foreach(\App\Models\Doctor::active()->with('department')->get() as $doc)
                    <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected':'' }}>
                        {{ $doc->name }} — {{ $doc->department->name ?? '' }}
                    </option>
                    @endforeach
                </select>
                @error('doctor_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Appointment Date <span class="required">*</span></label>
                <input type="date" name="appointment_date" id="dateInput" value="{{ old('appointment_date', now()->toDateString()) }}"
                       class="form-control" min="{{ now()->toDateString() }}" required onchange="loadSlots()">
                @error('appointment_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Time Slot <span class="required">*</span></label>
                <select name="slot_time" id="slotSelect" class="form-control" required>
                    <option value="">Select doctor & date first</option>
                </select>
                @error('slot_time')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Patient Name <span class="required">*</span></label>
                <input type="text" name="patient_name" value="{{ old('patient_name') }}" class="form-control" required>
                @error('patient_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Patient Phone <span class="required">*</span></label>
                <input type="tel" name="patient_phone" value="{{ old('patient_phone') }}" class="form-control" required>
                @error('patient_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Appointment</button>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadSlots() {
    const doctorId = document.getElementById('doctorSelect').value;
    const date = document.getElementById('dateInput').value;
    const slotSelect = document.getElementById('slotSelect');
    if (!doctorId || !date) return;
    slotSelect.innerHTML = '<option>Loading...</option>';
    fetch(`/appointment/slots?doctor_id=${doctorId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (data.slots && data.slots.length > 0) {
                slotSelect.innerHTML = '<option value="">Select a slot</option>' +
                    data.slots.map(s => {
                        const [h, m] = s.split(':');
                        const hr = h % 12 || 12, ampm = h < 12 ? 'AM' : 'PM';
                        return `<option value="${s}">${hr}:${m} ${ampm}</option>`;
                    }).join('');
            } else {
                slotSelect.innerHTML = '<option value="">No slots available for this date</option>';
            }
        })
        .catch(() => { slotSelect.innerHTML = '<option value="">Error loading slots</option>'; });
}
</script>
@endpush
