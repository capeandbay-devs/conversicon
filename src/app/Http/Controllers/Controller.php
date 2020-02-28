<?php

namespace CapeAndBay\Conversicon\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as ConversicaController;

class Controller extends ConversicaController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
