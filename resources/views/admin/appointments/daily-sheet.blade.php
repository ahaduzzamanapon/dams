<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Patient Sheet — {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #1a1a1a; background: #fff; }
        .no-print { background: #f0f4ff; padding: 12px 20px; display: flex; gap: 12px; align-items: center; }
        .no-print button { padding: 8px 20px; background: #0d6eaf; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .no-print a { padding: 8px 20px; border: 1px solid #ccc; border-radius: 6px; text-decoration: none; color: #555; }
        .sheet { padding: 24px; }
        .sheet-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d6eaf; padding-bottom: 12px; }
        .sheet-header h1 { font-size: 20px; color: #0d6eaf; }
        .sheet-header p { color: #555; margin-top: 4px; }
        .sheet-meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 12px; color: #444; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0d6eaf; color: #fff; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; text-align: left; }
        tbody tr:nth-child(even) { background: #f9fafc; }
        .status-confirmed { color: #16a34a; font-weight: 600; }
        .status-pending { color: #d97706; font-weight: 600; }
        .status-cancelled { color: #dc2626; font-weight: 600; }
        .status-completed { color: #6366f1; font-weight: 600; }
        .footer-note { margin-top: 20px; font-size: 11px; color: #888; text-align: right; }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨️ Print</button>
    <a href="{{ route('admin.appointments.daily-sheet') }}?date={{ request('date') }}&doctor_id={{ request('doctor_id') }}">Refresh</a>
    <a href="{{ route('admin.appointments.index') }}">← Back</a>
    <form method="GET" style="display:flex;gap:8px;margin-left:auto">
        <select name="doctor_id" class="form-control" style="padding:6px;border:1px solid #ccc;border-radius:4px">
            <option value="">All Doctors</option>
            @foreach($doctors as $doc)
            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected':'' }}>{{ $doc->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ $date }}" style="padding:6px;border:1px solid #ccc;border-radius:4px">
        <button type="submit" style="padding:6px 14px;background:#0d6eaf;color:#fff;border:none;border-radius:4px;cursor:pointer">Go</button>
    </form>
</div>

<div class="sheet">
    <div class="sheet-header">
        <h1>🏥 DAMS Medical Center</h1>
        <p>Daily Patient Sheet — {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
    </div>

    <div class="sheet-meta">
        <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
        <span>Total Patients: <strong>{{ $appointments->count() }}</strong></span>
    </div>

    @if($appointments->isEmpty())
    <p style="text-align:center;padding:30px;color:#888">No appointments for this date.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Time</th>
                <th>Patient Name</th>
                <th>Phone</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $i => $apt)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ \Carbon\Carbon::parse($apt->slot_time)->format('h:i A') }}</strong></td>
                <td>{{ $apt->patient_name }}</td>
                <td>{{ $apt->patient_phone }}</td>
                <td>{{ $apt->doctor->name }}</td>
                <td>{{ $apt->doctor->department->name ?? '—' }}</td>
                <td class="status-{{ $apt->status }}">{{ $apt->status_label }}</td>
                <td>{{ $apt->notes ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer-note">
        DAMS Medical Center — Printed by {{ auth()->user()->name }} — {{ now()->format('d M Y, h:i A') }}
    </div>
</div>

</body>
</html>
