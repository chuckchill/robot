<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 10:17
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\CodeException;
use App\Facades\Logger;
use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use App\Models\Api\UsersAuth;
use App\Services\Helper;
use App\Services\ModelService\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    public $userInfo;

    public function __construct(UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function bindAccount(Request $request)
    {
        try {
            $account = $request->get("account");
            $password = $request->get("password");
            $user = \JWTAuth::authenticate();
            if (!Helper::isUserName($account)) {
                throw new CodeException(config("code.reg.account_invalid"));
            }
            if (!Helper::isPassword($password)) {
                throw new CodeException(config("code.reg.password_invalid"));
            }
            $userAuth = UsersAuth::where(["uid" => $user->id, 'identity_type' => 'sys'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.account_cannot_change"));
            }
            $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.acount_exist"));
            }
            $this->userInfo->registerData("sys", $account, Hash::make($password), $user->id);
            return $this->response->array(['code' => 0, 'message' => '设置成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**绑定
     * @param Request $request
     * @return mixed
     */
    public function BindDevice(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $name = $request->get("name");
            $role = $request->get("role");
            $user = \JWTAuth::authenticate();
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            if (!$name) {
                throw new CodeException(config('code.login.device_name_notnull'));
            }
            $userAuth = UsersAuth::where(['identity_type' => 'sys', 'uid' => $user->id])->first();
            if (!$userAuth) {//用户是否存在
                throw new CodeException(config("code.login.device_require_account"));
            }
            $deviceBind = DeviceBind::where(["device_id" => $device->id, "is_master" => 1])->first();
            if ($deviceBind) {
                if ($deviceBind->uid == $user->id) {//已绑定
                    throw new CodeException(config('code.login.device_bindforyou'));
                }
                //其他人绑定
                throw new CodeException(config('code.login.device_bindforother'));
            }
            $deviceBind = new DeviceBind();
            $deviceBind->uid = $user->id;
            $deviceBind->device_id = $device->id;
            $deviceBind->role = $role;
            $deviceBind->is_master = 1;
            $deviceBind->is_enable = 1;
            $device->name = $name;
            $deviceBind->save();
            $device->save();
            return $this->response->array(['code' => 0, 'message' => '绑定成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }


    /**授权
     * @param Request $request
     * @return mixed
     */
    public function authDevice(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $account = $request->get("account");
            $role = $request->get("name");
            $user = \JWTAuth::authenticate();
            $enable = $request->get("is_enable");
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {//设备是否存在
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
            if (!$userAuth) {//用户是否存在
                throw new CodeException(config("code.login.account_notexist"));
            }
            $deviceBind = DeviceBind::where([
                "device_id" => $device->id,
                "uid" => $user->id,
                "is_master" => 1
            ])->first();
            if (!$deviceBind) {//当前用户是否为master
                throw new CodeException(config('code.login.device_notmaster_change'));
            }
            if ($deviceBind->uid == $userAuth->uid) {//master不能修改
                throw new CodeException(config('code.login.device_master_connot_change'));
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
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**解绑
     * @param Request $request
     * @return mixed
     */
    public function unBindDevice(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $account = $request->get("account");
            $user = \JWTAuth::authenticate();
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
            if (!$userAuth) {//判断账号是否存在
                throw new CodeException(config("code.login.account_notexist"));
            }
            $deviceBind = DeviceBind::where([
                "device_id" => $device->id,
                "uid" => $user->id,
                'is_master' => 1
            ])->first();
            if ($deviceBind) {//主监护人
                if ($userAuth->uid == $user->id) {//解绑所有
                    DeviceBind::where(["device_id" => $deviceBind->device_id])->delete();
                    Logger::info("设备" . $deviceBind->sno . "解除所有绑定", "device_bind");
                    throw new CodeException(config("code.login.device_unbind_all"));
                } else {//解绑他人
                    DeviceBind::where(["device_id" => $device->id, "uid" => $userAuth->uid])->delete();
                    Logger::info("设备" . $deviceBind->sno . "解绑" . $userAuth->uid, "device_bind");
                    throw new CodeException(config('code.login.device_unbind_auth'));
                }

            } else {//非主
                if ($userAuth->uid != $user->id) {//无法解绑
                    throw new CodeException(config('code.login.device_connot_unbind'));
                } else {//解绑
                    DeviceBind::where(["device_id" => $device->id, "uid" => $userAuth->uid])->delete();
                    Logger::info("设备" . $device->sno . "解绑" . $userAuth->uid, "device_bind");
                    throw new CodeException(config('code.login.device_unbind_own'));
                }
            }
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function setAlarmclock(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $data = $request->get("data");
            $user = \JWTAuth::authenticate();
            if (!$sno) {
                throw new CodeException(config("code.login.device_sno_notnull"));
            }
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $deviceBind = DeviceBind::where([
                "device_id" => $device->id,
                "is_master" => 1,
                'uid' => $user->id
            ])->first();
            if (!$deviceBind) {
                throw new CodeException(config('code.login.device_unbind'));
            }
            $path = "alarmclock/" . ($user->id % 20) . "/" . $user->id . "/";
            $path = Helper::mkDir($path) . md5($sno) . ".clock";
            Storage::put($path, $data);
            return $this->response->array(['code' => 0, 'message' => '设置成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAlarmclock(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $user = \JWTAuth::authenticate();
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $path = "alarmclock/" . ($user->id % 20) . "/" . $user->id . "/";
            $path = Helper::mkDir($path) . md5($sno) . ".clock";
            return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => Storage::get($path)]);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function setPhoneBook(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $data = $request->get("data");
            $user = \JWTAuth::authenticate();
            if (!$sno) {
                throw new CodeException(config("code.login.device_sno_notnull"));
            }
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $deviceBind = DeviceBind::where([
                "device_id" => $device->id,
                "is_master" => 1,
                'uid' => $user->id
            ])->first();
            if (!$deviceBind) {
                throw new CodeException(config('code.login.device_unbind'));
            }
            $path = "phonebook/" . ($user->id % 20) . "/" . $user->id . "/";
            $path = Helper::mkDir($path) . md5($sno) . ".pb";
            Storage::put($path, $data);
            return $this->response->array(['code' => 0, 'message' => '设置成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPhoneBook(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $user = \JWTAuth::authenticate();
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $path = "alarmclock/" . ($user->id % 20) . "/" . $user->id . "/";
            $path = Helper::mkDir($path) . md5($sno) . ".pb";
            return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => Storage::get($path)]);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDeviceBinder(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                throw new CodeException(config('code.login.device_sno_notexist'));
            }
            $deviceBind = DeviceBind::where(['device_id' => $device->id])
                ->leftJoin("app_users_auth", 'app_users_auth.uid', '=', 'devices_bind.uid')
                ->where(['app_users_auth.identity_type' => "sys"])
                ->get();

            $data = $deviceBind->map(function ($item, $key) use ($device) {
                return [
                    'account' => $item->identifier,
                    'is_master' => (boolean)$item->is_master,
                    'is_enable' => (boolean)$item->is_enable,
                    'bind_time' => $item->created_at->toDateTimeString(),
                    'role' => (string)$item->role,
                    'name' => $device->name,
                ];
            });
            return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => $data]);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getUserDevice(Request $request)
    {
        try {
            $user = \JWTAuth::authenticate();
            $deviceBind = DeviceBind::select([
                "app_users_auth.identifier", "devices_bind.is_master",
                "devices_bind.is_enable", "devices_bind.created_at",
                "devices_bind.role", "devices.name",
            ])
                ->leftJoin("app_users_auth", 'app_users_auth.uid', '=', 'devices_bind.uid')
                ->leftJoin("devices", 'devices.id', '=', 'devices_bind.device_id')
                ->where([
                    'app_users_auth.identity_type' => "sys",
                    'devices_bind.uid' => $user->id
                ])
                ->get();

            $data = $deviceBind->map(function ($item, $key) {
                return [
                    'account' => $item->identifier,
                    'is_master' => (boolean)$item->is_master,
                    'is_enable' => (boolean)$item->is_enable,
                    'bind_time' => $item->created_at->toDateTimeString(),
                    'role' => (string)$item->role,
                    'name' => $item->name,
                ];
            });
            return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => $data]);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }
}