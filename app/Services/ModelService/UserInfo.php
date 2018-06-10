<?php

namespace App\Services\ModelService;

use App\Models\Api\User;
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

    /**
     * @param $identityType
     * @param $identifier
     * @param string $credential
     */
    public function registerData($identityType, $identifier, $credential = "", $uid)
    {
        $userAuth = new UsersAuth();
        $userAuth->uid = $uid;
        $userAuth->identity_type = $identityType;
        $userAuth->identifier = $identifier;
        $userAuth->credential = $credential;
        $userAuth->ifverified = "YES";
        $userAuth->save();
    }
}