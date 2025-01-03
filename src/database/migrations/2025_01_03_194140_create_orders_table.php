<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('item_id')->references('item_id')->on('items')->onDelete('cascade');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('payment_method');
            $table->foreignId('destination_id')->references('destination_id')->on('destinations')->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
