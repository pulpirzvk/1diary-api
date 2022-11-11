<?php

namespace App\Console\Commands\Test;

use App\Models\Post;
use App\Services\PostSearcher\PostSearcher;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

/**
 * @see https://serversideup.net/filtering-meilisearch-search-results-with-laravel-scout/
 * @see https://serversideup.net/advanced-meilisearch-queries-with-laravel-scout/
 * @see https://docs.meilisearch.com/learn/advanced/filtering_and_faceted_search.html
 * @see https://laracasts.com/discuss/channels/laravel/scout-meilisearch-driver-attribute-is-not-filterable
 */
class SearchPost extends Command
{
    protected $signature = 'test:search:post {user} {--s=} {--publish=} {--create=} {--tag=*}';

    protected $description = 'Тестирование поиска';

    public function handle(): int
    {
        $s = $this->option('s');

        $userId = $this->argument('user');

        $tags = $this->option('tag');

        $publishRange = $this->option('publish');

        $createRange = $this->option('create');

        list($publishStart, $publishEnd) = array_pad(explode(':', $publishRange), 2, null);

        list($createStart, $createEnd) = array_pad(explode(':', $createRange), 2, null);

        $items = PostSearcher::make($userId)
            ->setSearchString($s)
            ->setPublishedAtFrom($publishStart)
            ->setPublishedAtTill($publishEnd)
            ->setCreatedAtFrom($createStart)
            ->setCreatedAtTill($createEnd)
            ->setTagIds($tags)
            ->get();

        $this->line(vsprintf('Search: "%s". User ID: "%s". Published: [%s]. Created: [%s]. Count: %s', [
            $s,
            $userId,
            $publishRange,
            $createRange,
            $items->count(),
        ]));

        $headers = [
            'UUID',
            'User',
            'Publish',
            'Title',
        ];

        $this->table($headers, $items->map(static function (Post $item) {
            return [
                $item->getKey(),
                $item->user_id,
                $item->published_at->format('Y-m-d'),
                $item->title,
            ];
        }));

        return CommandAlias::SUCCESS;
    }

    protected function getStartTimestamp(string $date): int
    {
        return Carbon::createFromFormat('Y-m-d', $date)->startOfDay()->getTimestamp();
    }

    protected function getEndTimestamp(string $date): int
    {
        return Carbon::createFromFormat('Y-m-d', $date)->endOfDay()->getTimestamp();
    }
}
