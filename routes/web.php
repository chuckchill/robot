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
    $host = app("request")->getHost();
    if ($host == "203.195.176.132") {
        return redirect('/admin');
    }
    return view("welcome");
});

Auth::routes();

Route::get('/app', 'HomeController@getApp');
Route::get('/apidoc', 'HomeController@apiDoc');
Route::get('/qiniu/index', 'QiniuController@index');
Route::any('/qiniu/backend-video-callback', ['as' => 'qiniu.backend_video_callback', 'uses' => 'QiniuController@backendVideoCallback']);
Route::any('/qiniu/user-upload-callback', ['as' => 'qiniu.user-upload-callback', 'uses' => 'QiniuController@userUploadCallback']);//七牛上传回调