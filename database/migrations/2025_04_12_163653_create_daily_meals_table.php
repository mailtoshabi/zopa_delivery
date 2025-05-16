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
        Schema::create('daily_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->text('reason')->nullable();
            $table->boolean('is_delivered')->comment('1-Delivered 0-UnDelivered')->default(0);
            $table->boolean('status')->comment('1-Active 0-Cancelled')->default(1);
            $table->boolean('is_auto')->comment('1-Auto Generated 0-Customer Requested')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_meals');
    }
};
