<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->session()->get("api_token", null)) 
            return redirect()->route('user.login');
 
        return $next($request);
    }
    
}
