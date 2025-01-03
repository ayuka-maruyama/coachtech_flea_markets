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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id('destination_id');
            $table->foreignId('item_id')->references('item_id')->on('items')->onDelete('cascade');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('postal_number');
            $table->string('address');
            $table->string('building');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
