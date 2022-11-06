<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\Post
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', static function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignId('user_id')->comment('Пользователь')->constrained();
            $table->string('title')->nullable()->comment('Заголовок');
            $table->text('content')->nullable()->comment('Основной текст');
            $table->timestamp('published_at')->useCurrent()->comment('Время публикации');

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'user_id',
                'published_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
