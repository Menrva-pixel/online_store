<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('shipping_address');
            $table->enum('payment_method', ['bank_transfer', 'cod', 'ewallet'])->nullable();
            $table->string('shipping_courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->enum('status', [
                'waiting_payment', 
                'processing', 
                'shipped', 
                'completed', 
                'cancelled'
            ])->default('waiting_payment');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('order_number');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};