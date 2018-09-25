<?php
/**
 * Created by PhpStorm.
 * User: albert
 * Date: 2018/9/25
 * Time: 21:00
 */

namespace App\Http\Controllers\Admin;


use App\Models\Api\User;
use App\Models\Common\FeedBack;
use Illuminate\Http\Request;

class FeedbackController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = (int)$request->get('length') | 3;
            $data['recordsTotal'] = FeedBack::count();
            $data['recordsFiltered'] = FeedBack::count();
            $data['data'] = FeedBack::skip($start)->take($length)
                ->orderBy('id', 'desc')
                ->get();
            $users = $this->getUser($data['data']->pluck('uid', 'uid')->toArray());
            foreach ($data['data'] as $key => $val) {
                $data['data'][$key]['name'] = array_get($users, $val->uid);
            }
            return response()->json($data);
        }
        return view('admin.feedback.index');
    }

    public function getUser($ids)
    {
        $users = User::whereIn('id', $ids)->get();
        return $users->pluck('nick_name', 'id');
    }
}