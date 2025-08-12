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
        Schema::table('meal_wallet', function (Blueprint $table) {
            $table->foreignId('wallet_group_id')
                ->nullable()
                ->after('customer_id')
                ->constrained('wallet_groups')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('meal_wallet', function (Blueprint $table) {
            $table->dropForeign(['wallet_group_id']);
            $table->dropColumn('wallet_group_id');
        });
    }
};
