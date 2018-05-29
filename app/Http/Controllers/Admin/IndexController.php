<?php

namespace App\Http\Controllers\Admin;

<<<<<<< HEAD
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use App\Models\Application;
use App\Models\AppAndroid as Android;
use App\Models\AppIos as Ios;
use QrCode;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('后台首页，当前用户名：'.auth('admin')->user()->name);
    }

=======
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class IndexController extends Controller
{
    public function index()
    {

        die(1);
        $superAdmin = new Role();
        $superAdmin->name         = 'SuperAdmin';
        $superAdmin->display_name = '超级管理员'; // optional
        $superAdmin->description  = '管理整个系统'; // optional
        $superAdmin->save();

        $admin = new Role();
        $admin->name         = 'Admin';
        $admin->display_name = '管理员'; // optional
        $admin->description  = '管理后台'; // optional
        $admin->save();

        $adminUser = Admin::where('email','admin@admin.com')->first();
        $adminUser->attachRole($superAdmin);





    }
>>>>>>> 9f6be1fd51e379122e42c5f5be2d6ce8955c112a

}
