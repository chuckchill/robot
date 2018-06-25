<?php

$api->post('startup-page', ['uses' => 'AppConfigController@startupPpage']);//启动页
$api->post('link-page', ['uses' => 'AppConfigController@linkPage']);//引导页

$api->post('account-reg', ['uses' => 'RegisterController@account']);//账号注册

$api->post('reg-send-sms', ['uses' => 'RegisterController@sendSms']);//发送邮件
$api->post('mobile-reg', ['uses' => 'RegisterController@mobile']);//账号注册

$api->post('reg-send-email', ['uses' => 'RegisterController@sendEmail']);//发送邮件
$api->post('email-reg', ['uses' => 'RegisterController@email']);//账号注册

$api->post('account-login', ['uses' => 'AuthController@account']);//账号登录

$api->post('sms-login', ['uses' => 'AuthController@mobile']);//手机号码登录
$api->post('login-send-sms', ['uses' => 'AuthController@sendSms']);//账号注册

$api->post('wx-login', ['uses' => 'AuthController@wxLogin']);//微信登录


$api->group(['middleware' => ['api.auth']], function ($api) {
    $api->get('test', ['uses' => 'IndexController@index']);
    $api->post('logout', ['uses' => 'AuthController@logout']);//退出登录

    $api->post('edit-user', ['uses' => 'UserController@editUser']);//修改用户信息
    $api->post('set-account', ['uses' => 'UserController@bindAccount']);//设置账户
    $api->post('set-alarmclock', ['uses' => 'UserController@setAlarmclock']);//设置账户
    $api->post('get-alarmclock', ['uses' => 'UserController@getAlarmclock']);//设置账户
    $api->post('bind-device', ['uses' => 'UserController@BindDevice']);//绑定
    $api->post('auth-device', ['uses' => 'UserController@authDevice']);//授权
    $api->post('unbind-device', ['uses' => 'UserController@unBindDevice']);//解绑
    $api->post('get-device-binder', ['uses' => 'UserController@getDeviceBinder']);//获取设备绑定用户
    $api->post('get-user-device', ['uses' => 'UserController@getUserDevice']);//获取用户绑定设备

    $api->post('get-video-type', ['uses' => 'VideosController@getVideosType']);//获取视频分类
    $api->post('get-videos', ['uses' => 'VideosController@getVideos']);//查询视频
    $api->post('get-video-src', ['uses' => 'VideosController@getVideoSrc']);//获取视频地址


    $api->post('get-video-upload-token', ['uses' => 'VideosController@getUploadToken']);//获取视频地址
    $api->post('get-live-videos', ['uses' => 'VideosController@getLiveVideos']);//获取视频地址

});
