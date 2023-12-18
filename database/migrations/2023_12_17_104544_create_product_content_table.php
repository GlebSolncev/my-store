<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Контент для клиента
         * Под заполнение администратором\контент-менеджером
         */
        Schema::create('product_content', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->string('image');
            $table->string('vendor', 50);
            $table->text('description');
            $table->string('vendorCode', 50);
            $table->boolean('available');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->primary(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_content');
    }
};
