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
use App\Services\Qiniu;
use Illuminate\Http\Request;


class QiniuController extends Controller
{
    public function index()
    {
        $qn = new Qiniu();

        $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"name":"$(fname)"}';
        $policy = array(
            'callbackUrl' => route('qiniu.callback'),
            'callbackBody' => $returnBody,
            'callbackBodyType' => 'application/json'
        );
        $token = $qn->getToken('chuckchill', $policy);
        return view('qiniu.index')->with([
            'token' => $token
        ]);
    }

    public function callback(Request $request)
    {
        $qn = new Qiniu();
        if (!$qn->vertifyCallback()) {
            Logger::info('回调鉴权失败', 'qiniu');
        }
        Logger::info("回调实体:".json_encode($request->all()),'qiniu');
    }
}