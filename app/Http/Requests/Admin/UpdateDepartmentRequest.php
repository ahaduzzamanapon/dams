<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('department.edit');
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', Rule::unique('departments', 'name')->ignore($this->route('department'))],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
            'order'       => ['integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', true)]);
    }
}
