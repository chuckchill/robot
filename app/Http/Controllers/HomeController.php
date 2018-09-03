<?php

namespace App\Http\Controllers;

use App\Services\emq\Package;
use App\Services\Qiniu;
use Illuminate\Http\Request;
use Qiniu\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$data="5252005D04000000002B3230313630395F636F6D2E6D616E62752E736D617274726F626F745F383635313832303331323731333233BAEB72815A47C5C8C3C248C5C29CA4DF5A6ADF37031F50C3B1693EBB9A750F453741F00200000D0A";
        $package=new Package();
        $package->unpackData(hex2bin($data));
        return "";*/
        return view('welcome');
    }

    public function apiDoc()
    {
        $content = file_get_contents(storage_path("/system/apidoc.json"));
        $docs = json_decode($content, true);
        return view('apidoc', compact("docs"));
    }

    public function getApp(Request $request)
    {
        $key = $request->get("key");
        $qiniu = new Qiniu();
        $url = $qiniu->getDownloadUrl($key);
        return redirect($url);
    }
}
