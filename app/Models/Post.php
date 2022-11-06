<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Provider\Node\StaticNodeProvider;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;

class Post extends Model
{
    use HasFactory,
        HasUuids,
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

    public function newUniqueId(): string
    {
        $nodeProvider = new StaticNodeProvider(new Hexadecimal(dechex($this->user_id)));

        return (string)Uuid::uuid6($nodeProvider->getNode());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
