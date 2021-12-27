<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        return $next($request);
    }
}
