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
        Schema::create('customer_meal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('customer_orders')->onDelete('cascade');
            $table->foreignId('meal_id')->constrained()->onDelete('cascade');
            $table->double('price');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_meal');
    }
};
