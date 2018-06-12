<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/5/31
 * Time: 13:47
 */

namespace App\Http\Controllers;


use App\Facades\Logger;
use App\Http\Controllers\Controller;
use App\Models\Common\Videos;
use App\Services\Qiniu;
use Illuminate\Http\Request;


class QiniuController extends Controller
{
    public function index()
    {
        $qn = new Qiniu();
        $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"fname":"$(fname)"}';
        $policy = array(
            'returnBody' => $returnBody
        );
        $token = $qn->getToken('chuckchill', $policy);
        return view('qiniu.index')->with([
            'token' => $token
        ]);
    }

    public function backendVideoCallback(Request $request)
    {
        $qn = new Qiniu();
        $url = route('qiniu.backend_video_callback');
        if (!$qn->vertifyCallback($url)) {
            Logger::info('回调鉴权失败', 'qiniu');
            return ['ret' => 'failed'];
        }
        Logger::info("回调实体:" . json_encode($request->all()), 'qiniu');
        $body = $request->all();
        $key = array_get($body, 'key');
        $name = array_get($body, 'name');
        $fname = array_get($body, 'fname');
        $buid = array_get($body, 'buid');
        $status = array_get($body, 'status');
        $type = array_get($body, 'type');
        $video = new Videos();
        $video->key = $key;
        $video->name = $name ? $name : $fname;
        $video->buid = $buid;
        $video->key = $key;
        $video->status = (int)$status;
        $video->type = $type;
        $video->save();
        return ['ret' => 'success'];
    }
}