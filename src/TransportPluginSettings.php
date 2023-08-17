<?php

namespace RecursiveTree\Seat\TransportPlugin;

use RecursiveTree\Seat\TreeLib\Helpers\Setting;

class TransportPluginSettings
{
    public static $PRICE_PROVIDER_INSTANCE_ID;

    public static function init(){
        self::$PRICE_PROVIDER_INSTANCE_ID = Setting::create("transportplugin","seat-prices-core.price_provider_instance.id",true);
    }
}