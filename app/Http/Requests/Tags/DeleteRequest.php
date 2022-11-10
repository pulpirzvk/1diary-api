<?php

namespace App\Http\Requests\Tags;

use App\Models\Tags\Tag;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        /** @var Tag $tag */
        $tag = $this->route()->parameter('tag');

        return $tag->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function bodyParameters(): array
    {
        return [
            //
        ];
    }
}
