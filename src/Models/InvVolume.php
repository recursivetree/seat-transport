<?php

namespace RecursiveTree\Seat\TransportPlugin\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;

class InvVolume extends Model
{
    public $timestamps = false;

    protected $table = 'invVolumes';
    protected $primaryKey = 'typeID';

    public $incrementing = false;
}