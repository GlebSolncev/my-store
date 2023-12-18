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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('api_id')->unique()->index();
            $table->string('name');
            $table->string('slug')->index()->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedFloat('price');
            $table->tinyInteger('quantity');
            $table->boolean('stock');
            $table->boolean('recently_created')->default(false);

            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
