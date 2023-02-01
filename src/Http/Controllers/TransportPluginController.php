<?php

namespace RecursiveTree\Seat\TransportPlugin\Http\Controllers;

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
        return view("transportplugin::settings", compact("stations","structures","routes"));
    }

    public function saveSettings(Request $request){
        $request->validate([
            "source_location"=>"required|integer",
            "destination_location"=>"required|integer",
            "collateral"=>"required|numeric",
            "iskm3"=>"required|numeric"
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
        $route->save();

        $request->session()->flash("success","Successfully added/updated route!");

        return $this->settings();
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

    public function postCalculate(Request $request){
        $request->validate([
            "route"=>"required|integer",
            "items"=>"required|string"
        ]);

        $route = TransportRoute::find($request->route);

        $parsed_data = Parser::parseFitOrMultiBuy($request->items, false);
        $volume = 0;
        foreach ($parsed_data->items->iterate() as $item){
            $typeModel = $item->getTypeModel();
            $volume += $typeModel->volume;
        }

        $collateral = 0;
        $appraised_items = EvePraisalPriceProvider::getPrices($parsed_data->items,new SeatTransportPriceProviderSettings());
        foreach ($appraised_items as $item){
            $collateral += $item->getTotalPrice();
        }

        $cost = $route->isk_per_m3 * $volume + $collateral * ($route->collateral_percentage/100.0);

        return view("transportplugin::costs",compact("cost","route","collateral","volume"));
    }
}