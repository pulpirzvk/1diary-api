<?php

namespace App\Services\PostSearcher;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PostSearcher
{
    protected int $userId;

    protected ?string $s = null;

    protected array $tagIds = [];

    protected ?Date $publishedAtFrom = null;

    protected ?Date $publishedAtTill = null;

    protected ?Date $createdAtFrom = null;

    protected ?Date $createdAtTill = null;

    protected ?int $limit = null;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public static function make(int|User $user): PostSearcher
    {
        return new PostSearcher($user instanceof User ? $user->id : $user);
    }

    public function limit(?int $limit): PostSearcher
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return Collection<Post>
     */
    public function get(): Collection
    {
        $search = Post::search($this->s)
            ->where('user_id', $this->userId);


        if ($this->publishedAtFrom) {
            $search->where('published_at >', $this->publishedAtFrom->getStartOfDayTimestamp());
        }

        if ($this->publishedAtTill) {
            $search->where('published_at <', $this->publishedAtTill->getEndOfDayTimestamp());
        }

        if ($this->createdAtFrom) {
            $search->where('created_at >', $this->createdAtFrom->getStartOfDayTimestamp());
        }

        if ($this->createdAtTill) {
            $search->where('created_at <', $this->createdAtTill->getEndOfDayTimestamp());
        }

        if ($this->tagIds) {
            $search->whereIn('tags', $this->tagIds);
        }

        if ($this->limit) {
            $search->take($this->limit);
        }

        return $search->orderBy('uuid')->get();
    }

    public function setSearchString(?string $s): PostSearcher
    {
        $this->s = $s;

        return $this;
    }

    public function setTagIds(array $tagIds = []): PostSearcher
    {
        $this->tagIds = $tagIds;

        return $this;
    }

    public function setPublished(Carbon|string|null $date): PostSearcher
    {
        $this->publishedAtFrom = Date::make($date);

        $this->publishedAtTill = Date::make($date);

        return $this;
    }

    public function setPublishedAtFrom(Carbon|string|null $date): PostSearcher
    {
        $this->publishedAtFrom = Date::make($date);

        return $this;
    }

    public function setPublishedAtTill(Carbon|string|null $date): PostSearcher
    {
        $this->publishedAtTill = Date::make($date);

        return $this;
    }

    public function setCreated(Carbon|string|null $date): PostSearcher
    {
        $this->createdAtFrom = Date::make($date);

        $this->createdAtTill = Date::make($date);

        return $this;
    }

    public function setCreatedAtFrom(Carbon|string|null $date): PostSearcher
    {
        $this->createdAtFrom = Date::make($date);

        return $this;
    }

    public function setCreatedAtTill(Carbon|string|null $date): PostSearcher
    {
        $this->createdAtTill = Date::make($date);

        return $this;
    }


}
