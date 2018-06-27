<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/6/26
 * Time: 20:54
 */

namespace App\Http\Controllers\Admin;

use App\Models\Api\User;
use App\Services\ModelService\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppUserController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userModel = $this->getUserModel();
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search');
            $data['recordsTotal'] = User::where(['status' => 1])->count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = $userModel->count();
                $data['data'] = $userModel->where(function ($query) use ($search) {
                    $query->where('a.identifier', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('b.identifier', 'like', '%' . $search['value'] . '%');
                })
                    ->where(['status' => 1])
                    ->skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            } else {
                $data['recordsFiltered'] = User::where(['status' => 1])->count();
                $data['data'] = $userModel->skip($start)->take($length)
                    ->orderBy('id', SORT_DESC)
                    ->get();
            }
            return response()->json($data);
        }
        return view('admin.appuser.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = User::find((int)$id);
        $tag->status = 0;
        $tag->save();
        event(new \App\Events\userActionEvent('\App\Models\Api\User', $tag->id, 2, '注销了用户：' . $tag->nick_name));
        return redirect()->back()
            ->withSuccess("注销成功");
    }

    private function getUserModel()
    {
        return User::select(["app_users.*", "a.identifier as account", "b.identifier as mobile"])
            ->where(['status' => 1])
            ->leftJoin('app_users_auth as a', function ($join) {
                $join->on('a.uid', '=', 'app_users.id')
                    ->on('a.identity_type', '=', DB::raw("'sys'"));
            })->leftJoin('app_users_auth as b', function ($join) {
                $join->on('b.uid', '=', 'app_users.id')
                    ->on('b.identity_type', '=', DB::raw("'mobile'"));
            });
    }
}