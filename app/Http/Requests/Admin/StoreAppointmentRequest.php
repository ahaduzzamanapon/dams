<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('appointment.create');
    }

    public function rules(): array
    {
        return [
            'doctor_id'        => ['required', 'exists:doctors,id'],
            'patient_name'     => ['required', 'string', 'max:150'],
            'patient_phone'    => ['required', 'string', 'max:20'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'slot_time'        => ['required', 'date_format:H:i'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ];
    }
}
