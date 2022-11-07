<?php

namespace App\Http\Requests\Tags;

use App\Models\Tag;
use App\Models\User;
use App\Rules\TagSlug;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:20',
                (new TagSlug)->ignore($this->route()->originalParameter('tag')),
            ],
        ];
    }
}
