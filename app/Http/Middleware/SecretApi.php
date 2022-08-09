<?php

namespace App\Http\Middleware;

use App\Traits\Message;
use Closure;
use Illuminate\Http\Request;

class SecretApi
{

    use Message;

    public function handle(Request $request, Closure $next)
    {
        if($request->header('secretApi') == env('API_PASSWORD','Snr92EUKCmrE06PiJ')){

            return $next($request);
        }

        return $this->sendError('UnAuthenticated');
    }

}
