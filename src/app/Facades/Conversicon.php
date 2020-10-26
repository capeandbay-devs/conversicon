<?php

namespace CapeAndBay\Conversicon\app\Facades;

use CapeAndBay\Conversicon\Conversica;
use Illuminate\Support\Facades\Facade;

class Conversicon extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Conversica::class;
    }
}
