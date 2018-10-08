<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/9/30
 * Time: 17:07
 */

namespace App\Http\Controllers\Api;


use App\Models\Api\Evaluation;
use App\Models\Common\Sicker;
use App\Services\Helper;
use Illuminate\Http\Request;

class EvaluateController extends BaseController
{
    public function entry()
    {
        return redirect('/webview/list-user');
    }

    public function listUser(Request $request)
    {
        $device = $request->get('device');
        $sicker = Sicker::where(['device_id' => $device->id])->paginate(10);
        return view('webview.evaluate.list_user', [
            'sicker' => $sicker
        ]);
    }

    public function history(Request $request)
    {
        $sickerId = $request->get('sicker_id');
        $sicker = Sicker::find($sickerId);
        if (!$sicker) {
            return redirect('webview/error?error=病人不存在');
        }
        $evaluation = Evaluation::where(['sicker_id' => $sickerId])->paginate(10);
        return view('webview.evaluate.history', [
            'evaluation' => $evaluation,
            'sickerId' => $sickerId
        ]);
    }

    public function addEvaluation(Request $request)
    {
        $sickerId = $request->get('sicker_id');
        $sicker = Sicker::find($sickerId);
        if (!$sicker) {
            return redirect('webview/error?error=病人不存在');
        }
        return view('webview.evaluate.add', [
            'sickerId' => $request->get('sicker_id'),
            'temp' => Helper::getEvaluationTemp($sicker)
        ]);
    }

    public function saveEvaluation(Request $request)
    {
        $sickerId = $request->get('sicker_id');
        $sicker = Sicker::find($sickerId);
        if (!$sicker) {
            return redirect('webview/error?error=病人不存在');
        }
        $path = "/upload/evaluation/{$sicker->device_id}/{$sickerId}/";
        $dir = Helper::mkDir(public_path($path));
        $file = date("YmdHis") . ".html";
        $content = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"> ".$request->get('content');
        file_put_contents($dir . $file, $content);
        $evaluation = new Evaluation();
        $evaluation->sicker_id = $sickerId;
        $evaluation->file_path = $path . $file;
        $evaluation->name = $file;
        $evaluation->save();
        return redirect('/webview/eva-history?sicker_id=' . $sickerId);
    }

    public function error(Request $request)
    {
        return view('webview.evaluate.error');
    }
}