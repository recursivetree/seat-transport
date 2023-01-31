<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    public function up()
    {
        Schema::create('seat_transport_route', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("source_location_id");
            $table->bigInteger("destination_location_id");
            $table->float("isk_per_m3");
            $table->float("collateral_percentage");
        });

    }

    public function down()
    {
        Schema::dropIfExists('seat_transport_route');
    }
}

