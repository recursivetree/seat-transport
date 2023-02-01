<?php

namespace RecursiveTree\Seat\TransportPlugin;

use RecursiveTree\Seat\TreeLib\Helpers\Setting;

class TransportPluginSettings
{
    public static $INFO_TEXT;


    public static function init(){
        self::$INFO_TEXT = Setting::create("transportplugin","infotext",true);
    }
}