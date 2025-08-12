<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('wallet_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // system key
            $table->string('display_name');   // visible label
            $table->boolean('status')->default(1)->comment('1 - Active, 0 - Inactive');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_groups');
    }
};
