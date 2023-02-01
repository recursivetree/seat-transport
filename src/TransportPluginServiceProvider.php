<?php

namespace RecursiveTree\Seat\TransportPlugin;

use Seat\Services\AbstractSeatPlugin;

class TransportPluginServiceProvider extends AbstractSeatPlugin
{
    public function boot(){
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang/', 'transportplugin');
        $this->loadViewsFrom(__DIR__ . '/resources/views/', 'transportplugin');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function register(){
        $this->mergeConfigFrom(__DIR__ . '/Config/transportplugin.sidebar.php','package.sidebar');
        $this->mergeConfigFrom(__DIR__ . '/Config/transportplugin.sde.tables.php','seat.sde.tables');
        $this->registerPermissions(__DIR__ . '/Config/transportplugin.permissions.php', 'transportplugin');

        TransportPluginSettings::init();
    }

    public function getName(): string
    {
        return 'SeAT Transport Quote Calculator';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/recursivetree/seat-transport';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-transport';
    }

    public function getPackagistVendorName(): string
    {
        return 'recursivetree';
    }
}