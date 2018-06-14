<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/7
 * Time: 21:35
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\CodeException;
use App\Models\Api\User;
use App\Models\Api\UsersAuth;
use App\Services\ModelService\UserInfo;
use App\Services\Sms;
use App\Services\Wechat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function account(Request $request)
    {

        $account = $request->get('account');
        $password = $request->get('password');
        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if (!$account) {
            code_exception("code.login.account_notnull");
        }
        if (!$password) {
            code_exception("code.login.password_notnull");
        }
        if (!$userAuth) {
            code_exception("code.login.account_notexist");
        }
        if (!Hash::check($password, $userAuth->credential)) {
            code_exception("code.login.password_invalid");
        }
        return $this->response->array([
            'code' => 0,
            'message' => '登录成功',
            'data' => $this->getLoginData($userAuth->uid)
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function mobile(Request $request)
    {
        $mobile = $request->get('mobile');
        $code = $request->get('code');

        if (!$mobile) {
            code_exception('code.login.mobile_notnull');
        }
        $sms = new Sms($mobile);
        $sms->checkLoginSms($code);
        $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
        if (!$userAuth) {
            code_exception("code.login.mobile_notexist");
        }
        return $this->response->array([
            'code' => 0,
            'message' => '登录成功',
            'data' => $this->getLoginData($userAuth->uid)
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function sendSms(Request $request)
    {
        $mobile = $request->get('mobile');
        if (!$mobile) {
            code_exception('code.login.mobile_notnull');
        }
        $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
        if (!$userAuth) {
            code_exception("code.login.mobile_notexist");
        }
        $sms = new Sms($mobile);
        $sms->sendLoginSms();
        return $this->response->array(['code' => 0, 'message' => '发送成功']);
    }

    public function wxLogin(Request $request)
    {
        $code = $request->get("code");
        $wx = new Wechat();
        $userInfo = $wx->getUserInfo($code);
        $unionId = array_get($userInfo, "unionid");

        $userAuth = UsersAuth::where(["identifier" => $unionId, "identity_type" => "wx"])->first();
        if (!$userAuth) {
            $userAuth = new UsersAuth();
            $user = new User();
        } else {
            $user = User::where(["id" => $userAuth->uid])->first();
        }
        $user->nick_name = array_get($userInfo, "nickname");
        $user->gender = array_get($userInfo, "sex") == 1 ? "男" : "女";
        $user->profile_img = $user->profile_img ? $user->profile_img : $wx->storeImage(array_get($userInfo, "unionid"));
        $user->save();
        $userAuth->identifier = $unionId;
        $userAuth->identity_type = "wx";
        $userAuth->ifverified = "YES";
        $userAuth->uid = $user->id;
        $userAuth->save();
        return $this->response->array([
            'code' => 0,
            'message' => '登录成功',
            'data' => $this->getLoginData($userAuth->uid)
        ]);
    }

    /**
     * @param $uid
     * @return array
     */
    public function getLoginData($uid)
    {
        $user = User::find($uid);
        $token = \JWTAuth::fromUser($user);
        $userService = new UserInfo();
        $account = $userService->getAllAccount($uid);
        return [
            'token' => $token,
            'account' => array_get($account, 'sys.identifier', ""),
            'mobile' => array_get($account, 'mobile.identifier', ""),
            'email' => array_get($account, 'email.identifier', ""),
            'nike' => array_get($account, 'email.identifier', ""),
            'nick_name' => array_get($user, "nick_name", ""),
            'gender' => array_get($user, "gender", ""),
            'birthday' => array_get($user, "birthday", ""),
            'profile_img' => $user->profile_img ? array_get($user, "profile_img") : "",
        ];
    }

}