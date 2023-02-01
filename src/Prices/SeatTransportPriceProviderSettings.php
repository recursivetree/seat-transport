<?php
namespace RecursiveTree\Seat\TransportPlugin\Prices;

use RecursiveTree\Seat\TreeLib\Prices\PriceProviderSettings;

class SeatTransportPriceProviderSettings implements PriceProviderSettings
{

    public function getPreferredMarketHub()
    {
        return "jita";
    }

    public function getPreferredPriceType()
    {
        return "sell";
    }
}