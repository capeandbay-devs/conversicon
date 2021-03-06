<?php

Route::group([
    'namespace' => 'CapeAndBay\Conversicon\app\Http\Controllers',
    'middleware' => 'conversicon.basic',
    'prefix' => config('conversica.route_prefix')
], function() {
    Route::post('/message', 'ConversiconReceptionController@message');
    Route::post('/lead', 'ConversiconReceptionController@lead');
});
