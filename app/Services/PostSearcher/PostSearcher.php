<?php

namespace App\Services\PostSearcher;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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

    protected ?string $sortBy = 'uuid';

    protected ?string $sortDirection = 'DESC';

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public static function make(int|User $user): PostSearcher
    {
        return new PostSearcher($user instanceof User ? $user->id : $user);
    }

    public function fillFromRequest(Request $request): PostSearcher
    {
        return $this->setSearchString($request->input('s'))
            ->setPublishedAtFrom($request->input('published_from'))
            ->setPublishedAtTill($request->input('published_till'))
            ->setCreatedAtFrom($request->input('created_from'))
            ->setCreatedAtTill($request->input('created_till'))
            ->setTagIds($request->input('tags', []))
            ->setSortBy($request->input('sort_by'))
            ->setSortDirection('sort_in');
    }

    public function hasEmptyConditions(): bool
    {
        return empty($this->s)
            && empty($this->tagIds)
            && empty($this->publishedAtFrom)
            && empty($this->publishedAtTill)
            && empty($this->createdAtFrom)
            && empty($this->createdAtTill);
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

        if ($this->sortBy) {
            $search->orderBy($this->sortBy, $this->sortDirection);
        }

        return $search->orderBy('uuid')->get();
    }

    public function setSortBy(?string $sortBy): PostSearcher
    {
        $sortBy = strtolower($sortBy ?: '');

        $this->sortBy = in_array($sortBy, [
            'uuid',
            'published_at',
        ]) ? $sortBy : 'uuid';

        return $this;
    }

    public function setSortDirection(?string $sortDirection): PostSearcher
    {
        $sortDirection = strtoupper($sortDirection ?: '');

        $this->sortDirection = in_array($sortDirection, [
            'DESC',
            'ASC',
        ]) ? $sortDirection : 'DESC';

        return $this;
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
