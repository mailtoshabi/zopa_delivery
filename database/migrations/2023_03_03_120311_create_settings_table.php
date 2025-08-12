<?php

use App\Models\Settings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('value')->nullable();
            $table->timestamps();
        });
        Settings::create(['term'=>'company_name', 'value'=>config('app.name')]);
        Settings::create(['term'=>'trade_name', 'value'=>config('app.name')]);
        Settings::create(['term'=>'email', 'value'=>'zopafood@gmail.com']);
        Settings::create(['term'=>'phone', 'value'=>'8089552553']);
        Settings::create(['term'=>'address1', 'value'=>'10/363, Chundakkadan Building']);
        Settings::create(['term'=>'address2', 'value'=>'Pazhayangadi, Kondotty PO']);
        Settings::create(['term'=>'address3', 'value'=>'Near Calicut Airport']);
        Settings::create(['term'=>'city', 'value'=>'Kondotty']);
        Settings::create(['term'=>'district_id', 'value'=>'230']);
        Settings::create(['term'=>'state_id', 'value'=>'12']);
        Settings::create(['term'=>'postal_code', 'value'=>'673638']);
        Settings::create(['term'=>'website', 'value'=>'www.zopa.in']);
        Settings::create(['term'=>'reset', 'value'=>'0']);
        Settings::create(['term'=>'cin', 'value'=>'']);
        Settings::create(['term'=>'pan', 'value'=>'ABWFA6262D']);
        Settings::create(['term'=>'bank_id', 'value'=>'5']);
        Settings::create(['term'=>'account_name', 'value'=>'AZZET GROUP']);
        Settings::create(['term'=>'account_number', 'value'=>'921020050860357']);
        Settings::create(['term'=>'bank_branch', 'value'=>'Kondotty']);
        Settings::create(['term'=>'ifsc', 'value'=>'UTIB0003043']);
        Settings::create(['term'=>'default_branch_id', 'value'=>'2']);

        // Settings::create(['term'=>'whatsapp', 'value'=>'9809373738']);
        // Settings::create(['term'=>'facebook', 'value'=>'www.facebook.com']);
        // Settings::create(['term'=>'instagram ', 'value'=>'www.instagram.com']);
        // Settings::create(['term'=>'youtube', 'value'=>'www.youtube.com']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
