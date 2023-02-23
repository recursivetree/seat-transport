<?php

namespace RecursiveTree\Seat\TransportPlugin\Http\Controllers;

use RecursiveTree\Seat\TransportPlugin\Models\InvVolume;
use RecursiveTree\Seat\TransportPlugin\Models\TransportRoute;
use RecursiveTree\Seat\TransportPlugin\Prices\SeatTransportPriceProviderSettings;
use RecursiveTree\Seat\TreeLib\Helpers\Parser;
use RecursiveTree\Seat\TreeLib\Prices\EvePraisalPriceProvider;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class TransportPluginController extends Controller
{
    public function settings(){
        $stations = UniverseStation::all();
        $structures = UniverseStructure::all();
        $routes = TransportRoute::all();
        $info_text = "";
        return view("transportplugin::settings", compact("stations","structures","routes","info_text"));
    }

    public function saveRoute(Request $request){
        $request->validate([
            "source_location"=>"required|integer",
            "destination_location"=>"required|integer",
            "collateral"=>"required|numeric",
            "iskm3"=>"required|numeric",
            "info_text"=>"present|string|nullable",
            "maxm3"=>"present|integer|nullable",
            "rushmarkup"=>"present|numeric|nullable",
            "baseprice"=>"required|integer",
            "maxcollateral"=>"present|integer|nullable"
        ]);

        $route = TransportRoute::where("source_location_id",$request->source_location)
            ->where("destination_location_id",$request->destination_location)
            ->first();

        if ($route == null){
            $route = new TransportRoute();
        }

        $route->source_location_id = $request->source_location;
        $route->destination_location_id = $request->destination_location;
        $route->isk_per_m3 = $request->iskm3;
        $route->collateral_percentage = $request->collateral;
        $route->info_text = $request->info_text;
        $route->maxvolume = $request->maxm3;
        $route->rush_markup = $request->rushmarkup;
        $route->base_price = $request->baseprice;
        $route->max_collateral = $request->maxcollateral;
        $route->save();

        $request->session()->flash("success","Successfully added/updated route!");

        return redirect()->route("transportplugin.settings");
    }

    public function deleteRoute(Request $request){
        $request->validate([
            "id"=>"required|integer"
        ]);

        TransportRoute::destroy($request->id);

        $request->session()->flash("success","Successfully deleted route!");

        return redirect()->back();
    }

    public function calculate(){
        $routes = TransportRoute::all();
        return view("transportplugin::calculate", compact("routes"));
    }

    const ILLEGAL_GROUPS = [
        448,//cargo containers
        12,//Secure Cargo Container
        340,//Audit Log Secure Container
        649//Freight Container
    ];

    public function postCalculate(Request $request){
        $request->validate([
            "route"=>"required|integer",
            "items"=>"required|string",
            "rush_contract"=>"nullable"
        ]);

        $route = TransportRoute::find($request->route);

        //parse copy paste area
        $parser_result = \RecursiveTree\Seat\TreeLib\Parser\Parser::parseItems($request->items);

        if($parser_result == null || $parser_result->items->isEmpty()){
            $request->session()->flash("error","You need to enter at least one item!");
            return redirect()->back();
        }


        $volume = 0;
        foreach ($parser_result->items as $item){

            if(in_array($item->typeModel->typeID,self::ILLEGAL_GROUPS)){
                $name = $item->typeModel->typeName;
                $request->session()->flash("error","You are not allowed to transport $name(s) in your contract!");
                return redirect()->back();
            }

            //steve's inv_volumes are wrong, bcs and bs is swapped, porpoise is wrong
            $item_volume = null;

            switch ($item->typeModel->groupID){
                // battleships
                case 27:{
                    $item_volume = 50000;
                    break;
                }
                //combat bcs and attack bcs
                case 1201:
                case 419:{
                    $item_volume = 15000;
                    break;
                }
            }

            switch ($item->typeModel->typeID){
                // porpoise
                case 42244:{
                    $item_volume = 50000;
                    break;
                }
            }

            if($item_volume == null){
                //3 layers: specified volume, InvVolumes, typeModel volume
                $item_volume = $item->volume ?? InvVolume::find($item->typeModel->typeID)->volume ?? $item->typeModel->volume;
            }

            $volume += $item_volume * $item->amount;
        }

        if ($route->maxvolume && $volume > $route->maxvolume){
            $request->session()->flash("error","This route can only transport up to $route->maxvolume m3 per contract. You tried to submit a contract with a volume of $volume m3. Please consider splitting up your contract in multiple smaller contracts to get it transported.");
            return redirect()->back();
        }

        $collateral = 0;
        $appraised_items = EvePraisalPriceProvider::getPrices($parser_result->items,new SeatTransportPriceProviderSettings());
        foreach ($appraised_items as $item){
            $collateral += $item->price * $item->amount;
        }

        if ($route->max_collateral && $collateral > $route->max_collateral){
            $request->session()->flash("error","This route only allows a collateral up to $route->max_collateral ISK per contract. You tried to submit a contract with a collateral of $collateral ISK. Please consider splitting up your contract in multiple smaller contracts to get it transported.");
            return redirect()->back();
        }

        $cost = $route->isk_per_m3 * $volume + $collateral * ($route->collateral_percentage/100.0) + $route->base_price;

        if($request->rush_contract){
            if(!$route->rush_markup){
                $request->session()->flash("error","Rush contracts are not available on this route");
                return redirect()->back();
            }
            $cost = $cost * (1+$route->rush_markup/100);
        }

        return view("transportplugin::costs",compact("cost","route","collateral","volume"));
    }
}