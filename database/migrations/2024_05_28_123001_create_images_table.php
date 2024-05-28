<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {

            $table->id();

            $table->string('path')
                ->comment('Путь к файлу');

            $table->string('filename')
                ->comment('Имя файла');

            $table->string('ext')
                ->comment('Расширение файла');

            $table->decimal('size', 8, 2)
                ->nullable()
                ->comment('Размеры файла');

            $table->timestamps();

            $table->comment('Файлы изображений');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
