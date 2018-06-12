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
    return view('welcome');
});

Auth::routes();

Route::get('/apidoc', 'HomeController@apiDoc');
Route::get('/qiniu/index', 'QiniuController@index');
Route::any('/qiniu/backend-video-callback', ['as' => 'qiniu.backend_video_callback', 'uses' => 'QiniuController@backendVideoCallback']);