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
        Schema::create('kitchen_addon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained()->cascadeOnDelete();

            $table->string('price', 255)->nullable();
            $table->string('image_filename')->nullable();
            $table->boolean('status')->default(1)->comment('1 - Active, 0 - Inactive');

            $table->timestamps();

            $table->unique(['kitchen_id', 'addon_id']); // Prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_addon');
    }
};
