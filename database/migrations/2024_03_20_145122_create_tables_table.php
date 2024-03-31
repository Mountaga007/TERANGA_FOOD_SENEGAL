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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->integer('numero');
            $table->string('nombre_place');
            $table->enum('disponibilte',['disponible','non_disponible'])->default('disponible');

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
        Schema::dropIfExists('tables');
    }
};
