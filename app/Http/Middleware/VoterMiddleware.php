<?php

namespace App\Http\Middleware;

use Closure;

class VoterMiddleware
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
        $voter_table_id = $request->session()->get('voter_table_id');               
        if($voter_table_id == null)
        {
            return abort(403,'Please, Click your link again');
        }
        return $next($request);
    }
}
