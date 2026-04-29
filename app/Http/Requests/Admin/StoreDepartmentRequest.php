<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('department.create');
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'unique:departments,name'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['boolean'],
            'order'       => ['integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug'      => Str::slug($this->name ?? ''),
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}
