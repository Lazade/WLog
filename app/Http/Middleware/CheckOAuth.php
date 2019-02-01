<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Cache;

use Closure;

class CheckOAuth
{

    // param
    public $attributes;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cache::has('refresh_token')) {
            $token = Cache::get('refresh_token');
            $request->attributes->set('token', $token);
            return $next($request);
        } else {
            return redirect()->to('/avalon/login/google');
        }
    }
}
