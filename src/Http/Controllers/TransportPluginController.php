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
            "baseprice"=>"required|integer"
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

        $parsed_data = Parser::parseFitOrMultiBuy($request->items, false);
        //no items found, try to apply the inventory parser
        if ($parsed_data->items->count()==0){
            $parsed_data = Parser::parseInventoryExpanded($request->items);
            $request->session()->flash("warning","Seat used an experimental parser to read your items. Please check that the volume matches the ingame value and the collateral is reasonable!");
        }

        $volume = 0;
        foreach ($parsed_data->items->iterate() as $item){
            $type_model = $item->getTypeModel();

            if(in_array($type_model->groupID,self::ILLEGAL_GROUPS)){
                $request->session()->flash("error","You are not allowed to transport $type_model->typeName (s) in your contract!");
                return redirect()->back();
            }

            $item_volume = InvVolume::find($item->getTypeId())->volume ?? $type_model->volume;

            $volume += $item_volume * $item->getAmount();
        }

        if ($route->maxvolume && $volume > $route->maxvolume){
            $request->session()->flash("error","This route can only transport up to $route->maxvolume m3 per contract. You tried to submit a contract with a volume of $volume m3. Please consider splitting up your contract in multiple smaller contracts to get it transported.");
            return redirect()->back();
        }

        $collateral = 0;
        $appraised_items = EvePraisalPriceProvider::getPrices($parsed_data->items,new SeatTransportPriceProviderSettings());
        foreach ($appraised_items as $item){
            $collateral += $item->getTotalPrice();
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