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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->enum('status', [
                'pending', 
                'waiting_payment', 
                'processing', 
                'shipped', 
                'completed', 
                'cancelled'
            ])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->string('shipping_carrier')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->text('shipping_address');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->text('notes')->nullable();
            $table->timestamp('payment_due_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_due_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};