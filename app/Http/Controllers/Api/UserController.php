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
use App\Http\Controllers\Api\Traits\SikerTrait;
use App\Models\Api\AppusersContacts;
use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use App\Models\Api\UsersAuth;
use App\Services\Helper;
use App\Services\ModelService\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends BaseController
{
    use SikerTrait;
    /**
     * @var UserInfo
     */
    public $userInfo;

    /**
     * UserController constructor.
     * @param UserInfo $userInfo
     */
    public function __construct(UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function bindAccount(Request $request)
    {

        $account = $request->get("account");
        $password = $request->get("password");
        $user = \JWTAuth::authenticate();
        if (!Helper::isUserName($account)) {
            code_exception("code.reg.account_invalid");
        }
        if (!Helper::isPassword($password)) {
            code_exception("code.reg.password_invalid");
        }
        $userAuth = UsersAuth::where(["uid" => $user->id, 'identity_type' => 'sys'])->first();
        if ($userAuth) {
            code_exception("code.reg.account_cannot_change");
        }
        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if ($userAuth) {
            code_exception("code.reg.acount_exist");
        }
        $this->userInfo->registerData("sys", $account, Hash::make($password), $user->id);
        return $this->response->array(['code' => 0, 'message' => '设置成功']);

    }

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


    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function setAlarmclock(Request $request)
    {
        try {
            $sno = $request->get("sno");
            $data = $request->get("data");
            $user = \JWTAuth::authenticate();
            if (!$sno) {
                code_exception("code.login.device_sno_notnull");
            }
            $device = Devices::where(["sno" => $sno])->first();
            if (!$device) {
                code_exception('code.login.device_sno_notexist');
            }
            $deviceBind = DeviceBind::where([
                "device_id" => $device->id,
                "is_master" => 1,
                'uid' => $user->id
            ])->first();
            if (!$deviceBind) {
                code_exception('code.login.device_unbind');
            }
            $path = "alarmclock/" . ($device->id % 20) . "/" . $device->id . "/";
            Helper::mkDir($path);
            $path .= md5($sno) . ".clock";
            Storage::put($path, $data);
            return $this->response->array(['code' => 0, 'message' => '设置成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function getAlarmclock(Request $request)
    {
        $sno = $request->get("sno");
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
        }
        $path = "alarmclock/" . ($device->id % 20) . "/" . $device->id . "/" . md5($sno) . ".clock";
        $clock = Storage::exists($path) ? Storage::get($path) : '';
        return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => $clock]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function setPhoneBook(Request $request)
    {

        $sno = $request->get("sno");
        $data = $request->get("data");
        $user = \JWTAuth::authenticate();
        if (!$sno) {
            code_exception("code.login.device_sno_notnull");
        }
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
        }
        $deviceBind = DeviceBind::where([
            "device_id" => $device->id,
            "is_master" => 1,
            'uid' => $user->id
        ])->first();
        if (!$deviceBind) {
            code_exception('code.login.device_unbind');
        }
        $path = "phonebook/" . ($user->id % 20) . "/" . $user->id . "/";
        $path = Helper::mkDir($path) . md5($sno) . ".pb";
        Storage::put($path, $data);
        return $this->response->array(['code' => 0, 'message' => '设置成功']);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function getPhoneBook(Request $request)
    {

        $sno = $request->get("sno");
        $user = \JWTAuth::authenticate();
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
        }
        $path = "alarmclock/" . ($user->id % 20) . "/" . $user->id . "/";
        $path = Helper::mkDir($path) . md5($sno) . ".pb";
        return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => Storage::get($path)]);

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function getDeviceBinder(Request $request)
    {

        $sno = $request->get("sno");
        $device = Devices::where(["sno" => $sno])->first();
        if (!$device) {
            code_exception('code.login.device_sno_notexist');
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
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function getUserDevice(Request $request)
    {

        $user = \JWTAuth::authenticate();
        $deviceBind = DeviceBind::select([
            "devices.sno", "devices_bind.is_master",
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
                'sno' => $item->sno,
                'is_master' => (boolean)$item->is_master,
                'is_enable' => (boolean)$item->is_enable,
                'bind_time' => $item->created_at->toDateTimeString(),
                'role' => (string)$item->role,
                'name' => $item->name,
            ];
        });
        return $this->response->array(['code' => 0, 'message' => '获取成功', "data" => $data]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function editUser(Request $request)
    {
        $user = \JWTAuth::authenticate();
        $user->nick_name = $request->get('nick_name');
        $user->birthday = $request->get('birthday');
        $user->gender = $request->get('gender');
        $user->city = $request->get('city');
        $user->province = $request->get('province');
        $user->save();
        return $this->response->array(['code' => 0, 'message' => '修改成功']);
    }

    /**添加联系人
     * @param Request $request
     * @return mixed
     */
    public function addContacts(Request $request)
    {
        $user = \JWTAuth::authenticate();
        $identifier = $request->get('identifier');
        $userAuth = UsersAuth::select(['app_users.type', 'app_users.id'])
            ->leftJoin('app_users', 'app_users.id', '=', 'app_users_auth.uid')
            ->where(['identifier' => $identifier])
            ->whereIn('identity_type', ['sys', 'mobile'])
            ->first();
        if (!$userAuth) {
            code_exception('code.login.account_notexist');
        }
        if ($userAuth->type == $user->type) {
            code_exception('code.login.contacts_must_distinct');
        }
        $userLink = AppusersContacts::where([
            'contract_uid' => $userAuth->id,
            'uid' => $user->id
        ])->first();
        if ($userLink) {
            code_exception('code.login.contacts_exist');
        }
        $userLink = new AppusersContacts();
        $userLink->uid = $user->id;
        $userLink->contract_uid = $userAuth->id;
        $userLink->save();
        return $this->response->array(['code' => 0, 'message' => '添加成功']);
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        $user = \JWTAuth::authenticate();
        $contacts = AppusersContacts::select(['app_users_contacts.contract_uid', 'app_users.nick_name', 'app_users.profile_img'])
            ->leftJoin('app_users', 'app_users.id', '=', 'app_users_contacts.contract_uid')
            ->where('app_users_contacts.uid', '=', $user->id)
            ->get();
        $data = $contacts->map(function ($item, $key) {
            return [
                'nick_name' => $item->nick_name,
                'id' => $item->contract_uid,
                'profile_img' => UserInfo::getAvator(),
            ];
        });
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => $data->toArray()
        ]);
    }

    /**
     * @return mixed
     */
    public function delContacts(Request $request)
    {
        $contact_id = $request->get('contact_id');
        $user = \JWTAuth::authenticate();
        AppusersContacts::where([
            'contract_uid' => $contact_id,
            'uid' => $user->id,
        ])->delete();
        return $this->response->array([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }
}