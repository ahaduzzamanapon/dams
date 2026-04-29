<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('user.create');
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'confirmed', Password::min(8)],
            'roles'     => ['required', 'array', 'min:1'],
            'roles.*'   => ['string', 'exists:roles,name'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', true)]);
    }
}
