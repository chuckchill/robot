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

class AppUserController extends BaseController
{
    public function index(Request $request)
    {
        $user = new UserInfo();
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $data['recordsTotal'] = User::where(['status' => 1])->count();
            $data['data'] = User::where(['status' => 1])->skip($start)->take($length)
                ->orderBy('id', SORT_DESC)
                ->get()->toArray();
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key] = array_merge($item, $user->getRegInfo($item['id']));
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
}