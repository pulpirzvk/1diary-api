<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', static function (Blueprint $table) {
            $table->comment('Теги пользователя');

            $table->uuid()->primary();

            $table->foreignId('user_id')->comment('Пользователь')->constrained();
            $table->string('name')->comment('Имя тега');
            $table->string('slug')->comment('Слаг');

            $table->timestamps();
            $table->softDeletes();

            $table->unique([
                'user_id',
                'slug',
            ]);
        });

        Schema::create('post_tag', static function (Blueprint $table) {
            $table->comment('Связь запись - тег');

            $table->foreignUuid('post_uuid')->comment('Запись')->constrained('posts', 'uuid');
            $table->foreignUuid('tag_uuid')->comment('Тег')->constrained('tags', 'uuid');

            $table->unique([
                'post_uuid',
                'tag_uuid',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('tags');
    }
};
