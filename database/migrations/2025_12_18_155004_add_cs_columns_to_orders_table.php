<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk proses order
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            
            // Kolom untuk shipping
            $table->foreignId('shipped_by')->nullable()->constrained('users');
            $table->timestamp('shipped_at')->nullable();
            
            // Kolom untuk completion
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            
            // Kolom untuk cancellation
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropForeign(['shipped_by']);
            $table->dropForeign(['completed_by']);
            $table->dropForeign(['cancelled_by']);
            
            $table->dropColumn([
                'processed_by', 'processed_at',
                'shipped_by', 'shipped_at',
                'completed_by', 'completed_at',
                'cancelled_by', 'cancelled_at', 'cancellation_reason'
            ]);
        });
    }
};