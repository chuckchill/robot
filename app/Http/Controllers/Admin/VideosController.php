<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/11
 * Time: 15:31
 */

namespace App\Http\Controllers\Admin;


use App\Models\Common\Videos;
use App\Services\Qiniu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideosController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $data['recordsTotal'] = Videos::count();
            $data['recordsFiltered'] = $data['recordsTotal'];
            $data['data'] = Videos::skip($start)->take($length)
                ->orderBy('id', SORT_DESC)
                ->get();
            return response()->json($data);
        }

        return view('admin.videos.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $qn = new Qiniu();
        $uid = auth('admin')->user()->id;
        $returnBody = [
            "key" => "$(key)",
            "hash" => "$(hash)",
            "fsize" => "$(fsize)",
            "fname" => "$(fname)",
            "name" => "$(x:name)",
            "status" => "$(x:status)",
            "type" => "$(x:type)",
            "buid" => "{$uid}",
        ];
        $policy = array(
            'callbackUrl' => route('qiniu.backend_video_callback'),
            'callbackBody' => json_encode($returnBody),
            'callbackBodyType' => 'application/json'
        );
        $token = $qn->getToken(config('qiniu.bucket.videos'), $policy);
        return view('admin.videos.create', [
            'token' => $token
        ]);
    }
}