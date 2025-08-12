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
             $table->decimal('delivery_distance', 8, 2)->default(5); // in KM
             $table->time('cutoff_time')->default('08:50')->after('status');
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
