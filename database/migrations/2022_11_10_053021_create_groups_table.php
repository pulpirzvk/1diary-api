<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tag_groups', static function (Blueprint $table) {
            $table->comment('Группы тегов');

            $table->uuid()->primary();

            $table->foreignId('user_id')->comment('Пользователь')->constrained();
            $table->string('name', 40)->comment('Имя группы');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tag_tag_group', static function (Blueprint $table) {
            $table->comment('Связь группа тегов - тег');

            $table->foreignUuid('group_uuid')->comment('Запись')->constrained('tag_groups', 'uuid');
            $table->foreignUuid('tag_uuid')->comment('Тег')->constrained('tags', 'uuid');

            $table->unique([
                'group_uuid',
                'tag_uuid',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_tag_group');
        Schema::dropIfExists('tag_groups');
    }
};
