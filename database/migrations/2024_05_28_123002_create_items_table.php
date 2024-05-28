<?php

use App\Enums\ESoftStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('title')
                ->comment('Название товара');

            $table->text('body')
                ->default(null)
                ->nullable()
                ->comment('Описание товара');

            $table->decimal('price', 8, 2)
                ->nullable(false)
                ->comment('Цена');

            $table->unsignedBigInteger('stock')
                ->nullable(false)
                ->default(0)
                ->comment('Остаток');

            $table->unsignedTinyInteger('status')
                ->default(ESoftStatus::ACTIVE->value)
                ->comment('Статус товара');

            $table->timestamps();

            $table->comment('Каталог товаров');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
