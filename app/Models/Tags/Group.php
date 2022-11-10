<?php

namespace App\Models\Tags;

use App\Models\User;
use Database\Factories\Tags\GroupFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Provider\Node\StaticNodeProvider;
use Ramsey\Uuid\Type\Hexadecimal;
use Ramsey\Uuid\Uuid;

class Group extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes;

    protected $table = 'tag_groups';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';


    protected $fillable = [
        'name',
        'user_id',
    ];

    protected static function newFactory(): GroupFactory
    {
        return GroupFactory::new();
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
        return $this->belongsToMany(Tag::class, 'tag_tag_group', 'group_uuid', 'tag_uuid');
    }
}
