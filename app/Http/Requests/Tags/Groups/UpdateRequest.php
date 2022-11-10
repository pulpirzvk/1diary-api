<?php

namespace App\Http\Requests\Tags\Groups;

use App\Models\Tags\Group;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        /** @var Group $group */
        $group = $this->route()->parameter('group');

        return $group->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:40',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Имя группы тегов',
            ],
        ];
    }
}
