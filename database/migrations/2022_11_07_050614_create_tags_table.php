<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', static function (Blueprint $table) {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
