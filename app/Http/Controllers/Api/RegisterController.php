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
use App\Services\Sms;
use App\Services\Validator;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class RegisterController extends BaseController
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function account(Request $request)
    {
        try {
            $account = $request->get("account");
            $password = $request->get("password");
            if (!Helper::isUserName($account)) {
                throw new CodeException(config("code.reg.account_invalid"));
            }
            if (!Helper::isPassword($password)) {
                throw new CodeException(config("code.reg.password_invalid"));
            }
            $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.acount_exist"));
            }
            $this->registerData("sys", $account, Hash::make($password));
            return $this->response->array(['code' => 0, 'message' => '注册成功']);
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
            $sms = new Sms($mobile);
            $sms->checkRegSms($code);
            $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.mobile_exist"));
            }
            $this->registerData("mobile", $mobile);
            return $this->response->array(['code' => 0, 'message' => '注册成功']);
        } catch (\Exception $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function email(Request $request)
    {
        $email = $request->get('email');
        $code = $request->get('code');
        try {
            $mail = new Email($email);
            $mail->validateCode($code);
            $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.email_exist"));
            }
            $this->registerData("email", $email);
            return $this->response->array(['code' => 0, 'message' => '注册成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendEmail(Request $request)
    {
        try {
            $email = $request->get('email');
            if (!$email) {
                throw new CodeException(config('code.reg.email_notnull'));
            }
            $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
            if ($userAuth) {
                throw new CodeException(config("code.reg.email_exist"));
            }
            $mail = new Email($email);
            $mail->sendRandCode();
            return $this->response->array(['code' => 0, 'message' => '发送成功']);
        } catch (CodeException $e) {
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
            $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
            if (!$mobile) {
                throw new CodeException(config('code.reg.mobile_notnull'));
            }
            if ($userAuth) {
                throw new CodeException(config("code.reg.mobile_exist"));
            }
            $sms = new Sms($mobile);
            $sms->sendRegSms();
            return $this->response->array(['code' => 0, 'message' => '发送成功']);
        } catch (CodeException $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param $identityType
     * @param $identifier
     * @param string $credential
     */
    public function registerData($identityType, $identifier, $credential = "")
    {
        $user = new User();
        $user->save();
        $userAuth = new UsersAuth();
        $userAuth->uid = $user->id;
        $userAuth->identity_type = $identityType;
        $userAuth->identifier = $identifier;
        $userAuth->credential = $credential;
        $userAuth->ifverified = "YES";
        $userAuth->save();
    }
}