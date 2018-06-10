<?php

namespace App\Http\Controllers;

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
        return view('home');
    }

    public function apiDoc()
    {
        $content = file_get_contents(storage_path("/system/apidoc.json"));
        $doc = json_decode($content, true);
        return view('apidoc', compact("doc"));
    }
}
