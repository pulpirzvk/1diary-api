<?php

namespace App\Models;

use App\Models\Tags\Group;
use App\Models\Tags\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function tagGroups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
