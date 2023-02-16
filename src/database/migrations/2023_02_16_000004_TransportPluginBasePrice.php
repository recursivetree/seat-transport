<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransportPluginBasePrice extends Migration
{
    public function up()
    {
        Schema::table('seat_transport_route', function (Blueprint $table) {
            $table->bigInteger("base_price")->unsigned()->default(0);
        });

    }

    public function down()
    {
        Schema::table('seat_transport_route', function (Blueprint $table) {
            $table->dropColumn("base_price");
        });
    }
}

