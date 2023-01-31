<?php

namespace RecursiveTree\Seat\TransportPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;

class TransportRoute extends Model
{
    public $timestamps = false;

    protected $table = 'seat_transport_route';

    public function source_station()
    {
        return $this->hasOne(UniverseStation::class, 'station_id', 'source_location_id');
    }
    public function source_structure()
    {
        return $this->hasOne(UniverseStructure::class, 'structure_id', 'source_location_id');
    }
    public function source_location(){
        return $this->source_station ?: $this->source_structure;
    }

    public function destination_station()
    {
        return $this->hasOne(UniverseStation::class, 'station_id', 'destination_location_id');
    }
    public function destination_structure()
    {
        return $this->hasOne(UniverseStructure::class, 'structure_id', 'destination_location_id');
    }
    public function destination_location(){
        return $this->destination_station ?: $this->destination_structure;
    }
}