<?php

namespace App\Rules;

use App\Models\Tags\Tag;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class TagSlug implements InvokableRule
{
    protected ?string $ignoreId = null;

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function __invoke($attribute, $value, $fail): void
    {
        $slug = Tag::makeSlug($value);

        $builder = Tag::query()
            ->where('user_id', '=', auth()->id())
            ->where('slug', '=', $slug);

        if ($this->ignoreId) {
            $builder->where('uuid', '<>', $this->ignoreId);
        }

        $exists = $builder->exists();

        if ($exists) {
            $fail('validation.tag_slug')->translate();
        }
    }

    public function ignore(?string $ignoreId): TagSlug
    {
        $this->ignoreId = $ignoreId;

        return $this;
    }


}
