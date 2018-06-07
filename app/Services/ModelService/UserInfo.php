<?php

namespace App\Services\ModelService;

use App\Models\Api\UsersAuth;

/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/7
 * Time: 22:24
 */
class UserInfo
{
    public function getAllAccount($uid)
    {
        $account = UsersAuth::select('identity_type', 'identifier')
            ->where(["uid" => $uid])
            ->get()
            ->keyBy('identity_type')
            ->toArray();
        return $account;
    }
}