<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('item_options', function (Blueprint $table) {

            $table->unsignedBigInteger('option_id')
                ->index()
                ->comment('ID файла');

            $table->unsignedBigInteger('item_id')
                ->index()
                ->comment('ID товара');

            $table->string('value')
                ->nullable(false)
                ->comment('Значение опции товара');

            $table->comment('Pivot опции товаров');

            $table->foreign('option_id', 'io_option_key')
                ->references('id')
                ->on('options')
                ->onDelete('cascade');

            $table->foreign('item_id', 'io_item_key')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_images', function(Blueprint $table) {
            $table->dropForeign('io_option_key');
            $table->dropForeign('io_item_key');
        });

        Schema::dropIfExists('item_options');
    }
};
