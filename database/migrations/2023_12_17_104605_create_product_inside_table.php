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
         * Внутрянка.
         * Внутреняя цена, ссылка на продукт у поставщика и тд.
         * То что не нужно знать клиенту, но пригодиться админу
         */
        Schema::create('product_inside', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedFloat('wholesaleprice');
            $table->text('url');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
            $table->primary(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inside');
    }
};
