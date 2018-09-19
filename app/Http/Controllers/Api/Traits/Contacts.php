<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/19
 * Time: 14:54
 */

namespace App\Http\Controllers\Api\Traits;


use App\Models\Api\AppusersContacts;
use App\Models\Api\UsersAuth;
use App\Services\ModelService\UserInfo;
use Illuminate\Http\Request;

trait Contacts
{
    /**添加联系人
     * @param Request $request
     * @return mixed
     */
    public function addContacts(Request $request)
    {
        $user = \JWTAuth::authenticate();
        $identifier = $request->get('identifier');
        $userAuth = UsersAuth::select(['app_users.type', 'app_users.id'])
            ->leftJoin('app_users', 'app_users.id', '=', 'app_users_auth.uid')
            ->where(['identifier' => $identifier])
            ->whereIn('identity_type', ['sys', 'mobile'])
            ->first();
        if (!$userAuth) {
            code_exception('code.login.account_notexist');
        }
        if ($userAuth->type == $user->type) {
            code_exception('code.login.contacts_must_distinct');
        }
        $userLink = AppusersContacts::where([
            'contract_uid' => $userAuth->id,
            'uid' => $user->id
        ])->first();
        if ($userLink) {
            code_exception('code.login.contacts_exist');
        }
        $userLink = new AppusersContacts();
        $userLink->uid = $user->id;
        $userLink->contract_uid = $userAuth->id;
        $userLink->save();
        return $this->response->array(['code' => 0, 'message' => '添加成功']);
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        $user = \JWTAuth::authenticate();
        $contacts = AppusersContacts::select(['app_users_contacts.contract_uid', 'app_users.nick_name', 'app_users.profile_img'])
            ->leftJoin('app_users', 'app_users.id', '=', 'app_users_contacts.contract_uid')
            ->where('app_users_contacts.uid', '=', $user->id)
            ->get();
        $data = $contacts->map(function ($item, $key) {
            return [
                'nick_name' => $item->nick_name,
                'id' => $item->contract_uid,
                'profile_img' => UserInfo::getAvator(),
            ];
        });
        return $this->response->array([
            'code' => 0,
            'message' => '获取成功',
            'data' => $data->toArray()
        ]);
    }

    /**
     * @return mixed
     */
    public function delContacts(Request $request)
    {
        $contact_id = $request->get('contact_id');
        $user = \JWTAuth::authenticate();
        AppusersContacts::where([
            'contract_uid' => $contact_id,
            'uid' => $user->id,
        ])->delete();
        return $this->response->array([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }
}