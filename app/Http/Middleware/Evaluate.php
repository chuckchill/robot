<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/30
 * Time: 20:26
 */

namespace App\Http\Middleware;

use App\Models\Api\Devices;
use App\Models\Api\User;
use App\Services\Aes;
use Closure;
use Illuminate\Support\Facades\Session;

class Evaluate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->is('webview/error')) {
            if ($request->has('face_token')) {
                $face_token = $request->get('face_token');
            } elseif ($request->header('face_token')) {
                $face_token = $request->header('face_token');
            } else {
                $face_token = Session::get('face_token');
            }
            try {
                $uid = Aes::opensslDecrypt($face_token, '123');
                $user = User::find($uid);
                if (!$user) {
                    throw new \Exception("用户不存在");
                }
            } catch (\Exception $e) {
                return redirect('webview/error?error=用户不存在');
            }
            $request->attributes->add([
                'user' => $user
            ]);
            Session::put('face_token', $face_token);
        }
        return $next($request);
    }
}