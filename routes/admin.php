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


    //后台用户管理路由
    Route::get('user/index', ['as' => 'admin.user.index', 'uses' => 'BackendUserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.index', 'uses' => 'BackendUserController@index']);
    Route::resource('user', 'BackendUserController', ['names' => ['update' => 'admin.user.edit', 'store' => 'admin.user.create']]);
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

    //文章管理
    Route::get('article/index', ['as' => 'admin.article.index', 'uses' => 'ArticleController@index']);  //用户管理
    Route::post('article/index', ['as' => 'admin.article.index', 'uses' => 'ArticleController@index']);  //
    Route::post('article/upload-img', ['as' => 'admin.article.upload-img', 'uses' => 'ArticleController@postUploadImage']);  //
    Route::resource('article', 'ArticleController', [
        'names' => [
            'update' => 'admin.article.edit',
            'store' => 'admin.article.create',
            'show' => 'admin.article.show',
        ]
    ]);
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
    //录播视频管理
    Route::get('live-videos/index', ['as' => 'admin.live-videos.index', 'uses' => 'LiveVideosController@index']);  //用户管理
    Route::post('live-videos/index', ['as' => 'admin.live-videos.index', 'uses' => 'LiveVideosController@index']);  //
    Route::resource('live-videos', 'LiveVideosController', [
        'names' => [
            'update' => 'admin.live-videos.edit',
            'store' => 'admin.live-videos.create',
            'show' => 'admin.live-videos.show',
        ]
    ]);
    //媒体分类
    Route::get('media-type/index', ['as' => 'admin.media-type.index', 'uses' => 'MediaTypeController@index']);  //用户管理
    Route::resource('media-type', 'MediaTypeController', [
        'names' => [
            'update' => 'admin.media-type.edit',
            'store' => 'admin.media-type.create',
            'show' => 'admin.media-type.show',
        ]
    ]);
    //注册用户管理路由
    Route::get('appuser/index', ['as' => 'admin.appuser.index', 'uses' => 'AppUserController@index']);  //用户管理
    Route::post('appuser/index', ['as' => 'admin.appuser.index', 'uses' => 'AppUserController@index']);
    Route::resource('appuser', 'AppUserController', ['names' => ['update' => 'admin.appuser.edit', 'store' => 'admin.appuser.create']]);
    //其他配置
    Route::get('other/index', ['as' => 'admin.other.index', 'uses' => 'OtherController@index']);  //用户管理
    Route::post('other/keyword', ['as' => 'admin.other.keywork', 'uses' => 'OtherController@keyword']);  //
});

Route::get('/', function () {
    return redirect('/admin/index');
});

