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
        $province = $request->get("province");
        $city = $request->get("city");
        $county = $request->get("county");
        $type = $request->get("type");
        $sicker_type = $request->get("sicker_type");
        $docker_no = $request->get("docker_no");
        $user = new User();
        if (!Helper::isUserName($account)) {
            code_exception("code.reg.account_invalid");
        }
        if (!Helper::isPassword($password)) {
            code_exception("code.reg.password_invalid");
        }
        if ($type == 'sicker') {//病人必须填写类型
            if (!$sicker_type) {
                code_exception("code.common.sicker_type_notnull");
            } else {
                $user->sicker_type = $sicker_type;
            }
        }
        if ($type == 'docker') {//医生必须填写编号
            if (!$docker_no) {
                code_exception("code.common.dockerno_notnull");
            } else {
                $user->docker_no = $docker_no;
            }
        }
        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if ($userAuth) {
            code_exception("code.reg.acount_exist");
        }

        $user->nick_name = str_random(8);
        $user->type = $type;
        $user->province = $province;
        $user->city = $city;
        $user->county = $county;
        $user->save();
        $this->userInfo->registerData("sys", $account, Hash::make($password), $user->id);
        $this->userInfo->saveUserExtra($type, $sicker_type, $docker_no);
        return $this->response->array([
            'code' => 0,
            'message' => '注册成功',
            "data" => $this->userInfo->getLoginData($user->id)
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
        $type = $request->get("type");
        $sms = new Sms($mobile);
        $sms->checkRegSms($code);
        $userAuth = UsersAuth::where(["identifier" => $mobile, 'identity_type' => 'mobile'])->first();
        if ($userAuth) {
            code_exception("code.reg.mobile_exist");
        }

        $user = new User();
        $user->nick_name = str_random(8);
        $user->type = $type;
        $user->save();
        $this->userInfo->registerData("mobile", $mobile, "", $user->id);
        return $this->response->array([
            'code' => 0,
            'message' => '注册成功',
            "data" => $this->userInfo->getLoginData($user->id)
        ]);

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
        $type = $request->get("type");
        $mail = new Email($email);
        $mail->validateCode($code);
        $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
        if ($userAuth) {
            code_exception("code.reg.email_exist");
        }
        $user = new User();
        $user->nick_name = str_random(8);
        $user->type = $type;
        $user->save();
        $this->userInfo->registerData("email", $email, "", $user->id);
        return $this->response->array([
            'code' => 0,
            'message' => '注册成功',
            "data" => $this->userInfo->getLoginData($user->id)
        ]);

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
            code_exception('code.reg.email_notnull');
        }
        $userAuth = UsersAuth::where(["identifier" => $email, 'identity_type' => 'email'])->first();
        if ($userAuth) {
            code_exception("code.reg.email_exist");
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
            code_exception('code.reg.mobile_notnull');
        }
        if ($userAuth) {
            code_exception("code.reg.mobile_exist");
        }
        $sms = new Sms($mobile);
        $sms->sendRegSms();
        return $this->response->array(['code' => 0, 'message' => '发送成功']);
    }

}