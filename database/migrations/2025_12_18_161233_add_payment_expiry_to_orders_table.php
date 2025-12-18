<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('payment_expiry_at')->nullable()->after('status');
            $table->timestamp('auto_cancelled_at')->nullable()->after('cancelled_at');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_expiry_at', 'auto_cancelled_at']);
        });
    }
};