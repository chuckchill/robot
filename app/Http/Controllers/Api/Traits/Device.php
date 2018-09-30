<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/19
 * Time: 14:59
 */

namespace App\Http\Controllers\Api\Traits;


use App\Exceptions\CodeException;
use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use App\Models\Api\UsersAuth;
use Illuminate\Http\Request;

trait Device
{
    /**绑定设备
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function BindDevice(Request $request)
    {
        $sno = $request->get("sno");
        $role = $request->get("role");
        $user = \JWTAuth::authenticate();
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
        }
        $deviceBind = DeviceBind::where(["device_id" => $device->id, 'uid' => $user->id])->first();
        if ($deviceBind) {
            code_exception('code.login.device_bindforyou');
        }
        $deviceBind = new DeviceBind();
        $deviceBind->uid = $user->id;
        $deviceBind->device_id = $device->id;
        $deviceBind->role = $role;
        $deviceBind->is_master = 1;
        $deviceBind->is_enable = 1;
        $deviceBind->save();
        return $this->response->array(['code' => 0, 'message' => '绑定成功']);
    }

    /**解绑设备
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function unBindDevice(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $user = \JWTAuth::authenticate();
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                code_exception('code.login.device_sno_notexist');
            }
            DeviceBind::where(["device_id" => $device->id, "uid" => $user->id])->delete();
            return $this->response->array(['code' => 0, 'message' => '解绑成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**授权设备
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function authDevice(Request $request)
    {
        //code_exception('code.common.api_blockup');
        $sno = $request->get("sno");
        $account = $request->get("account");
        $role = $request->get("name");
        $user = \JWTAuth::authenticate();
        $enable = $request->get("enable");
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {//设备是否存在
            code_exception('code.login.device_sno_notexist');
        }
        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if (!$userAuth) {//用户是否存在
            code_exception("code.login.account_notexist");
        }
        $deviceBind = DeviceBind::where([
            "device_id" => $device->id,
            "uid" => $user->id,
            "is_master" => 1
        ])->first();
        if (!$deviceBind) {//当前用户是否为master
            code_exception('code.login.device_notmaster_change');
        }
        if ($deviceBind->uid == $userAuth->uid) {//master不能修改
            code_exception('code.login.device_master_connot_change');
        }
        $newBind = DeviceBind::where(["device_id" => $device->id, "uid" => $userAuth->uid])->first();
        if (!$newBind) {
            $newBind = new DeviceBind();
        }
        $newBind->uid = $userAuth->uid;
        $newBind->device_id = $device->id;
        $newBind->role = $role;
        $newBind->is_master = $user->id == $userAuth->uid ? 1 : 0;
        $newBind->is_enable = $user->id == $userAuth->uid ? 1 : ($enable ? 1 : 0);
        $newBind->save();
        return $this->response->array(['code' => 0, 'message' => '授权成功']);
    }

}