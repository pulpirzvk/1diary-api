<?php

namespace App\Models;

use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Ramsey\Uuid\Provider\Node\StaticNodeProvider;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;

class Post extends Model
{
    use HasFactory,
        HasUuids,
        Searchable,
        SoftDeletes;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function searchableAs(): string
    {
        return 'posts_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at->getTimestamp(),
            'created_at' => $this->created_at->getTimestamp(),
            'tags' => $this->tags->pluck('uuid')->toArray(),
        ];
    }

    public function getScoutKey(): string
    {
        return $this->uuid;
    }

    public function getScoutKeyName(): string
    {
        return 'uuid';
    }

    public function newUniqueId(): string
    {
        $nodeProvider = new StaticNodeProvider(new Hexadecimal(dechex($this->attributes['user_id'])));

        return (string)Uuid::uuid6($nodeProvider->getNode());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_uuid', 'tag_uuid');
    }
}
