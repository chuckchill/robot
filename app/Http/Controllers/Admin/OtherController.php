<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 2018/6/11
 * Time: 17:19
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class OtherController
 * @package App\Http\Controllers\Admin
 */
class OtherController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $keywork = "";
        if (is_file(storage_path("app/hot_keyword.kw"))) {
            $keywork = Storage::get("hot_keyword.kw");
        }
        return view("admin.other.index", [
            'keyword' => $keywork
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function keyword(Request $request)
    {
        $keyword = $request->get("keyword");
        Storage::put("hot_keyword.kw", $keyword);
        return ["code" => 200, "message" => "ok"];
    }

}