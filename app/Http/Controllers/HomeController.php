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

       /* $package = new Package();
        $content = json_encode([1, 2, 3]);
        $desc = "3539323838383030303031313231";
        $src = "676F6E67796F6E67313633636F6D5F636F6D2E6D616E62752E736D617274726F626F745F383635313832303331323731333233";
        $res = $package->packProtocol(hex2bin($src), hex2bin($desc), $content, 'logOut');
        $pack = bin2hex($res);
        dump($package);
        $unPack = $package->unpackProtocol(hex2bin($pack));
        dump($unPack);*/
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
