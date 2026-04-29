@extends('layouts.app')
@section('title', 'Book Appointment — DAMS Medical Center')
@section('meta-description', 'Book a doctor appointment at DAMS Medical Center. Select department, doctor, and time slot instantly.')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Book an Appointment</h1>
        <p>Fill in the form below — no account needed</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:760px">

        {{-- Success Message --}}
        @if(session('booking_success'))
        @php $b = session('booking_success'); @endphp
        <div class="booking-success">
            <div style="font-size:3rem">🎉</div>
            <h2>Appointment Request Submitted!</h2>
            <p>Thank you, <strong>{{ $b['name'] }}</strong>!</p>
            <div class="success-details">
                <div><span>👨‍⚕️ Doctor</span><strong>{{ $b['doctor'] }}</strong></div>
                <div><span>📅 Date</span><strong>{{ $b['date'] }}</strong></div>
                <div><span>🕐 Time</span><strong>{{ \Carbon\Carbon::parse($b['time'])->format('h:i A') }}</strong></div>
            </div>
            <p class="text-muted mt-4">Our team will confirm your appointment shortly. You may receive a call for verification.</p>
            <a href="{{ route('appointment.form') }}" class="btn btn-primary mt-4">Book Another</a>
        </div>
        @else

        @if($errors->any())
        <div class="alert alert-danger mb-4">
            @foreach($errors->all() as $e)<p style="margin:2px 0">{{ $e }}</p>@endforeach
        </div>
        @endif

        {{-- Step Wizard --}}
        <div class="wizard-steps" id="wizardSteps">
            <div class="wizard-step active" data-step="1"><span>1</span> Department</div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step="2"><span>2</span> Doctor</div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step="3"><span>3</span> Date & Slot</div>
            <div class="wizard-line"></div>
            <div class="wizard-step" data-step="4"><span>4</span> Your Info</div>
        </div>

        <form method="POST" action="{{ route('appointment.store') }}" id="bookingForm">
            @csrf
            <input type="hidden" name="doctor_id" id="hiddenDoctorId">
            <input type="hidden" name="appointment_date" id="hiddenDate">
            <input type="hidden" name="slot_time" id="hiddenSlot">

            {{-- Step 1: Department --}}
            <div class="wizard-panel active" id="step1">
                <div class="card">
                    <div class="card-body">
                        <h3 class="step-title">Select a Department</h3>
                        <div class="dept-grid-booking">
                            @foreach($departments as $dept)
                            <button type="button" class="dept-btn" data-dept-id="{{ $dept->id }}"
                                    onclick="selectDept({{ $dept->id }}, '{{ $dept->name }}', this)">
                                <span class="dept-icon-lg">{{ $dept->icon }}</span>
                                <span>{{ $dept->name }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-lg mt-4 float-right" id="nextStep1" disabled onclick="goStep(2)">Next →</button>
            </div>

            {{-- Step 2: Doctor --}}
            <div class="wizard-panel" id="step2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="step-title">Select a Doctor</h3>
                        <div id="doctorList" class="doctor-select-list">
                            <p class="text-muted text-center py-4">Loading doctors...</p>
                        </div>
                    </div>
                </div>
                <div style="display:flex;gap:12px;margin-top:16px">
                    <button type="button" class="btn btn-outline btn-lg" onclick="goStep(1)">← Back</button>
                    <button type="button" class="btn btn-primary btn-lg" id="nextStep2" disabled onclick="goStep(3)">Next →</button>
                </div>
            </div>

            {{-- Step 3: Date & Slot --}}
            <div class="wizard-panel" id="step3">
                <div class="card">
                    <div class="card-body">
                        <h3 class="step-title">Choose Date & Time Slot</h3>
                        <div class="form-group">
                            <label>Appointment Date</label>
                            <input type="date" id="datePickerInput" class="form-control"
                                   min="{{ now()->toDateString() }}" onchange="loadSlots()">
                        </div>
                        <div id="slotsArea" style="display:none">
                            <label>Available Slots</label>
                            <div id="slotGrid" class="slot-grid"></div>
                        </div>
                        <p id="slotMsg" class="text-muted mt-2"></p>
                    </div>
                </div>
                <div style="display:flex;gap:12px;margin-top:16px">
                    <button type="button" class="btn btn-outline btn-lg" onclick="goStep(2)">← Back</button>
                    <button type="button" class="btn btn-primary btn-lg" id="nextStep3" disabled onclick="goStep(4)">Next →</button>
                </div>
            </div>

            {{-- Step 4: Patient Info --}}
            <div class="wizard-panel" id="step4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="step-title">Your Information</h3>
                        <div id="bookingSummary" class="booking-summary mb-4"></div>
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" name="patient_name" value="{{ old('patient_name') }}" class="form-control @error('patient_name') is-invalid @enderror" placeholder="Your full name" required>
                            @error('patient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span class="required">*</span></label>
                            <input type="tel" name="patient_phone" value="{{ old('patient_phone') }}" class="form-control @error('patient_phone') is-invalid @enderror" placeholder="01XXXXXXXXX" required>
                            @error('patient_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <p class="text-muted" style="font-size:13px">📞 Our team will call to verify and confirm your appointment.</p>
                    </div>
                </div>
                <div style="display:flex;gap:12px;margin-top:16px">
                    <button type="button" class="btn btn-outline btn-lg" onclick="goStep(3)">← Back</button>
                    <button type="submit" class="btn btn-primary btn-lg">✓ Submit Request</button>
                </div>
            </div>
        </form>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
let selectedDeptId = null, selectedDoctorId = null, selectedDoctorName = null;
let selectedDate = null, selectedSlot = null;

function goStep(n) {
    document.querySelectorAll('.wizard-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.wizard-step').forEach((s, i) => {
        s.classList.toggle('active', i < n);
        s.classList.toggle('completed', i < n - 1);
    });
    document.getElementById('step' + n).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function selectDept(id, name, btn) {
    selectedDeptId = id;
    document.querySelectorAll('.dept-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('nextStep1').disabled = false;
    loadDoctors(id);
}

function loadDoctors(deptId) {
    const list = document.getElementById('doctorList');
    list.innerHTML = '<p class="text-muted text-center py-4">Loading...</p>';
    fetch(`/appointment/doctors?department_id=${deptId}`)
        .then(r => r.json())
        .then(doctors => {
            if (!doctors.length) { list.innerHTML = '<p class="text-muted text-center py-4">No doctors in this department.</p>'; return; }
            list.innerHTML = doctors.map(d => `
                <div class="doctor-select-item" onclick="selectDoctor(${d.id}, '${d.name}', this)">
                    <div class="doctor-select-avatar">👨‍⚕️</div>
                    <div><strong>${d.name}</strong><br><small class="text-muted">${d.designation}</small></div>
                </div>`).join('');
        });
}

function selectDoctor(id, name, el) {
    selectedDoctorId = id;
    selectedDoctorName = name;
    document.querySelectorAll('.doctor-select-item').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('nextStep2').disabled = false;
    document.getElementById('hiddenDoctorId').value = id;
}

function loadSlots() {
    const date = document.getElementById('datePickerInput').value;
    if (!date || !selectedDoctorId) return;
    selectedDate = date;
    document.getElementById('hiddenDate').value = date;
    const msg = document.getElementById('slotMsg');
    const area = document.getElementById('slotsArea');
    const grid = document.getElementById('slotGrid');
    msg.textContent = 'Loading slots...';
    area.style.display = 'none';
    fetch(`/appointment/slots?doctor_id=${selectedDoctorId}&date=${date}`)
        .then(r => r.json())
        .then(data => {
            msg.textContent = '';
            if (!data.slots || !data.slots.length) {
                msg.textContent = '⚠️ No available slots for this date. Try another date.';
                document.getElementById('nextStep3').disabled = true;
                return;
            }
            area.style.display = 'block';
            grid.innerHTML = data.slots.map(s => {
                const [h, m] = s.split(':');
                const hr = h % 12 || 12, ampm = h < 12 ? 'AM' : 'PM';
                return `<button type="button" class="slot-btn" onclick="selectSlot('${s}', '${hr}:${m} ${ampm}', this)">${hr}:${m} ${ampm}</button>`;
            }).join('');
            document.getElementById('nextStep3').disabled = true;
        });
}

function selectSlot(val, label, btn) {
    selectedSlot = val;
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('hiddenSlot').value = val;
    document.getElementById('nextStep3').disabled = false;
    // Update booking summary
    document.getElementById('bookingSummary').innerHTML = `
        <div class="summary-row"><span>👨‍⚕️ Doctor</span><strong>${selectedDoctorName}</strong></div>
        <div class="summary-row"><span>📅 Date</span><strong>${selectedDate}</strong></div>
        <div class="summary-row"><span>🕐 Time</span><strong>${label}</strong></div>`;
}
</script>
@endpush
