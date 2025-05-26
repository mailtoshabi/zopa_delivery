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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('whatsapp')->unique();
            $table->string('password');
            $table->string('office_name')->nullable();
            $table->string('city')->nullable();
            $table->string('landmark')->nullable();
            $table->string('designation')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
            $table->foreignId('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->cascadeOnDelete();
            $table->string('postal_code')->nullable();
            $table->text('image_filename')->nullable();
            $table->foreignId('kitchen_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('status')->comment('1-Active 0-Inactive')->default(1);
            $table->text('firebase_uid')->nullable();
            // $table->string('otp_code')->nullable();
            // $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_approved')->comment('1-Approved 0-Unapporved')->default(0);
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
