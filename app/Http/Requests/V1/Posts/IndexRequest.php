<?php

namespace App\Http\Requests\V1\Posts;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}
