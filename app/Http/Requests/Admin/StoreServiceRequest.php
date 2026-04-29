<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('service.create');
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:150', 'unique:services,title'],
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
