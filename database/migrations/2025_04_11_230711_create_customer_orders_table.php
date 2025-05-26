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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // Single invoice
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->smallInteger('pay_method')->nullable()->comment('1: Online, 2: COD, 3: Bank Transfer');
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->decimal('amount', 10, 2);
            $table->double('discount')->default(0);
            $table->double('delivery_charge')->default(0);
            $table->boolean('is_paid')->default(0)->comment('1-paid, 0-not paid');
            $table->boolean('status')->default(1)->comment('1-Active, 0-Cancelled');
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
