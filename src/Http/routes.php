<?php

Route::group([
    'namespace'  => 'RecursiveTree\Seat\TransportPlugin\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => 'transport',
], function () {

});