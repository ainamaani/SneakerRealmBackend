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
            $table->foreignId('user_id')->constrained('custom_users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->foreignId('sneaker_id')->constrained('sneakers')->onDelete('cascade');
            $table->integer('quantity');
            $table->float('unit_price');
            $table->float('quantity_price');
            $table->string('status')->default(OrderStatus::PENDING->value);
            $table->text('delivery_address');
            $table->string('payment_method');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('delivery_date')->nullable();
            $table->timestamps();
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
