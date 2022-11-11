<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'string|required',
            'password' => 'string|required',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'login' => [
                'description' => 'Логин. В качестве логина выступает e-mail пользователя.',
            ],
            'password' => [
                'description' => 'Пароль',
            ],
        ];
    }
}
