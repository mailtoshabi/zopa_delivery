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
        Schema::table('kitchens', function (Blueprint $table) {
        $table->string('display_name')->after('name')->nullable();
        $table->string('license_number')->nullable();
        $table->string('fssai_number')->nullable();
        $table->string('license_file')->nullable(); // File path
        $table->string('fssai_certificate')->nullable(); // File path
        $table->text('other_documents')->nullable(); // JSON if multiple files
        $table->boolean('is_approved')->default(false);
        $table->timestamp('approved_at')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchens', function (Blueprint $table) {
            //
        });
    }
};
