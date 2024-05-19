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
        Schema::create('sneaker_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sneaker_id')->constrained('sneakers')->onDelete('cascade');
            $table->integer('size');
            $table->string('color');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sneaker_variants');
    }
};
