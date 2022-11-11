<?php

namespace App\Console\Commands\Scout;

use Illuminate\Console\Command;
use MeiliSearch\Client;
use Symfony\Component\Console\Command\Command as CommandAlias;

class Manage extends Command
{
    protected $signature = 'scout:manage {index=posts_index}
    {--reset} {--reset:sortable} {--reset:searchable} {--reset:filterable}
    {--sort=*} {--search=*} {--filter=*}
    ';

    protected $description = 'Управление параметрами индекса Meilisearch';

    protected Client $client;

    protected string $index;

    public function handle(): int
    {
        $this->client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        $this->index = $this->argument('index');

        $this->reset();

        $this->updateSortable();

        $this->updateSearchable();

        $this->updateFilterable();

        return CommandAlias::SUCCESS;
    }

    protected function updateFilterable(): void
    {
        $add = $this->option('filter');

        if ($add) {
            $this->client->index($this->index)->updateFilterableAttributes($add);
        }
    }

    protected function updateSearchable(): void
    {
        $add = $this->option('search');

        if ($add) {
            $this->client->index($this->index)->updateSearchableAttributes($add);
        }
    }

    protected function updateSortable(): void
    {
        $add = $this->option('sort');

        if ($add) {
            $this->client->index($this->index)->updateSortableAttributes($add);
        }
    }

    protected function reset(): void
    {
        $resetAll = $this->option('reset');
        $resetSortable = $this->option('reset:sortable');
        $resetSearchable = $this->option('reset:searchable');
        $resetFilterable = $this->option('reset:filterable');

        if ($resetAll || $resetSortable) {
            $this->client->index($this->index)->resetSortableAttributes();
        }

        if ($resetAll || $resetFilterable) {
            $this->client->index($this->index)->resetFilterableAttributes();
        }

        if ($resetAll || $resetSearchable) {
            $this->client->index($this->index)->resetSearchableAttributes();
        }
    }
}
