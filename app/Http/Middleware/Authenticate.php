<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
<<<<<<< HEAD
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
=======
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
<<<<<<< HEAD
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                $login_path = [
                    'admin' => '/admin/login',
                ];
                $url = empty($guard) ? '/login' : (isset($login_path[$guard]) ? $login_path[$guard] : '/login');

                return redirect()->guest($url);
=======
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                switch ($guard) {
                    case 'admin':
                        $path = 'admin/login';
                        break;

                    default:
                        $path = 'user/login';
                        break;
                }

                return redirect()->guest($path);
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a
            }
        }

        return $next($request);
    }
}
