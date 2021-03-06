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
            'doctor_id' => $user->type == 'doctor' ? $user->id : $userAuth->id,
            'sicker_id' => $user->type == 'doctor' ? $userAuth->id : $user->id,
        ])->first();
        if ($userLink) {
            code_exception('code.login.contacts_exist');
        }
        $userLink = new AppusersContacts();
        $userLink->doctor_id = $user->type == 'doctor' ? $user->id : $userAuth->id;
        $userLink->sicker_id = $user->type == 'doctor' ? $userAuth->id : $user->id;
        $userLink->save();
        return $this->response->array(['code' => 0, 'message' => '添加成功']);
    }

    /**
     * @return mixed
     */
    public function getContacts()
    {
        $user = \JWTAuth::authenticate();
        $cfield = $user->type == 'doctor' ? 'doctor_id' : 'sicker_id';
        $field = $user->type == 'doctor' ? 'sicker_id' : 'doctor_id';
        $contacts = AppusersContacts::select(["app_users_contacts.{$field}", 'app_users.nick_name', 'app_users.profile_img'])
            ->leftJoin('app_users', 'app_users.id', '=', "app_users_contacts.{$field}")
            ->where("app_users_contacts.{$cfield}", '=', $user->id)
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
        if ($user->type == 'doctor') {//医生删除病人
            AppusersContacts::where([
                "sicker_id" => $contact_id,
                'doctor_id' => $user->id,
            ])->delete();
        } else {
            AppusersContacts::where([//病人删除医生
                                     "sicker_id" => $user->id,
                                     'doctor_id' => $contact_id,
            ])->delete();
        }
        return $this->response->array([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }
}