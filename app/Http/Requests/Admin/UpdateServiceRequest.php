<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('service.edit');
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:150', Rule::unique('services', 'title')->ignore($this->route('service'))],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'is_active'   => ['boolean'],
            'order'       => ['integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', true)]);
    }
}
