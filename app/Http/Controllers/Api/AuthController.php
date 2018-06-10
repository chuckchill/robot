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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function account(Request $request)
    {
        try {
            $account = $request->get('account');
            $password = $request->get('password');
            $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
            if (!$account) {
                throw new CodeException(config("code.login.account_notnull"));
            }
            if (!$password) {
                throw new CodeException(config("code.login.password_notnull"));
            }
            if (!$userAuth) {
                throw new CodeException(config("code.login.account_notexist"));
            }
            if (!Hash::check($password, $userAuth->credential)) {
                throw new CodeException(config("code.login.password_invalid"));
            }
            return $this->response->array([
                'code' => 0,
                'message' => '登录成功',
                'data' => $this->getLoginData($userAuth->uid)
            ]);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function mobile(Request $request)
    {
        $mobile = $request->get('mobile');
        $code = $request->get('code');
        try {
            if (!$mobile) {
                throw new CodeException(config('code.login.mobile_notnull'));
            }
            $sms = new Sms($mobile);
            $sms->checkLoginSms($code);
            $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
            if (!$userAuth) {
                throw new CodeException(config("code.login.mobile_notexist"));
            }
            return $this->response->array([
                'code' => 0,
                'message' => '登录成功',
                'data' => $this->getLoginData($userAuth->uid)
            ]);
        } catch (\Exception $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendSms(Request $request)
    {
        try {
            $mobile = $request->get('mobile');
            if (!$mobile) {
                throw new CodeException(config('code.login.mobile_notnull'));
            }
            $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
            if (!$userAuth) {
                throw new CodeException(config("code.login.mobile_notexist"));
            }
            $sms = new Sms($mobile);
            $sms->sendLoginSms();
            return $this->response->array(['code' => 0, 'message' => '发送成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
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