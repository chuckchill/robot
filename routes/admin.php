<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout');
Route::post('logout', 'LoginController@logout');

Route::get('/', 'IndexController@index');


Route::get('index', [
    'as' => 'admin.index', 'uses' => function () {
        return redirect('/admin/log-viewer');
    }
]);


Route::group(['middleware' => ['auth:admin', 'menu', 'authAdmin']], function () {

    //权限管理路由
    Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::get('permission/manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
    Route::get('permission/{cid?}', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']);
    Route::post('permission/index', ['as' => 'admin.permission.index', 'uses' => 'PermissionController@index']); //查询
    Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);


    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.index', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);


    //用户管理路由
    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'BackendUserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'BackendUserController@index']);
    Route::resource('user', 'BackendUserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);
    //操作日志查询
    Route::get('log/index', ['as' => 'admin.log.index', 'uses' => 'LogController@index']);  //用户管理
    Route::post('log/index', ['as' => 'admin.log.index', 'uses' => 'LogController@index']);  //用户管理

    //App启动页配置
    Route::get('startup-page/index', ['as' => 'admin.startup-page.index', 'uses' => 'StartupPageController@index']);  //用户管理
    Route::post('startup-page/index', ['as' => 'admin.startup-page.index', 'uses' => 'StartupPageController@index']);  //
    Route::resource('startup-page', 'StartupPageController', ['names' => ['update' => 'admin.startup-page.edit', 'store' => 'admin.startup-page.create']]);

    //App引导页配置
    Route::get('link-page/index', ['as' => 'admin.link-page.index', 'uses' => 'LinkPageController@index']);  //用户管理
    Route::post('link-page/index', ['as' => 'admin.link-page.index', 'uses' => 'LinkPageController@index']);  //
    Route::resource('link-page', 'LinkPageController', ['names' => ['update' => 'admin.link-page.edit', 'store' => 'admin.link-page.create']]);

    //视频管理
    Route::get('videos/index', ['as' => 'admin.videos.index', 'uses' => 'VideosController@index']);  //用户管理
    Route::post('videos/index', ['as' => 'admin.videos.index', 'uses' => 'VideosController@index']);  //
    Route::resource('videos', 'VideosController', [
        'names' => [
            'update' => 'admin.videos.edit',
            'store' => 'admin.videos.create',
            'show' => 'admin.videos.show',
        ]
    ]);
    //视频分类
    Route::get('videos-type/index', ['as' => 'admin.videos-type.index', 'uses' => 'VideosTypeController@index']);  //用户管理
    Route::resource('videos-type', 'VideosTypeController', [
        'names' => [
            'update' => 'admin.videos-type.edit',
            'store' => 'admin.videos-type.create',
            'show' => 'admin.videos-type.show',
        ]
    ]);
    //其他配置
    Route::get('other/index', ['as' => 'admin.other.index', 'uses' => 'OtherController@index']);  //用户管理
    Route::post('other/keyword', ['as' => 'admin.other.keywork', 'uses' => 'OtherController@keyword']);  //
});

Route::get('/', function () {
    return redirect('/admin/index');
});

