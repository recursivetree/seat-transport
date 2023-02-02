<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportPluginTables extends Migration
{
    public function up()
    {
        // this check is there because I had to rename the migration. On existing installs, this will just skip table creation
        if(!Schema::hasTable('seat_transport_route')) {
            Schema::create('seat_transport_route', function (Blueprint $table) {
                $table->bigIncrements("id");
                $table->bigInteger("source_location_id");
                $table->bigInteger("destination_location_id");
                $table->float("isk_per_m3");
                $table->float("collateral_percentage");
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('seat_transport_route');
    }
}

