<?php

namespace App\Http\Requests\Current;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
                ,
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'password' => [
                'description' => 'Новый пароль',
            ],
        ];
    }
}
