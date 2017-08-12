<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        #el middleware se ejecutara despues de la petición
        $response = $next($request);
        #lo que hace el middleware - por defecto el header (no es necesario) debria empezar con mayuscula
        $response->headers->set($header, config('app.name'));
        #sigue 
        return $response;
    }
}
