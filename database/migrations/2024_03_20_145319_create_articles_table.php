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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('nom_article');
            $table->string('description_article');
            $table->integer('prix_article');
            $table->string('image_article');

            $table->unsignedBigInteger('personnel_restaurant_id');
            $table->foreign('personnel_restaurant_id')->references('id')->on('personnel__restaurants')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
