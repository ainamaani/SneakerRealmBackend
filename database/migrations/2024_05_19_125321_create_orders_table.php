<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('order_number')->unique();
            $table->string('status')->default(OrderStatus::PENDING->value);
            $table->text('delivery_address');
            $table->string('payment_method');
            $table->dateTime('order_date')->useCurrent();
            $table->dateTime('delivery_date')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            // Define the foreign key constrait
            $table->foreign('user_id')->references('id')->on('custom_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
