<?php

namespace CapeAndBay\Conversicon\Http\Middleware;

use Closure;

class ValidateBasicHeader
{
    public function __construct()
    {

    }

    public function handle($request, Closure $next)
    {
        // @todo - Validate Basic Auth Headers
        $basic_deets = $request->header('Authorization');

        if($basic_deets)
        {
            $auth_array = explode(" ", $basic_deets);
            $un_pw = explode(":", base64_decode($auth_array[1]));
            $un = $un_pw[0];
            $pw = $un_pw[1];

            if(($un == config('conversica.basic.username')) && ($pw == config('conversica.basic.password')))
            {
                return $next($request);
            }

            return response('Unauthorized', 401);
        }

        return response('Bad Request', 400);
    }
}
