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
    protected $fields = [
        'type' => '',
        'status' => '',
        'name' => '',
    ];

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
        $token = $qn->getToken(config('qiniu.bucket.videos.bucket'), $policy);
        return view('admin.videos.create', [
            'token' => $token
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Videos::find($id);
        if (!$video) return redirect()->back()->withErrors("找不到该视频!");
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $video->$field);
        }
        $data['id'] = $id;
        return view('admin.videos.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $video = Videos::find((int)$id);
        foreach (array_keys($this->fields) as $field) {
            if ($request->get($field) != "") {
                $video->$field = $request->get($field);
            }
        }
        $video->save();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Videos', $video->id, 3, '编辑了视频：' . $video->name));
        return redirect('/admin/videos')->withSuccess('修改成功！');
    }

    public function destroy($id)
    {
        $video = Videos::find((int)$id);
        if (!$video) return redirect()->back()->withErrors("找不到该视频!");
        $video->delete();
        event(new \App\Events\userActionEvent('\App\Models\Admin\Videos', $video->id, 2, '删除了视频：' . $video->id . "({$video->key})"));
        return redirect()->back()
            ->withSuccess("删除成功");
    }

    public function show($key)
    {
        $qn=new Qiniu();
        return view('admin.videos.show', [
            'video_url'=>$qn->getDownloadUrl($key)
        ]);
    }
}