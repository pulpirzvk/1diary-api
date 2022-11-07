<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TagCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
