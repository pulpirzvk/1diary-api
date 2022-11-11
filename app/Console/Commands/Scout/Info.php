<?php

namespace App\Console\Commands\Scout;

use Illuminate\Console\Command;
use MeiliSearch\Client;
use Symfony\Component\Console\Command\Command as CommandAlias;

class Info extends Command
{
    protected $signature = 'scout:info {index=posts_index}';

    protected $description = 'Базовая информация о индексе';

    public function handle(): int
    {
        $index = $this->argument('index');

        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        dump($client->index($index)->stats());

        dump($client->index($index)->getSettings());

        return CommandAlias::SUCCESS;
    }
}
