<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
<<<<<<< HEAD
            $url = $guard ? 'admin/index':'/home';
            return redirect($url);
=======
            switch ($guard) {
                case 'admin':
                    $path = 'admin';
                    break;

                default:
                    $path = 'user';
                    break;
            }

            return redirect($path);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
        }

        return $next($request);
    }
}
