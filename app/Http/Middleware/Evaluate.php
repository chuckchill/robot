<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/30
 * Time: 20:26
 */

namespace App\Http\Middleware;

use App\Models\Api\Devices;
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
            if ($request->has('sno')) {
                $sno = $request->get('sno');
            } elseif ($request->header('sno')) {
                $sno = $request->header('sno');
            } else {
                $sno = Session::get('sno');
            }
            $device = Devices::where(['sno' => $sno])->first();
            if (!$device) {
                return redirect('webview/error?error=该设备未添加到平台');
            }
            $request->attributes->add([
                'device' => $device
            ]);
            Session::put('sno', $sno);
        }
        return $next($request);
    }
}