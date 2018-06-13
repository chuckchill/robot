<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 15:49
 */

namespace App\Http\Controllers\Api;


use App\Exceptions\CodeException;
use App\Models\Api\User;
use App\Models\Api\UsersAuth;
use App\Services\Email;
use App\Services\Helper;
use App\Services\ModelService\UserInfo;
use App\Services\Sms;
use App\Services\Validator;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class RegisterController extends BaseController
{
    public $userInfo;

    public function __construct(UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function account(Request $request)
    {
        $account = $request->get("account");
        $password = $request->get("password");
        if (!Helper::isUserName($account)) {
            $this->codeException("code.reg.account_invalid");
        }
        if (!Helper::isPassword($password)) {
            $this->codeException("code.reg.password_invalid");
        }

        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if ($userAuth) {
            $this->codeException("code.reg.acount_exist");
        }
        $user = new User();
        $user->save();
        $this->userInfo->registerData("sys", $account, Hash::make($password), $user->id);
        return $this->response->array(['code' => 0, 'message' => '注册成功']);
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

        $sms = new Sms($mobile);
        $sms->checkRegSms($code);
        $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
        if ($userAuth) {
            $this->codeException("code.reg.mobile_exist");
        }

        $user = new User();
        $user->save();
        $this->userInfo->registerData("mobile", $mobile, "", $user->id);
        return $this->response->array(['code' => 0, 'message' => '注册成功']);

    }


    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function email(Request $request)
    {
        $email = $request->get('email');
        $code = $request->get('code');
        $mail = new Email($email);
        $mail->validateCode($code);
        $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
        if ($userAuth) {
            $this->codeException("code.reg.email_exist");
        }
        $user = new User();
        $user->save();
        $this->userInfo->registerData("email", $email, "", $user->id);
        return $this->response->array(['code' => 0, 'message' => '注册成功']);

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function sendEmail(Request $request)
    {
        $email = $request->get('email');
        if (!$email) {
            $this->codeException('code.reg.email_notnull');
        }
        $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
        if ($userAuth) {
            $this->codeException("code.reg.email_exist");
        }
        $mail = new Email($email);
        $mail->sendRandCode();
        return $this->response->array(['code' => 0, 'message' => '发送成功']);

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CodeException
     */
    public function sendSms(Request $request)
    {
        $mobile = $request->get('mobile');
        $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
        if (!$mobile) {
            $this->codeException('code.reg.mobile_notnull');
        }
        if ($userAuth) {
            $this->codeException("code.reg.mobile_exist");
        }
        $sms = new Sms($mobile);
        $sms->sendRegSms();
        return $this->response->array(['code' => 0, 'message' => '发送成功']);
    }

}