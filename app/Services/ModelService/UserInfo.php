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
            'nick_name' => array_get($user, "nick_name", ""),
            'gender' => array_get($user, "gender", ""),
            'birthday' => array_get($user, "birthday", ""),
            'province' => array_get($user, "province", ""),
            'city' => array_get($user, "city", ""),
            'profile_img' => $user->profile_img ? array_get($user, "profile_img") : "",
        ];
    }

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
        return $userAuth;
    }

    public function getRegInfo($uid)
    {
        $allIdentifer = $this->getAllAccount($uid);
        foreach ($allIdentifer as $identifier) {
            if ($identifier['identity_type'] == "mobile") {
                $mobile = $identifier['identifier'];
            }
            if ($identifier['identity_type'] == "sys") {
                $account = $identifier['identifier'];
            }
        }
        return compact('mobile', 'account');
    }
}