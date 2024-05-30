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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sneaker_id');
            $table->integer('rating')->unsigned()->default(1);
            $table->text('review')->nullable();
            $table->timestamps();


            // define the foreign key constraints
            $table->foreign('user_id')->references('id')->on('custom_users')->onDelete('cascade');
            $table->foreign('sneaker_id')->references('id')->on('sneakers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
