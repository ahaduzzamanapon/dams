<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('doctor.edit');
    }

    public function rules(): array
    {
        return [
            'department_id'    => ['required', 'exists:departments,id'],
            'name'             => ['required', 'string', 'max:150'],
            'designation'      => ['required', 'string', 'max:150'],
            'bmdc_no'          => ['nullable', 'string', 'max:50', Rule::unique('doctors', 'bmdc_no')->ignore($this->route('doctor'))],
            'degrees'          => ['nullable', 'string', 'max:255'],
            'bio'              => ['nullable', 'string'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'        => ['boolean'],
            'is_featured'      => ['boolean'],
            'order'            => ['integer', 'min:0'],
            'fees'             => ['nullable', 'array'],
            'fees.*.label'     => ['required_with:fees', 'string', 'max:100'],
            'fees.*.amount'    => ['required_with:fees', 'numeric', 'min:0'],
            'schedules'               => ['nullable', 'array'],
            'schedules.*.day_of_week' => ['required_with:schedules', 'integer', 'min:0', 'max:6'],
            'schedules.*.start_time'  => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.end_time'    => ['required_with:schedules', 'date_format:H:i'],
            'schedules.*.slot_duration_minutes' => ['required_with:schedules', 'integer', 'min:5', 'max:120'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'   => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
        ]);
    }
}
