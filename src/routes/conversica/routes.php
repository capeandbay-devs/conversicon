<?php

Route::group([
    'namespace' => 'CapeAndBay\Conversicon\Http\Controllers',
    'middleware' => 'conversica.basic',
    'prefix' => config('conversica.route_prefix')
], function() {
    Route::post('/message', 'ConversiconReceptionController@message');
    Route::post('/lead', 'ConversiconReceptionController@lead');
});
