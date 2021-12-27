<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AccessApiTokenMiddleWare
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
        // $accessToken ='XAV540aNLNlQIspYl4JiU49LTS6P46tOe5dM01TayGpfXR1s1EQk4KFN6zoG';
        $accessToken = $request->header('access_token');
        $accessapiToken = DB::table('accesstoken')->where('access_token', '=', $accessToken)->count() ? true : false;
        // dd($accessapiToken);
        if ($accessapiToken) {
            return $next($request);
        }else{
            return response()->json(["Unauthorized Request!"],401);
        }       
     }
}
