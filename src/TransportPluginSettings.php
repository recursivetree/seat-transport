<?php

namespace RecursiveTree\Seat\TransportPlugin;

use RecursiveTree\Seat\TreeLib\Helpers\Setting;

class TransportPluginSettings
{
    public static $PRICE_PROVIDER;

    public static function init(){
        self::$PRICE_PROVIDER = Setting::create("transportplugin","priceprovider",true);
    }
}