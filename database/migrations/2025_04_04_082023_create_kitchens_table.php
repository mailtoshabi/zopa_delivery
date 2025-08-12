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
        Schema::create('kitchens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->unique();
            $table->string('whatsapp')->nullable()->unique();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('city');
            $table->foreignId('district_id')->index();
            $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
            $table->foreignId('state_id')->index();
            $table->foreign('state_id')->references('id')->on('states')->cascadeOnDelete();
            $table->string('postal_code')->nullable();
            $table->string('image_filename', 255)->nullable();
            $table->boolean('status')->comment('1-Active 0-Inactive')->default(0);
            $table->foreignId('user_id')->nullable()->index()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchens');
    }
};
