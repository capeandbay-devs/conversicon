<?php

Route::group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => 'api',
    'prefix' => config('conversica.route_prefix')
], function() {

});
