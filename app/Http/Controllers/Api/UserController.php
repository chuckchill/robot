<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/9
 * Time: 10:17
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\CodeException;
use App\Facades\Face;
use App\Facades\Logger;
use App\Http\Controllers\Api\Traits\Contacts;
use App\Http\Controllers\Api\Traits\Device;
use App\Http\Controllers\Api\Traits\SikerTrait;
use App\Models\Api\AppusersContacts;
use App\Models\Api\DeviceBind;
use App\Models\Api\Devices;
use App\Models\Api\UsersAuth;
use App\Models\Common\FeedBack;
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
    use SikerTrait, Contacts, Device;
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
            ->rightJoin("devices", 'devices.id', '=', 'devices_bind.device_id')
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

    /**修改密码
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $oldPwd = $request->get('old_password');
        $newPwd = $request->get('new_password');
        $user = \JWTAuth::authenticate();
        $userAuth = UsersAuth::where(["uid" => $user->id, 'identity_type' => 'sys'])->first();
        if (!$userAuth) {
            code_exception('code.login.account_notexist');
        }
        if (!Hash::check($oldPwd, $userAuth->credential)) {
            code_exception("code.login.password_invalid");
        }
        $userAuth->credential = Hash::make($newPwd);
        $userAuth->save();
        return $this->response->array(['code' => 0, 'message' => '修改成功']);
    }


    /**用户反馈
     * @param Request $request
     * @return mixed
     */
    public function addFeedback(Request $request)
    {
        $user = \JWTAuth::authenticate();
        $content = $request->get('content');
        $len = mb_strlen($content);
        if ($len > 200 || $len < 10) {
            code_exception('code.common.lenlimit', '反馈信息长度为10-200个字符串');
        }
        $today = Carbon::today()->getTimestamp();
        $time = FeedBack::where(['uid' => $user->id])->where('created_at', '>', $today)->get();
        if ($time->count() >= config('other.feedback_timelimit')) {
            code_exception('code.common.timelimit', '每天只能提交3次反馈信息');
        }
        $feeback = new FeedBack();
        $feeback->uid = $user->id;
        $feeback->content = $content;
        $feeback->save();
        return $this->response->array(['code' => 0, 'message' => '提交成功']);
    }
}