<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        /*if (!in_array($request->getClientIp(), $this->whitelistIps)) {
            abort(403, "You are restricted to access the site.");
        }*/

        if( $ip = $request->getClientIp() ) {
            $db_ip = DB::table('ip_addresses')
                ->where('ip', '=', $ip)->first();

                if(is_null($db_ip) || !$db_ip->can_access) {
                    return abort(403, "You Are Not Authorised.\nOnly Defined IPs Can Access.");
                }
        }

        return $next($request);
    }
}
