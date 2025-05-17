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
        Schema::create('addon_wallet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->boolean('status')->comment('1-Active 0-Suspended')->default(1);
            $table->boolean('is_on')->comment('1-on 0-off')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_wallet');
    }
};
