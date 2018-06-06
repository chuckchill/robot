<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/31
 * Time: 17:57
 */

namespace App\Http\Controllers\Api;


use App\Models\User;

class IndexController
{
    public function startupPpage()
    {
        dd(123);
    }

    public function linkPage()
    {

    }

    public function login()
    {
        $user = User::first();
        $token = \JWTAuth::fromUser($user);
        dd($token);
    }

    public function index()
    {
        var_dump(\JWTAuth::authenticate());
    }
}