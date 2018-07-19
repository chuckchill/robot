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

Route::get('/', function () {
    return view("welcome");
    return redirect('/admin');
});

Auth::routes();

Route::get('/app', 'HomeController@getApp');
Route::get('/qiniu/index', 'QiniuController@index');
Route::any('/qiniu/backend-video-callback', ['as' => 'qiniu.backend_video_callback', 'uses' => 'QiniuController@backendVideoCallback']);
Route::any('/qiniu/user-upload-callback', ['as' => 'qiniu.user-upload-callback','uses' => 'QiniuController@userUploadCallback']);//七牛上传回调