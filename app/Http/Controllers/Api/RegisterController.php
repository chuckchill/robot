<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 15:49
 */

namespace App\Http\Controllers\Api;


use App\Models\Api\User;
use App\Models\Api\UsersAuth;
use App\Services\Helper;
use App\Services\Validator;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class RegisterController extends BaseController
{

    public function account(Request $request)
    {
        $account = $request->get("account");
        $password = $request->get("password");
        if (!Helper::isUserName($account)) {
            return $this->response->array(config("code.reg.account_invalid"));
        }
        $userAuth = UsersAuth::where(["identifier" => $account, 'identity_type' => 'sys'])->first();
        if ($userAuth) {
            return $this->response->array(config("code.reg.acount_exist"));
        }
        if (!Helper::isPassword($password)) {
            return $this->response->array(config("code.reg.password_invalid"));
        }
        $user = new User();
        $user->save();
        $userAuth = new UsersAuth();
        $userAuth->uid = $user->id;
        $userAuth->identity_type = 'sys';
        $userAuth->identifier = $account;
        $userAuth->credential = Hash::make($password);
        $userAuth->ifverified = "YES";
        $userAuth->save();
    }


    public function mobile(Request $request)
    {

    }


    public function email(Request $request)
    {

    }

    public function sendEmail(Request $request)
    {
        try {

        } catch (\Exception $e) {
            return $this->response->array(['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    public function sendSms(Request $request)
    {

    }
}