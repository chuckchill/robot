<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/7/14
 * Time: 23:09
 */

namespace App\Http\Middleware;

use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use Closure;

class AuthDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $sno = $request->header("sno");
        $device = Devices::where(['sno' => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
        }
        $deviceBind = DeviceBind::where(['device_id' => $device->id, 'is_master' => 1])->first();
        if (!$deviceBind) {
            code_exception('code.login.device_unbind');
        }
        $request->attributes->add([
            'device' => $device,
            'deviceBind' => $deviceBind,
        ]);
        return $next($request);
    }
}