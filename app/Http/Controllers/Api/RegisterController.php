<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/7
 * Time: 15:49
 */

namespace App\Http\Controllers\Api;


use App\Models\Api\UsersAuth;
use App\Services\Validator;
use Illuminate\Http\Request;

class RegisterController extends BaseController
{

    public function account(Request $request)
    {
        $account = $request->get("account");
        $password = $request->get("password");
        UsersAuth::where("account");
    }


    public function mobile(Request $request)
    {

    }


    public function email(Request $request)
    {

    }

    public function sendEmail(Request $request)
    {

    }

    public function sendSms(Request $request)
    {

    }
}