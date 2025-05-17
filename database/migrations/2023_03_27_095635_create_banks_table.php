<?php

use App\Models\Bank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('banks', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('name')->unique();
        //     $table->boolean('status')->default(1);
        //     $table->timestamps();
        // });

        //  // Seed the banks table with major banks in India
        //  DB::table('banks')->insert([
        //     ['name' => 'State Bank of India', 'status' => 1],
        //     ['name' => 'Punjab National Bank', 'status' => 1],
        //     ['name' => 'HDFC Bank', 'status' => 1],
        //     ['name' => 'ICICI Bank', 'status' => 1],
        //     ['name' => 'Axis Bank', 'status' => 1],
        //     ['name' => 'Bank of Baroda', 'status' => 1],
        //     ['name' => 'Canara Bank', 'status' => 1],
        //     ['name' => 'Union Bank of India', 'status' => 1],
        //     ['name' => 'Indian Bank', 'status' => 1],
        //     ['name' => 'Bank of India', 'status' => 1],
        //     ['name' => 'Central Bank of India', 'status' => 1],
        //     ['name' => 'Indian Overseas Bank', 'status' => 1],
        //     ['name' => 'IDFC FIRST Bank', 'status' => 1],
        //     ['name' => 'Kotak Mahindra Bank', 'status' => 1],
        //     ['name' => 'RBL Bank', 'status' => 1],
        //     ['name' => 'Standard Chartered Bank', 'status' => 1],
        //     ['name' => 'DBS Bank India', 'status' => 1],
        //     ['name' => 'Citi Bank', 'status' => 1],
        //     ['name' => 'HSBC India', 'status' => 1],
        //     ['name' => 'IndusInd Bank', 'status' => 1],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('banks');
    }
};
