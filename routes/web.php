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
Route::get('/test', 'HomeController@index');
Route::get('/apidoc', 'HomeController@apiDoc');
Route::get('/qiniu/index', 'QiniuController@index');
Route::any('/qiniu/backend-video-callback', ['as' => 'qiniu.backend_video_callback', 'uses' => 'QiniuController@backendVideoCallback']);
Route::any('/qiniu/user-upload-callback', ['as' => 'qiniu.user-upload-callback', 'uses' => 'QiniuController@userUploadCallback']);//七牛上传回调
Route::any('/qiniu/common-callback', ['as' => 'qiniu.common-callback', 'uses' => 'QiniuController@commonCallback']);//七牛上传回调
Route::any('/qiniu/article-callback', ['as' => 'qiniu.article-callback', 'uses' => 'QiniuController@articleCallback']);//七牛上传回调


Route::group(['middleware' => ['evaluate'], 'prefix' => 'webview'], function () {
    Route::get('/entry', 'Api\EvaluateController@entry');
    Route::get('/error', 'Api\EvaluateController@error');
    Route::get('/list-user', 'Api\EvaluateController@listUser');
    Route::get('/eva-history', 'Api\EvaluateController@history');
    Route::get('/eva-add', 'Api\EvaluateController@addEvaluation');
    Route::post('/eva-add', 'Api\EvaluateController@saveEvaluation');
});