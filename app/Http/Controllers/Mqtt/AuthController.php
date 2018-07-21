<?php

namespace App\Http\Controllers\Mqtt;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/7/20
 * Time: 22:49
 */
class AuthController extends BaseController
{
    public function auth(Request $request)
    {
        return response("",404);
    }

    public function acl()
    {
        return ["code"=>200];
    }

    public function superAuth()
    {
        return ["code"=>200];
    }
}