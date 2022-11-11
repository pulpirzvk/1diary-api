<?php

namespace App\Console\Commands\Deploy;

use App\Models\Post;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PostsIndex extends Command
{
    protected $signature = 'deploy:posts_index';

    protected $description = 'Сгенерировать индекс для поиска по постам';

    public function handle(): int
    {
        $this->line('Deleting posts index...');
        $this->call('scout:delete-index', [
            'name' => 'posts_index',
        ]);

        $this->line('Creating posts index...');
        $this->call('scout:index', [
            'name' => 'posts_index',
        ]);

        $this->line('Flush posts index...');
        $this->call('scout:flush', [
            'model' => Post::class,
        ]);

        $this->line('Reset posts index attributes...');
        $this->call('scout:manage', [
            '--reset' => true,
        ]);
        $this->info('Posts index attributes was reset successfully');

        $this->line('Updating posts index searchable attributes...');
        $this->call('scout:manage', [
            '--search' => [
                'title',
                'content',
            ],
        ]);
        $this->info('Posts index searchable attributes was updated successfully');

        $this->line('Updating posts index sortable attributes...');
        $this->call('scout:manage', [
            '--sort' => [
                'uuid',
                'published_at',
            ],
        ]);
        $this->info('Posts index sortable attributes was updated successfully');

        $this->line('Updating posts index filterable attributes...');
        $this->call('scout:manage', [
            '--filter' => [
                'user_id',
                'published_at',
                'created_at',
                'tags',
            ],
        ]);
        $this->info('Posts index filterable attributes was updated successfully');

        $this->line('Importing data for posts index...');
        $this->call('scout:import', [
            'model' => Post::class,
        ]);

        return CommandAlias::SUCCESS;
    }
}
