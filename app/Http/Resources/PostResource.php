<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->toAtomString(),
            'published_at' => $this->published_at->toAtomString(),
        ];
    }
}
