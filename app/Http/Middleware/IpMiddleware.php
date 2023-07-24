<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::check() &&
            !setting('site.system_state') &&
            !auth()->user()->hasPermission('browse_when_system_off')) {

            auth()->logout();

            return abort(403, "System Suspended, Only Super Admin Can Login.");
        }

        if(setting('site.only_ip') && $ip = $request->getClientIp() ) {
            $db_ip = DB::table('ip_addresses')
                ->where('ip', '=', $ip)->first();

                if(is_null($db_ip) || !$db_ip->can_access) {
                    return abort(403, "You Are Not Authorised.\nOnly Defined IPs Can Access.");
                }
        }

        return $next($request);
    }
}
