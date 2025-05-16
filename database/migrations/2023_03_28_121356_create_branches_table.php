<?php

use App\Http\Utilities\Utility;
use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        // Schema::create('branches', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('trade_name')->nullable();
        //     $table->string('email')->nullable()->unique();
        //     $table->string('phone')->nullable();
        //     $table->string('address1')->nullable();
        //     $table->string('address2')->nullable();
        //     $table->string('address3')->nullable();
        //     $table->string('city')->nullable();
        //     $table->foreignId('district_id')->nullable();
        //     $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
        //     $table->foreignId('state_id')->nullable();
        //     $table->foreign('state_id')->references('id')->on('states')->cascadeOnDelete();
        //     $table->string('postal_code')->nullable();
        //     $table->text('image')->nullable();
        //     $table->string('website')->nullable(); // Customer website

        //     $table->string('gstin')->nullable(); // Optional GSTIN (Goods and Services Tax Identification Number)
        //     $table->string('cin')->nullable(); // Optional CIN (Corporate Identification Number)
        //     $table->string('pan')->nullable(); // Optional PAN (Permanent Account Number)
        //     $table->foreignId('bank_id')->nullable()->constrained()->onDelete('cascade');
        //     $table->string('account_name')->nullable(); // Account name
        //     $table->string('account_number')->nullable(); // Account number
        //     $table->string('bank_branch')->nullable(); // Branch name
        //     $table->string('ifsc')->nullable(); // IFSC (Indian Financial System Code)
        //     $table->boolean('is_approved')->comment('1-Approved 0-Unapporved')->default(0);
        //     $table->boolean('status')->comment('1-Active 0-Inactive')->default(1);
        //     $table->timestamps();
        // });

        // DB::table('branches')->insert([
        // ['name'=>'Azzet Group',
        // 'trade_name'=>'Azzet Group',
        // 'email'=>'azzetgroup@gmail.com',
        // 'phone'=>'8089552553',
        // 'address1'=>'10/363, Chundakkadan Building',
        // 'address2'=>'Pazhayangadi, Kondotty PO',
        // 'address3'=>'Near Calicut Airport',
        // 'city'=>'Kondotty',
        // 'district_id'=>'230',
        // 'state_id'=>'12',
        // 'postal_code'=>'673638',
        // 'website'=>'www.azzetgroup.com',
        // 'gstin'=>'32ABWFA6262D1ZF',
        // 'cin'=>'',
        // 'pan'=>'ABWFA6262D',
        // 'bank_id'=>'5',
        // 'account_name'=>'AZZET GROUP',
        // 'account_number'=>'921020050860357',
        // 'bank_branch'=>'Kondotty',
        // 'ifsc'=>'UTIB0003043',
        // 'status' => 1,
        // 'created_at' => now()],
        // ['name'=>'Foco Creativo',
        // 'trade_name'=>'Foco Creativo',
        // 'email'=>'foco@gmail.com',
        // 'phone'=>'7510310132',
        // 'address1'=>'Building No:13/1080A Amina Arcade, Kolathara',
        // 'address2'=>'Road Near Falcon Tiles',
        // 'address3'=>'',
        // 'city'=>'Kozhikode',
        // 'district_id'=>'231',
        // 'state_id'=>'12',
        // 'postal_code'=>'673019',
        // 'website'=>'www.foco.com',
        // 'gstin'=>'32AAJFF6743Q1ZW',
        // 'cin'=>'',
        // 'pan'=>'AA6262DBWF',
        // 'bank_id'=>'5',
        // 'account_name'=>'FOCO CREATIVO',
        // 'account_number'=>'921020050860357',
        // 'bank_branch'=>'Calicut',
        // 'ifsc'=>'UTIB0003043',
        // 'status' => 1,
        // 'created_at' => now()]
        // ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('branches');
    }
};
