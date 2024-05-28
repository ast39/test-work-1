<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('item_images', function (Blueprint $table) {

            $table->unsignedBigInteger('image_id')
                ->index()
                ->comment('ID файла');

            $table->unsignedBigInteger('item_id')
                ->index()
                ->comment('ID товара');

            $table->comment('Pivot изображения товаров');

            $table->foreign('image_id', 'ii_image_key')
                ->references('id')
                ->on('images')
                ->onDelete('cascade');

            $table->foreign('item_id', 'ii_item_key')
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
            $table->dropForeign('ii_file_key');
            $table->dropForeign('ii_item_key');
        });

        Schema::dropIfExists('item_images');
    }
};
