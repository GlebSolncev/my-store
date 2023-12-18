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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('api_id', 50)->nullable()->index();
            $table->string('slug')->unique()->index();
            $table->string('title');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_category')->default(true); // true: for products, false: for site pages
            $table->boolean('is_active')->default(false);

            $table->timestamps();

            $table->foreign('parent_id', 'frg_parent_id_to_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
