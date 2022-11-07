<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Ramsey\Uuid\Provider\Node\StaticNodeProvider;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;

class Tag extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes;

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
    ];

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Tag::makeSlug($value);
    }

    public static function makeSlug(?string $value): string
    {
        return Str::of($value)->ascii()->slug()->lower()->toString();
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
}
