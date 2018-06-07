<?php


$api->get('login', 'IndexController@login');
$api->get('startup-page', ['uses' => 'AppConfigController@startupPpage']);//启动页
$api->get('link-page', ['uses' => 'AppConfigController@linkPage']);//引导页

$api->post('account-reg', ['uses' => 'RegisterController@account']);//账号注册

$api->post('reg-send-sms', ['uses' => 'RegisterController@sendSms']);//发送邮件
$api->post('mobile-reg', ['uses' => 'RegisterController@mobile']);//账号注册

$api->post('reg-send-email', ['uses' => 'RegisterController@sendEmail']);//发送邮件
$api->post('email-reg', ['uses' => 'RegisterController@email']);//账号注册

$api->post('account-login', ['uses' => 'AuthController@account']);//账号登录

$api->post('sms-login', ['uses' => 'AuthController@mobile']);//手机号码登录
$api->post('login-send-sms', ['uses' => 'AuthController@sendSms']);//账号注册

$api->group(['middleware' => ['api.auth']], function ($api) {
    $api->get('test', ['uses' => 'IndexController@index']);
});
