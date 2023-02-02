<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransportPluginUpdate1 extends Migration
{
    public function up()
    {
        Schema::table('seat_transport_route', function (Blueprint $table) {
            $table->string("info_text")->nullable();
            $table->integer("maxvolume")->nullable();
        });

    }

    public function down()
    {
        Schema::table('seat_transport_route', function (Blueprint $table) {
            $table->dropColumn("info_text");
            $table->dropColumn("maxvolume");
        });
    }
}

