<?php

namespace App\Http\Requests\Auth;

use App\Services\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //'phone' => 'string|required|unique:users',
            'name' => 'required|string|max:20',
            'surname' => 'sometimes|nullable|max:20',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
                ,
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $name = $this->input('name');

        if ($parts = explode(' ', $name)) {
            $this->merge([
                'name' => $parts[0] ?? null,
                'surname' => $parts[1] ?? null,
            ]);
        }

        $this->merge([
            'phone' => Helper::cleanPhone($this->input('phone')),
        ]);
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Имя или Имя и через пробел Фамилия',
            ],
            'email' => [
                'description' => 'Email',
            ],
            'password' => [
                'description' => 'Пароль',
            ],
        ];
    }
}
