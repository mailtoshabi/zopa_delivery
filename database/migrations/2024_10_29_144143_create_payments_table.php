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
        // Schema::create('payments', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('sale_id')->constrained()->onDelete('cascade');
        //     $table->double('amount');
        //     $table->smallInteger('payment_method')->nullable()->comment('1:Cash, 2:Bank, 3:UPI');
        //     $table->string('transaction_id')->unique()->nullable();
        //     $table->date('paid_at')->nullable();
        //     $table->text('description')->nullable();
        //     $table->smallInteger('status')->default(0)->comment('0:Pending, 1:Completed, 2:Failed, 3:Refunded');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('payments');
    }
};
