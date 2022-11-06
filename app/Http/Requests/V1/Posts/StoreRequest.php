<?php

namespace App\Http\Requests\V1\Posts;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:80',
            'content' => 'required|string|max:9000',
            'published_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }
}
